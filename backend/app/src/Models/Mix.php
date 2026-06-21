<?php

namespace App\Models;

class Mix
{
    public ?int $id;
    public string $title;
    public string $artist;
    public string $genre;
    public string $platform;
    public string $mixUrl;
    public string $coverImageUrl;
    public string $duration;
    public string $submittedBy;
    public string $submittedDate;
    public string $description;
    public string $status;
    public bool $featured;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->artist = $data['artist'] ?? '';
        $this->genre = $data['genre'] ?? '';
        $this->platform = $data['platform'] ?? '';
        $this->mixUrl = $data['mixUrl'] ?? '';
        $this->coverImageUrl = $data['coverImageUrl'] ?? '';
        $this->duration = $data['duration'] ?? '';
        $this->submittedBy = $data['submittedBy'] ?? '';
        $this->submittedDate = $data['submittedDate'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->status = $data['status'] ?? 'published';
        $this->featured = $data['featured'] ?? false;
    }
}
