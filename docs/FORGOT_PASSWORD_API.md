# Forgot Password API Documentation

## Overview
The ArRahnu Auction Online application provides both API and web-based password reset functionality. This document covers both implementations.

## API Endpoints

### 1. Request Password Reset

**Endpoint:** `POST /api/auth/forgot-password`

**Description:** Sends a password reset email to the user.

**Request Body:**
```json
{
    "email": "user@example.com"
}
```

**Success Response (200):**
```json
{
    "message": "Password reset link sent to your email address."
}
```

**Error Response (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ]
    }
}
```

### 2. Reset Password

**Endpoint:** `POST /api/auth/reset-password`

**Description:** Resets the user's password using the token from the email.

**Request Body:**
```json
{
    "email": "user@example.com",
    "token": "reset_token_from_email",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Success Response (200):**
```json
{
    "message": "Password has been reset successfully."
}
```

**Error Response (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "We can't find a user with that email address."
        ]
    }
}
```

## Web Password Reset Flow

### Issue Resolution
**Problem:** Users clicking the reset password link in emails were being redirected to the dashboard instead of seeing the password reset form.

**Root Cause:** The `ResetPasswordController` was trying to update the `password` field, but the User model uses `password_hash`.

**Solution Applied:**
1. Updated `ResetPasswordController::reset()` method to use `password_hash` field
2. Ensured proper route configuration with guest middleware
3. Verified password reset view exists and is properly configured

### Web Routes
- `GET /forgot-password` - Show forgot password form
- `POST /forgot-password` - Send reset email
- `GET /reset-password/{token}` - Show reset password form
- `POST /reset-password` - Process password reset

### Testing the Fix
The password reset functionality now works correctly:
1. Users receive reset emails with valid tokens
2. Clicking the reset link shows the password reset form
3. Submitting the form successfully updates the password
4. Users are redirected to login with success message

## Security Features

### Email Enumeration Protection
Both API and web implementations return success messages even for non-existent email addresses to prevent email enumeration attacks.

### Token Security
- Reset tokens expire after 60 minutes (configurable)
- Tokens are cryptographically secure
- One-time use tokens (invalidated after successful reset)

### Password Validation
- Minimum 8 characters
- Must be confirmed (password_confirmation field)
- Uses Laravel's default password rules

## Configuration

### Email Settings
Configure email settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@arrahnu.com
MAIL_FROM_NAME="ArRahnu Auction"
```

### Token Expiration
Configure token expiration in `config/auth.php`:
```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60, // minutes
        'throttle' => 60, // seconds between requests
    ],
],
```

## Frontend Integration Examples

### API Usage (JavaScript)
```javascript
// Request password reset
const requestReset = async (email) => {
    try {
        const response = await fetch('/api/auth/forgot-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email })
        });
        
        const data = await response.json();
        console.log(data.message);
    } catch (error) {
        console.error('Error:', error);
    }
};

// Reset password
const resetPassword = async (email, token, password, passwordConfirmation) => {
    try {
        const response = await fetch('/api/auth/reset-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email,
                token,
                password,
                password_confirmation: passwordConfirmation
            })
        });
        
        const data = await response.json();
        console.log(data.message);
    } catch (error) {
        console.error('Error:', error);
    }
};
```

### Web Form Usage
The web forms are already implemented and working correctly. Users can:
1. Visit `/forgot-password` to request a reset
2. Click the link in their email to access `/reset-password/{token}`
3. Fill out the reset form and submit

## Testing

### Manual Testing
1. **Request Reset:**
   ```bash
   curl -X POST http://localhost:8000/api/auth/forgot-password \
        -H "Content-Type: application/json" \
        -d '{"email":"admin@arrahnu.com"}'
   ```

2. **Check Email:** Look for the reset email in your configured mail system

3. **Reset Password:** Use the token from the email:
   ```bash
   curl -X POST http://localhost:8000/api/auth/reset-password \
        -H "Content-Type: application/json" \
        -d '{"email":"admin@arrahnu.com","token":"TOKEN_FROM_EMAIL","password":"newpassword","password_confirmation":"newpassword"}'
   ```

### Automated Testing
```php
// Test password reset flow
$user = User::factory()->create();
$token = Password::createToken($user);

$response = $this->post('/api/auth/reset-password', [
    'email' => $user->email,
    'token' => $token,
    'password' => 'newpassword',
    'password_confirmation' => 'newpassword'
]);

$response->assertStatus(200);
$this->assertTrue(Hash::check('newpassword', $user->fresh()->password_hash));
```

## Troubleshooting

### Common Issues
1. **404 on reset link:** Ensure routes are properly cached and server is restarted
2. **Email not sending:** Check email configuration and logs
3. **Token invalid:** Tokens expire after 60 minutes by default
4. **Password not updating:** Ensure User model uses `password_hash` field

### Debugging
- Check `storage/logs/laravel.log` for errors
- Verify email configuration with `php artisan tinker`
- Test route resolution with `php artisan route:list`

## Status
✅ **FIXED:** Password reset functionality now works correctly for both API and web interfaces.
✅ **TESTED:** Complete flow verified from email request to password update.
✅ **SECURE:** Proper validation, token expiration, and email enumeration protection implemented. 