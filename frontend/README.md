# SK Production Hub Frontend

This folder contains the Vue 3 frontend for SK Production Hub.

The app lets visitors browse published music mixes, view featured mixes, open mix details, read comments, and see vote counts. Logged-in users can submit mixes, view their own submissions, comment, and vote. Admin users can review and manage mixes through protected admin pages.

## Tech Stack

- Vue 3
- Vite
- JavaScript
- Vue Router
- Pinia
- Tailwind CSS
- Storybook

API requests are made through `src/utils/api.js`, which wraps browser `fetch` and adds the JWT authorization header when a token exists.

## Project Structure

```text
src/
  assets/        Global CSS
  components/    Reusable Vue components and page views
  router/        Vue Router setup
  stores/        Pinia stores
  utils/         API helper functions
  config.js      Frontend configuration
  main.js        App entry point
```

Some component filenames are inherited from the original boilerplate. They now display mix data in the SK Production Hub interface.

## Setup

```bash
npm install
npm run dev
```

One-line version from the project root:

```bash
cd frontend && npm install && npm run dev
```

The frontend runs at:

```text
http://localhost:5173
```

The backend API should be running at:

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

## Main Pages

- `/` - Home page with featured mixes
- `/mixes` - Public mix archive with search, filter, and pagination
- `/mixes/:id` - Mix detail page
- `/login` - Login page
- `/register` - Register page
- `/submit` - Submit a mix
- `/my-submissions` - User submissions
- `/admin/pending` - Admin pending review
- `/admin/mixes` - Admin mix management
