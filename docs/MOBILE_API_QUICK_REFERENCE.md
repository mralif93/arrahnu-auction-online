# Mobile API Quick Reference Guide
## ArRahnu Auction Online - Developer Cheat Sheet

### 🚀 Base URL
```
Development: http://localhost:8000/api
Production: https://your-domain.com/api
```

### 🔐 Authentication Header
```
Authorization: Bearer {token}
```

---

## 📋 Quick Endpoint Reference

### 🔐 Authentication (Public)
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/auth/register` | Register new user |
| `POST` | `/auth/login` | Login user |
| `POST` | `/auth/forgot-password` | Request password reset |
| `POST` | `/auth/reset-password` | Reset password |
| `GET` | `/health` | API health check |

### 👤 User Management (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/user/profile` | Get complete user profile |
| `PUT` | `/user/profile` | Update user profile |
| `PUT` | `/user/password` | Change password |
| `POST` | `/user/avatar` | Upload avatar |
| `DELETE` | `/user/avatar` | Remove avatar |
| `PUT` | `/user/preferences` | Update preferences |
| `GET` | `/user/bidding-activity` | Get bidding activity |
| `GET` | `/user/watchlist` | Get user watchlist |
| `DELETE` | `/user/account` | Delete user account |

### 🏠 Address Management (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/addresses` | List user addresses |
| `POST` | `/addresses` | Create new address |
| `GET` | `/addresses/{id}` | Get address details |
| `PUT` | `/addresses/{id}` | Update address |
| `DELETE` | `/addresses/{id}` | Delete address |
| `POST` | `/addresses/{id}/set-primary` | Set primary address |
| `GET` | `/addresses/states/list` | Get Malaysian states |
| `GET` | `/addresses/statistics` | Address statistics |
| `GET` | `/addresses/export` | Export addresses |
| `GET` | `/addresses/suggestions` | Address suggestions |
| `GET` | `/addresses/validation/rules` | Validation rules |
| `POST` | `/addresses/validate/postcode` | Validate postcode |

### 🎯 Bidding System (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/bids` | Get bidding history |
| `POST` | `/bids` | Place new bid |
| `GET` | `/bids/active` | Get active bids |
| `GET` | `/bids/statistics` | Bidding statistics |
| `POST` | `/bids/{id}/cancel` | Cancel bid |

### 🏛️ Auctions (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/auctions/active` | Get active auctions |
| `GET` | `/auctions/collaterals/{id}` | Get collateral details |
| `GET` | `/auctions/collaterals/{id}/live-updates` | Live bidding updates |

### 🔧 Admin Dashboard (Admin Only)
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/admin/dashboard/overview` | System overview |
| `GET` | `/admin/dashboard/user-analytics` | User analytics |
| `GET` | `/admin/dashboard/auction-analytics` | Auction analytics |
| `GET` | `/admin/dashboard/system-metrics` | System metrics |
| `GET` | `/admin/dashboard/activity-feed` | Activity feed |
| `GET` | `/admin/dashboard/alerts` | System alerts |

---

## 🔥 Essential API Calls for Mobile Apps

### 1. User Authentication Flow
```javascript
// Register
POST /auth/register
{
  "full_name": "John Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone_number": "+60123456789",
  "role": "bidder"
}

// Login
POST /auth/login
{
  "email": "john@example.com",
  "password": "password123"
}
// Returns: { token, user }

// Logout
POST /auth/logout
Headers: { Authorization: "Bearer {token}" }
```

### 2. User Profile Management
```javascript
// Get Profile
GET /user/profile
Headers: { Authorization: "Bearer {token}" }
// Returns: { user, statistics, profile_completion }

// Update Profile
PUT /user/profile
{
  "full_name": "John Updated",
  "nationality": "Malaysian",
  "occupation": "Engineer"
}

// Upload Avatar
POST /user/avatar
Content-Type: multipart/form-data
Body: { avatar: [file] }
```

### 3. Address Management
```javascript
// Get Addresses
GET /addresses?per_page=20&page=1
Headers: { Authorization: "Bearer {token}" }

// Create Address
POST /addresses
{
  "address_line_1": "123 Main Street",
  "city": "Kuala Lumpur",
  "state": "Kuala Lumpur",
  "postcode": "50000",
  "country": "Malaysia",
  "is_primary": true
}

// Get Malaysian States
GET /addresses/states/list
```

### 4. Bidding Operations
```javascript
// Get Active Auctions
GET /auctions/active?per_page=20&search=gold

// Get Collateral Details
GET /auctions/collaterals/{id}
// Returns: { collateral, auction, bidding_info, recent_bids }

// Place Bid
POST /bids
{
  "collateral_id": "uuid",
  "amount": 1500.00
}

// Get Live Updates (Poll every 5-10 seconds)
GET /auctions/collaterals/{id}/live-updates
// Returns: { current_highest_bid, is_user_winning, time_remaining }

