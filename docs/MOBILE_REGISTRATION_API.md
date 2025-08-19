# Mobile Registration API Documentation

## User Registration Endpoint

Register a new user account in the ArRahnu Auction system.

### Endpoint Details
- **URL**: `/api/auth/register`
- **Method**: `POST`
- **Content-Type**: `application/json`
- **Accept**: `application/json`

### Request Body

```json
{
    "full_name": "string",       // Required: User's full name
    "username": "string",        // Required: Unique username
    "email": "string",          // Required: Valid email address
    "password": "string",       // Required: Minimum 8 characters
    "password_confirmation": "string", // Required: Must match password
    "phone_number": "string",   // Optional: Format: +[country_code][number]
    "role": "string"           // Optional: "bidder" (default), "maker", or "checker"
}
```

### Field Validations

| Field | Requirements | Description |
|-------|-------------|-------------|
| `full_name` | Required, max 255 chars | User's complete name |
| `username` | Required, max 255 chars, unique | Unique identifier for the user |
| `email` | Required, max 255 chars, valid email format, unique | Valid email address |
| `password` | Required, min 8 chars | Strong password |
| `password_confirmation` | Required, must match password | Password verification |
| `phone_number` | Optional, max 20 chars, unique | International format phone number |
| `role` | Optional, enum | One of: "bidder", "maker", "checker". Defaults to "bidder" |

### Successful Response (201 Created)

```json
{
    "success": true,
    "message": "Registration successful. Your account is pending email verification and admin approval.",
    "data": {
        "user": {
            "id": "uuid-string",
            "full_name": "Test User",
            "username": "testuser123",
            "email": "user@example.com",
            "phone_number": "+60123456789",
            "role": "bidder",
            "status": "pending_approval",
            "email_verification_required": true,
            "requires_admin_approval": true
        },
        "verification_email_sent": boolean
    }
}
```

### Error Responses

#### 1. Validation Errors (422 Unprocessable Content)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field_name": [
            "Error message"
        ]
    }
}
```

Common validation errors:
- Username already taken
- Email already registered
- Phone number already registered
- Invalid email format
- Password too short
- Password confirmation mismatch

#### 2. Server Error (500 Internal Server Error)
```json
{
    "success": false,
    "message": "Registration failed. Please try again.",
    "error": "Detailed error message" // Only in development
}
```

### Post-Registration Flow

1. After successful registration:
   - Account status will be "pending_approval"
   - Email verification is required
   - Admin approval is required

2. User should:
   - Check email for verification link
   - Wait for admin approval
   - Use the verification status endpoint to check progress

### Sample Code

#### Swift Example
```swift
struct RegistrationRequest: Codable {
    let fullName: String
    let username: String
    let email: String
    let password: String
    let passwordConfirmation: String
    let phoneNumber: String?
    let role: String?
    
    enum CodingKeys: String, CodingKey {
        case fullName = "full_name"
        case username
        case email
        case password
        case passwordConfirmation = "password_confirmation"
        case phoneNumber = "phone_number"
        case role
    }
}

func registerUser(request: RegistrationRequest) {
    let url = URL(string: "https://your-api-base-url/api/auth/register")!
    var urlRequest = URLRequest(url: url)
    urlRequest.httpMethod = "POST"
    urlRequest.setValue("application/json", forHTTPHeaderField: "Content-Type")
    
    do {
        let jsonData = try JSONEncoder().encode(request)
        urlRequest.httpBody = jsonData
        
        URLSession.shared.dataTask(with: urlRequest) { data, response, error in
            // Handle response
        }.resume()
    } catch {
        // Handle error
    }
}
```

#### Kotlin Example
```kotlin
data class RegistrationRequest(
    @SerializedName("full_name") val fullName: String,
    val username: String,
    val email: String,
    val password: String,
    @SerializedName("password_confirmation") val passwordConfirmation: String,
    @SerializedName("phone_number") val phoneNumber: String?,
    val role: String?
)

fun registerUser(request: RegistrationRequest) {
    val retrofit = Retrofit.Builder()
        .baseUrl("https://your-api-base-url/")
        .addConverterFactory(GsonConverterFactory.create())
        .build()

    val apiService = retrofit.create(ApiService::class.java)
    
    apiService.register(request).enqueue(object : Callback<RegistrationResponse> {
        override fun onResponse(call: Call<RegistrationResponse>, response: Response<RegistrationResponse>) {
            // Handle success
        }
        
        override fun onFailure(call: Call<RegistrationResponse>, t: Throwable) {
            // Handle error
        }
    })
}
```

### Important Notes

1. **Security**:
   - Always use HTTPS for API calls
   - Never store passwords in plain text
   - Implement proper error handling
   - Consider implementing rate limiting in your app

2. **Validation**:
   - Implement client-side validation before API calls
   - Handle all possible error responses
   - Show appropriate user feedback

3. **User Experience**:
   - Show loading state during registration
   - Clearly communicate registration status
   - Guide users through the email verification process
   - Provide clear feedback about admin approval status

4. **Testing**:
   - Test with various input combinations
   - Test network error scenarios
   - Test validation error handling
   - Test success flow completely

### Related Endpoints

- `POST /api/auth/login` - User login
- `GET /api/auth/verify-email/{token}` - Email verification
- `POST /api/auth/resend-verification` - Resend verification email
- `GET /api/auth/verification-status` - Check verification status

For more information about these endpoints, refer to the complete API documentation. 