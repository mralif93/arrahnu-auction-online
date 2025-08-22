# Address Module Documentation

## Overview

The Address Module provides comprehensive address management functionality for the ArRahnu Auction Online platform. Users can manage multiple addresses for delivery and billing purposes, with support for primary address designation and full CRUD operations. The module includes a robust service layer, enhanced API endpoints, and comprehensive admin management features.

## Recent Enhancements (v2.0)

### Service Layer Architecture
- **AddressService**: Centralized business logic for all address operations
- **Enhanced Validation**: Improved postcode validation and address formatting
- **Transaction Safety**: Database transactions for data integrity
- **Statistics & Analytics**: Comprehensive address statistics and reporting

### Enhanced API Features
- **Advanced Filtering**: Filter addresses by state, primary status, date ranges
- **Address Statistics**: User and global address analytics
- **Export Functionality**: Export addresses in various formats
- **Address Suggestions**: Smart address suggestions based on user input
- **Postcode Validation**: Real-time postcode format validation
- **Bulk Operations**: Admin bulk operations for address management

### Admin Management
- **Global Address Management**: Admin can view and manage all user addresses
- **Advanced Search**: Search across users and address fields
- **User Transfer**: Transfer addresses between users
- **Bulk Actions**: Delete, set/unset primary addresses in bulk
- **Comprehensive Statistics**: Global address analytics and reporting

## Features

### Core Features
- **Multiple Address Management**: Users can add, edit, view, and delete multiple addresses
- **Primary Address System**: Designate one address as primary for default use
- **Malaysian Address Support**: Pre-configured with Malaysian states and postal code validation
- **Address Validation**: Comprehensive validation for all address fields
- **Security**: Users can only access and modify their own addresses
- **Responsive Design**: Works seamlessly on desktop and mobile devices

### User Interface Features
- **Modern UI**: Clean, responsive design with dark/light mode support
- **Interactive Forms**: Real-time validation and user-friendly error messages
- **Copy to Clipboard**: Easy address copying functionality
- **Confirmation Modals**: Safe deletion with confirmation dialogs
- **Quick Actions**: Easy access from profile management

## Database Schema

### Addresses Table
```sql
CREATE TABLE addresses (
    id UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postcode VARCHAR(20) NOT NULL,
    country VARCHAR(100) DEFAULT 'Malaysia',
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_addresses_user_id (user_id)
);
```

### User Table Integration
```sql
-- Users table has a reference to primary address
ALTER TABLE users ADD COLUMN primary_address_id UUID NULL;
ALTER TABLE users ADD FOREIGN KEY (primary_address_id) REFERENCES addresses(id) ON DELETE SET NULL;
```

## API Endpoints

### Authentication Required
All address endpoints require authentication using Bearer token.

### Address Management

#### Get User Addresses
```http
GET /api/addresses
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Addresses retrieved successfully.",
    "data": {
        "addresses": [
            {
                "id": "uuid",
                "user_id": "uuid",
                "address_line_1": "123 Jalan Bukit Bintang",
                "address_line_2": "Unit 5-2",
                "city": "Kuala Lumpur",
                "state": "Kuala Lumpur",
                "postcode": "50200",
                "country": "Malaysia",
                "is_primary": true,
                "created_at": "2024-01-01T00:00:00Z",
                "updated_at": "2024-01-01T00:00:00Z"
            }
        ],
        "total": 1,
        "primary_address": {
            "id": "uuid",
            "address_line_1": "123 Jalan Bukit Bintang",
            // ... full address object
        }
    }
}
```

#### Create New Address
```http
POST /api/addresses
Authorization: Bearer {token}
Content-Type: application/json

{
    "address_line_1": "456 Jalan Raja Chulan",
    "address_line_2": "Apartment 3B",
    "city": "Kuala Lumpur",
    "state": "Kuala Lumpur",
    "postcode": "50300",
    "country": "Malaysia",
    "is_primary": false
}
```

