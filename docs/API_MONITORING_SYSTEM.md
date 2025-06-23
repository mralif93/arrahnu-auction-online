# API Monitoring System Documentation

## Overview

The API Monitoring System provides comprehensive monitoring and health checking capabilities for the ArRahnu Auction Online platform. It monitors all API endpoints, system resources, database connectivity, cache performance, and provides real-time status updates.

## Features

### 🔍 **Real-time Monitoring**
- Overall system health status
- Individual service health checks
- API endpoint availability
- Performance metrics
- Resource usage monitoring

### 📊 **Comprehensive Metrics**
- Database connectivity and performance
- Cache system status
- Queue system health
- Storage system status
- Memory and disk usage
- PHP configuration details

### 🚨 **Status Categories**
- **Healthy** ✅ - All systems operational
- **Degraded** ⚠️ - Some issues detected
- **Unhealthy** ❌ - Critical issues present

## API Endpoints

### Base URL
```
http://localhost:8000/api/monitoring
```

### Available Endpoints

#### 1. Overall Status
```http
GET /api/monitoring/status
```
**Response:**
```json
{
  "success": true,
  "message": "API Status Overview",
  "data": {
    "timestamp": "2025-06-23T03:14:57.056055Z",
    "overall_status": "healthy",
    "services": {
      "database": {"status": "healthy", "message": "Database connection successful"},
      "cache": {"status": "healthy", "message": "Cache connection successful"},
      "queue": {"status": "healthy", "message": "Queue system available"},
      "storage": {"status": "healthy", "message": "Storage connection successful"},
      "system_resources": {"status": "healthy", "memory_usage_percentage": 7.81}
    },
    "summary": {
      "total_endpoints": 3,
      "healthy_endpoints": 0,
      "unhealthy_endpoints": 0,
      "response_time": 16.48
    }
  }
}
```

#### 2. Health Check
```http
GET /api/monitoring/health
```
**Response:**
```json
{
  "success": true,
  "message": "API Health Check",
  "data": {
    "timestamp": "2025-06-23T03:15:01.724037Z",
    "application": {
      "name": "ArRahnu Auction Online",
      "environment": "local",
      "debug": true,
      "version": "1.0.0",
      "timezone": "UTC"
    },
    "database": {"status": "healthy", "message": "Database connection successful"},
    "cache": {"status": "healthy", "message": "Cache connection successful"},
    "queue": {"status": "healthy", "message": "Queue system available"},
    "storage": {"status": "healthy", "message": "Storage connection successful"},
    "system": {"status": "healthy", "memory_usage_percentage": 4.69}
  }
}
```

#### 3. Endpoints Status
```http
GET /api/monitoring/endpoints
```
**Response:**
```json
{
  "success": true,
  "message": "API Endpoints Status",
  "data": {
    "public": {
      "auth_register": {
        "status": "healthy",
        "method": "POST",
        "endpoint": "/api/auth/register",
        "requires_auth": false,
        "response_time": 45.2
      }
    },
    "protected": {
      "user_profile": {
        "status": "healthy",
        "method": "GET",
        "endpoint": "/api/user/profile",
        "requires_auth": true,
        "response_time": 23.1
      }
    },
    "admin": {
      "admin_dashboard_overview": {
        "status": "healthy",
        "method": "GET",
        "endpoint": "/api/admin/dashboard/overview",
        "requires_auth": true,
        "requires_admin": true,
        "response_time": 67.8
      }
    }
  }
}
```

#### 4. Performance Metrics
```http
GET /api/monitoring/performance
```
**Response:**
```json
{
  "success": true,
  "message": "API Performance Metrics",
  "data": {
    "timestamp": "2025-06-23T03:15:10.123456Z",
    "memory_usage": {
      "current": 4194304,
      "peak": 5242880,
      "limit": "128M"
    },
    "execution_time": {
      "current": 0.18,
      "max_execution_time": "30"
    },
    "database": {
      "connections": "active",
      "query_count": 15
    },
    "cache": {
      "driver": "database",
      "status": "connected"
    },
    "queue": {
      "driver": "database",
      "status": "active"
    }
  }
}
```

#### 5. Usage Statistics
```http
GET /api/monitoring/usage
```
**Response:**
```json
{
  "success": true,
  "message": "API Usage Statistics",
  "data": {
    "timestamp": "2025-06-23T03:15:06.566681Z",
    "users": {
      "total": 12,
      "active": 9,
      "pending_verification": 3
    },
    "auctions": {
      "total": 14,
      "active": 1,
      "completed": 5,
      "scheduled": 3
    },
    "collaterals": {
      "total": 67,
      "active": 36,
      "pending_approval": 7
    },
    "bids": {
      "total": 32,
      "today": 32,
      "this_week": 32
    },
    "addresses": {
      "total": 15,
      "primary": 12
    }
  }
}
```

