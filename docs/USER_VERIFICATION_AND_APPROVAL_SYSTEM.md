# User Verification and Approval System

## Overview

The ArRahnu Auction Online platform now includes a comprehensive user verification and approval system that ensures secure user registration and admin oversight. This system implements email verification requirements and admin approval workflows for enhanced security.

## System Architecture

### 1. Database Schema Enhancements

The system adds the following fields to the users table:

#### Login Tracking Fields
- `last_api_login_at` - Timestamp of last API login
- `last_web_login_at` - Timestamp of last web login  
- `last_login_source` - Source of last login ('api' or 'web')
- `last_login_ip` - IP address of last login
- `last_login_user_agent` - User agent of last login

#### Email Verification Workflow
- `email_verified_at` - Timestamp when email was verified
- `requires_admin_approval` - Boolean flag (default: true)
- `email_verification_required` - Boolean flag (default: true)
- `verification_token_expires_at` - Token expiration timestamp
- `failed_verification_attempts` - Counter for failed attempts

#### Admin Approval System
- `approved_at` - Timestamp when approved by admin
- `rejected_at` - Timestamp when rejected by admin
- `approval_notes` - Admin notes for approval/rejection
- `registration_source` - Source of registration ('web', 'api', 'admin')

#### Security Features
- `login_attempts` - Failed login attempt counter
- `last_login_attempt_at` - Timestamp of last login attempt
- `account_locked_until` - Account lock expiration timestamp

### 2. User Registration Flow

```
1. User Registration
   ↓
2. Email Verification Required
   ↓
3. Admin Approval Required
   ↓
4. Account Active
```

## Implementation Details

### 1. Services

#### EmailVerificationService
- **Purpose**: Manages email verification workflow
- **Key Methods**:
  - `sendVerificationEmail()` - Generates tokens and sends verification emails
  - `verifyEmail()` - Validates tokens and marks emails as verified
  - `resendVerificationEmail()` - Resends verification with rate limiting
  - `getVerificationStatus()` - Returns verification status details

#### AdminApprovalService
- **Purpose**: Manages admin approval workflow
- **Key Methods**:
  - `getPendingUsers()` - Gets paginated list of users needing approval
  - `approveUser()` - Approves user with notifications
  - `rejectUser()` - Rejects user with notifications
  - `bulkApproveUsers()` - Batch approval functionality
  - `getApprovalStatistics()` - Dashboard statistics

### 2. Enhanced User Model

#### New Helper Methods
- `isEmailVerified()` - Check if email is verified
- `requiresEmailVerification()` - Check if email verification is required
- `requiresAdminApproval()` - Check if admin approval is required
- `isAccountLocked()` - Check if account is temporarily locked
- `canLogin()` - Comprehensive login eligibility check
- `updateLoginTracking()` - Update login tracking fields
- `incrementLoginAttempts()` - Track failed login attempts
- `markEmailAsVerified()` - Mark email as verified
- `approveAccount()` - Approve account with admin details
- `rejectAccount()` - Reject account with admin details

#### Constants
```php
// Login sources
const LOGIN_SOURCE_API = 'api';
const LOGIN_SOURCE_WEB = 'web';

// Registration sources  
const REGISTRATION_SOURCE_WEB = 'web';
const REGISTRATION_SOURCE_API = 'api';
const REGISTRATION_SOURCE_ADMIN = 'admin';
```

### 3. API Enhancements

#### Enhanced Registration Endpoint
- **Endpoint**: `POST /api/auth/register`
- **New Features**:
  - Sets verification requirements automatically
  - Sends verification email immediately
  - Tracks registration source
  - Returns verification status

#### Enhanced Login Endpoint
- **Endpoint**: `POST /api/auth/login`
- **New Features**:
  - Checks account lock status
  - Verifies email verification requirement
  - Verifies admin approval requirement
  - Tracks login source (API vs web)
  - Records IP and user agent
  - Implements failed login attempt tracking

#### New Verification Endpoints
- `GET /api/auth/verify-email/{token}` - Verify email with token
- `POST /api/auth/resend-verification` - Resend verification email
- `POST /api/auth/verification-status` - Check verification status

### 4. Email Templates

#### Email Verification Template
- **File**: `resources/views/emails/email-verification.blade.php`
- **Features**:
  - Modern, responsive design
  - Clear call-to-action button
  - Expiration time display
  - Security notes
  - Mobile-friendly layout

#### Account Approval Template
- **File**: `resources/views/emails/account-approved.blade.php`
- **Features**:
  - Welcome message
  - Next steps guidance
  - Login instructions

#### Account Rejection Template
- **File**: `resources/views/emails/account-rejected.blade.php`
- **Features**:
  - Polite rejection message
  - Reason for rejection
  - Contact information for appeals

### 5. Admin Dashboard