**Response:**
```json
{
    "success": true,
    "message": "Address created successfully.",
    "data": {
        "address": {
            "id": "new-uuid",
            "user_id": "user-uuid",
            "address_line_1": "456 Jalan Raja Chulan",
            "address_line_2": "Apartment 3B",
            "city": "Kuala Lumpur",
            "state": "Kuala Lumpur",
            "postcode": "50300",
            "country": "Malaysia",
            "is_primary": false,
            "created_at": "2024-01-01T00:00:00Z",
            "updated_at": "2024-01-01T00:00:00Z"
        },
        "is_primary": false
    }
}
```

#### Get Specific Address
```http
GET /api/addresses/{address_id}
Authorization: Bearer {token}
```

#### Update Address
```http
PUT /api/addresses/{address_id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "address_line_1": "789 Updated Street",
    "address_line_2": "Suite 10",
    "city": "Petaling Jaya",
    "state": "Selangor",
    "postcode": "47400",
    "country": "Malaysia",
    "is_primary": true
}
```

#### Set Address as Primary
```http
POST /api/addresses/{address_id}/set-primary
Authorization: Bearer {token}
```

#### Delete Address
```http
DELETE /api/addresses/{address_id}
Authorization: Bearer {token}
```

**Note:** Cannot delete the only address. Must have at least one address.

### Utility Endpoints

#### Get Malaysian States
```http
GET /api/addresses/states/list
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Malaysian states retrieved successfully.",
    "data": {
        "states": [
            "Johor", "Kedah", "Kelantan", "Kuala Lumpur", 
            "Labuan", "Melaka", "Negeri Sembilan", "Pahang",
            "Penang", "Perak", "Perlis", "Putrajaya",
            "Sabah", "Sarawak", "Selangor", "Terengganu"
        ],
        "total": 16
    }
}
```

#### Get Validation Rules
```http
GET /api/addresses/validation/rules
Authorization: Bearer {token}
```

## Enhanced API Endpoints (v2.0)

### User Address Management (Enhanced)

#### Get User Addresses with Filtering
```http
GET /api/addresses?order_by=created_at&order_direction=desc&state=Selangor&is_primary=true
Authorization: Bearer {token}
```

**Query Parameters:**
- `order_by`: Field to order by (is_primary, created_at, city, state)
- `order_direction`: asc or desc
- `state`: Filter by specific state
- `is_primary`: Filter by primary status (true/false)

**Enhanced Response:**
```json
{
    "success": true,
    "message": "Addresses retrieved successfully.",
    "data": {
        "addresses": [...],
        "statistics": {
            "total_addresses": 3,
            "primary_address": {...},
            "states_covered": 2,
            "state_distribution": {
                "Selangor": 2,
                "Kuala Lumpur": 1
            },
            "most_recent": {...},
            "oldest": {...}
        }
    }
}
```

#### Get User Address Statistics
```http
GET /api/addresses/statistics
Authorization: Bearer {token}
```

#### Export User Addresses
```http
GET /api/addresses/export
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Addresses exported successfully.",
    "data": {
        "addresses": [
            {
                "id": "uuid",
                "address_line_1": "123 Street",
                "full_address": "123 Street, City, State 12345, Malaysia",
                "created_at": "2024-01-01T00:00:00.000Z",
                "updated_at": "2024-01-01T00:00:00.000Z"
            }
        ],
        "total": 1,
        "exported_at": "2024-01-01T00:00:00.000Z"
    }
}
```

#### Get Address Suggestions
```http
GET /api/addresses/suggestions?query=Main&limit=5
Authorization: Bearer {token}
```

#### Enhanced Address Details
```http
GET /api/addresses/{address_id}
Authorization: Bearer {token}
```

**Enhanced Response:**
```json
{
    "success": true,
    "message": "Address retrieved successfully.",
    "data": {
        "address": {...},
        "full_address": "123 Street, City, State 12345, Malaysia",
        "formatted_addresses": {
            "short": "City, State 12345",
            "single_line": "123 Street, City, State 12345, Malaysia",
            "full": "123 Street\nCity, State 12345\nMalaysia"
        }
    }
}
```

#### Validate Postcode
```http
POST /api/addresses/validate/postcode
Authorization: Bearer {token}
Content-Type: application/json

{
    "postcode": "50000",
    "country": "Malaysia"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Postcode validation completed.",
    "data": {
        "postcode": "50000",
        "country": "Malaysia",
        "is_valid": true
    }
}
```

