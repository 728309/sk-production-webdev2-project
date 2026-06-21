<?php

namespace App\Repositories;

use App\Models\Mix;

interface IMixRepository
{
    /**
     * @return Mix[]
     */
    public function getAll(int $page = 1, int $limit = 6, ?string $genre = null, ?string $search = null, ?string $status = 'published'): array;
    public function count(?string $genre = null, ?string $search = null, ?string $status = 'published'): int;
    public function getById(int $id): ?Mix;
    public function getByUserId(int $userId): array;
    public function getPending(): array;
    public function create(Mix $mix): Mix;
    public function update(int $id, Mix $mix): bool;
    public function approve(int $id): ?Mix;
    public function reject(int $id, string $reviewNote): ?Mix;
    public function delete(int $id): bool;
}
