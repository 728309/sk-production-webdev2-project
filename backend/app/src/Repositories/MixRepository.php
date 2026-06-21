<?php

namespace App\Repositories;

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
    }

    /**
     * @return Mix[]
     */
    public function getAll(int $page = 1, int $limit = 6, ?string $genre = null, ?string $search = null): array
    {
        $offset = ($page - 1) * $limit;
        $filters = $this->buildFilterSql($genre, $search);

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

    public function count(?string $genre = null, ?string $search = null): int
    {
        $filters = $this->buildFilterSql($genre, $search);

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
                submitted_date,
                description,
                status,
                featured
            ) VALUES (
                :title,
                :artist,
                :genre,
                :platform,
                :mix_url,
                :cover_image_url,
                :duration,
                :submitted_by,
                :submitted_date,
                :description,
                :status,
                :featured
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
                submitted_date = :submitted_date,
                description = :description,
                status = :status,
                featured = :featured
            WHERE id = :id'
        );

        $parameters = $this->getMixParameters($mix);
        $parameters['id'] = $id;

        $statement->execute($parameters);

        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $statement = $this->connection->prepare('DELETE FROM mixes WHERE id = :id');
        $statement->execute(['id' => $id]);

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
            'submittedDate' => $row['submitted_date'],
            'description' => $row['description'] ?? '',
            'status' => $row['status'],
            'featured' => (bool) $row['featured'],
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
            'submitted_date' => $mix->submittedDate,
            'description' => $mix->description,
            'status' => $mix->status ?: 'published',
            'featured' => $mix->featured ? 1 : 0,
        ];
    }

    private function buildFilterSql(?string $genre, ?string $search): array
    {
        $conditions = [];
        $params = [];

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
}
