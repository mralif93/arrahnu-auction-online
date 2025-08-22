# ArRahnu Auction Dashboard API Documentation

## Overview
The Dashboard API provides comprehensive monitoring, analytics, and real-time data for admin dashboard interfaces. All endpoints require admin authentication and provide data optimized for dashboard widgets, charts, and monitoring displays.

## Base URL
```
http://127.0.0.1:8000/api/admin/dashboard
```

## Authentication
All dashboard endpoints require:
1. **Bearer Token**: Valid API token from admin login
2. **Admin Role**: User must have `is_admin = true`

```
Authorization: Bearer {admin-token}
```

## Dashboard Endpoints

### 1. Dashboard Overview
**GET** `/overview`

Get comprehensive dashboard overview with key metrics and summary data.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Dashboard overview retrieved successfully",
    "data": {
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "overview": {
            "summary": {
                "total_users": 150,
                "active_users": 120,
                "pending_users": 30,
                "total_auctions": 300,
                "active_auctions": 45,
                "total_collaterals": 1200,
                "total_branches": 25,
                "total_accounts": 500,
                "users_growth_percentage": 12.5,
                "auctions_growth_percentage": 8.3
            },
            "recent_activity": {
                "new_users_today": 5,
                "new_auctions_today": 3,
                "logins_today": 25,
                "active_2fa_sessions": 2,
                "recent_registrations": [...],
                "recent_auctions": [...]
            },
            "system_health": {
                "database": {
                    "status": "healthy",
                    "response_time_ms": 2.5
                },
                "cache": {
                    "status": "healthy",
                    "response_time_ms": 1.2
                },
                "storage": {
                    "status": "healthy"
                },
                "overall_status": "healthy"
            },
            "quick_stats": {
                "memory_usage": {
                    "current": 52428800,
                    "peak": 67108864,
                    "formatted": {
                        "current": "50.0 MB",
                        "peak": "64.0 MB"
                    }
                },
                "response_time": 45.2,
                "php_version": "8.4.1",
                "laravel_version": "11.x"
            }
        }
    }
}
```

### 2. User Analytics
**GET** `/user-analytics`

Get detailed user analytics with charts data for dashboard visualization.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Query Parameters:**
- `period` (optional): Number of days for analytics (default: 30)

**Examples:**
```
GET /api/admin/dashboard/user-analytics?period=7
GET /api/admin/dashboard/user-analytics?period=90
```

**Response (200):**
```json
{
    "success": true,
    "message": "User analytics retrieved successfully",
    "data": {
        "period": "30 days",
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "analytics": {
            "user_growth": {
                "labels": ["Jun 1", "Jun 2", "Jun 3", "..."],
                "data": [2, 5, 3, 8, 1, 4, 6],
                "total_period": 29
            },
            "user_status_distribution": {
                "labels": ["active", "pending_approval", "inactive"],
                "data": [120, 30, 5],
                "colors": {
                    "active": "#10B981",
                    "pending_approval": "#F59E0B",
                    "inactive": "#EF4444"
                }
            },
            "user_role_distribution": {
                "labels": ["bidder", "maker", "checker"],
                "data": [100, 35, 20],
                "colors": {
                    "bidder": "#3B82F6",
                    "maker": "#8B5CF6",
                    "checker": "#06B6D4"
                }
            },
            "login_activity": {
                "labels": ["Jun 1", "Jun 2", "Jun 3", "..."],
                "data": [15, 22, 18, 25, 12, 20, 28],
                "total_period": 140
            },
            "registration_trends": {
                "labels": ["Week 1", "Week 2", "Week 3", "Week 4"],
                "data": [8, 12, 6, 15],
                "average_per_week": 10.25
            }
        }
    }
}
```

### 3. Auction Analytics
**GET** `/auction-analytics`

Get auction performance analytics and revenue metrics.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Query Parameters:**
- `period` (optional): Number of days for analytics (default: 30)

**Response (200):**
```json
{
    "success": true,
    "message": "Auction analytics retrieved successfully",
    "data": {
        "period": "30 days",
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "analytics": {
            "auction_performance": {
                "labels": ["Jun 1", "Jun 2", "Jun 3", "..."],
                "datasets": [
                    {
                        "label": "Created",
                        "data": [3, 5, 2, 8, 4, 6, 3],
                        "color": "#3B82F6"
                    },
                    {
                        "label": "Completed",
                        "data": [2, 3, 4, 5, 3, 4, 6],
                        "color": "#10B981"
                    }
                ]
            },
            "auction_status_distribution": {
                "labels": ["active", "completed", "draft", "cancelled"],
                "data": [45, 200, 35, 20],
                "colors": {
                    "active": "#10B981",
                    "completed": "#3B82F6",
                    "draft": "#6B7280",
                    "cancelled": "#EF4444"
                }
            },
            "collateral_categories": {
                "labels": ["Gold", "Electronics", "Vehicles", "Property", "Others"],
                "data": [45, 25, 15, 10, 5],
                "colors": ["#F59E0B", "#3B82F6", "#10B981", "#8B5CF6", "#6B7280"]
            },
            "auction_trends": {
                "labels": ["Jan 2025", "Feb 2025", "Mar 2025"],
                "data": [85, 92, 78],
                "trend": "stable"
            },
            "revenue_metrics": {
                "total_revenue": 125000,
                "revenue_growth": 12.5,
                "average_auction_value": 2500,
                "commission_earned": 6250,
                "monthly_breakdown": {
                    "labels": ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                    "data": [15000, 18000, 22000, 19000, 25000, 26000]
                }
            }
        }
    }
}
```

### 4. System Metrics
**GET** `/system-metrics`

Get comprehensive system performance and health metrics.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "System metrics retrieved successfully",
    "data": {
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "metrics": {
            "performance": {
                "memory": {
                    "current": 52428800,
                    "peak": 67108864,
                    "limit": "512M",
                    "usage_percentage": 10.24
                },
                "cpu": {
                    "load_average": 0.5,
                    "status": "normal"
                },
                "response_time": 45.2
            },
            "database": {
                "connection_status": "healthy",
                "response_time_ms": 2.5,
                "total_tables": 15,
                "total_records": 2188
            },
            "storage": {
                "total_space": "500.0 GB",
                "used_space": "250.5 GB",
                "free_space": "249.5 GB",
                "usage_percentage": 50.1,
                "writable": true
            },
            "security": {
                "active_sessions": 2,
                "failed_logins_today": 0,
                "admin_users": 3,
                "pending_approvals": 30,
                "two_factor_enabled": true
            },
            "uptime": {
                "uptime_percentage": 99.9,
                "last_downtime": null,
                "average_response_time": 150,
                "status": "operational"
            }
        }
    }
}
```

