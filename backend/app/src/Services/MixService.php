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
    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): ?Mix
    {
        return $this->repository->getById($id);
    }

    public function create(Mix $mix): Mix
    {
        return $this->repository->create($mix);
    }

    public function update(int $id, Mix $mix): bool
    {
        return $this->repository->update($id, $mix);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
