<?php

namespace App\Services;

use App\Inheritance\Repositories\IUserRepository;
use App\Inheritance\Services\IAuthService;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Utils\JwtHelper;

class AuthService implements IAuthService
{
    private IUserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function register(string $name, string $email, string $password): array
    {
        $this->validateRegistration($name, $email, $password);

        if ($this->userRepository->findByEmail($email)) {
            throw new \RuntimeException('Email already exists', 409);
        }

        $user = new User([
            'name' => $name,
            'email' => $email,
            'passwordHash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
        ]);

        $user = $this->userRepository->create($user);

        return [
            'message' => 'User registered successfully',
            'user' => $this->userToArray($user),
        ];
    }

    public function login(string $email, string $password): array
    {
        if ($email === '' || $password === '') {
            throw new \RuntimeException('Invalid email or password', 401);
        }

        $user = $this->userRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user->passwordHash)) {
            throw new \RuntimeException('Invalid email or password', 401);
        }

        return [
            'token' => JwtHelper::createToken($user),
            'user' => $this->userToArray($user),
        ];
    }

    public function getUserFromToken(string $token): ?User
    {
        $payload = JwtHelper::validateToken($token);

        if (!$payload || !isset($payload->userId)) {
            return null;
        }

        return $this->userRepository->findById((int)$payload->userId);
    }

    private function validateRegistration(string $name, string $email, string $password): void
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Name is required', 400);
        }

        if ($email === '') {
            throw new \InvalidArgumentException('Email is required', 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email must be valid', 400);
        }

        if ($password === '') {
            throw new \InvalidArgumentException('Password is required', 400);
        }

        if (strlen($password) < 6) {
            throw new \InvalidArgumentException('Password must be at least 6 characters', 400);
        }
    }

    public function userToArray(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}
