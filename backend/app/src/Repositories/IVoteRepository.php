<?php

namespace App\Repositories;

interface IVoteRepository
{
    public function getCounts(int $mixId): array;
    public function getUserVote(int $mixId, int $userId): ?string;
    public function saveVote(int $mixId, int $userId, string $voteType): void;
}