### 5. Activity Feed
**GET** `/activity-feed`

Get real-time activity feed for dashboard monitoring.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Query Parameters:**
- `limit` (optional): Number of activities to return (default: 20)
- `type` (optional): Filter by activity type: all, users, auctions, system (default: all)

**Examples:**
```
GET /api/admin/dashboard/activity-feed?limit=10&type=users
GET /api/admin/dashboard/activity-feed?type=auctions
```

**Response (200):**
```json
{
    "success": true,
    "message": "Activity feed retrieved successfully",
    "data": {
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "activities": [
            {
                "type": "user_registration",
                "title": "New user registered",
                "description": "John Doe (john@example.com) registered",
                "timestamp": "2025-06-03T14:25:00.000000Z",
                "icon": "user-plus",
                "color": "blue"
            },
            {
                "type": "auction_created",
                "title": "New auction created",
                "description": "Gold Jewelry Auction #123",
                "timestamp": "2025-06-03T14:20:00.000000Z",
                "icon": "gavel",
                "color": "green"
            }
        ],
        "total": 15,
        "type_filter": "all"
    }
}
```

### 6. System Alerts
**GET** `/alerts`

Get system alerts and notifications for admin attention.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Alerts retrieved successfully",
    "data": {
        "timestamp": "2025-06-03T14:30:00.000000Z",
        "total_alerts": 3,
        "alerts": {
            "critical": [
                {
                    "id": "disk_space_critical",
                    "title": "Critical: Low Disk Space",
                    "message": "Disk usage is at 92.5%",
                    "timestamp": "2025-06-03T14:30:00.000000Z",
                    "severity": "critical"
                }
            ],
            "warnings": [
                {
                    "id": "pending_users_warning",
                    "title": "Warning: Many Pending User Approvals",
                    "message": "15 users are waiting for approval",
                    "timestamp": "2025-06-03T14:30:00.000000Z",
                    "severity": "warning"
                }
            ],
            "info": [
                {
                    "id": "new_users_info",
                    "title": "Info: New User Registrations",
                    "message": "5 new users registered today",
                    "timestamp": "2025-06-03T14:30:00.000000Z",
                    "severity": "info"
                }
            ],
            "system": []
        }
    }
}
```

## Chart Data Format

### Line Charts (User Growth, Login Activity)
```json
{
    "labels": ["Jun 1", "Jun 2", "Jun 3"],
    "data": [2, 5, 3],
    "total_period": 10
}
```

### Pie Charts (Status Distribution, Role Distribution)
```json
{
    "labels": ["active", "pending", "inactive"],
    "data": [120, 30, 5],
    "colors": {
        "active": "#10B981",
        "pending": "#F59E0B",
        "inactive": "#EF4444"
    }
}
```

### Multi-Dataset Charts (Auction Performance)
```json
{
    "labels": ["Jun 1", "Jun 2", "Jun 3"],
    "datasets": [
        {
            "label": "Created",
            "data": [3, 5, 2],
            "color": "#3B82F6"
        },
        {
            "label": "Completed",
            "data": [2, 3, 4],
            "color": "#10B981"
        }
    ]
}
```

## Color Scheme

### Status Colors
- **Success/Active**: `#10B981` (Green)
- **Warning/Pending**: `#F59E0B` (Amber)
- **Error/Inactive**: `#EF4444` (Red)
- **Info/Neutral**: `#3B82F6` (Blue)
- **Secondary**: `#6B7280` (Gray)

