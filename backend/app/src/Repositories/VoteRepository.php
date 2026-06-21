<?php

namespace App\Repositories;

use App\Utils\Database;
use PDO;

class VoteRepository implements IVoteRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    public function getCounts(int $mixId): array
    {
        $statement = $this->connection->prepare(
            "SELECT
                COALESCE(SUM(vote_type = 'like'), 0) AS likes,
                COALESCE(SUM(vote_type = 'dislike'), 0) AS dislikes
             FROM votes
             WHERE mix_id = :mix_id"
        );
        $statement->execute(['mix_id' => $mixId]);

        $row = $statement->fetch();

        return [
            'likes' => (int)$row['likes'],
            'dislikes' => (int)$row['dislikes'],
        ];
    }

    public function getUserVote(int $mixId, int $userId): ?string
    {
        $statement = $this->connection->prepare(
            'SELECT vote_type FROM votes WHERE mix_id = :mix_id AND user_id = :user_id'
        );
        $statement->execute([
            'mix_id' => $mixId,
            'user_id' => $userId,
        ]);

        $row = $statement->fetch();

        return $row ? $row['vote_type'] : null;
    }

    public function saveVote(int $mixId, int $userId, string $voteType): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO votes (mix_id, user_id, vote_type)
             VALUES (:mix_id, :user_id, :vote_type)
             ON DUPLICATE KEY UPDATE
                vote_type = VALUES(vote_type),
                updated_at = CURRENT_TIMESTAMP'
        );

        $statement->execute([
            'mix_id' => $mixId,
            'user_id' => $userId,
            'vote_type' => $voteType,
        ]);
    }
}
