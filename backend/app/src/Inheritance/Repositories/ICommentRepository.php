<?php

namespace App\Inheritance\Repositories;

use App\Models\Comment;

interface ICommentRepository
{
    /**
     * @return Comment[]
     */
    public function getByMixId(int $mixId): array;
    public function getById(int $id): ?Comment;
    public function create(int $mixId, int $userId, string $body): Comment;
    public function delete(int $id): bool;
}
