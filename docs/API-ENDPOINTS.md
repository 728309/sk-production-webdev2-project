# API Endpoints

Local base URL:

```text
http://localhost
```

Authenticated requests use:

```text
Authorization: Bearer TOKEN
```

All responses are JSON.

## Health

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/health` | Public | Verifies that the API route layer is running. |
| GET | `/health/db` | Public | Verifies that the API can connect to the database. |

## Auth

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| POST | `/auth/register` | Public | Create a normal user account. |
| POST | `/auth/login` | Public | Log in and receive a JWT token. |
| GET | `/auth/me` | User token | Return the current user. |

Register body:

```json
{
  "name": "Demo User",
  "email": "demo@example.com",
  "password": "password123"
}
```

Login body:

```json
{
  "email": "user@skproduction.test",
  "password": "password123"
}
```

## Public Mixes

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/mixes` | Public | Returns published mixes only. Supports pagination, search, and genre filtering. |
| GET | `/mixes/featured` | Public | Returns featured published mixes for the home page. |
| GET | `/mixes/{id}` | Public | Returns one published mix. Pending or rejected mixes return `404`. |

Supported query parameters for `GET /mixes`:

- `page`
- `limit`
- `genre`
- `search`

## User Mixes

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| POST | `/mixes` | User token | Submit a new mix. New submissions start as `pending`. |
| GET | `/my/mixes` | User token | Return mixes submitted by the logged-in user. |
| GET | `/my/mixes/summary` | User token | Return submission counts for total, pending, approved, and rejected mixes. |

Submission body:

```json
{
  "title": "Late Night Mix",
  "artist": "DJ Demo",
  "genre": "House",
  "platform": "SoundCloud",
  "mixUrl": "https://example.com/mix",
  "coverImageUrl": "https://example.com/image.jpg",
  "duration": "42:18",
  "description": "Short description of the mix."
}
```

## Comments

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/mixes/{id}/comments` | Public | Return comments for a published mix. |
| POST | `/mixes/{id}/comments` | User token | Add a comment to a published mix. |
| DELETE | `/comments/{id}` | User token | Delete own comment. Admins can delete any comment. |

Comment body:

```json
{
  "body": "Great selection."
}
```

Comments are blocked for pending or rejected mixes.

## Votes

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/mixes/{id}/votes` | Public | Return like/dislike counts. If a valid token is sent, also returns the user's vote. |
| POST | `/mixes/{id}/votes` | User token | Create or update the logged-in user's vote. |

Vote body:

```json
{
  "voteType": "like"
}
```

Allowed vote types are `like` and `dislike`. Votes are blocked for pending or rejected mixes.

## Admin Mixes

All admin routes require a JWT for a user with role `admin`.

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| GET | `/admin/mixes` | Admin token | Return all mixes, including pending, published, and rejected. |
| GET | `/admin/mixes/pending` | Admin token | Return only pending submissions. |
| PUT | `/admin/mixes/{id}` | Admin token | Edit mix details, status, and featured flag. |
| DELETE | `/admin/mixes/{id}` | Admin token | Delete a mix and related comments/votes. |
| PUT | `/admin/mixes/{id}/approve` | Admin token | Publish a pending mix. |
| PUT | `/admin/mixes/{id}/reject` | Admin token | Reject a mix with a review note. |
| PUT | `/admin/mixes/{id}/feature` | Admin token | Mark a mix as featured. |
| PUT | `/admin/mixes/{id}/unfeature` | Admin token | Remove a mix from featured mixes. |

Admin REST aliases:

| Method | Path | Auth | Description |
| --- | --- | --- | --- |
| PUT | `/mixes/{id}` | Admin token | REST alias for `PUT /admin/mixes/{id}`. |
| DELETE | `/mixes/{id}` | Admin token | REST alias for `DELETE /admin/mixes/{id}`. |

Supported query parameters for `GET /admin/mixes`:

- `page`
- `limit`
- `search`
- `genre`
- `status`

Reject body:

```json
{
  "reviewNote": "Please add a working mix link."
}
```

Admin update body uses the same fields as mix submission plus:

```json
{
  "status": "published",
  "featured": true
}
```

## Error Responses

| Status | Meaning |
| --- | --- |
| `400` | Validation problem or invalid JSON body |
| `401` | Missing, invalid, or expired token |
| `403` | Logged-in user does not have permission |
| `404` | Route, mix, or comment not found |
| `405` | Existing route called with the wrong HTTP method |
| `409` | Duplicate email during registration |
| `500` | Generic internal server error |

Example:

```json
{
  "error": "Invalid token"
}
```
