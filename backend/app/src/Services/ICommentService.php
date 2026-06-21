<?php

namespace App\Services;

use App\Models\Comment;

interface ICommentService
{
    /**
     * @return Comment[]
     */
    public function getByMixId(int $mixId): array;
    public function getById(int $id): ?Comment;
    public function create(int $mixId, int $userId, string $body): Comment;
    public function delete(int $id): bool;
}
