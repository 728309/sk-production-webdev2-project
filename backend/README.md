# SK Production Hub Backend

This folder contains the PHP REST API for SK Production Hub. It uses Docker, nginx, PHP, Composer, FastRoute, PDO, MariaDB/MySQL, and JWT authentication.

The API supports public mix browsing, user accounts, mix submissions, comments, votes, and admin mix management.

## Local URLs

- Backend API: http://localhost
- phpMyAdmin: http://localhost:8080

## Project Structure

```text
backend/
  app/
    public/          API entry point and route dispatcher
    src/
      Controllers/   Request handlers
      Framework/     Base controller helper
      Models/        Simple data models
      Repositories/  Database queries
      Services/      Application logic
      Utils/         Database and JWT helpers
      data/          Backup JSON data
    composer.json
    composer.lock
  database/
    init.sql         Database schema and seed data
  docker-compose.yml
  nginx.conf
  PHP.Dockerfile
```

## Start Backend

```bash
cd backend
docker compose up -d --build
```

One-line version:

```bash
cd backend && docker compose up -d --build
```

## Reset Database

This removes the database volume and recreates the tables and seed data from `database/init.sql`.

```bash
cd backend
docker compose down -v
docker compose up -d --build
```

One-line version:

```bash
cd backend && docker compose down -v && docker compose up -d --build
```

## Test Accounts

Admin:

```text
email: admin@skproduction.test
password: password123
```

User:

```text
email: user@skproduction.test
password: password123
```

## API Overview

### Auth

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| POST | `/auth/register` | Public | Register a new user account. |
| POST | `/auth/login` | Public | Log in and receive a JWT token. |
| GET | `/auth/me` | User token required | Return the current user. |

### Public Mixes

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/mixes` | Public | Return published mixes with search, genre filtering, and pagination. |
| GET | `/mixes/featured` | Public | Return featured published mixes. |
| GET | `/mixes/{id}` | Public | Return one published mix by ID. Pending and rejected mixes return 404. |
| GET | `/mixes/{id}/comments` | Public | Return comments for a published mix. |
| GET | `/mixes/{id}/votes` | Public | Return vote counts for a published mix. |

### Logged-In User

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| POST | `/mixes` | User token required | Submit a new mix for review. |
| GET | `/my/mixes` | User token required | Return mixes submitted by the logged-in user. |
| POST | `/mixes/{id}/comments` | User token required | Add a comment to a published mix. |
| DELETE | `/comments/{id}` | User token required | Delete your own comment. Admins can delete any comment. |
| POST | `/mixes/{id}/votes` | User token required | Like or dislike a published mix. |

### Admin

All admin endpoints require a valid JWT token from an admin user.

| Method | Path | Description |
| --- | --- | --- |
| GET | `/admin/mixes` | Return all mixes, including pending, published, and rejected. |
| GET | `/admin/mixes/pending` | Return pending mix submissions. |
| PUT | `/admin/mixes/{id}` | Edit a mix. |
| DELETE | `/admin/mixes/{id}` | Delete a mix and related comments/votes. |
| PUT | `/admin/mixes/{id}/approve` | Approve and publish a mix. |
| PUT | `/admin/mixes/{id}/reject` | Reject a mix with a review note. |
| PUT | `/admin/mixes/{id}/feature` | Mark a mix as featured. |
| PUT | `/admin/mixes/{id}/unfeature` | Remove a mix from featured mixes. |

## Database

The database name is `developmentdb`. The development user is `developer` with password `secret123`.

Tables and starter data are created from `database/init.sql` when the database container is created. The old `app/src/data/mixes.json` file is kept only as backup data and is not used by the API.
