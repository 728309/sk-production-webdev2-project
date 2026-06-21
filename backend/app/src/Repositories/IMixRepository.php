<?php

namespace App\Repositories;

use App\Models\Mix;

interface IMixRepository
{
    /**
     * @return Mix[]
     */
    public function getAll(): array;
    public function getById(int $id): ?Mix;
    public function create(Mix $mix): Mix;
    public function update(int $id, Mix $mix): bool;
    public function delete(int $id): bool;
}
