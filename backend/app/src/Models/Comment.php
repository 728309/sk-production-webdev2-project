<?php

namespace App\Models;

class Comment
{
    public ?int $id;
    public int $mixId;
    public int $userId;
    public string $userName;
    public string $body;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->mixId = $data['mixId'] ?? 0;
        $this->userId = $data['userId'] ?? 0;
        $this->userName = $data['userName'] ?? '';
        $this->body = $data['body'] ?? '';
        $this->createdAt = $data['createdAt'] ?? '';
        $this->updatedAt = $data['updatedAt'] ?? '';
    }
}
