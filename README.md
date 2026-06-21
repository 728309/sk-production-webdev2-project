# SK Production Hub

SK Production Hub is a music mix submission and review platform. Visitors can browse approved mixes, registered users can submit mixes and interact with published mixes, and admins can review the queue before content appears publicly.

This project rebuilds an earlier Web Development 1 idea into a Web Development 2 architecture: a Vue 3 frontend, a PHP REST API backend, MariaDB/MySQL persistence, Docker services, JWT authentication, and separate user/admin roles.

## Who Is This App For?

- Visitors who want to discover curated music mixes.
- Producers, DJs, or community members who want to submit a mix.
- Admin reviewers who need a small dashboard for approving, rejecting, editing, featuring, and deleting submissions.
- Teachers reviewing a Web Development 2 project with frontend, backend, database, authentication, and role authorization.

## Why This Project?

The topic is small enough to explain clearly but complete enough to prove full-stack skills. It demonstrates public browsing, protected user actions, admin-only workflows, relational data, REST endpoints, reusable Vue components, and a Docker-based development setup.

## Tech Stack

| Layer | Technology | Purpose |
| --- | --- | --- |
| Frontend | Vue 3, Vite, JavaScript | Single-page app and page rendering |
| Routing/state | Vue Router, Pinia | Routes, guards, login state |
| Styling | CSS, Tailwind utility classes | Responsive layout and reusable UI styling |
| API client | Browser `fetch` via `frontend/src/utils/api.js` | Shared request helper with JWT headers |
| Backend | PHP, FastRoute | REST API routing and controllers |
| Backend structure | Controller -> Service -> Repository | Clear separation between requests, logic, and database access |
| Database | MariaDB/MySQL, PDO | Relational storage with prepared statements |
| Auth | JWT bearer tokens, password hashing | Login sessions and protected routes |
| Environment | Docker, nginx, phpMyAdmin | Local backend/database stack |

## Main Features

### Public Visitor

- Browse published mixes only.
- Search mixes by title, artist, genre, or description.
- Filter by genre and use pagination.
- View featured mixes on the home page.
- Open a mix detail page.
- Play supported SoundCloud, Mixcloud, and YouTube mix links in an embedded player.
- Read comments and vote counts.

### Logged-In User

- Register and log in.
- Submit a mix for admin review.
- View personal submissions and review status.
- Comment on published mixes.
- Like or dislike published mixes.
- Delete own comments.

### Admin

- View pending mix submissions.
- Approve a mix so it becomes public.
- Reject a mix with a review note.
- View all mixes with search, genre, status, and pagination filters.
- Edit mix details.
- Feature or unfeature mixes.
- Delete mixes and related comments/votes.
- Delete any comment when needed.

## Architecture

### Frontend Structure

```text
frontend/src/
  api/          Feature-specific API modules
  assets/       Global CSS
  components/   Atoms, molecules, organisms, templates, and pages
  router/       Vue Router routes and guards
  stores/       Pinia auth store
  utils/        Shared fetch helper
```

The frontend keeps raw endpoint calls in `src/api/` modules such as `mixApi.js`, `authApi.js`, `commentApi.js`, and `voteApi.js`. Pages call those modules instead of building every request inline.

### Backend Structure

```text
backend/app/src/
  Controllers/   HTTP request/response handling
  Services/      Business rules and validation
  Repositories/  PDO database queries
  Models/        Simple data objects
  Framework/     Shared controller helpers
  Utils/         Database and JWT utilities
```

Controllers parse requests and return JSON. Services hold rules such as registration validation, vote validation, and comment validation. Repositories use PDO prepared statements for database access.

### Database

The schema and seed data are in `backend/database/init.sql`. Docker imports this file when the MariaDB volume is first created. The database includes users, mixes, comments, and votes.

### Authentication And Authorization

- Passwords are stored with PHP `password_hash`.
- Successful login returns a JWT.
- The frontend stores the JWT in local storage and sends it as `Authorization: Bearer TOKEN`.
- Backend user routes require a valid token.
- Backend admin routes require a valid token and the `admin` role.

## Security Notes

- PDO prepared statements are used for database queries.
- Public mix endpoints only return `published` mixes.
- Comments and votes only work on published mixes.
- Admin routes are checked in the PHP backend, not only in the frontend.
- Password hashes are not returned in API responses.
- Invalid JSON returns a JSON `400` error.
- Missing/invalid tokens return JSON `401` errors.
- Forbidden admin actions return JSON `403` errors.
- Unexpected backend errors return a generic JSON `500` response.

## Responsiveness And Accessibility

- Main pages use responsive cards, forms, and table wrappers.
- The navbar has desktop and mobile layouts.
- Forms use visible labels and HTML input types where possible.
- Buttons and links keep clear focus/hover states.
- Admin tables scroll horizontally on smaller screens instead of breaking layout.

## Setup Quick Start

Start the backend:

```bash
cd backend
docker compose up -d --build
```

Start the frontend in a second terminal:

```bash
cd frontend
npm install
npm run dev
```

Local URLs:

- Frontend: http://localhost:5173
- Backend API: http://localhost
- Backend health: http://localhost/health
- Database health: http://localhost/health/db
- phpMyAdmin: http://localhost:8080

## Test Accounts

Admin:

- Email: `admin@skproduction.test`
- Password: `password123`

User:

- Email: `user@skproduction.test`
- Password: `password123`

## Teacher Demo Flow

1. Open `http://localhost:5173`.
2. Browse featured mixes on the home page.
3. Go to `/mixes`, test search, genre filtering, and pagination.
4. Open `/mixes/1`, check details, comments, and vote counts.
5. Log in as the normal user and submit a mix at `/submit`.
6. Open `/my-submissions` to show the pending submission.
7. Log out, then log in as the admin.
8. Open `/admin/pending` and approve or reject a pending mix.
9. Open `/admin/mixes` to show admin filters, edit, feature/unfeature, and delete actions.
10. Open `http://localhost/health` and `http://localhost/health/db` to verify backend and database status.

## Final Moodle Zip

Include:

- `README.md`, `AI-DISCLOSURE.md`, and the `docs/` folder.
- `frontend/` source files, `package.json`, and `package-lock.json`.
- `backend/` source files, `composer.json`, and `composer.lock`.
- Docker files, nginx config, and `backend/database/init.sql`.
- `.env.example` files.

Do not include:

- `.git/`
- `frontend/node_modules/`
- `frontend/dist/`
- `backend/app/vendor/`
- Real `.env` files or secrets

The lock files should stay included because they help reproduce the dependency versions.

## Documentation

- [Setup Guide](docs/SETUP.md)
- [API Endpoints](docs/API-ENDPOINTS.md)
- [Test Accounts](docs/TEST-ACCOUNTS.md)
- [AI Disclosure](AI-DISCLOSURE.md)
