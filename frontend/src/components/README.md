# Components

This folder keeps the SK HUB interface organized with a simple Atomic Design structure.

## Folder Structure

```text
components/
  atoms/       Small reusable UI pieces
  molecules/   Small combinations of atoms
  organisms/   Larger interface sections
  templates/   Page layouts that receive data through props
  pages/       Route-level pages
```

## How To Read It

- Atoms should stay small and reusable.
- Molecules combine atoms into a focused UI pattern.
- Organisms are larger sections such as the header, footer, cards, and detail views.
- Templates arrange organisms into reusable page layouts.
- Pages connect route behavior, API calls, and page-specific state.

This keeps the frontend easier to explain: layout and styling live in components, while API calls and route behavior stay in pages, stores, and API modules.
