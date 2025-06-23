# Mobile API Services Documentation
## ArRahnu Auction Online - Complete API Reference for Mobile Applications

### 📱 Overview
This document provides comprehensive information about all API services available for mobile applications in the ArRahnu Auction Online platform. The APIs are designed with mobile-first principles, offering optimized performance, real-time capabilities, and seamless user experience.

### 🌐 Base URLs
```
Production: https://your-domain.com/api
Development: http://localhost:8000/api
Testing: http://localhost:8000/api
```

### 🔐 Authentication
All protected endpoints require Bearer token authentication using Laravel Sanctum:
```
Authorization: Bearer {your-access-token}
```

### 📋 Response Format
All APIs follow a consistent JSON response structure:
```json
{
    "success": true|false,
    "message": "Human readable message",
    "data": { ... },
    "error_code": "SPECIFIC_ERROR_CODE",
    "errors": { ... }
}
```

---

## 🔐 Authentication Services

### 1. User Registration
**POST** `/auth/register`

Register a new user account with pending approval status.

**Request Body:**
```json
{
    "full_name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone_number": "+60123456789",
    "role": "bidder"
}
```

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
            "phone_number": "+60123456789",
            "role": "bidder",
            "status": "pending_approval",
            "created_at": "2024-01-01T00:00:00Z"
        }
    }
}
```

### 2. User Login
**POST** `/auth/login`

Authenticate user and receive access token.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123",
    "remember": false
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "your-access-token",
        "token_type": "Bearer",
        "expires_in": 3600,
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "role": "bidder",
            "is_admin": false,
            "status": "active",
            "last_login_at": "2024-01-01T00:00:00Z"
        }
    }
}
```

### 3. Password Reset Request
**POST** `/auth/forgot-password`

Request password reset link via email.

**Request Body:**
```json
{
    "email": "john@example.com"
}
```

### 4. Password Reset Confirmation
**POST** `/auth/reset-password`

Reset password using token from email.

