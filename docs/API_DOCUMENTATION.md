# ArRahnu Auction API Documentation

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
The API uses Laravel Sanctum for authentication. After login, include the Bearer token in the Authorization header:

```
Authorization: Bearer {your-token-here}
```

## Response Format
All API responses follow this format:

```json
{
    "success": true|false,
    "message": "Response message",
    "data": {}, // Optional data object
    "errors": {} // Optional validation errors
}
```

## Endpoints

### 1. Health Check
**GET** `/health`

Check if the API is running.

**Response:**
```json
{
    "success": true,
    "message": "API is running",
    "timestamp": "2025-06-03T14:30:00.000000Z",
    "version": "1.0.0"
}
```

### 2. API Information
**GET** `/info`

Get API information and available endpoints.

**Response:**
```json
{
    "success": true,
    "data": {
        "api_name": "ArRahnu Auction API",
        "version": "1.0.0",
        "description": "API for ArRahnu Auction Online Platform",
        "endpoints": {...},
        "authentication": {...}
    }
}
```

## Authentication Endpoints

### 3. Register User
**POST** `/auth/register`

Register a new user account.

**Request Body:**
```json
{
    "full_name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone_number": "+1234567890",
    "role": "bidder"
}
```

**Validation Rules:**
- `full_name`: required, string, max 255 characters
- `username`: required, string, max 255 characters, unique
- `email`: required, valid email, max 255 characters, unique
- `password`: required, string, min 8 characters, confirmed
- `phone_number`: optional, string, max 20 characters
- `role`: optional, one of: maker, checker, bidder (default: bidder)

**Response (201):**
```json
{
    "success": true,
    "message": "Registration successful. Your account is pending approval.",
    "data": {
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "phone_number": "+1234567890",
            "role": "bidder",
            "status": "pending_approval"
        }
    }
}
```

### 4. Login User
**POST** `/auth/login`

Login with email and password.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123",
    "remember": false
}
```

**Response (200) - Without 2FA:**
```json
{
    "success": true,
    "message": "Login successful",
    "requires_2fa": false,
    "data": {
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "phone_number": "+1234567890",
            "role": "bidder",
            "is_admin": false,
            "status": "active"
        },
        "token": "your-api-token",
        "token_type": "Bearer"
    }
}
```

**Response (200) - With 2FA:**
```json
{
    "success": true,
    "message": "A verification code has been sent to your email.",
    "requires_2fa": true,
    "data": {
        "user_id": "uuid",
        "email": "john@example.com",
        "expires_in": 750
    }
}
```

### 5. Verify 2FA Code
**POST** `/auth/2fa/verify`

Verify the 2FA code sent to email.

**Request Body:**
```json
{
    "code": "123456"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "phone_number": "+1234567890",
            "role": "bidder",
            "is_admin": false,
            "status": "active"
        },
        "token": "your-api-token",
        "token_type": "Bearer"
    }
}
```

### 6. Resend 2FA Code
**POST** `/auth/2fa/resend`

Resend the 2FA verification code.

**Response (200):**
```json
{
    "success": true,
    "message": "A new verification code has been sent to your email.",
    "data": {
        "expires_in": 750
    }
}
```

### 7. Logout User
**POST** `/auth/logout`

Logout and revoke the current token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Logout successful"
}
```

### 8. Get User Profile
**GET** `/auth/profile`

Get the authenticated user's profile.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "phone_number": "+1234567890",
            "role": "bidder",
            "is_admin": false,
            "status": "active",
            "created_at": "2025-06-03T14:30:00.000000Z",
            "last_login_at": "2025-06-03T15:30:00.000000Z"
        }
    }
}
```

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### Authentication Error (401)
```json
{
    "success": false,
    "message": "These credentials do not match our records."
}
```

### Authorization Error (403)
```json
{
    "success": false,
    "message": "Your account is not active. Please contact support."
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Registration failed. Please try again.",
    "error": "Detailed error message (only in debug mode)"
}
```

## User Roles
- **bidder**: Regular user who can participate in auctions
- **maker**: User who can create records (requires approval)
- **checker**: User who can approve records created by makers

## User Statuses
- **pending_approval**: New account waiting for admin approval
- **active**: Account is active and can be used
- **inactive**: Account is temporarily disabled
- **rejected**: Account registration was rejected

## Testing

### Using cURL

**Register:**
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Test User",
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "bidder"
  }'
```

**Login:**
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@arrahnu.com",
    "password": "password"
  }'
```

**Get Profile:**
```bash
curl -X GET http://127.0.0.1:8000/api/auth/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Admin API Endpoints

For admin users only, additional endpoints are available for system monitoring and management:

### Dashboard Monitoring
- **GET** `/admin/dashboard/overview` - Get dashboard overview with key metrics
- **GET** `/admin/dashboard/user-analytics` - Get user analytics and charts
- **GET** `/admin/dashboard/auction-analytics` - Get auction performance analytics
- **GET** `/admin/dashboard/system-metrics` - Get system performance metrics
- **GET** `/admin/dashboard/activity-feed` - Get real-time activity feed
- **GET** `/admin/dashboard/alerts` - Get system alerts and notifications

### System Monitoring
- **GET** `/admin/system/status` - Get system health status
- **GET** `/admin/system/performance` - Get performance metrics
- **GET** `/admin/system/activities` - Get recent system activities
- **GET** `/admin/logs/errors` - Get error logs with filtering
- **POST** `/admin/system/clear-caches` - Clear system caches

See `DASHBOARD_API_DOCUMENTATION.md` for detailed dashboard API documentation.
See `ADMIN_API_DOCUMENTATION.md` for detailed admin API documentation.

## Notes
- All timestamps are in ISO 8601 format (UTC)
- UUIDs are used for user IDs
- 2FA codes expire after 12.5 minutes (750 seconds)
- API tokens don't expire by default (configurable)
- New user accounts require admin approval before they can login
- Admin endpoints require both authentication and admin privileges