#### 6. System Resources
```http
GET /api/monitoring/resources
```
**Response:**
```json
{
  "success": true,
  "message": "System Resources Status",
  "data": {
    "timestamp": "2025-06-23T03:15:08.803499Z",
    "memory": {
      "usage": 4194304,
      "peak": 4194304,
      "limit": "128M",
      "percentage": 3.13
    },
    "disk": {
      "free_space": 813088817152,
      "total_space": 994662584320,
      "usage_percentage": 18.25
    },
    "php": {
      "version": "8.4.1",
      "extensions": ["Core", "date", "libxml", ...],
      "max_execution_time": "30",
      "upload_max_filesize": "2M",
      "post_max_size": "8M"
    },
    "server": {
      "software": "PHP/8.4.1 (Development Server)",
      "php_sapi": "cli-server",
      "server_time": "2025-06-23 03:15:08"
    }
  }
}
```

#### 7. Database Status
```http
GET /api/monitoring/database
```
**Response:**
```json
{
  "success": true,
  "message": "Database Status",
  "data": {
    "timestamp": "2025-06-23T03:15:12.345678Z",
    "status": "connected",
    "driver": "sqlite",
    "database": "/path/to/database.sqlite",
    "host": "localhost",
    "port": "3306",
    "version": "3.43.2"
  }
}
```

#### 8. Cache Status
```http
GET /api/monitoring/cache
```
**Response:**
```json
{
  "success": true,
  "message": "Cache Status",
  "data": {
    "timestamp": "2025-06-23T03:15:14.567890Z",
    "status": "connected",
    "driver": "database",
    "test_passed": true,
    "prefix": "arrahnu_auction_online_cache_",
    "ttl": 60
  }
}
```

#### 9. Queue Status
```http
GET /api/monitoring/queue
```
**Response:**
```json
{
  "success": true,
  "message": "Queue Status",
  "data": {
    "timestamp": "2025-06-23T03:15:16.789012Z",
    "driver": "database",
    "connections": {
      "database": {
        "driver": "database",
        "table": "jobs",
        "queue": "default"
      }
    },
    "failed_driver": "database",
    "status": "active"
  }
}
```

#### 10. Error Summary
```http
GET /api/monitoring/errors
```
**Response:**
```json
{
  "success": true,
  "message": "API Error Summary",
  "data": {
    "timestamp": "2025-06-23T03:15:18.901234Z",
    "total_errors": 5,
    "recent_errors": [
      "2025-06-23 03:14:30.123 ERROR: Database connection failed",
      "2025-06-23 03:14:25.456 ERROR: Cache write failed"
    ],
    "log_file_exists": true,
    "log_file_size": 1024000
  }
}
```

#### 11. Test Specific Endpoint
```http
POST /api/monitoring/test-endpoint
```
**Request Body:**
```json
{
  "endpoint": "/api/auth/login",
  "method": "POST",
  "data": {
    "email": "test@example.com",
    "password": "password"
  }
}
```
**Response:**
```json
{
  "success": true,
  "message": "Endpoint Test Result",
  "data": {
    "status": "healthy",
    "status_code": 200,
    "response_time": 45.2,
    "response_size": 1024,
    "method": "POST",
    "endpoint": "/api/auth/login"
  }
}
```

## Web Dashboard

### Access URL
```
http://localhost:8000/admin/api-monitoring
```

### Features
- **Real-time Status Display** - Live updates every 30 seconds
- **Service Health Cards** - Visual status indicators for all services
- **Endpoint Monitoring** - Detailed status of all API endpoints
- **Resource Usage Charts** - Memory and disk usage visualization
- **Usage Statistics** - User, auction, and system statistics
- **Manual Refresh** - On-demand status updates

### Dashboard Sections

#### 1. Overall Status
- System-wide health indicator
- Response time metrics
- Endpoint health summary

#### 2. Service Status Cards
- Database connectivity
- Cache system status
- Queue system health
- Storage system status

#### 3. Endpoints Monitoring
- Public endpoints status
- Protected endpoints status
- Admin endpoints status
- Response time tracking

#### 4. System Resources
- Memory usage with progress bars
- Disk usage visualization
- PHP configuration details
- Server information

#### 5. Usage Statistics
- User statistics (total, active, pending)
- Auction statistics (total, active, completed)
- Collateral statistics (total, active, pending)
- Bid statistics (total, today, this week)
- Address statistics (total, primary)

## Command Line Interface

### Test All Monitoring Services
```bash
php artisan api:test-monitoring
```

### Test Specific Endpoint
```bash
php artisan api:test-monitoring --endpoint=/api/auth/login
```

### Command Output Example
```
🔍 Testing API Monitoring System...

📊 Testing Overall Status...
✅ Overall Status: healthy
📊 Response Time: 22.77ms
🔗 Endpoints: 0/3 Healthy
  ✅ database: healthy
  ✅ cache: healthy
  ✅ queue: healthy
  ✅ storage: healthy
  ✅ system_resources: healthy

🏥 Testing Health Check...
📱 Application: ArRahnu Auction Online v1.0.0
🌍 Environment: local
🕐 Timezone: UTC
  ✅ database: healthy
  ✅ cache: healthy
  ✅ queue: healthy
  ✅ storage: healthy
  ✅ system: healthy

📈 Testing Usage Statistics...
👥 Users: 12 total, 9 active
🏷️ Auctions: 14 total, 1 active
💎 Collaterals: 67 total, 36 active
💰 Bids: 32 total, 32 today
📍 Addresses: 15 total, 12 primary

✅ API Monitoring Test Complete!
```