**Request Body:**
```json
{
    "token": "reset-token",
    "email": "john@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### 5. User Logout
**POST** `/auth/logout` 🔒

Logout user and invalidate current token.

**Response (200):**
```json
{
    "success": true,
    "message": "Logout successful"
}
```

### 6. Get User Profile
**GET** `/auth/profile` 🔒

Get basic authenticated user profile.

---

## 👤 User Management Services

### 1. Get Complete User Profile
**GET** `/user/profile` 🔒

Get comprehensive user profile with statistics and completion tracking.

**Response (200):**
```json
{
    "success": true,
    "message": "Profile retrieved successfully",
    "data": {
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "phone_number": "+60123456789",
            "date_of_birth": "1990-01-01",
            "gender": "male",
            "nationality": "Malaysian",
            "occupation": "Engineer",
            "role": "bidder",
            "status": "active",
            "avatar_url": "/storage/avatars/avatar.jpg",
            "is_email_verified": true,
            "is_phone_verified": false,
            "2fa": false,
            "created_at": "2024-01-01T00:00:00Z",
            "last_login_at": "2024-01-01T00:00:00Z"
        },
        "statistics": {
            "total_bids": 25,
            "active_bids": 3,
            "successful_bids": 5,
            "total_spent": 15000.00,
            "account_age_days": 180,
            "last_bid_date": "2024-01-01T00:00:00Z"
        },
        "profile_completion": {
            "percentage": 85,
            "completed_fields": 9,
            "total_fields": 11,
            "missing_fields": ["date_of_birth", "avatar"]
        }
    }
}
```

### 2. Update User Profile
**PUT** `/user/profile` 🔒

Update user profile information.

**Request Body:**
```json
{
    "full_name": "John Updated Doe",
    "date_of_birth": "1990-01-01",
    "gender": "male",
    "nationality": "Malaysian",
    "occupation": "Senior Engineer"
}
```

### 3. Update Password
**PUT** `/user/password` 🔒

Change user password with current password verification.

**Request Body:**
```json
{
    "current_password": "oldpassword",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

### 4. Update Avatar
**POST** `/user/avatar` 🔒

Upload and update user avatar image.

**Request Body:** (multipart/form-data)
```
avatar: [image file] (max 2MB, jpeg/png/jpg/gif)
```

### 5. Remove Avatar
**DELETE** `/user/avatar` 🔒

Remove user avatar and reset to default.

### 6. Update User Preferences
**PUT** `/user/preferences` 🔒

Update notification and app preferences.

**Request Body:**
```json
{
    "email_notifications": true,
    "sms_notifications": true,
    "push_notifications": true,
    "bid_notifications": true,
    "auction_reminders": true,
    "marketing_emails": false,
    "language": "en",
    "timezone": "Asia/Kuala_Lumpur",
    "currency_preference": "MYR"
}
```

### 7. Get Bidding Activity
**GET** `/user/bidding-activity` 🔒

Get user's bidding activity and analytics.

**Query Parameters:**
- `period` (optional): Number of days (default: 30)
- `page` (optional): Page number for pagination

**Response (200):**
```json
{
    "success": true,
    "message": "Bidding activity retrieved successfully",
    "data": {
        "period": "30 days",
        "statistics": {
            "total_bids": 15,
            "unique_auctions": 8,
            "total_amount_bid": 25000.00,
            "average_bid": 1666.67,
            "winning_bids": 3,
            "successful_bids": 2
        },
        "daily_activity": [
            {
                "date": "2024-01-01",
                "bids_count": 3,
                "total_amount": 5000.00
            }
        ],
        "recent_bids": [
            {
                "id": "bid-uuid",
                "amount": 1500.00,
                "collateral_title": "Gold Necklace",
                "auction_status": "active",
                "is_winning": false,
                "created_at": "2024-01-01T00:00:00Z"
            }
        ]
    }
}
```

### 8. Get User Watchlist
**GET** `/user/watchlist` 🔒

Get user's favorite auctions and collaterals.

**Response (200):**
```json
{
    "success": true,
    "message": "Watchlist retrieved successfully",
    "data": {
        "watchlist": [
            {
                "collateral": {
                    "id": "uuid",
                    "title": "Gold Ring",
                    "description": "18K Gold Ring",
                    "estimated_value": 2000.00,
                    "minimum_bid": 1000.00,
                    "image_url": "/storage/collaterals/image.jpg"
                },
                "auction": {
                    "id": "uuid",
                    "status": "active",
                    "start_date": "2024-01-01T00:00:00Z",
                    "end_date": "2024-01-07T23:59:59Z"
                },
                "user_highest_bid": 1500.00,
                "current_highest_bid": 1800.00,
                "is_winning": false,
                "time_remaining": 3600
            }
        ],
        "total_items": 5
    }
}
```

### 9. Delete User Account
**DELETE** `/user/account` 🔒

Permanently delete user account with safety checks.

---

## 🏠 Address Management Services

### 1. Get User Addresses
**GET** `/addresses` 🔒

Get user's addresses with filtering and pagination.

**Query Parameters:**
- `search` (optional): Search term
- `state` (optional): Filter by state
- `is_primary` (optional): Filter by primary status (true/false)
- `per_page` (optional): Items per page (max 50, default 15)
- `page` (optional): Page number

**Response (200):**
```json
{
    "success": true,
    "message": "Addresses retrieved successfully",
    "data": {
        "addresses": [
            {
                "id": "uuid",
                "address_line_1": "123 Main Street",
                "address_line_2": "Unit 4B",
                "city": "Kuala Lumpur",
                "state": "Kuala Lumpur",
                "postcode": "50000",
                "country": "Malaysia",
                "is_primary": true,
                "formatted_address": "123 Main Street, Unit 4B, 50000 Kuala Lumpur, Kuala Lumpur, Malaysia",
                "created_at": "2024-01-01T00:00:00Z",
                "updated_at": "2024-01-01T00:00:00Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 3,
            "last_page": 1
        },
        "statistics": {
            "total_addresses": 3,
            "primary_addresses": 1,
            "states_count": 2
        }
    }
}
```

### 2. Create Address
**POST** `/addresses` 🔒

Create a new address for the user.

**Request Body:**
```json
{
    "address_line_1": "123 Main Street",
    "address_line_2": "Unit 4B",
    "city": "Kuala Lumpur",
    "state": "Kuala Lumpur",
    "postcode": "50000",
    "country": "Malaysia",
    "is_primary": true
}
```

### 3. Get Address Details
**GET** `/addresses/{id}` 🔒

Get specific address with formatted versions.

### 4. Update Address
**PUT** `/addresses/{id}` 🔒

Update existing address information.

### 5. Delete Address
**DELETE** `/addresses/{id}` 🔒

Delete an address (cannot delete primary address if it's the only one).

### 6. Set Primary Address
**POST** `/addresses/{id}/set-primary` 🔒

Set an address as the primary address.

### 7. Get Malaysian States
**GET** `/addresses/states/list` 🔒

Get list of Malaysian states for address forms.

**Response (200):**
```json
{
    "success": true,
    "data": {
        "states": [
            "Johor",
            "Kedah",
            "Kelantan",
            "Kuala Lumpur",
            "Labuan",
            "Malacca",
            "Negeri Sembilan",
            "Pahang",
            "Penang",
            "Perak",
            "Perlis",
            "Putrajaya",
            "Sabah",
            "Sarawak",
            "Selangor",
            "Terengganu"
        ]
    }
}
```

### 8. Get Address Statistics
**GET** `/addresses/statistics` 🔒

Get user's address statistics and analytics.

### 9. Export Addresses
**GET** `/addresses/export` 🔒

Export user's addresses as CSV file.

### 10. Get Address Suggestions
**GET** `/addresses/suggestions` 🔒

Get address suggestions based on user input.

### 11. Get Validation Rules
**GET** `/addresses/validation/rules` 🔒

Get address validation rules for form validation.

### 12. Validate Postcode
**POST** `/addresses/validate/postcode` 🔒

Validate Malaysian postcode format.

---

## 🎯 Bidding Services

### 1. Get Bidding History
**GET** `/bids` 🔒

Get user's bidding history with filtering and pagination.

**Query Parameters:**
- `status` (optional): Filter by bid status
- `collateral_id` (optional): Filter by collateral
- `per_page` (optional): Items per page (max 50)
- `page` (optional): Page number

**Response (200):**
```json
{
    "success": true,
    "message": "Bidding history retrieved successfully",
    "data": {
        "bids": [
            {
                "id": "uuid",
                "amount": 1500.00,
                "status": "active",
                "is_winning": false,
                "collateral": {
                    "id": "uuid",
                    "title": "Gold Necklace",
                    "image_url": "/storage/collaterals/image.jpg"
                },
                "auction": {
                    "id": "uuid",
                    "status": "active",
                    "end_date": "2024-01-07T23:59:59Z"
                },
                "created_at": "2024-01-01T00:00:00Z"
            }
        ],
        "pagination": {...},
        "statistics": {
            "total_bids": 25,
            "active_bids": 3,
            "winning_bids": 2
        }
    }
}
```

### 2. Place Bid
**POST** `/bids` 🔒

Place a new bid on a collateral.

**Request Body:**
```json
{
    "collateral_id": "uuid",
    "amount": 1500.00
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Bid placed successfully",
    "data": {
        "bid": {
            "id": "uuid",
            "amount": 1500.00,
            "status": "active",
            "is_winning": true,
            "created_at": "2024-01-01T00:00:00Z"
        },
        "collateral": {
            "current_highest_bid": 1500.00,
            "total_bids": 5
        }
    }
}
```

### 3. Get Active Bids
**GET** `/bids/active` 🔒

Get user's currently active bids.

### 4. Get Bidding Statistics
**GET** `/bids/statistics` 🔒

Get comprehensive bidding statistics and analytics.

### 5. Cancel Bid
**POST** `/bids/{id}/cancel` 🔒

Cancel a bid (only if not winning and auction allows cancellation).

---

## 🏛️ Auction Services

### 1. Get Active Auctions
**GET** `/auctions/active` 🔒

Get list of active auctions with collaterals.

**Query Parameters:**
- `search` (optional): Search term
- `category` (optional): Filter by category
- `min_value` (optional): Minimum estimated value
- `max_value` (optional): Maximum estimated value
- `per_page` (optional): Items per page (max 50)

**Response (200):**
```json
{
    "success": true,
    "message": "Active auctions retrieved successfully",
    "data": {
        "auctions": [
            {
                "id": "uuid",
                "title": "Gold Jewelry Auction",
                "description": "Premium gold jewelry collection",
                "status": "active",
                "start_date": "2024-01-01T00:00:00Z",
                "end_date": "2024-01-07T23:59:59Z",
                "collaterals_count": 15,
                "total_bids": 45,
                "featured_collateral": {
                    "id": "uuid",
                    "title": "Gold Necklace",
                    "image_url": "/storage/collaterals/image.jpg",
                    "estimated_value": 2000.00,
                    "current_highest_bid": 1500.00
                }
            }
        ],
        "pagination": {...}
    }
}
```

### 2. Get Collateral Details
**GET** `/auctions/collaterals/{id}` 🔒

Get detailed information about a specific collateral.

**Response (200):**
```json
{
    "success": true,
    "message": "Collateral details retrieved successfully",
    "data": {
        "collateral": {
            "id": "uuid",
            "title": "18K Gold Necklace",
            "description": "Beautiful 18K gold necklace with intricate design",
            "category": "jewelry",
            "estimated_value": 2000.00,
            "minimum_bid": 1000.00,
            "current_highest_bid": 1500.00,
            "total_bids": 8,
            "images": [
                {
                    "id": "uuid",
                    "image_url": "/storage/collaterals/image1.jpg",
                    "is_primary": true
                }
            ],
            "condition": "excellent",
            "specifications": {
                "weight": "25g",
                "purity": "18K",
                "length": "45cm"
            }
        },
        "auction": {
            "id": "uuid",
            "status": "active",
            "start_date": "2024-01-01T00:00:00Z",
            "end_date": "2024-01-07T23:59:59Z",
            "time_remaining": 3600
        },
        "bidding_info": {
            "user_highest_bid": 1500.00,
            "is_user_winning": true,
            "can_bid": true,
            "next_minimum_bid": 1550.00
        },
        "recent_bids": [
            {
                "amount": 1500.00,
                "bidder": "Anonymous",
                "created_at": "2024-01-01T00:00:00Z"
            }
        ]
    }
}
```

### 3. Get Live Updates
**GET** `/auctions/collaterals/{id}/live-updates` 🔒

Get real-time updates for a collateral's bidding status.

**Response (200):**
```json
{
    "success": true,
    "message": "Live updates retrieved successfully",
    "data": {
        "current_highest_bid": 1600.00,
        "total_bids": 9,
        "is_user_winning": false,
        "time_remaining": 3500,
        "last_bid_time": "2024-01-01T00:05:00Z",
        "activity_status": "active"
    }
}
```

---

## 🔧 Admin Services (Admin Only)

### Dashboard Analytics
- **GET** `/admin/dashboard/overview` - System overview
- **GET** `/admin/dashboard/user-analytics` - User analytics
- **GET** `/admin/dashboard/auction-analytics` - Auction analytics
- **GET** `/admin/dashboard/system-metrics` - System metrics
- **GET** `/admin/dashboard/activity-feed` - Recent activities
- **GET** `/admin/dashboard/alerts` - System alerts

### System Management
- **GET** `/admin/system/status` - System status
- **GET** `/admin/system/performance` - Performance metrics
- **GET** `/admin/system/activities` - Recent activities
- **GET** `/admin/logs/errors` - Error logs
- **POST** `/admin/system/clear-caches` - Clear system caches

### Admin Address Management
- **GET** `/admin/addresses` - All user addresses
- **POST** `/admin/addresses` - Create address for user
- **GET** `/admin/addresses/statistics` - Address statistics
- **GET** `/admin/addresses/export` - Export all addresses
- **GET** `/admin/addresses/filter-options` - Filter options
- **POST** `/admin/addresses/bulk-action` - Bulk operations
- **GET** `/admin/addresses/users/{user}` - User's addresses

### Settings Management
- **GET** `/admin/settings` - Get system settings
- **POST** `/admin/settings/update` - Update settings
- **POST** `/admin/settings/reset` - Reset to defaults
- **GET** `/admin/settings/system-status` - System status

---

## 🔧 Utility Services

### 1. API Health Check
**GET** `/health`

Check if the API is running and healthy.

**Response (200):**
```json
{
    "success": true,
    "message": "API is running",
    "timestamp": "2024-01-01T00:00:00Z",
    "version": "1.0.0"
}
```

### 2. API Information
**GET** `/info`

Get comprehensive API information and available endpoints.

---

## 📱 Mobile Integration Guidelines

### 🔐 Security Best Practices
1. **Token Storage**: Store JWT tokens securely using device keychain/keystore
2. **Token Refresh**: Implement automatic token refresh before expiration
3. **SSL Pinning**: Implement certificate pinning for production
4. **Biometric Auth**: Support fingerprint/face ID for quick login
5. **Session Management**: Handle token expiration gracefully

### ⚡ Performance Optimization
1. **Caching**: Cache user data, addresses, and static content
2. **Pagination**: Use pagination for all list endpoints
3. **Image Loading**: Implement progressive image loading
4. **Offline Support**: Queue actions when offline
5. **Background Sync**: Sync data in background

### 🔄 Real-time Features
1. **Polling**: Poll live-updates endpoint every 5-10 seconds during active bidding
2. **Push Notifications**: Integrate FCM/APNS for bid notifications
3. **WebSocket**: Consider WebSocket for real-time bidding updates
4. **Background Updates**: Update bid status in background

### 📊 Error Handling
1. **Consistent Errors**: All errors follow the same JSON structure
2. **Error Codes**: Use specific error codes for different scenarios
3. **Retry Logic**: Implement exponential backoff for failed requests
4. **User Feedback**: Show meaningful error messages to users

### 🎨 UI/UX Considerations
1. **Loading States**: Show loading indicators for all API calls
2. **Empty States**: Handle empty data gracefully
3. **Offline Indicators**: Show offline status when no connection
4. **Pull to Refresh**: Implement pull-to-refresh on list screens
5. **Deep Linking**: Support deep links to auctions/collaterals

---

## 🧪 Testing and Development

### Development Setup
```bash
# Start development server
php artisan serve --host=0.0.0.0 --port=8000

# Clear caches
php artisan route:clear && php artisan config:clear && php artisan cache:clear

# View all API routes
php artisan route:list --path=api
```

### API Testing
```bash
# Test API health
curl http://localhost:8000/api/health

# Test user registration
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"full_name":"Test User","username":"testuser","email":"test@example.com","password":"password123","password_confirmation":"password123","role":"bidder"}'

# Test login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'
```

### Rate Limiting
- Authentication endpoints: 5 requests per minute
- General API endpoints: 60 requests per minute
- Bidding endpoints: 10 requests per minute
- Admin endpoints: 100 requests per minute

---

## 📚 Additional Resources

### Documentation Files
- `docs/MOBILE_API_DOCUMENTATION.md` - Detailed API documentation
- `docs/ADDRESS_MODULE_DOCUMENTATION.md` - Address management guide
- `docs/API_DOCUMENTATION.md` - General API documentation
- `docs/MOBILE_API_SUMMARY.md` - Implementation summary

### Test Scripts
- `tests/test_mobile_apis.php` - Comprehensive API testing
- `scripts/api_verification_test.php` - API verification script

### Example Mobile App Integration
```javascript
// React Native example
class ArRahnuAPI {
    constructor(baseURL = 'http://localhost:8000/api') {
        this.baseURL = baseURL;
        this.token = null;
    }

    async login(email, password) {
        const response = await fetch(`${this.baseURL}/auth/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();
        if (data.success) {
            this.token = data.data.token;
            await this.storeToken(this.token);
        }
        return data;
    }

    async getProfile() {
        return this.authenticatedRequest('/user/profile');
    }

    async getActiveAuctions() {
        return this.authenticatedRequest('/auctions/active');
    }

    async placeBid(collateralId, amount) {
        return this.authenticatedRequest('/bids', 'POST', {
            collateral_id: collateralId,
            amount: amount
        });
    }

    async authenticatedRequest(endpoint, method = 'GET', body = null) {
        const headers = {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.token}`
        };
        
        const response = await fetch(`${this.baseURL}${endpoint}`, {
            method,
            headers,
            body: body ? JSON.stringify(body) : null
        });
        
        return response.json();
    }
}
```

---

## 🎯 API Endpoints Summary

### Public Endpoints (5)
- `POST /auth/register` - User registration
- `POST /auth/login` - User login
- `POST /auth/forgot-password` - Password reset request
- `POST /auth/reset-password` - Password reset confirmation
- `GET /health` - API health check

### User Endpoints (9)
- `GET /user/profile` - Get user profile
- `PUT /user/profile` - Update profile
- `PUT /user/password` - Change password
- `POST /user/avatar` - Upload avatar
- `DELETE /user/avatar` - Remove avatar
- `PUT /user/preferences` - Update preferences
- `GET /user/bidding-activity` - Bidding activity
- `GET /user/watchlist` - User watchlist
- `DELETE /user/account` - Delete account

### Address Endpoints (12)
- `GET /addresses` - List addresses
- `POST /addresses` - Create address
- `GET /addresses/{id}` - Get address
- `PUT /addresses/{id}` - Update address
- `DELETE /addresses/{id}` - Delete address
- `POST /addresses/{id}/set-primary` - Set primary
- `GET /addresses/states/list` - Malaysian states
- `GET /addresses/statistics` - Address statistics
- `GET /addresses/export` - Export addresses
- `GET /addresses/suggestions` - Address suggestions
- `GET /addresses/validation/rules` - Validation rules
- `POST /addresses/validate/postcode` - Validate postcode

### Bidding Endpoints (5)
- `GET /bids` - Bidding history
- `POST /bids` - Place bid
- `GET /bids/active` - Active bids
- `GET /bids/statistics` - Bidding statistics
- `POST /bids/{id}/cancel` - Cancel bid

### Auction Endpoints (3)
- `GET /auctions/active` - Active auctions
- `GET /auctions/collaterals/{id}` - Collateral details
- `GET /auctions/collaterals/{id}/live-updates` - Live updates

### Admin Endpoints (25+)
- Dashboard analytics (6 endpoints)
- System management (5 endpoints)
- Address management (11 endpoints)
- Settings management (4 endpoints)

### Total: 59+ API Endpoints

---

## 🚀 Ready for Mobile Development

The ArRahnu Auction Online platform provides a comprehensive, production-ready API ecosystem for mobile applications with:

✅ **Complete Authentication System** with JWT tokens
✅ **Real-time Bidding Capabilities** with live updates
✅ **Comprehensive User Management** with profile tracking
✅ **Malaysian-localized Address System** with validation
✅ **Advanced Admin Dashboard** with analytics
✅ **Mobile-optimized Responses** with pagination
✅ **Robust Error Handling** with specific error codes
✅ **Security Features** with rate limiting and validation
✅ **Extensive Documentation** with examples and guides

Your mobile app can now provide users with a complete auction experience including user registration, profile management, address management, real-time bidding, auction browsing, and comprehensive analytics - all powered by a secure, scalable, and well-documented API.