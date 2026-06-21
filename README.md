# SK Production Hub

SK Production Hub is a curated music mix platform where visitors can discover approved mixes, users can submit mixes, comment, vote, and admins can approve, reject, edit, feature, and manage mixes.

This project is an improvement and refactor of a Web Development 1 project concept into a Web Development 2 architecture with a Vue frontend and a PHP REST API backend.

## Technologies Used

### Frontend

- Vue 3
- JavaScript
- Vue Router
- Pinia
- CSS / Tailwind CSS
- Fetch-based API helper in `frontend/src/utils/api.js`

Note: Axios is not installed in the current project. The API helper fills the same role by wrapping browser `fetch` calls and adding the JWT token when available.

### Backend

- PHP
- Composer
- FastRoute
- PDO
- firebase/php-jwt
- REST API

### Database / Environment

- MariaDB/MySQL
- Docker
- phpMyAdmin
- nginx

## Main Features

### Public Visitor

- Browse published mixes
- Search, filter, and paginate mixes
- View featured mixes
- View mix details
- Read comments and vote counts

### Logged-in User

- Register and login
- Submit a mix
- View own submissions
- Comment on mixes
- Like or dislike mixes

### Admin

- View pending submissions
- Approve or reject mixes
- Manage all mixes
- Edit and delete mixes
- Feature or unfeature mixes
- Delete comments

## Project Structure

```text
sk-production-webdev2-project/
  backend/
    app/
      public/
      src/
    database/
    docker-compose.yml
  frontend/
    src/
      components/
      router/
      stores/
      utils/
```

- `backend/` contains the PHP REST API, Docker setup, SQL seed file, models, repositories, services, and controllers.
- `frontend/` contains the Vue 3 application, Vue Router routes, Pinia auth store, components, pages, and API helper.

## Setup Quick Start

### Backend

```bash
cd backend
docker compose up -d --build
```

One-line version:

```bash
cd backend && docker compose up -d --build
```

### Frontend

```bash
cd frontend
npm install
npm run dev
```

One-line version:

```bash
cd frontend && npm install && npm run dev
```

### Database Reset

```bash
cd backend && docker compose down -v && docker compose up -d --build
```

## Local URLs

- Frontend: http://localhost:5173
- Backend API: http://localhost
- phpMyAdmin: http://localhost:8080

## Test Accounts

### Admin

- Email: `admin@skproduction.test`
- Password: `password123`

### User

- Email: `user@skproduction.test`
- Password: `password123`

## Documentation

- [Setup Guide](docs/SETUP.md)
- [API Endpoints](docs/API-ENDPOINTS.md)
- [Test Accounts](docs/TEST-ACCOUNTS.md)
- [AI Disclosure](AI-DISCLOSURE.md)