## Monitoring Service Architecture

### Core Components

#### 1. ApiMonitoringController
- Handles HTTP requests for monitoring data
- Provides RESTful API endpoints
- Returns standardized JSON responses

#### 2. ApiMonitoringService
- Core business logic for monitoring
- Service health checks
- Performance metrics calculation
- Resource usage monitoring

#### 3. TestApiMonitoring Command
- Command-line interface for testing
- Comprehensive service validation
- Detailed status reporting

### Service Checks

#### Database Monitoring
- Connection status verification
- Query performance tracking
- Connection pool health
- Database version information

#### Cache Monitoring
- Cache driver status
- Read/write test operations
- Cache hit/miss ratios
- Memory usage tracking

#### Queue Monitoring
- Queue driver status
- Job processing health
- Failed job tracking
- Queue depth monitoring

#### Storage Monitoring
- File system accessibility
- Read/write permissions
- Disk space monitoring
- Storage driver status

#### System Resources
- Memory usage tracking
- CPU utilization
- Disk space monitoring
- PHP configuration validation

## Integration Examples

### 1. Health Check Integration
```bash
# Simple health check
curl -s http://localhost:8000/api/monitoring/health | jq '.data.application.name'

# Check specific service
curl -s http://localhost:8000/api/monitoring/status | jq '.data.services.database.status'
```

### 2. Monitoring Dashboard Integration
```javascript
// Auto-refresh monitoring data
setInterval(() => {
    fetch('/api/monitoring/status')
        .then(response => response.json())
        .then(data => {
            updateDashboard(data);
        });
}, 30000);
```

### 3. Alert System Integration
```php
// Check for critical issues
$status = $monitoringService->getOverallStatus();
if ($status['overall_status'] === 'unhealthy') {
    // Send alert notification
    Notification::send($admins, new SystemAlertNotification($status));
}
```

### 4. Performance Monitoring
```php
// Track API performance
$performance = $monitoringService->getPerformanceMetrics();
if ($performance['memory_usage']['current'] > $threshold) {
    // Trigger memory optimization
    Cache::clear();
}
```

## Best Practices

### 1. Regular Monitoring
- Set up automated health checks every 5 minutes
- Monitor during peak usage hours
- Track performance trends over time

### 2. Alert Thresholds
- Memory usage > 80%: Warning
- Memory usage > 95%: Critical
- Disk usage > 90%: Warning
- Disk usage > 95%: Critical
- Response time > 1000ms: Warning

### 3. Maintenance
- Regular log file rotation
- Cache cleanup scheduling
- Database optimization
- Storage cleanup

### 4. Security
- Monitor access to admin endpoints
- Track authentication failures
- Monitor API rate limiting
- Log security events

## Troubleshooting

### Common Issues

#### 1. Database Connection Issues
```bash
# Check database status
curl -s http://localhost:8000/api/monitoring/database

# Verify database file permissions
ls -la database/database.sqlite
```

#### 2. Cache Issues
```bash
# Check cache status
curl -s http://localhost:8000/api/monitoring/cache

# Clear cache if needed
php artisan cache:clear
```

#### 3. High Memory Usage
```bash
# Check memory usage
curl -s http://localhost:8000/api/monitoring/resources | jq '.data.memory'

# Optimize memory usage
php artisan config:cache
php artisan route:cache
```

#### 4. Slow Response Times
```bash
# Check performance metrics
curl -s http://localhost:8000/api/monitoring/performance

# Optimize database queries
php artisan db:show --counts
```

## Future Enhancements

### Planned Features
1. **Real-time WebSocket Updates** - Live dashboard updates
2. **Email/SMS Alerts** - Automated alert notifications
3. **Performance History** - Historical performance tracking
4. **Custom Thresholds** - Configurable alert levels
5. **API Rate Limiting** - Monitor API usage patterns
6. **Load Balancing** - Multiple server monitoring
7. **Mobile App** - Mobile monitoring dashboard

### Integration Possibilities
1. **External Monitoring Services** - New Relic, DataDog integration
2. **Log Aggregation** - ELK Stack integration
3. **Metrics Collection** - Prometheus/Grafana integration
4. **Incident Management** - PagerDuty integration

## Support

For issues or questions regarding the API Monitoring System:

1. Check the troubleshooting section above
2. Review the application logs: `storage/logs/laravel.log`
3. Test individual components using the CLI commands
4. Contact the development team for assistance

---

**Last Updated:** June 23, 2025  
**Version:** 1.0.0  
**Maintainer:** Development Team 