<?php

namespace App\Services;

use App\Models\Mix;

interface IMixService
{
    /**
     * @return Mix[]
     */
    public function getAll(int $page = 1, int $limit = 6, ?string $genre = null, ?string $search = null): array;
    public function count(?string $genre = null, ?string $search = null): int;
    public function getById(int $id): ?Mix;
    public function create(Mix $mix): Mix;
    public function update(int $id, Mix $mix): bool;
    public function delete(int $id): bool;
}