### Admin Address Management

#### Get All Addresses with Advanced Filtering
```http
GET /api/admin/addresses?search=john&state=Selangor&is_primary=true&user_id=123&date_from=2024-01-01&per_page=20&page=1
Authorization: Bearer {admin_token}
```

**Query Parameters:**
- `search`: Search across address fields and user information
- `state`: Filter by state
- `is_primary`: Filter by primary status
- `user_id`: Filter by specific user
- `date_from`, `date_to`: Date range filters
- `order_by`, `order_direction`: Sorting options
- `per_page`, `page`: Pagination

**Response:**
```json
{
    "success": true,
    "message": "Addresses retrieved successfully.",
    "data": {
        "addresses": [...],
        "statistics": {
            "total_addresses": 150,
            "primary_addresses": 75,
            "recent_addresses": 25,
            "top_state": "Selangor",
            "users_with_addresses": 75,
            "average_addresses_per_user": 2.0
        },
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 150,
            "last_page": 8
        },
        "filters_applied": {
            "search": "john",
            "state": "Selangor"
        }
    }
}
```

#### Create Address for Any User
```http
POST /api/admin/addresses
Authorization: Bearer {admin_token}
Content-Type: application/json

{
    "user_id": "user-uuid",
    "address_line_1": "123 Admin Street",
    "city": "Kuala Lumpur",
    "state": "Kuala Lumpur",
    "postcode": "50000",
    "country": "Malaysia",
    "is_primary": true
}
```

#### Get Global Address Statistics
```http
GET /api/admin/addresses/statistics
Authorization: Bearer {admin_token}
```

#### Export All Addresses
```http
GET /api/admin/addresses/export?state=Selangor&is_primary=true
Authorization: Bearer {admin_token}
```

#### Get Filter Options
```http
GET /api/admin/addresses/filter-options
Authorization: Bearer {admin_token}
```

**Response:**
```json
{
    "success": true,
    "message": "Filter options retrieved successfully.",
    "data": {
        "states": ["Johor", "Kedah", ...],
        "users": [
            {
                "id": "uuid",
                "full_name": "John Doe",
                "username": "johndoe",
                "email": "john@example.com"
            }
        ],
        "primary_options": [
            {"value": true, "label": "Primary"},
            {"value": false, "label": "Secondary"}
        ]
    }
}
```

#### Bulk Operations
```http
POST /api/admin/addresses/bulk-action
Authorization: Bearer {admin_token}
Content-Type: application/json

{
    "action": "delete",
    "address_ids": ["uuid1", "uuid2", "uuid3"],
    "user_id": "user-uuid"
}
```

**Actions:**
- `delete`: Delete multiple addresses
- `set_primary`: Set addresses as primary (requires user_id)
- `unset_primary`: Unset primary status

#### Get User's Addresses (Admin)
```http
GET /api/admin/addresses/users/{user_id}
Authorization: Bearer {admin_token}
```

#### Update Address with User Transfer
```http
PUT /api/admin/addresses/{address_id}
Authorization: Bearer {admin_token}
Content-Type: application/json

{
    "user_id": "new-user-uuid",
    "address_line_1": "Updated Address",
    "city": "New City",
    "state": "New State",
    "postcode": "12345",
    "is_primary": true
}
```

## Service Layer Documentation

### AddressService Methods

#### Core Operations
```php
// Create address for user
$address = $addressService->createAddress($user, $addressData);

// Update existing address
$updatedAddress = $addressService->updateAddress($address, $updateData);

// Delete address with safety checks
$result = $addressService->deleteAddress($address);

// Set address as primary
$addressService->setPrimaryAddress($user, $address);
```

#### Statistics and Analytics
```php
// Get user address statistics
$userStats = $addressService->getUserAddressStatistics($user);

// Get global statistics (admin)
$globalStats = $addressService->getGlobalStatistics();
```

