<?php

namespace App\Repositories;

use App\Models\Mix;
use App\Utils\Database;
use PDO;

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
    public function getAll(): array
    {
        $statement = $this->connection->query('SELECT * FROM mixes ORDER BY id');
        $rows = $statement->fetchAll();

        return array_map([$this, 'mapRowToMix'], $rows);
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
}