// Get User's Bidding History
GET /bids?per_page=20&status=active
```

---

## 📱 Mobile App Implementation Tips

### 🔐 Authentication Management
```javascript
class AuthManager {
  async login(email, password) {
    const response = await api.post('/auth/login', { email, password });
    if (response.success) {
      await this.storeToken(response.data.token);
      return response.data.user;
    }
    throw new Error(response.message);
  }

  async storeToken(token) {
    // Use secure storage (Keychain/Keystore)
    await SecureStore.setItemAsync('auth_token', token);
  }

  async getToken() {
    return await SecureStore.getItemAsync('auth_token');
  }

  async logout() {
    await api.post('/auth/logout');
    await SecureStore.deleteItemAsync('auth_token');
  }
}
```

### 🔄 Real-time Bidding Updates
```javascript
class BiddingManager {
  constructor() {
    this.updateInterval = null;
  }

  startLiveUpdates(collateralId, callback) {
    this.updateInterval = setInterval(async () => {
      try {
        const response = await api.get(`/auctions/collaterals/${collateralId}/live-updates`);
        if (response.success) {
          callback(response.data);
        }
      } catch (error) {
        console.error('Live update failed:', error);
      }
    }, 5000); // Poll every 5 seconds
  }

  stopLiveUpdates() {
    if (this.updateInterval) {
      clearInterval(this.updateInterval);
      this.updateInterval = null;
    }
  }

  async placeBid(collateralId, amount) {
    return await api.post('/bids', {
      collateral_id: collateralId,
      amount: amount
    });
  }
}
```

### 📊 Data Caching Strategy
```javascript
class CacheManager {
  constructor() {
    this.cache = new Map();
    this.cacheTimeout = 5 * 60 * 1000; // 5 minutes
  }

  async get(key, fetchFunction) {
    const cached = this.cache.get(key);
    if (cached && Date.now() - cached.timestamp < this.cacheTimeout) {
      return cached.data;
    }

    const data = await fetchFunction();
    this.cache.set(key, {
      data,
      timestamp: Date.now()
    });
    return data;
  }

  clear() {
    this.cache.clear();
  }
}

// Usage
const cache = new CacheManager();
const profile = await cache.get('user_profile', () => api.get('/user/profile'));
```

### 🏠 Address Management Helper
```javascript
class AddressManager {
  async getStates() {
    return await api.get('/addresses/states/list');
  }

  async getUserAddresses(filters = {}) {
    const params = new URLSearchParams(filters);
    return await api.get(`/addresses?${params}`);
  }

  async createAddress(addressData) {
    return await api.post('/addresses', addressData);
  }

  async setPrimaryAddress(addressId) {
    return await api.post(`/addresses/${addressId}/set-primary`);
  }

