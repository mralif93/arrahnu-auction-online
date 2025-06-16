# Mobile API Documentation
## ArRahnu Auction Online - Mobile Application APIs

### Overview
This documentation covers all mobile-friendly APIs for the ArRahnu Auction Online platform, specifically designed for mobile applications with optimized responses, real-time features, and comprehensive user management.

### Base URL
```
Production: https://your-domain.com/api
Development: http://localhost:8000/api
```

### Authentication
All protected endpoints require Bearer token authentication:
```
Authorization: Bearer {your-token}
```

---

## 🔐 Authentication APIs

### Register User
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
    "phone_number": "+60123456789",
    "role": "bidder"
}
```

**Response:**
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
            "status": "pending_approval"
        }
    }
}
```

### Login User
**POST** `/auth/login`

Authenticate user and get access token.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
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
            "email": "john@example.com",
            "role": "bidder",
            "status": "active"
        }
    }
}
```

### Verify 2FA Code
**POST** `/auth/2fa/verify` or **POST** `/verify/2fa`

Verify two-factor authentication code during login.

**Request Body:**
```json
{
    "code": "123456"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "email": "john@example.com",
            "role": "bidder",
            "status": "active"
        },
        "token": "your-access-token",
        "token_type": "Bearer"
    }
}
```

### Logout User
**POST** `/auth/logout` 🔒

Logout user and invalidate token.

**Response:**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

## 👤 User Management APIs

### Get User Profile
**GET** `/user/profile` 🔒

Get complete user profile with statistics and addresses.

**Response:**
```json
{
    "success": true,
    "message": "Profile retrieved successfully.",
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
            "email_verified_at": "2024-01-01T00:00:00Z",
            "phone_verified_at": null,
            "two_factor_enabled": false,
            "created_at": "2024-01-01T00:00:00Z",
            "updated_at": "2024-01-01T00:00:00Z"
        },
        "addresses": [...],
        "primary_address": {...},
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

### Update User Profile
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

**Response:**
```json
{
    "success": true,
    "message": "Profile updated successfully.",
    "data": {
        "user": {...},
        "updated_fields": ["full_name", "occupation"],
        "profile_completion": {...}
    }
}
```

### Update Password
**PUT** `/user/password` 🔒

Update user password.