#### Advanced Operations
```php
// Search addresses with filters
$addresses = $addressService->searchAddresses($filters);

// Bulk operations
$result = $addressService->bulkOperation($addressIds, 'delete');

// Get address suggestions
$suggestions = $addressService->getAddressSuggestions($user, 'Main Street');

// Format address for display
$formatted = $addressService->formatAddress($address, 'single_line');

// Validate postcode
$isValid = $addressService->validatePostcode('50000', 'Malaysia');
```

#### Utility Methods
```php
// Get Malaysian states
$states = $addressService->getMalaysianStates();

// Export user addresses
$exportData = $addressService->exportUserAddresses($user);
```

## Error Handling

### Common Error Responses

#### Validation Error
```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": {
        "postcode": ["The postcode must be exactly 5 digits."],
        "state": ["The state field is required."]
    }
}
```

#### Business Logic Error
```json
{
    "success": false,
    "message": "Cannot delete your only address. Please add another address first.",
    "error_code": "ONLY_ADDRESS"
}
```

#### Authorization Error
```json
{
    "success": false,
    "message": "Unauthorized access to address."
}
```

## Testing

### Running Enhanced Tests
```bash
php test_enhanced_address_api.php
```

### Test Coverage
- ✅ AddressService integration
- ✅ Enhanced API endpoints
- ✅ Statistics and analytics
- ✅ Admin API endpoints
- ✅ Bulk operations
- ✅ Address formatting and validation
- ✅ Error handling and edge cases

```http
GET /api/addresses/validation/rules
Authorization: Bearer {token}
```

## Web Routes

### Address Management Pages
- `GET /addresses` - List all user addresses
- `GET /addresses/create` - Show create address form
- `POST /addresses` - Store new address
- `GET /addresses/{address}` - Show address details
- `GET /addresses/{address}/edit` - Show edit address form
- `PUT /addresses/{address}` - Update address
- `POST /addresses/{address}/set-primary` - Set as primary address
- `DELETE /addresses/{address}` - Delete address

### Utility Routes
- `GET /addresses/api/states` - Get Malaysian states (for AJAX)

## Validation Rules

### Address Validation
```php
[
    'address_line_1' => 'required|string|max:255',
    'address_line_2' => 'nullable|string|max:255',
    'city' => 'required|string|max:100',
    'state' => 'required|string|max:100',
    'postcode' => 'required|string|max:20',
    'country' => 'required|string|max:100',
    'is_primary' => 'boolean'
]
```

### Frontend Validation
- **Postcode**: Must be exactly 5 digits for Malaysian addresses
- **State**: Must be selected from predefined Malaysian states
- **Required Fields**: Address Line 1, City, State, Postcode, Country

## Model Relationships

### Address Model
```php
class Address extends Model
{
    // Belongs to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Get full formatted address
    public function getFullAddressAttribute(): string
    {
        // Returns comma-separated full address
    }
    
    // Set as primary address
    public function setPrimary(): void
    {
        // Unsets other primary addresses and sets this as primary
    }
}
```

### User Model Integration
```php
class User extends Model
{
    // Has many addresses
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'user_id');
    }
    
    // Primary address relationship
    public function primaryAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'primary_address_id');
    }
}
```

## Controllers

### AddressController (Web)
- Handles web interface for address management
- Returns Blade views with proper data
- Handles form submissions and redirects

### Api\AddressController (API)
- Handles API requests for address management
- Returns JSON responses
- Comprehensive error handling

## Security Features

### Access Control
- Users can only access their own addresses
- Middleware ensures authentication
- Route model binding with ownership verification

### Data Protection
- Input validation and sanitization
- CSRF protection on web forms
- SQL injection prevention through Eloquent ORM

### Business Logic Protection
- Cannot delete the only address
- Primary address management ensures data consistency
- Automatic primary address assignment for first address

## Frontend Integration

### JavaScript Features
```javascript
// Copy address to clipboard
function copyAddress() {
    navigator.clipboard.writeText(address);
    // Show success feedback
}

// Postcode formatting
document.getElementById('postcode').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 5);
});

// Form validation
function validateForm() {
    // Check postcode length, required fields, etc.
}
```

### AJAX Integration
```javascript
// Get states dynamically
fetch('/addresses/api/states')
    .then(response => response.json())
    .then(data => {
        // Populate state dropdown
    });
```

## Usage Examples

