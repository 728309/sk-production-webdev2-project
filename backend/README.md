# Mixes API

A RESTful API for managing mixes built with PHP, following MVC architecture patterns.

## Project Structure

```
backend/
├── app/
│   ├── public/
│   │   └── index.php          # Entry point and route dispatcher
│   └── src/
│       ├── Controllers/       # Request handlers
│       ├── Services/          # Business logic layer
│       ├── Repositories/      # Data access layer
│       ├── Models/            # Data models
│       ├── Framework/         # Base controller class
│       ├── Utils/             # Utility classes (JsonStore)
│       └── data/              # JSON data storage
├── docker-compose.yml         # Docker services configuration
├── nginx.conf                 # Nginx configuration
└── PHP.Dockerfile             # PHP Docker image configuration
```

## API Endpoints

### Get All Mixes

```
GET /mixes
```

Returns a list of all mixes.

### Get Mix by ID

```
GET /mixes/{id}
```

Returns a specific mix by its ID.

### Create Mix

```
POST /mixes
Content-Type: application/json

{
    "title": "Rooftop House Session",
    "artist": "Lena Cole",
    "genre": "House",
    "platform": "SoundCloud",
    "mixUrl": "https://soundcloud.com/skproductionhub/rooftop-house-session",
    "coverImageUrl": "https://images.unsplash.com/photo-1501386761578-eac5c94b800a",
    "duration": "46:09",
    "submittedBy": "Maya Brooks",
    "submittedDate": "2026-05-12",
    "description": "Uplifting house selections with soulful vocals.",
    "status": "published",
    "featured": true
}
```

Creates a new mix and returns it with the assigned ID.

### Update Mix

```
PUT /mixes/{id}
Content-Type: application/json

{
    "title": "Updated Mix Title",
    "artist": "Lena Cole",
    "genre": "House",
    "platform": "SoundCloud",
    "mixUrl": "https://soundcloud.com/skproductionhub/updated-mix",
    "coverImageUrl": "https://images.unsplash.com/photo-1501386761578-eac5c94b800a",
    "duration": "48:10",
    "submittedBy": "Maya Brooks",
    "submittedDate": "2026-05-16",
    "description": "Updated mix description.",
    "status": "published",
    "featured": false
}
```

Updates an existing mix by ID.

### Delete Mix

```
DELETE /mixes/{id}
```

Deletes a mix by ID.

## Getting Started

### Prerequisites

- Docker and Docker Compose

### Installation

1. Go to the backend folder:

```bash
cd backend
```

2. Start the Docker containers:

```bash
docker-compose up
```

3. Run composer commands when needed:

```bash
docker-compose exec php composer [...]
```

### Running the Application

The application will be available at:

- **API**: http://localhost
- **phpMyAdmin**: http://localhost:8080

## Docker Services

- **nginx**: Web server (port 80)
- **php**: PHP-FPM service
- **mysql**: MariaDB database (port 3306)
  - Username: `developer`
  - Password: `secret123`
  - Database: `developmentdb`
- **phpmyadmin**: Database management interface (port 8080)

## Data Storage

Currently, the application uses JSON file-based storage (`app/src/data/mixes.json`) for demonstration purposes. In production, this should be replaced with a proper database implementation.
