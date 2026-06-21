<?php

namespace App\Services;

use App\Models\Mix;
use App\Repositories\IMixRepository;
use App\Repositories\MixRepository;

class MixService implements IMixService
{
    private IMixRepository $repository;

    public function __construct()
    {
        $this->repository = new MixRepository();
    }

    /**
     * @return Mix[]
     */
    public function getAll(int $page = 1, int $limit = 6, ?string $genre = null, ?string $search = null, ?string $status = 'published'): array
    {
        return $this->repository->getAll($page, $limit, $genre, $search, $status);
    }

    public function count(?string $genre = null, ?string $search = null, ?string $status = 'published'): int
    {
        return $this->repository->count($genre, $search, $status);
    }

    public function getById(int $id): ?Mix
    {
        return $this->repository->getById($id);
    }

    public function getFeatured(int $limit = 3): array
    {
        return $this->repository->getFeatured($limit);
    }

    public function getByUserId(int $userId): array
    {
        return $this->repository->getByUserId($userId);
    }

    public function getSubmissionSummaryByUserId(int $userId): array
    {
        return $this->repository->getSubmissionSummaryByUserId($userId);
    }

    public function getPending(): array
    {
        return $this->repository->getPending();
    }

    public function create(Mix $mix): Mix
    {
        return $this->repository->create($mix);
    }

    public function update(int $id, Mix $mix): bool
    {
        return $this->repository->update($id, $mix);
    }

    public function updateAndReturn(int $id, Mix $mix): ?Mix
    {
        return $this->repository->updateAndReturn($id, $mix);
    }

    public function approve(int $id): ?Mix
    {
        return $this->repository->approve($id);
    }

    public function reject(int $id, string $reviewNote): ?Mix
    {
        return $this->repository->reject($id, $reviewNote);
    }

    public function setFeatured(int $id, bool $featured): ?Mix
    {
        return $this->repository->setFeatured($id, $featured);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
