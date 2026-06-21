# Setup Guide

This guide explains how to run SK Production Hub locally for development or grading.

## Required Tools

- Docker Desktop
- Node.js and npm
- Git
- A code editor such as VS Code

## Project Services

| Service | URL | Purpose |
| --- | --- | --- |
| Frontend | http://localhost:5173 | Vue app |
| Backend API | http://localhost | PHP REST API |
| API health | http://localhost/health | Quick backend check |
| DB health | http://localhost/health/db | Quick database check |
| phpMyAdmin | http://localhost:8080 | Database inspection |

## Environment Files

Development examples are included:

- `backend/.env.example`
- `frontend/.env.example`

They document the local defaults used by Docker:

```text
DB_HOST=mysql
DB_NAME=developmentdb
DB_USER=developer
DB_PASSWORD=secret123
JWT_SECRET=sk-production-dev-secret
VITE_API_BASE_URL=http://localhost
```

For this assignment setup, you do not need to create real `.env` files unless you want to customize values. Real `.env` files should not be included in the Moodle zip.

## Start The Backend

From the project root:

```bash
cd backend
docker compose up -d --build
```

This starts:

- nginx on port `80`
- PHP app container
- MariaDB/MySQL database
- phpMyAdmin on port `8080`

Docker imports the schema and seed data from:

```text
backend/database/init.sql
```

## Start The Frontend

Open a second terminal:

```bash
cd frontend
npm install
npm run dev
```

Open:

```text
http://localhost:5173
```

## Verify The Backend

Open these URLs in the browser:

```text
http://localhost/health
http://localhost/health/db
```

Expected result: JSON with `"status": "ok"`.

You can also test:

```text
http://localhost/mixes
http://localhost/mixes/featured
```

## Reset The Database

Use this when you want the seed data back:

```bash
cd backend
docker compose down -v
docker compose up -d --build
```

The `-v` flag removes the database volume. When the database starts again, MariaDB recreates the tables from `init.sql`.

## Demo Accounts

Admin:

- Email: `admin@skproduction.test`
- Password: `password123`

User:

- Email: `user@skproduction.test`
- Password: `password123`

## Teacher Demo Flow

1. Start Docker backend and Vite frontend.
2. Open `/` and show featured mixes.
3. Open `/mixes`, search for a mix, filter by genre, and use pagination.
4. Open `/mixes/1` and show details, comments, and votes.
5. Log in as `user@skproduction.test`.
6. Submit a mix at `/submit`.
7. Open `/my-submissions` and show the pending status.
8. Log in as `admin@skproduction.test`.
9. Open `/admin/pending` and approve or reject a submission.
10. Open `/admin/mixes` and show filtering, edit, feature/unfeature, and delete controls.
11. Open `/health` and `/health/db` to show backend/database status.

## Final Moodle Zip Checklist

Include:

- Source code in `frontend/` and `backend/`
- `package-lock.json` and `composer.lock`
- `backend/database/init.sql`
- Docker and nginx files
- `.env.example` files
- `README.md`, `AI-DISCLOSURE.md`, and `docs/`

Exclude:

- `.git/`
- `frontend/node_modules/`
- `frontend/dist/`
- `backend/app/vendor/`
- `.env` and `.env.*` files with real secrets

## Troubleshooting

- If the frontend cannot load data, confirm Docker containers are running.
- If `/health/db` fails, reset the database volume and rebuild.
- If ports `80`, `3306`, `5173`, or `8080` are already in use, stop the conflicting service or change the Docker/Vite ports.
- If login fails after editing seed data, reset the database volume.
