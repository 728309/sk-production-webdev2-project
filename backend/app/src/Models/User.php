<?php

namespace App\Models;

class User
{
    public ?int $id;
    public string $name;
    public string $email;
    public string $passwordHash;
    public string $role;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->passwordHash = $data['passwordHash'] ?? $data['password_hash'] ?? '';
        $this->role = $data['role'] ?? 'user';
    }
}
