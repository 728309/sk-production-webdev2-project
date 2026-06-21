# API Endpoints

Base URL for local development:

```text
http://localhost
```

Authentication uses a JWT in the `Authorization` header:

```text
Authorization: Bearer TOKEN
```

## Auth

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| POST | `/auth/register` | Public | Register a new user account. |
| POST | `/auth/login` | Public | Log in and receive a JWT token. |
| GET | `/auth/me` | User token required | Return the currently logged-in user. |

## Mixes

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/mixes` | Public | Return published mixes with pagination, search, and genre filtering. |
| GET | `/mixes/featured` | Public | Return featured published mixes for the home page. |
| GET | `/mixes/{id}` | Public | Return one published mix by ID. Pending and rejected mixes return 404 on this public endpoint. |
| POST | `/mixes` | User token required | Submit a new mix. Submitted mixes start as pending. |
| GET | `/my/mixes` | User token required | Return mixes submitted by the logged-in user. |

Supported query parameters for `GET /mixes`:

- `page`
- `limit`
- `genre`
- `search`

## Comments

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/mixes/{id}/comments` | Public | Return comments for a published mix. Pending and rejected mixes return 404. |
| POST | `/mixes/{id}/comments` | User token required | Add a comment to a published mix. Pending and rejected mixes return 404. |
| DELETE | `/comments/{id}` | User token required | Delete a comment. Admins can delete any comment. Users can delete their own comments. |

## Votes

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/mixes/{id}/votes` | Public | Return like and dislike counts for a published mix. If a valid token is included, also returns the user's vote. Pending and rejected mixes return 404. |
| POST | `/mixes/{id}/votes` | User token required | Like or dislike a published mix. Sending a different vote updates the existing vote. Pending and rejected mixes return 404. |

## Admin

All admin endpoints require a valid JWT token from a user with the `admin` role.

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/admin/mixes` | Admin token required | Return all mixes, including pending, published, and rejected. Supports pagination and filters. |
| GET | `/admin/mixes/pending` | Admin token required | Return only pending mix submissions. |
| PUT | `/admin/mixes/{id}` | Admin token required | Edit a mix. |
| DELETE | `/admin/mixes/{id}` | Admin token required | Delete a mix. |
| PUT | `/admin/mixes/{id}/approve` | Admin token required | Approve a pending mix and publish it. |
| PUT | `/admin/mixes/{id}/reject` | Admin token required | Reject a pending mix with a review note. |
| PUT | `/admin/mixes/{id}/feature` | Admin token required | Mark a mix as featured. |
| PUT | `/admin/mixes/{id}/unfeature` | Admin token required | Remove a mix from featured mixes. |

Supported query parameters for `GET /admin/mixes`:

- `page`
- `limit`
- `search`
- `genre`
- `status`