### Creating an Address via API
```javascript
const createAddress = async (addressData) => {
    try {
        const response = await fetch('/api/addresses', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(addressData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log('Address created:', result.data.address);
        } else {
            console.error('Error:', result.message);
        }
    } catch (error) {
        console.error('Network error:', error);
    }
};
```

### Setting Primary Address
```javascript
const setPrimaryAddress = async (addressId) => {
    try {
        const response = await fetch(`/api/addresses/${addressId}/set-primary`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update UI to reflect primary address change
            updatePrimaryAddressUI(result.data.address);
        }
    } catch (error) {
        console.error('Error setting primary address:', error);
    }
};
```

## Integration with Other Modules

### Profile System
- Address management accessible from profile page
- Quick action buttons for easy navigation
- Address count displayed in profile completion

### Auction System
- Primary address used for delivery information
- Address selection during checkout process
- Billing address integration

### Admin System
- Admin can view user addresses in user management
- Address information displayed in user details
- No direct admin address management (users manage their own)

## Error Handling

### API Error Responses
```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": {
        "address_line_1": ["The address line 1 field is required."],
        "postcode": ["The postcode must be exactly 5 digits."]
    }
}
```

### Common Error Scenarios
1. **Unauthorized Access**: 403 when trying to access another user's address
2. **Validation Errors**: 422 with detailed field errors
3. **Not Found**: 404 when address doesn't exist
4. **Business Logic Errors**: 400 when trying to delete only address

## Testing

### API Testing Examples
```bash
# Get user addresses
curl -X GET "http://localhost:8000/api/addresses" \
  -H "Authorization: Bearer YOUR_TOKEN"

# Create new address
curl -X POST "http://localhost:8000/api/addresses" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "address_line_1": "123 Test Street",
    "city": "Kuala Lumpur",
    "state": "Kuala Lumpur",
    "postcode": "50200",
    "country": "Malaysia"
  }'

# Set primary address
curl -X POST "http://localhost:8000/api/addresses/ADDRESS_ID/set-primary" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Frontend Testing
1. **Form Validation**: Test all validation rules
2. **AJAX Functionality**: Test state loading and form submissions
3. **Responsive Design**: Test on various screen sizes
4. **Accessibility**: Test keyboard navigation and screen readers

## Deployment Considerations

### Database Migration
```bash
# Run the address migration
php artisan migrate

# Seed sample addresses (if needed)
php artisan db:seed --class=AddressSeeder
```

### Route Caching
```bash
# Clear and cache routes after adding address routes
php artisan route:clear
php artisan route:cache
```

### File Permissions
Ensure proper permissions for:
- View files in `resources/views/public/addresses/`
- Controller files
- Route files

## Future Enhancements

### Planned Features
1. **Address Validation Service**: Integration with postal service APIs
2. **Geolocation**: Map integration for address selection
3. **Address Templates**: Save common address patterns
4. **Bulk Import**: CSV import for multiple addresses
5. **Address History**: Track address changes over time

### API Enhancements
1. **Pagination**: For users with many addresses
2. **Search/Filter**: Find addresses by city, state, etc.
3. **Address Verification**: Real-time address validation
4. **Export**: Download addresses in various formats

## Troubleshooting

### Common Issues

#### Routes Not Working
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache
```

#### Views Not Found
- Check view file paths in `resources/views/public/addresses/`
- Verify controller return statements

#### API Errors
- Check authentication middleware
- Verify CSRF token for web requests
- Check database connections

#### Validation Errors
- Review validation rules in `ValidationService`
- Check frontend JavaScript validation
- Verify form field names match validation rules

### Debug Mode
Enable debug mode to see detailed error messages:
```php
// In .env file
APP_DEBUG=true
```

## Support

For issues or questions regarding the Address Module:
1. Check this documentation first
2. Review the code in controllers and models
3. Test API endpoints using the provided examples
4. Check Laravel logs for detailed error information

## Changelog

### Version 1.0.0 (Current)
- Initial address module implementation
- Full CRUD operations for addresses
- Primary address management
- Malaysian state support
- Web and API interfaces
- Responsive UI with dark/light mode
- Integration with profile system 