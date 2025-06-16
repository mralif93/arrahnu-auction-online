# ArRahnu Auction Admin API Documentation

## Overview
The Admin API provides system monitoring, error logging, and maintenance capabilities for administrators. All admin endpoints require authentication with an admin user account.

## Base URL
```
http://127.0.0.1:8000/api/admin
```

## Authentication
All admin endpoints require:
1. **Bearer Token**: Valid API token from login
2. **Admin Role**: User must have `is_admin = true`

```
Authorization: Bearer {admin-token}
```

## Admin Endpoints

### 1. System Status
**GET** `/system/status`

Get comprehensive system health and status information.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "System status retrieved successfully",
    "data": {
        "overall_status": "healthy|warning|critical",
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "database": {
            "status": "healthy",
            "connection": "active",
            "response_time_ms": 2.5,
            "driver": "mysql",
            "database": "arrahnu_auction"
        },
        "cache": {
            "status": "healthy",
            "driver": "file",
            "response_time_ms": 1.2
        },
        "storage": {
            "storage_writable": true,
            "public_writable": true,
            "logs_writable": true,
            "cache_writable": true,
            "sessions_writable": true,
            "disk_space": {
                "total": "500.0 GB",
                "used": "250.5 GB",
                "free": "249.5 GB",
                "usage_percentage": 50.1
            }
        },
        "metrics": {
            "total_users": 150,
            "active_users": 120,
            "pending_users": 30,
            "total_branches": 25,
            "total_accounts": 500,
            "total_collaterals": 1200,
            "total_auctions": 300,
            "active_2fa_codes": 5
        },
        "system": {
            "php_version": "8.4.1",
            "laravel_version": "11.x",
            "environment": "local",
            "debug_mode": true,
            "timezone": "UTC",
            "memory_limit": "512M",
            "max_execution_time": "30",
            "upload_max_filesize": "2M"
        }
    }
}
```

### 2. Performance Metrics
**GET** `/system/performance`

Get detailed performance metrics for system monitoring.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Performance metrics retrieved successfully",
    "data": {
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "metrics": {
            "memory": {
                "current_usage": 52428800,
                "current_usage_formatted": "50.0 MB",
                "peak_usage": 67108864,
                "peak_usage_formatted": "64.0 MB",
                "limit": "512M"
            },
            "database": {
                "connection_time_ms": 2.5,
                "active_connections": 1,
                "status": "healthy"
            },
            "cache": {
                "response_time_ms": 1.2,
                "driver": "file",
                "status": "healthy"
            },
            "queue": {
                "driver": "sync",
                "status": "not_implemented"
            },
            "response_times": {
                "current_request_time": 45.2,
                "note": "Response time for this API request"
            }
        }
    }
}
```

### 3. Recent Activities
**GET** `/system/activities`

Get recent system activities including users, logins, and errors.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Recent activities retrieved successfully",
    "data": {
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "activities": {
            "recent_users": [
                {
                    "id": "uuid",
                    "full_name": "John Doe",
                    "username": "johndoe",
                    "email": "john@example.com",
                    "status": "pending_approval",
                    "created_at": "2025-06-03T14:25:00.000000Z"
                }
            ],
            "recent_logins": [
                {
                    "id": "uuid",
                    "full_name": "Admin User",
                    "username": "admin",
                    "email": "admin@arrahnu.com",
                    "last_login_at": "2025-06-03T14:20:00.000000Z"
                }
            ],
            "recent_2fa_codes": [
                {
                    "id": 1,
                    "user_id": "uuid",
                    "created_at": "2025-06-03T14:15:00.000000Z",
                    "expires_at": "2025-06-03T14:20:00.000000Z",
                    "attempts": 0,
                    "user": {
                        "id": "uuid",
                        "full_name": "Admin User",
                        "email": "admin@arrahnu.com"
                    }
                }
            ],
            "recent_errors": [
                {
                    "timestamp": "2025-06-03 14:10:00",
                    "level": "ERROR",
                    "message": "Sample error message"
                }
            ]
        }
    }
}
```

### 4. Error Logs
**GET** `/logs/errors`

Get filtered error logs with pagination and search capabilities.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Query Parameters:**
- `level` (optional): Filter by log level (emergency, alert, critical, error, warning, notice, info, debug)
- `date` (optional): Filter by date (YYYY-MM-DD format)
- `limit` (optional): Number of logs to return (1-1000, default: 100)
- `search` (optional): Search term in log messages

**Examples:**
```
GET /api/admin/logs/errors?level=error&limit=50
GET /api/admin/logs/errors?date=2025-06-03&search=database
GET /api/admin/logs/errors?level=critical&limit=10
```

**Response (200):**
```json
{
    "success": true,
    "message": "Error logs retrieved successfully",
    "data": {
        "logs": [
            {
                "timestamp": "2025-06-03 14:25:42",
                "environment": "local",
                "level": "ERROR",
                "message": "file_get_contents(http://127.0.0.1:8000/api/auth/login): Failed to open stream",
                "context": "Stack trace and additional context..."
            }
        ],
        "total": 1500,
        "filtered": 25,
        "limit": 100
    }
}
```

### 5. Clear Caches
**POST** `/system/clear-caches`

Clear all application caches (config, routes, views, application cache).

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "All caches cleared successfully",
    "data": {
        "cleared": {
            "cache": "cleared",
            "routes": "cleared",
            "config": "cleared",
            "views": "cleared"
        },
        "timestamp": "2025-06-03T14:30:00.000000Z"
    }
}
```

## Status Indicators

### Overall System Status
- **healthy**: All systems operational
- **warning**: Minor issues detected
- **critical**: Major issues requiring attention

### Component Status
- **healthy**: Component functioning normally
- **error**: Component has issues

## Error Responses

### Authentication Required (401)
```json
{
    "message": "Unauthenticated."
}
```

### Admin Access Required (403)
```json
{
    "success": false,
    "message": "Access denied. Admin privileges required."
}
```

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "level": ["The selected level is invalid."]
    }
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Failed to retrieve system status",
    "error": "Detailed error message (debug mode only)"
}
```

## Usage Examples

### 1. Get System Status
```bash
curl -X GET http://127.0.0.1:8000/api/admin/system/status \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 2. Get Error Logs (Last 24 hours, errors only)
```bash
curl -X GET "http://127.0.0.1:8000/api/admin/logs/errors?level=error&date=2025-06-03&limit=50" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 3. Clear All Caches
```bash
curl -X POST http://127.0.0.1:8000/api/admin/system/clear-caches \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 4. Monitor Performance
```bash
curl -X GET http://127.0.0.1:8000/api/admin/system/performance \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

## Monitoring Dashboard Integration

These endpoints are designed to be integrated with monitoring dashboards:

1. **System Status**: Real-time health monitoring
2. **Performance Metrics**: Resource usage tracking
3. **Error Logs**: Issue detection and debugging
4. **Recent Activities**: User activity monitoring
5. **Cache Management**: Performance optimization

## Security Notes

- All admin endpoints require valid authentication
- Admin role verification is enforced
- Sensitive information is only shown in debug mode
- Log access is restricted to admin users only
- Cache clearing operations are logged for audit

## Rate Limiting

Admin API endpoints may be subject to rate limiting:
- System status: 60 requests per minute
- Error logs: 30 requests per minute
- Performance metrics: 60 requests per minute
- Cache clearing: 10 requests per minute
