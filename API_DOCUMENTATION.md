# KPRI Admin API Documentation

This document outlines the JWT-based authentication API for the KPRI Admin system.

## Authentication Endpoints

### Login

Authenticates a user and returns a JWT token.

```
POST /api/auth/login
```

**Request Body:**

```json
{
    "username": "kpriadmin",
    "password": "password123"
}
```

**Response (200 OK):**

```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id_user": 1,
        "nama_user": "KPRI Admin",
        "username": "kpriadmin",
        "id_role": 1
    },
    "role": "kpri admin"
}
```

### Get User Profile

Returns the authenticated user's profile information.

```
GET /api/auth/me
```

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response (200 OK):**

```json
{
    "user": {
        "id_user": 1,
        "nama_user": "KPRI Admin",
        "username": "kpriadmin",
        "id_role": 1
    },
    "role": "kpri admin"
}
```

### Refresh Token

Refreshes the JWT token.

```
POST /api/auth/refresh
```

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response (200 OK):**

```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id_user": 1,
        "nama_user": "KPRI Admin",
        "username": "kpriadmin",
        "id_role": 1
    },
    "role": "kpri admin"
}
```

### Logout

Invalidates the JWT token.

```
POST /api/auth/logout
```

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response (200 OK):**

```json
{
    "message": "Successfully logged out"
}
```

## Role-Based Routes

### KPRI Admin Dashboard

Accessible only to users with the 'kpri admin' role.

```
GET /api/admin/dashboard
```

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response (200 OK):**

```json
{
    "message": "KPRI Admin Dashboard"
}
```

### Admin Shop Dashboard

Accessible only to users with the 'admin shop' role.

```
GET /api/shop/dashboard
```

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response (200 OK):**

```json
{
    "message": "Shop Admin Dashboard"
}
```

## Error Responses

### Authentication Failed (401 Unauthorized)

```json
{
    "error": "Unauthorized"
}
```

### Validation Error (422 Unprocessable Entity)

```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "username": ["The username field is required"],
        "password": ["The password field is required"]
    }
}
```

### Role Authorization Error (403 Forbidden)

```json
{
    "error": "Unauthorized. Role required: kpri admin"
}
``` 