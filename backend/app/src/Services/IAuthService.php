<?php

namespace App\Services;

use App\Models\User;

interface IAuthService
{
    public function register(string $name, string $email, string $password): array;
    public function login(string $email, string $password): array;
    public function getUserFromToken(string $token): ?User;
    public function userToArray(User $user): array;
}
