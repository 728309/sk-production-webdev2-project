<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Utils\Database;
use PDO;

class CommentRepository implements ICommentRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    /**
     * @return Comment[]
     */
    public function getByMixId(int $mixId): array
    {
        $statement = $this->connection->prepare(
            'SELECT comments.*, users.name AS user_name
             FROM comments
             INNER JOIN users ON users.id = comments.user_id
             WHERE comments.mix_id = :mix_id
             ORDER BY comments.created_at ASC'
        );
        $statement->execute(['mix_id' => $mixId]);

        $rows = $statement->fetchAll();

        return array_map([$this, 'mapRowToComment'], $rows);
    }

    public function getById(int $id): ?Comment
    {
        $statement = $this->connection->prepare(
            'SELECT comments.*, users.name AS user_name
             FROM comments
             INNER JOIN users ON users.id = comments.user_id
             WHERE comments.id = :id'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapRowToComment($row);
    }

    public function create(int $mixId, int $userId, string $body): Comment
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (mix_id, user_id, body)
             VALUES (:mix_id, :user_id, :body)'
        );
        $statement->execute([
            'mix_id' => $mixId,
            'user_id' => $userId,
            'body' => $body,
        ]);

        return $this->getById((int)$this->connection->lastInsertId());
    }

    public function delete(int $id): bool
    {
        $statement = $this->connection->prepare('DELETE FROM comments WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }

    private function mapRowToComment(array $row): Comment
    {
        return new Comment([
            'id' => (int)$row['id'],
            'mixId' => (int)$row['mix_id'],
            'userId' => (int)$row['user_id'],
            'userName' => $row['user_name'],
            'body' => $row['body'],
            'createdAt' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
        ]);
    }
}
