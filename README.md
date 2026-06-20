# SK Production Hub

A full-stack web application for a music production hub, featuring a PHP REST API backend and a Vue 3 frontend.

## Architecture

This project consists of two main components:

- **Backend**: PHP REST API following MVC architecture patterns
- **Frontend**: Vue 3 application with Vite, Tailwind CSS, and Storybook

## Project Structure

```
sk-production-webdev2-project/
├── backend/          # PHP REST API
│   ├── app/          # Application code
│   ├── docker-compose.yml
│   └── README.md     # Backend documentation
└── frontend/         # Vue 3 application
    ├── src/          # Source code
    └── README.md     # Frontend documentation
```

## Quick Start

### Prerequisites

- **Docker and Docker Compose** for the backend
- **Node.js** ^20.19.0 or >=22.12.0 for the frontend
- **npm** or **yarn**

### Backend Setup

1. Navigate to the backend directory:

```bash
cd backend
```

2. Start Docker containers:

```bash
docker-compose up
```

The API will be available at **http://localhost**.

For detailed backend documentation, see [backend/README.md](./backend/README.md).

### Frontend Setup

1. Navigate to the frontend directory:

```bash
cd frontend
```

2. Install dependencies:

```bash
npm install
```

3. Start the development server:

```bash
npm run dev
```

The frontend will be available at **http://localhost:5173**.

For detailed frontend documentation, see [frontend/README.md](./frontend/README.md).
