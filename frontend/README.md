# SK Production Hub Frontend

This folder contains the Vue 3 frontend for SK Production Hub. It is a single-page application for browsing published mixes, submitting new mixes, commenting, voting, and managing admin review workflows.

## Stack

| Tool | Purpose |
| --- | --- |
| Vue 3 | Component framework |
| Vite | Development server and build tool |
| JavaScript | Frontend language |
| Vue Router | Page routing and route guards |
| Pinia | Auth state management |
| CSS/Tailwind utilities | Responsive styling |
| Storybook | Component previews |

## Project Layout

```text
frontend/src/
  api/          Feature API modules
  assets/       Global CSS
  components/   Reusable UI and pages
  router/       Route definitions and guards
  stores/       Pinia stores
  utils/        Shared API fetch helper
  config.js     Runtime API base URL
```

## API Structure

The frontend keeps one shared fetch helper in `src/utils/api.js`. Feature modules in `src/api/` wrap the actual backend endpoints:

- `authApi.js`
- `mixApi.js`
- `commentApi.js`
- `voteApi.js`

The shared helper adds the JWT bearer token from local storage when available. Pages use the feature API modules, which keeps page code easier to explain.

## Environment Variables

`frontend/.env.example` contains the development default:

```text
VITE_API_BASE_URL=http://localhost
```

The current config falls back to `http://localhost`, so Docker development works without extra setup.

## Setup

```bash
cd frontend
npm install
npm run dev
```

Open:

```text
http://localhost:5173
```

The backend should be running at:

```text
http://localhost
```

## Useful Scripts

```bash
npm run dev
npm run build
npm run preview
npm run storybook
```

## Routes

| Path | Purpose |
| --- | --- |
| `/` | Home page with featured mixes |
| `/mixes` | Public mix archive with search, filter, and pagination |
| `/mixes/:id` | Published mix detail page |
| `/login` | Login |
| `/register` | Register |
| `/submit` | Submit a mix, login required |
| `/my-submissions` | User submissions, login required |
| `/admin/pending` | Pending review queue, admin required |
| `/admin/mixes` | Admin management table, admin required |
| `/about` | Project/context page |
| `/contact` | Contact page |

## User Flow

- Visitors can browse and inspect published mixes.
- Logged-in users can submit mixes, comment, and vote.
- Admin users get admin links inside the My Account dropdown.
- Route guards prevent unauthenticated users from opening protected pages.
- Backend role checks still protect admin endpoints even if someone manually enters a URL.

## Responsiveness And Accessibility

- The header has a desktop menu and mobile menu.
- Cards collapse into one column on small screens.
- Admin tables use horizontal scrolling on smaller screens.
- Forms use labels and appropriate input types.
- Buttons and links have clear visual states.
