<?php

namespace App\Inheritance\Services;

interface IVoteService
{
    public function getSummary(int $mixId, ?int $userId = null): array;
    public function vote(int $mixId, int $userId, string $voteType): array;
}
