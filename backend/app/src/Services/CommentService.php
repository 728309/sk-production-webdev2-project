<?php

namespace App\Services;

use App\Inheritance\Repositories\ICommentRepository;
use App\Inheritance\Services\ICommentService;
use App\Models\Comment;
use App\Repositories\CommentRepository;

class CommentService implements ICommentService
{
    private ICommentRepository $repository;

    public function __construct()
    {
        $this->repository = new CommentRepository();
    }

    /**
     * @return Comment[]
     */
    public function getByMixId(int $mixId): array
    {
        return $this->repository->getByMixId($mixId);
    }

    public function getById(int $id): ?Comment
    {
        return $this->repository->getById($id);
    }

    public function create(int $mixId, int $userId, string $body): Comment
    {
        $body = trim($body);

        if ($body === '') {
            throw new \InvalidArgumentException('Comment body is required', 400);
        }

        return $this->repository->create($mixId, $userId, $body);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