#### User Management Interface
- **Route**: `/admin/users`
- **Features**:
  - Statistics dashboard
  - User filtering (all, active, pending, admin)
  - Approval/rejection actions
  - Email verification management
  - Bulk operations

#### Pending Approvals View
- **Route**: `/admin/users/pending-approvals`
- **Features**:
  - Dedicated pending approvals interface
  - Bulk approval functionality
  - Verification status display
  - Quick action buttons

## Security Features

### 1. Account Locking
- Automatic account locking after 5 failed login attempts
- Temporary lock duration: 15 minutes
- Prevents brute force attacks

### 2. Email Verification
- Verification tokens expire after 24 hours
- Rate limiting on verification email sending
- Failed verification attempt tracking

### 3. Admin Approval
- Required for all new registrations
- Admin notes for approval/rejection decisions
- Audit trail for all approval actions

### 4. Login Tracking
- Separate tracking for API and web logins
- IP address and user agent logging
- Failed login attempt monitoring

## API Response Examples

### Registration Response
```json
{
    "success": true,
    "message": "Registration successful. Please check your email for verification instructions.",
    "data": {
        "user": {
            "id": "uuid",
            "username": "john_doe",
            "email": "john@example.com",
            "full_name": "John Doe",
            "status": "pending_approval",
            "requires_email_verification": true,
            "requires_admin_approval": true
        },
        "verification_status": {
            "is_verified": false,
            "verification_sent": true,
            "expires_at": "2024-01-02T10:00:00Z"
        }
    }
}
```

### Login Response (Verification Required)
```json
{
    "success": false,
    "message": "Email verification required before login.",
    "error_code": "EMAIL_NOT_VERIFIED",
    "data": {
        "verification_status": {
            "is_verified": false,
            "can_resend": true,
            "next_resend_at": null
        }
    }
}
```

### Login Response (Approval Required)
```json
{
    "success": false,
    "message": "Account pending admin approval.",
    "error_code": "APPROVAL_REQUIRED",
    "data": {
        "approval_status": {
            "requires_approval": true,
            "is_approved": false,
            "is_rejected": false,
            "submitted_at": "2024-01-01T10:00:00Z"
        }
    }
}
```

## Configuration

### Environment Variables
```env
# Email verification settings
EMAIL_VERIFICATION_REQUIRED=true
EMAIL_VERIFICATION_EXPIRES_HOURS=24
EMAIL_VERIFICATION_MAX_ATTEMPTS=5

# Admin approval settings
ADMIN_APPROVAL_REQUIRED=true
AUTO_APPROVE_ADMIN_CREATED=false

# Security settings
MAX_LOGIN_ATTEMPTS=5
ACCOUNT_LOCK_DURATION_MINUTES=15
```

### Mail Configuration
Ensure proper mail configuration in `config/mail.php`:
```php
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'noreply@arrahnu-auction.com'),
    'name' => env('MAIL_FROM_NAME', 'ArRahnu Auction'),
],
```

## Testing

### Mobile API Verification
Run the comprehensive API test suite:
```bash
php tests/mobile_api_verification.php
```

### Manual Testing Checklist
- [ ] User registration with email verification
- [ ] Email verification link functionality
- [ ] Admin approval workflow
- [ ] Login with verification checks
- [ ] Account locking after failed attempts
- [ ] API vs web login tracking
- [ ] Email template rendering
- [ ] Admin dashboard functionality

## Troubleshooting

### Common Issues

#### 1. Email Verification Not Sent
- Check mail configuration
- Verify mail queue is running
- Check spam folder
- Review email logs

#### 2. Verification Link Expired
- Check token expiration settings
- Resend verification email
- Verify system time is correct

#### 3. Admin Approval Not Working
- Verify admin user permissions
- Check approval service configuration
- Review audit logs

#### 4. Account Locked
- Check failed login attempts
- Verify lock duration settings
- Reset account lock if needed

## Future Enhancements

### Planned Features
1. **SMS Verification**: Additional verification via SMS
2. **Social Login**: OAuth integration with Google/Facebook
3. **Two-Factor Authentication**: TOTP-based 2FA
4. **Advanced Analytics**: Detailed user behavior tracking
5. **Automated Approval**: Rule-based automatic approval
6. **Bulk User Management**: Advanced bulk operations

### Performance Optimizations
1. **Queue Processing**: Move email sending to queues
2. **Caching**: Cache verification status
3. **Database Indexing**: Optimize query performance
4. **Rate Limiting**: Implement advanced rate limiting

## Conclusion

The User Verification and Approval System provides a robust foundation for secure user management in the ArRahnu Auction Online platform. It ensures that only verified and approved users can access the system while maintaining detailed audit trails and security measures.

The system is designed to be scalable, maintainable, and secure, with comprehensive error handling and user-friendly interfaces for both end users and administrators. 