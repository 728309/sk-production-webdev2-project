<?php

namespace App\Repositories;

use App\Inheritance\Repositories\IUserRepository;
use App\Models\User;
use App\Utils\Database;
use PDO;

class UserRepository implements IUserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    public function findByEmail(string $email): ?User
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE email = :email');
        $statement->execute(['email' => $email]);

        $row = $statement->fetch();
        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    public function findById(int $id): ?User
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();
        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    public function create(User $user): User
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (name, email, password_hash, role)
            VALUES (:name, :email, :password_hash, :role)'
        );

        $statement->execute([
            'name' => $user->name,
            'email' => $user->email,
            'password_hash' => $user->passwordHash,
            'role' => $user->role,
        ]);

        $user->id = (int)$this->connection->lastInsertId();

        return $user;
    }

    private function mapRowToUser(array $row): User
    {
        return new User([
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'password_hash' => $row['password_hash'],
            'role' => $row['role'],
        ]);
    }
}
