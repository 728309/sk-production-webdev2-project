<?php

// NOTE: we are using a simplified file based storage for demo purposes
// For your assignment, you should use a database

namespace App\Repositories;

use App\Models\Mix;
use App\Utils\JsonStore;

class MixRepository implements IMixRepository
{
    private JsonStore $store;
    private const DATA_FILE = __DIR__ . '/../data/mixes.json';

    public function __construct()
    {
        $this->store = new JsonStore(self::DATA_FILE, Mix::class);
    }

    /**
     * @return Mix[]
     */
    public function getAll(): array
    {
        return $this->store->getAll();
    }

    public function getById(int $id): ?Mix
    {
        return $this->store->getById($id);
    }

    public function create(Mix $mix): Mix
    {
        $this->store->create($mix);
        return $mix;
    }

    public function update(Mix $mix): bool
    {
        return $this->store->update($mix);
    }

    public function delete(int $id): bool
    {
        return $this->store->delete($id);
    }
}