### Role Colors
- **Bidder**: `#3B82F6` (Blue)
- **Maker**: `#8B5CF6` (Purple)
- **Checker**: `#06B6D4` (Cyan)

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

### Server Error (500)
```json
{
    "success": false,
    "message": "Failed to retrieve dashboard data",
    "error": "Detailed error message (debug mode only)"
}
```

## Usage Examples

### 1. Get Dashboard Overview
```bash
curl -X GET http://127.0.0.1:8000/api/admin/dashboard/overview \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 2. Get User Analytics (Last 7 Days)
```bash
curl -X GET "http://127.0.0.1:8000/api/admin/dashboard/user-analytics?period=7" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 3. Get Activity Feed (Users Only)
```bash
curl -X GET "http://127.0.0.1:8000/api/admin/dashboard/activity-feed?type=users&limit=10" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 4. Get System Alerts
```bash
curl -X GET http://127.0.0.1:8000/api/admin/dashboard/alerts \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

## Dashboard Integration

### Real-time Updates
- **Polling**: Recommended 30-60 second intervals for overview data
- **Activity Feed**: 10-15 second intervals for real-time updates
- **Alerts**: 60 second intervals for alert monitoring

### Performance Optimization
- **Caching**: Most data is cached for 5-10 minutes
- **Pagination**: Activity feed supports pagination
- **Filtering**: Analytics support period filtering

### Widget Recommendations
1. **Overview Cards**: Summary metrics from overview endpoint
2. **Charts**: User/auction analytics for trend visualization
3. **Activity Timeline**: Real-time activity feed
4. **Alert Panel**: System alerts with severity indicators
5. **System Health**: Performance metrics and status indicators

## Security Notes
- All endpoints require admin authentication
- Data is filtered based on user permissions
- Sensitive system information only shown to super admins
- Rate limiting applied to prevent abuse
