<?php

namespace App\Repositories;

use App\Inheritance\Repositories\IMixRepository;
use App\Models\Mix;
use App\Utils\Database;
use PDO;
use PDOStatement;

class MixRepository implements IMixRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
        $this->ensurePublishedAtColumn();
    }

    /**
     * @return Mix[]
     */
    public function getAll(int $page = 1, int $limit = 6, ?string $genre = null, ?string $search = null, ?string $status = 'published'): array
    {
        $offset = ($page - 1) * $limit;
        $filters = $this->buildFilterSql($genre, $search, $status);

        $statement = $this->connection->prepare(
            'SELECT * FROM mixes ' . $filters['where'] . ' ORDER BY id LIMIT :limit OFFSET :offset'
        );

        $this->bindFilterValues($statement, $filters['params']);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll();

        return array_map([$this, 'mapRowToMix'], $rows);
    }

    public function count(?string $genre = null, ?string $search = null, ?string $status = 'published'): int
    {
        $filters = $this->buildFilterSql($genre, $search, $status);

        $statement = $this->connection->prepare(
            'SELECT COUNT(*) AS total FROM mixes ' . $filters['where']
        );

        $this->bindFilterValues($statement, $filters['params']);
        $statement->execute();

        $row = $statement->fetch();

        return (int)$row['total'];
    }

    public function getById(int $id): ?Mix
    {
        $statement = $this->connection->prepare('SELECT * FROM mixes WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();
        if (!$row) {
            return null;
        }

        return $this->mapRowToMix($row);
    }

    /**
     * @return Mix[]
     */
    public function getFeatured(int $limit = 3): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM mixes
             WHERE status = :status AND featured = 1
             ORDER BY submitted_date DESC, id DESC
             LIMIT :limit'
        );
        $statement->bindValue(':status', 'published');
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll();

        return array_map([$this, 'mapRowToMix'], $rows);
    }

    /**
     * @return Mix[]
     */
    public function getByUserId(int $userId): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM mixes
             WHERE submitted_by_user_id = :user_id
             ORDER BY submitted_date DESC, id DESC'
        );
        $statement->execute(['user_id' => $userId]);

        $rows = $statement->fetchAll();

        return array_map([$this, 'mapRowToMix'], $rows);
    }

    public function getSubmissionSummaryByUserId(int $userId): array
    {
        $statement = $this->connection->prepare(
            'SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = "published" THEN 1 ELSE 0 END) AS approved,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) AS rejected
             FROM mixes
             WHERE submitted_by_user_id = :user_id'
        );
        $statement->execute(['user_id' => $userId]);

        $row = $statement->fetch();

        return [
            'total' => (int)($row['total'] ?? 0),
            'pending' => (int)($row['pending'] ?? 0),
            'approved' => (int)($row['approved'] ?? 0),
            'rejected' => (int)($row['rejected'] ?? 0),
        ];
    }

    /**
     * @return Mix[]
     */
    public function getPending(): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM mixes WHERE status = :status ORDER BY id DESC'
        );
        $statement->execute(['status' => 'pending']);

        $rows = $statement->fetchAll();

        return array_map([$this, 'mapRowToMix'], $rows);
    }

    public function create(Mix $mix): Mix
    {
        $statement = $this->connection->prepare(
            'INSERT INTO mixes (
                title,
                artist,
                genre,
                platform,
                mix_url,
                cover_image_url,
                duration,
                submitted_by,
                submitted_by_user_id,
                submitted_date,
                published_at,
                description,
                status,
                featured,
                review_note
            ) VALUES (
                :title,
                :artist,
                :genre,
                :platform,
                :mix_url,
                :cover_image_url,
                :duration,
                :submitted_by,
                :submitted_by_user_id,
                :submitted_date,
                :published_at,
                :description,
                :status,
                :featured,
                :review_note
            )'
        );

        $statement->execute($this->getMixParameters($mix));
        $mix->id = (int) $this->connection->lastInsertId();

        return $mix;
    }

    public function update(int $id, Mix $mix): bool
    {
        $mix->id = $id;

        $statement = $this->connection->prepare(
            'UPDATE mixes SET
                title = :title,
                artist = :artist,
                genre = :genre,
                platform = :platform,
                mix_url = :mix_url,
                cover_image_url = :cover_image_url,
                duration = :duration,
                submitted_by = :submitted_by,
                submitted_by_user_id = :submitted_by_user_id,
                submitted_date = :submitted_date,
                published_at = :published_at,
                description = :description,
                status = :status,
                featured = :featured,
                review_note = :review_note
            WHERE id = :id'
        );

        $parameters = $this->getMixParameters($mix);
        $parameters['id'] = $id;

        $statement->execute($parameters);

        return $statement->rowCount() > 0;
    }

    public function updateAndReturn(int $id, Mix $mix): ?Mix
    {
        if (!$this->getById($id)) {
            return null;
        }

        $this->update($id, $mix);

        return $this->getById($id);
    }

    public function approve(int $id): ?Mix
    {
        if (!$this->getById($id)) {
            return null;
        }

        $statement = $this->connection->prepare(
            'UPDATE mixes
             SET status = :status, published_at = CURRENT_TIMESTAMP, review_note = NULL
             WHERE id = :id'
        );
        $statement->execute([
            'status' => 'published',
            'id' => $id,
        ]);

        return $this->getById($id);
    }

    public function reject(int $id, string $reviewNote): ?Mix
    {
        if (!$this->getById($id)) {
            return null;
        }

        $statement = $this->connection->prepare(
            'UPDATE mixes
             SET status = :status, published_at = NULL, review_note = :review_note
             WHERE id = :id'
        );
        $statement->execute([
            'status' => 'rejected',
            'review_note' => $reviewNote,
            'id' => $id,
        ]);

        return $this->getById($id);
    }

    public function setFeatured(int $id, bool $featured): ?Mix
    {
        if (!$this->getById($id)) {
            return null;
        }

        $statement = $this->connection->prepare(
            'UPDATE mixes SET featured = :featured WHERE id = :id'
        );
        $statement->execute([
            'featured' => $featured ? 1 : 0,
            'id' => $id,
        ]);

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        if (!$this->getById($id)) {
            return false;
        }

        $this->connection->beginTransaction();

        try {
            $deleteVotes = $this->connection->prepare('DELETE FROM votes WHERE mix_id = :id');
            $deleteVotes->execute(['id' => $id]);

            $deleteComments = $this->connection->prepare('DELETE FROM comments WHERE mix_id = :id');
            $deleteComments->execute(['id' => $id]);

            $statement = $this->connection->prepare('DELETE FROM mixes WHERE id = :id');
            $statement->execute(['id' => $id]);

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }

        return $statement->rowCount() > 0;
    }

    private function mapRowToMix(array $row): Mix
    {
        return new Mix([
            'id' => (int) $row['id'],
            'title' => $row['title'],
            'artist' => $row['artist'],
            'genre' => $row['genre'],
            'platform' => $row['platform'],
            'mixUrl' => $row['mix_url'],
            'coverImageUrl' => $row['cover_image_url'] ?? '',
            'duration' => $row['duration'] ?? '',
            'submittedBy' => $row['submitted_by'],
            'submittedByUserId' => $row['submitted_by_user_id'] !== null ? (int)$row['submitted_by_user_id'] : null,
            'submittedDate' => $row['submitted_date'],
            'publishedAt' => $row['published_at'] ?? null,
            'description' => $row['description'] ?? '',
            'status' => $row['status'],
            'featured' => (bool) $row['featured'],
            'reviewNote' => $row['review_note'] ?? null,
        ]);
    }

    private function getMixParameters(Mix $mix): array
    {
        return [
            'title' => $mix->title,
            'artist' => $mix->artist,
            'genre' => $mix->genre,
            'platform' => $mix->platform,
            'mix_url' => $mix->mixUrl,
            'cover_image_url' => $mix->coverImageUrl,
            'duration' => $mix->duration,
            'submitted_by' => $mix->submittedBy,
            'submitted_by_user_id' => $mix->submittedByUserId,
            'submitted_date' => $mix->submittedDate,
            'published_at' => $mix->publishedAt,
            'description' => $mix->description,
            'status' => $mix->status ?: 'published',
            'featured' => $mix->featured ? 1 : 0,
            'review_note' => $mix->reviewNote,
        ];
    }

    private function buildFilterSql(?string $genre, ?string $search, ?string $status): array
    {
        $conditions = [];
        $params = [];

        if ($status !== null && $status !== '') {
            $conditions[] = 'status = :status';
            $params['status'] = $status;
        }

        if ($genre !== null && $genre !== '') {
            $conditions[] = 'genre = :genre';
            $params['genre'] = $genre;
        }

        if ($search !== null && $search !== '') {
            $conditions[] = '(
                title LIKE :search_title
                OR artist LIKE :search_artist
                OR genre LIKE :search_genre
                OR description LIKE :search_description
            )';

            $searchValue = '%' . $search . '%';
            $params['search_title'] = $searchValue;
            $params['search_artist'] = $searchValue;
            $params['search_genre'] = $searchValue;
            $params['search_description'] = $searchValue;
        }

        $where = '';
        if (count($conditions) > 0) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        return [
            'where' => $where,
            'params' => $params,
        ];
    }

    private function bindFilterValues(PDOStatement $statement, array $params): void
    {
        foreach ($params as $name => $value) {
            $statement->bindValue(':' . $name, $value);
        }
    }

    private function ensurePublishedAtColumn(): void
    {
        $statement = $this->connection->query("SHOW COLUMNS FROM mixes LIKE 'published_at'");
        $column = $statement->fetch();

        if ($column) {
            return;
        }

        $this->connection->exec('ALTER TABLE mixes ADD published_at DATETIME NULL AFTER submitted_date');
        $this->connection->exec('UPDATE mixes SET published_at = submitted_date WHERE status = "published"');
    }
}
