# Setup Guide

This guide explains how to run SK Production Hub locally.

## Required Tools

- Git
- Docker Desktop
- Node.js and npm
- VS Code or another code editor

## Start the Backend

From the project root:

```bash
cd backend
docker compose up -d --build
```

This starts:

- nginx on http://localhost
- PHP application container
- MariaDB/MySQL database
- phpMyAdmin on http://localhost:8080

The database is seeded from:

```text
backend/database/init.sql
```

## Start the Frontend

Open a second terminal from the project root:

```bash
cd frontend
npm install
npm run dev
```

The frontend runs at:

```text
http://localhost:5173
```

## Reset the Database

If you need to reset the database back to the seed data, run this from the `backend` folder:

```bash
docker compose down -v
docker compose up -d --build
```

The `-v` flag removes the database volume, so MariaDB will recreate the database and import `backend/database/init.sql` again.

## Useful Local URLs

- Frontend: http://localhost:5173
- Backend API: http://localhost
- phpMyAdmin: http://localhost:8080

## Notes

- The backend must be running before most frontend pages can load data.
- If the frontend cannot connect to the API, check that the Docker containers are running.
- Demo users are seeded by the SQL file and are listed in [TEST-ACCOUNTS.md](TEST-ACCOUNTS.md).
