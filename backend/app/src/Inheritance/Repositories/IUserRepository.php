<?php

namespace App\Inheritance\Repositories;

use App\Models\User;

interface IUserRepository
{
    public function findByEmail(string $email): ?User;
    public function findById(int $id): ?User;
    public function create(User $user): User;
}