**Request Body:**
```json
{
    "current_password": "oldpassword",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

### Update Avatar
**POST** `/user/avatar` 🔒

Upload and update user avatar image.

**Request Body:** (multipart/form-data)
```
avatar: [image file] (max 2MB, jpeg/png/jpg/gif)
```

**Response:**
```json
{
    "success": true,
    "message": "Avatar updated successfully.",
    "data": {
        "avatar_url": "/storage/avatars/new-avatar.jpg",
        "user": {...}
    }
}
```

### Remove Avatar
**DELETE** `/user/avatar` 🔒

Remove user avatar.

### Update Preferences
**PUT** `/user/preferences` 🔒

Update user notification and app preferences.

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

### Get Bidding Activity
**GET** `/user/bidding-activity` 🔒

Get user's bidding activity and statistics.

**Query Parameters:**
- `period` (optional): Number of days (default: 30)

**Response:**
```json
{
    "success": true,
    "message": "Bidding activity retrieved successfully.",
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
        "daily_activity": [...],
        "recent_bids": [...]
    }
}
```

### Get Watchlist
**GET** `/user/watchlist` 🔒

Get user's watchlist (favorite auctions).

**Response:**
```json
{
    "success": true,
    "message": "Watchlist retrieved successfully.",
    "data": {
        "watchlist": [
            {
                "collateral": {...},
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

---

## 🏠 Address Management APIs

### Get User Addresses
**GET** `/addresses` 🔒

Get user's addresses with filtering and statistics.

**Query Parameters:**
- `search` (optional): Search term
- `state` (optional): Filter by state
- `is_primary` (optional): Filter by primary status
- `per_page` (optional): Items per page (max 50)

**Response:**
```json
{
    "success": true,
    "message": "Addresses retrieved successfully.",
    "data": {
        "addresses": [...],
        "pagination": {...},
        "statistics": {
            "total_addresses": 3,
            "primary_addresses": 1,
            "states_count": 2
        }
    }
}
```

### Create Address
**POST** `/addresses` 🔒

Create a new address.

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

### Update Address
**PUT** `/addresses/{id}` 🔒

Update an existing address.

### Delete Address
**DELETE** `/addresses/{id}` 🔒

Delete an address.

### Set Primary Address
**POST** `/addresses/{id}/set-primary` 🔒

Set an address as primary.

### Get States List
**GET** `/addresses/states/list` 🔒

Get list of Malaysian states.

**Response:**
```json
{
    "success": true,
    "data": {
        "states": [
            "Johor", "Kedah", "Kelantan", "Kuala Lumpur",
            "Labuan", "Melaka", "Negeri Sembilan", "Pahang",
            "Penang", "Perak", "Perlis", "Putrajaya",
            "Sabah", "Sarawak", "Selangor", "Terengganu"
        ]
    }
}
```

---

## 🎯 Bidding APIs

### Get User Bids
**GET** `/bids` 🔒

Get user's bidding history with pagination.

**Query Parameters:**
- `status` (optional): Filter by bid status
- `date_from` (optional): Start date filter
- `date_to` (optional): End date filter
- `per_page` (optional): Items per page (max 50)

**Response:**
```json
{
    "success": true,
    "message": "Bidding history retrieved successfully.",
    "data": {
        "bids": [
            {
                "id": "uuid",
                "collateral_id": "uuid",
                "bid_amount_rm": 1500.00,
                "bid_time": "2024-01-01T12:00:00Z",
                "status": "winning",
                "collateral": {
                    "id": "uuid",
                    "item_type": "Gold Jewelry",
                    "auction": {...},
                    "images": [...]
                }
            }
        ],
        "pagination": {...},
        "statistics": {
            "total_bids": 25,
            "active_bids": 3,
            "winning_bids": 2,
            "successful_bids": 5,
            "total_bid_amount": 35000.00,
            "average_bid_amount": 1400.00
        }
    }
}
```

### Place Bid
**POST** `/bids` 🔒

Place a new bid on a collateral.

**Request Body:**
```json
{
    "collateral_id": "uuid",
    "bid_amount": 1500.00
}
```

**Response:**
```json
{
    "success": true,
    "message": "Bid placed successfully!",
    "data": {
        "bid": {
            "id": "uuid",
            "collateral_id": "uuid",
            "user_id": "uuid",
            "bid_amount_rm": 1500.00,
            "bid_time": "2024-01-01T12:00:00Z",
            "status": "winning"
        },
        "collateral": {
            "id": "uuid",
            "item_type": "Gold Jewelry",
            "current_highest_bid": 1500.00,
            "bid_count": 8,
            "auction_end_time": "2024-01-02T12:00:00Z",
            "time_remaining": 86400
        }
    }
}
```

### Get Active Bids
**GET** `/bids/active` 🔒

Get user's current active bids.

**Response:**
```json
{
    "success": true,
    "message": "Active bids retrieved successfully.",
    "data": {
        "active_bids": [
            {
                "id": "uuid",
                "bid_amount_rm": 1500.00,
                "time_remaining": 3600,
                "is_winning": true,
                "current_highest_bid": 1500.00,
                "thumbnail": "/storage/images/item.jpg",
                "collateral": {...}
            }
        ],
        "total_active": 3,
        "winning_count": 2,
        "total_bid_value": 4500.00
    }
}
```

### Get Bidding Statistics
**GET** `/bids/statistics` 🔒

Get comprehensive bidding statistics.

**Response:**
```json
{
    "success": true,
    "message": "Bidding statistics retrieved successfully.",
    "data": {
        "statistics": {
            "total_bids": 25,
            "active_bids": 3,
            "winning_bids": 2,
            "successful_bids": 5,
            "total_spent": 15000.00,
            "total_bid_amount": 35000.00,
            "average_bid": 1400.00,
            "highest_bid": 5000.00,
            "first_bid_date": "2023-06-01T00:00:00Z",
            "last_bid_date": "2024-01-01T12:00:00Z"
        },
        "monthly_activity": [
            {
                "month": "2024-01",
                "bid_count": 8,
                "total_amount": 12000.00
            }
        ]
    }
}
```

### Cancel Bid
**POST** `/bids/{id}/cancel` 🔒

Cancel a bid (if allowed).

**Response:**
```json
{
    "success": true,
    "message": "Bid cancelled successfully.",
    "data": {
        "cancelled_bid_id": "uuid",
        "new_highest_bid": 1200.00
    }
}
```

---

## 🏛️ Auction APIs

### Get Active Auctions
**GET** `/auctions/active` 🔒

Get active auctions available for bidding.

**Query Parameters:**
- `search` (optional): Search term
- `per_page` (optional): Items per page (max 50)

**Response:**
```json
{
    "success": true,
    "message": "Active auctions retrieved successfully.",
    "data": {
        "auctions": [
            {
                "id": "uuid",
                "auction_title": "Gold Jewelry Auction",
                "description": "Premium gold jewelry collection",
                "start_datetime": "2024-01-01T10:00:00Z",
                "end_datetime": "2024-01-02T10:00:00Z",
                "status": "active",
                "time_remaining": 86400,
                "total_items": 15,
                "total_bids": 45,
                "total_value": 125000.00,
                "collaterals": [
                    {
                        "id": "uuid",
                        "item_type": "Gold Ring",
                        "starting_bid_rm": 500.00,
                        "current_highest_bid_rm": 1200.00,
                        "time_remaining": 86400,
                        "bid_count": 8,
                        "thumbnail": "/storage/images/ring.jpg",
                        "images": [...],
                        "bids": [...],
                        "highest_bidder": {...}
                    }
                ]
            }
        ],
        "pagination": {...}
    }
}
```

### Get Collateral Details
**GET** `/auctions/collaterals/{id}` 🔒

Get detailed information about a collateral for bidding.

**Response:**
```json
{
    "success": true,
    "message": "Collateral details retrieved successfully.",
    "data": {
        "collateral": {
            "id": "uuid",
            "item_type": "Gold Ring",
            "description": "18K gold ring with diamond",
            "estimated_value_rm": 2000.00,
            "starting_bid_rm": 500.00,
            "current_highest_bid_rm": 1200.00,
            "images": [...],
            "account": {...},
            "auction": {...}
        },
        "bidding_info": {
            "minimum_bid": 1201.00,
            "current_highest_bid": 1200.00,
            "starting_bid": 500.00,
            "bid_count": 8,
            "user_highest_bid": 1000.00,
            "is_user_highest_bidder": false,
            "user_bid_count": 2
        },
        "auction_info": {
            "id": "uuid",
            "title": "Gold Jewelry Auction",
            "status": "active",
            "end_datetime": "2024-01-02T10:00:00Z",
            "time_remaining": 86400,
            "is_ending_soon": false
        },
        "recent_bids": [
            {
                "id": "uuid",
                "amount": 1200.00,
                "bidder_name": "John Doe",
                "bid_time": "2024-01-01T15:30:00Z",
                "status": "winning",
                "is_current_user": false
            }
        ]
    }
}
```

### Get Live Updates
**GET** `/auctions/collaterals/{id}/live-updates` 🔒

Get real-time updates for a collateral (for live bidding).

**Response:**
```json
{
    "success": true,
    "message": "Live updates retrieved successfully.",
    "data": {
        "collateral_id": "uuid",
        "current_highest_bid": 1200.00,
        "bid_count": 8,
        "time_remaining": 86400,
        "auction_status": "active",
        "is_ending_soon": false,
        "latest_bids": [...],
        "minimum_next_bid": 1201.00,
        "timestamp": "2024-01-01T16:00:00Z"
    }
}
```

---

## 📱 Mobile-Specific Features

### Real-Time Updates
- Use the live updates endpoint for real-time bidding information
- Poll every 5-10 seconds during active bidding
- WebSocket support can be added for true real-time updates

### Offline Support
- Cache user profile and preferences locally
- Queue bid requests when offline (with proper validation)
- Sync when connection is restored

### Push Notifications
- Bid outbid notifications
- Auction ending reminders
- Winning bid confirmations
- Payment and delivery updates

### Image Optimization
- Multiple image sizes available for different screen densities
- Lazy loading support for auction listings
- Thumbnail URLs provided for quick loading

---

## 🔧 Error Handling

### Standard Error Response
```json
{
    "success": false,
    "message": "Error description",
    "error_code": "SPECIFIC_ERROR_CODE",
    "data": {
        "additional_info": "..."
    }
}
```

### Common Error Codes
- `INVALID_USER_ROLE`: User role not allowed for action
- `INACTIVE_USER`: User account not active
- `AUCTION_NOT_ACTIVE`: Auction is not currently active
- `AUCTION_ENDED`: Auction has ended
- `BID_TOO_LOW`: Bid amount below minimum
- `ALREADY_HIGHEST_BIDDER`: User is already highest bidder
- `INVALID_CURRENT_PASSWORD`: Current password incorrect
- `HAS_ACTIVE_BIDS`: Cannot perform action with active bids

---

## 🚀 Rate Limiting

- Authentication endpoints: 5 requests per minute
- Bidding endpoints: 10 requests per minute
- General endpoints: 60 requests per minute
- Live updates: 120 requests per minute

---

## 📊 Response Optimization

### Pagination
All list endpoints support pagination with consistent format:
```json
{
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 20,
        "total": 100,
        "from": 1,
        "to": 20
    }
}
```

### Filtering and Search
Most endpoints support filtering and search parameters for mobile optimization.

### Minimal Responses
Use `fields` parameter to request only needed fields:
```
GET /api/user/profile?fields=id,full_name,email,avatar_url
```

---

## 🔐 Security Features

- JWT token authentication
- Rate limiting
- Input validation and sanitization
- CORS support for mobile apps
- IP address logging for bids
- Two-factor authentication support
- Account deletion with safety checks

---

## 📱 Mobile App Integration Tips

1. **Token Management**: Store tokens securely using device keychain/keystore
2. **Offline Handling**: Cache critical data and handle network failures gracefully
3. **Real-time Updates**: Implement efficient polling or WebSocket connections
4. **Image Loading**: Use progressive loading and caching for images
5. **Push Notifications**: Integrate with FCM/APNS for real-time notifications
6. **Biometric Auth**: Support fingerprint/face ID for quick login
7. **Deep Linking**: Support deep links to specific auctions/collaterals

---

## 🧪 Testing

Use the provided test script to verify all APIs:
```bash
php test_mobile_apis.php
```

This comprehensive mobile API documentation ensures your mobile application has all the necessary endpoints for a complete auction experience with optimized performance and user experience. 