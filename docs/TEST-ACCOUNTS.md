# Test Accounts

These accounts are seeded by `backend/database/init.sql` for local SK HUB development and grading.

## Admin Account

- Email: `admin@skproduction.test`
- Password: `password123`
- Role: `admin`

Use this account to demonstrate:

- Pending review queue
- Approve and reject workflows
- Admin mix table
- Search, genre, and status filters
- Edit mix details
- Feature and unfeature actions
- Delete mix action
- Admin-only route protection

## User Account

- Email: `user@skproduction.test`
- Password: `password123`
- Role: `user`

Use this account to demonstrate:

- Login with JWT token
- Mix submission
- My Submissions page
- Comment creation
- Vote creation or update
- User-only route protection

## Public Visitor Demo

Without logging in, demonstrate:

- Home page featured mixes
- Mix archive search/filter/pagination
- Mix detail page
- Public comments
- Public vote counts

## Suggested Grading Order

1. Visitor flow: `/`, `/mixes`, `/mixes/1`.
2. User flow: login, `/submit`, `/my-submissions`, comment, vote.
3. Admin flow: login, `/admin/pending`, `/admin/mixes`.
4. API health: `/health`, `/health/db`.
5. Documentation: README, setup guide, API endpoints, AI disclosure.

This order shows public browsing first, then authenticated user actions, then backend-protected admin actions.

## Password Note

The demo passwords are intentionally simple because this is a local assignment project. Do not reuse these passwords for real accounts.
