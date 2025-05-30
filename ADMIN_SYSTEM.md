# Admin System Documentation

## Overview
The Arrahnu Auction platform now includes a comprehensive admin system with role-based access control using the `is_admin` field.

## Database Schema

### Users Table
- `is_admin` (boolean, default: false) - Determines if user has admin privileges

## User Model Methods

### Admin Check Methods
```php
// Check if user is admin
$user->isAdmin(); // returns boolean

// Check if user is regular user
$user->isUser(); // returns boolean
```

## Authentication & Authorization

### Middleware
- `AdminMiddleware` - Protects admin routes
- Redirects non-admin users to dashboard with error message
- Registered as 'admin' alias in bootstrap/app.php

### Protected Routes
```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', ...)->name('admin.dashboard');
    Route::get('/admin/users', ...)->name('admin.users');
});
```

## Admin Users

### Default Admin Account
- **Email**: admin@arrahnu.com
- **Password**: admin123
- **Role**: Admin

### Default Regular User Account
- **Email**: user@arrahnu.com
- **Password**: user123
- **Role**: User

### Creating Admin Users
```bash
# Run the seeder to create default accounts
php artisan db:seed --class=AdminUserSeeder

# Or manually in database/tinker
php artisan tinker
>>> $user = User::find(1);
>>> $user->is_admin = true;
>>> $user->save();
```

## Visual Indicators

### Admin Badges
- **Dashboard**: Orange badge with "Admin" text
- **Admin Panel**: Solid brand color badge
- **Navigation**: Conditional display based on admin status

### Admin Panel Access
- Only visible to admin users
- Appears in navigation when logged in as admin
- Direct link to admin dashboard

## Admin Features

### Admin Dashboard
- **URL**: `/admin/dashboard`
- **Features**:
  - Revenue analytics ($2.4M total)
  - Active auctions monitoring (127 live)
  - User management (15,847 total users)
  - Commission tracking ($240K earned)
  - Quick action buttons
  - Real-time activity feed
  - System status monitoring

### User Management
- **URL**: `/admin/users`
- **Features**:
  - Complete user listing with roles
  - Admin/User role indicators
  - Email verification status
  - User statistics dashboard
  - Registration date tracking
  - **Edit User Details**: Name, email, admin status
  - **Toggle Admin Privileges**: Quick admin role switching
  - **Delete Users**: Remove user accounts (with protection)
  - **Action Buttons**: Edit, Make/Remove Admin, Delete

## Security Features

### Access Control
- Middleware protection on all admin routes
- Automatic redirection for unauthorized access
- Session-based error messages
- Role-based navigation display

### Admin Identification
- Clear visual indicators throughout the platform
- Separate admin navigation
- Role-specific dashboard content
- Admin badge in all authenticated views

## Usage Examples

### Checking Admin Status in Views
```blade
@if(Auth::user()->isAdmin())
    <span class="admin-badge">Admin</span>
    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
@endif
```

### Protecting Routes
```php
// In routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin-only routes here
});
```

### Creating Admin Users Programmatically
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'New Admin',
    'email' => 'newadmin@example.com',
    'password' => Hash::make('password'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);
```

## Admin Dashboard Features

### Quick Actions
1. **Create Auction** - Direct auction creation
2. **Manage Users** - User management interface
3. **View Reports** - Analytics dashboard
4. **Manage Categories** - Category administration
5. **System Settings** - Platform configuration
6. **Send Notifications** - Bulk messaging

### User Edit/Update Actions
1. **Edit User Modal** - Comprehensive user editing interface
2. **Toggle Admin Status** - Quick role switching with confirmation
3. **Delete User** - Remove users with safety checks
4. **Bulk Operations** - Multiple user management (planned)

#### Edit User Features:
- **Name Update**: Change user display name
- **Email Update**: Modify user email (with uniqueness validation)
- **Admin Toggle**: Grant or revoke admin privileges
- **Safety Checks**: Prevent self-privilege removal
- **Validation**: Server-side form validation
- **Confirmation**: JavaScript confirmations for destructive actions

### Analytics
- Real-time revenue tracking
- User activity monitoring
- Auction performance metrics
- System health indicators

### Management Tools
- User role management
- Auction oversight
- Activity monitoring
- System administration

## Testing the Admin System

### Login as Admin
1. Go to `/login`
2. Email: `admin@arrahnu.com`
3. Password: `admin123`
4. Should see admin badge and admin panel link

### Login as Regular User
1. Go to `/login`
2. Email: `user@arrahnu.com`
3. Password: `user123`
4. Should NOT see admin badge or admin panel link

### Test Admin Protection
1. Login as regular user
2. Try to access `/admin/dashboard`
3. Should be redirected to dashboard with error message

## File Structure

### Models
- `app/Models/User.php` - User model with admin methods

### Middleware
- `app/Http/Middleware/AdminMiddleware.php` - Admin protection

### Views
- `resources/views/admin/dashboard.blade.php` - Admin dashboard
- `resources/views/admin/users.blade.php` - User management

### Migrations
- `database/migrations/*_add_is_admin_to_users_table.php` - Admin field

### Seeders
- `database/seeders/AdminUserSeeder.php` - Default admin accounts

## Future Enhancements

### Planned Features
- Role-based permissions (beyond admin/user)
- Admin activity logging
- User role modification interface
- Bulk user operations
- Advanced admin analytics

### Security Improvements
- Two-factor authentication for admins
- Admin session timeout
- IP-based admin access restrictions
- Admin action audit trail

## Troubleshooting

### Common Issues
1. **Admin routes not working**: Check middleware registration in bootstrap/app.php
2. **Admin badge not showing**: Verify user has is_admin = true in database
3. **Access denied errors**: Ensure user is logged in and has admin privileges

### Database Verification
```sql
-- Check admin users
SELECT name, email, is_admin FROM users WHERE is_admin = 1;

-- Make user admin
UPDATE users SET is_admin = 1 WHERE email = 'user@example.com';
```

This admin system provides a solid foundation for managing the Arrahnu Auction platform with proper role-based access control and comprehensive administrative tools.
