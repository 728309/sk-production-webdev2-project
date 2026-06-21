# SK Production Hub Backend

This folder contains the PHP REST API for SK HUB. It provides authentication, public mix browsing, user submissions, comments, votes, admin review actions, and health checks.

## Stack

| Tool | Purpose |
| --- | --- |
| PHP | Backend language |
| FastRoute | Request routing |
| PDO | Database access with prepared statements |
| MariaDB/MySQL | Relational database |
| firebase/php-jwt | JWT creation and validation |
| Composer | PHP dependency management |
| Docker, nginx | Local backend runtime |
| phpMyAdmin | Database inspection during development |

## Project Layout

```text
backend/
  app/
    public/index.php      Route entry point
    src/
      Controllers/        HTTP controllers
      Framework/          Shared controller helpers
      Models/             Simple model classes
      Repositories/       PDO database queries
      Services/           Business logic and validation
      Utils/              Database and JWT helpers
    composer.json
    composer.lock
  database/init.sql       Schema and seed data
  docker-compose.yml
  nginx.conf
  PHP.Dockerfile
  .env.example
```

## Architecture

The backend follows a clear Controller -> Service -> Repository structure.

- Controllers read route variables, JSON bodies, and auth state, then return JSON.
- Services contain rules such as registration validation, submission validation, comment validation, and vote validation.
- Repositories contain SQL and use PDO prepared statements.
- The shared controller base class centralizes JSON body parsing, bearer token reading, user/admin checks, and consistent JSON error responses.

This separation keeps the code beginner-friendly: route handling stays in controllers, business decisions stay in services, and database details stay in repositories.

## Environment Variables

`backend/.env.example` documents the development defaults:

```text
DB_HOST=mysql
DB_NAME=developmentdb
DB_USER=developer
DB_PASSWORD=secret123
JWT_SECRET=sk-production-dev-secret
```

The app can read these values from the environment. If no environment variables are set, it falls back to the Docker development defaults, so the existing Docker setup still works.

## Start Backend

```bash
cd backend
docker compose up -d --build
```

Verify:

- API: http://localhost/health
- Database: http://localhost/health/db
- phpMyAdmin: http://localhost:8080

## Reset Database

```bash
cd backend
docker compose down -v
docker compose up -d --build
```

The `-v` flag removes the database volume. MariaDB then imports `database/init.sql` again.

## API Route Groups

- `POST /auth/register`, `POST /auth/login`, `GET /auth/me`
- `GET /mixes`, `GET /mixes/featured`, `GET /mixes/{id}`
- `POST /mixes`, `GET /my/mixes`
- `GET/POST /mixes/{id}/comments`, `DELETE /comments/{id}`
- `GET/POST /mixes/{id}/votes`
- `GET/PUT/DELETE /admin/mixes...`
- `PUT /mixes/{id}`, `DELETE /mixes/{id}` as admin-token REST aliases for the main admin update/delete routes
- `GET /health`, `GET /health/db`

See [../docs/API-ENDPOINTS.md](../docs/API-ENDPOINTS.md) for the full table.

## Security And Error Handling

- Passwords are hashed with `password_hash`.
- JWTs are signed and validated in `JwtHelper`.
- User routes call `requireUser`.
- Admin routes call `requireAdmin`.
- Admin authorization is enforced in the PHP backend, not only by frontend route guards.
- Public mix endpoints filter to `published` mixes.
- Comments and votes reject unpublished mixes.
- PDO prepared statements are used for queries.
- API errors are returned as JSON: `400`, `401`, `403`, `404`, `405`, `409`, and generic `500`.
- Password hashes are used internally only and are not returned by auth responses.

## Seed Accounts

Admin:

- Email: `admin@skproduction.test`
- Password: `password123`

User:

- Email: `user@skproduction.test`
- Password: `password123`
