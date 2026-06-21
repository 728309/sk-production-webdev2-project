<?php

namespace App\Services;

use App\Inheritance\Repositories\IVoteRepository;
use App\Inheritance\Services\IVoteService;
use App\Repositories\VoteRepository;

class VoteService implements IVoteService
{
    private IVoteRepository $repository;

    public function __construct()
    {
        $this->repository = new VoteRepository();
    }

    public function getSummary(int $mixId, ?int $userId = null): array
    {
        $counts = $this->repository->getCounts($mixId);

        return [
            'likes' => $counts['likes'],
            'dislikes' => $counts['dislikes'],
            'userVote' => $userId ? $this->repository->getUserVote($mixId, $userId) : null,
        ];
    }

    public function vote(int $mixId, int $userId, string $voteType): array
    {
        if (!in_array($voteType, ['like', 'dislike'], true)) {
            throw new \InvalidArgumentException('Vote type must be like or dislike', 400);
        }

        // Repeating the same vote keeps it selected; changing vote type updates the row.
        $this->repository->saveVote($mixId, $userId, $voteType);

        return $this->getSummary($mixId, $userId);
    }
}