  validatePostcode(postcode) {
    // Malaysian postcode format: 5 digits
    return /^\d{5}$/.test(postcode);
  }
}
```

---

## 🚨 Error Handling

### Common Error Codes
| Code | Description | Action |
|------|-------------|---------|
| `401` | Unauthorized | Redirect to login |
| `403` | Forbidden | Show access denied |
| `422` | Validation Error | Show field errors |
| `429` | Rate Limited | Show retry message |
| `500` | Server Error | Show generic error |

### Error Response Format
```json
{
  "success": false,
  "message": "Human readable error message",
  "error_code": "SPECIFIC_ERROR_CODE",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### Error Handler Implementation
```javascript
class ErrorHandler {
  static handle(error, navigation) {
    if (error.status === 401) {
      // Token expired or invalid
      AuthManager.logout();
      navigation.navigate('Login');
    } else if (error.status === 422) {
      // Validation errors
      return error.data.errors;
    } else if (error.status === 429) {
      // Rate limited
      Alert.alert('Too Many Requests', 'Please wait before trying again.');
    } else {
      // Generic error
      Alert.alert('Error', error.data?.message || 'Something went wrong');
    }
  }
}
```

---

## 📊 Pagination & Filtering

### Standard Query Parameters
```javascript
// Pagination
?page=1&per_page=20

// Filtering
?search=gold&state=Selangor&is_primary=true

// Sorting
?sort_by=created_at&sort_order=desc

// Date Range
?start_date=2024-01-01&end_date=2024-01-31
```

### Pagination Helper
```javascript
class PaginationHelper {
  constructor(initialData = []) {
    this.data = initialData;
    this.currentPage = 1;
    this.hasMore = true;
    this.loading = false;
  }

  async loadMore(fetchFunction) {
    if (this.loading || !this.hasMore) return;

    this.loading = true;
    try {
      const response = await fetchFunction(this.currentPage + 1);
      if (response.data.length > 0) {
        this.data = [...this.data, ...response.data];
        this.currentPage++;
        this.hasMore = response.pagination.current_page < response.pagination.last_page;
      } else {
        this.hasMore = false;
      }
    } finally {
      this.loading = false;
    }
  }

  reset() {
    this.data = [];
    this.currentPage = 1;
    this.hasMore = true;
    this.loading = false;
  }
}
```

---

## 🔧 Development Commands

### Laravel Artisan Commands
```bash
# Start development server
php artisan serve --host=0.0.0.0 --port=8000

# Clear all caches
php artisan route:clear && php artisan config:clear && php artisan cache:clear

# View API routes
php artisan route:list --path=api

# Run database migrations
php artisan migrate

# Seed database with test data
php artisan db:seed
```

### API Testing with cURL
```bash
# Health check
curl http://localhost:8000/api/health

# Register user
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"full_name":"Test User","username":"testuser","email":"test@example.com","password":"password123","password_confirmation":"password123","role":"bidder"}'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Get profile (replace TOKEN)
curl -H "Authorization: Bearer TOKEN" http://localhost:8000/api/user/profile
```

---

## 📱 Mobile Framework Examples

### React Native
```javascript
import AsyncStorage from '@react-native-async-storage/async-storage';

const API_BASE = 'http://localhost:8000/api';

const apiClient = {
  async request(endpoint, options = {}) {
    const token = await AsyncStorage.getItem('auth_token');
    const config = {
      headers: {
        'Content-Type': 'application/json',
        ...(token && { Authorization: `Bearer ${token}` }),
        ...options.headers,
      },
      ...options,
    };

    const response = await fetch(`${API_BASE}${endpoint}`, config);
    return response.json();
  },

  get: (endpoint) => apiClient.request(endpoint),
  post: (endpoint, data) => apiClient.request(endpoint, {
    method: 'POST',
    body: JSON.stringify(data),
  }),
  put: (endpoint, data) => apiClient.request(endpoint, {
    method: 'PUT',
    body: JSON.stringify(data),
  }),
  delete: (endpoint) => apiClient.request(endpoint, { method: 'DELETE' }),
};
```

### Flutter/Dart
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiClient {
  static const String baseUrl = 'http://localhost:8000/api';
  String? _token;

  void setToken(String token) {
    _token = token;
  }

  Map<String, String> get headers => {
    'Content-Type': 'application/json',
    if (_token != null) 'Authorization': 'Bearer $_token',
  };

  Future<Map<String, dynamic>> get(String endpoint) async {
    final response = await http.get(
      Uri.parse('$baseUrl$endpoint'),
      headers: headers,
    );
    return json.decode(response.body);
  }

  Future<Map<String, dynamic>> post(String endpoint, Map<String, dynamic> data) async {
    final response = await http.post(
      Uri.parse('$baseUrl$endpoint'),
      headers: headers,
      body: json.encode(data),
    );
    return json.decode(response.body);
  }
}
```

---

## 🎯 Performance Optimization

### Image Loading
```javascript
// Progressive image loading
const ImageLoader = ({ uri, placeholder }) => {
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(false);

  return (
    <View>
      {loading && <Image source={placeholder} />}
      <Image
        source={{ uri }}
        onLoad={() => setLoading(false)}
        onError={() => setError(true)}
        style={{ display: loading ? 'none' : 'flex' }}
      />
    </View>
  );
};
```

### Offline Support
```javascript
import NetInfo from '@react-native-netinfo/netinfo';

class OfflineManager {
  constructor() {
    this.queue = [];
    this.isOnline = true;
    
    NetInfo.addEventListener(state => {
      this.isOnline = state.isConnected;
      if (this.isOnline) {
        this.processQueue();
      }
    });
  }

  async request(endpoint, options) {
    if (!this.isOnline) {
      this.queue.push({ endpoint, options });
      throw new Error('Offline - request queued');
    }
    return api.request(endpoint, options);
  }

  async processQueue() {
    while (this.queue.length > 0) {
      const { endpoint, options } = this.queue.shift();
      try {
        await api.request(endpoint, options);
      } catch (error) {
        console.error('Queued request failed:', error);
      }
    }
  }
}
```

---

## 📋 Summary

### Total API Endpoints: 59+
- **Public**: 5 endpoints
- **User Management**: 9 endpoints  
- **Address Management**: 12 endpoints
- **Bidding System**: 5 endpoints
- **Auction Management**: 3 endpoints
- **Admin Dashboard**: 25+ endpoints

### Key Features for Mobile Apps
✅ JWT Authentication with secure token management
✅ Real-time bidding with live updates
✅ Comprehensive user profile management
✅ Malaysian address system with validation
✅ Pagination and filtering on all list endpoints
✅ File upload support for avatars
✅ Error handling with specific error codes
✅ Rate limiting and security features
✅ Admin dashboard with analytics

### Ready for Production
The API is mobile-ready with optimized responses, comprehensive documentation, and robust error handling. Perfect for building native iOS/Android apps or cross-platform solutions with React Native/Flutter.