# Arrahnu Auction - Routes Documentation

## Overview
This document provides a clear overview of all web routes organized by functionality and access level.

## Route Structure

### 🌐 PUBLIC ROUTES (No Authentication Required)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/` | `home` | Homepage |
| GET | `/how-it-works` | `how-it-works` | How it works page |
| GET | `/about` | `about` | About page |
| GET | `/auctions` | `auctions.index` | Public auctions listing |
| GET | `/test-password-reset` | `test.password.reset` | Password reset test page |
| GET | `/color-test` | `color-test` | Color theme test page |

### 🔐 AUTHENTICATION ROUTES (Guest Only)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/login` | `login` | Show login form |
| POST | `/login` | - | Process login |
| GET | `/register` | `register` | Show registration form |
| POST | `/register` | - | Process registration |
| GET | `/forgot-password` | `password.request` | Show forgot password form |
| POST | `/forgot-password` | `password.email` | Send reset link |
| GET | `/reset-password/{token}` | `password.reset` | Show reset form |
| POST | `/reset-password` | `password.update` | Process password reset |
| POST | `/logout` | `logout` | Logout user |

### 👤 USER ROUTES (Authenticated Users)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/dashboard` | `dashboard` | User dashboard |
| GET | `/profile` | `profile.edit` | Show profile edit form |
| PUT | `/profile` | `profile.update` | Update profile |
| DELETE | `/profile` | `profile.destroy` | Delete account |

### 🛡️ ADMIN ROUTES (Admin Users Only)

All admin routes are prefixed with `/admin` and require admin authentication.

#### Admin Dashboard & Profile
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/dashboard` | `admin.dashboard` | Admin dashboard |
| GET | `/admin/profile` | `admin.profile` | Admin profile settings |

#### User Management (`/admin/users`)
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/users` | `admin.users.index` | List all users |
| PUT | `/admin/users/{user}` | `admin.users.update` | Update user |
| POST | `/admin/users/{user}/toggle-admin` | `admin.users.toggle-admin` | Toggle admin status |
| DELETE | `/admin/users/{user}` | `admin.users.delete` | Delete user |

#### Branch Management (`/admin/branches`)
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/branches` | `admin.branches.index` | List all branches |
| POST | `/admin/branches` | `admin.branches.store` | Create new branch |
| PUT | `/admin/branches/{branch}` | `admin.branches.update` | Update branch |
| POST | `/admin/branches/{branch}/toggle-status` | `admin.branches.toggle-status` | Toggle branch status |
| DELETE | `/admin/branches/{branch}` | `admin.branches.delete` | Delete branch |

#### Account Management (`/admin/accounts`)
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/accounts` | `admin.accounts.index` | List all accounts |
| GET | `/admin/accounts/{account}/collaterals` | `admin.accounts.collaterals` | View account collaterals |
| POST | `/admin/accounts/{account}/toggle-status` | `admin.accounts.toggle-status` | Toggle account status |
| DELETE | `/admin/accounts/{account}` | `admin.accounts.delete` | Delete account |

#### Collateral Management (`/admin/collaterals`)
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/collaterals` | `admin.collaterals.index` | List all collaterals |
| POST | `/admin/collaterals/{collateral}/toggle-status` | `admin.collaterals.toggle-status` | Toggle collateral status |
| DELETE | `/admin/collaterals/{collateral}` | `admin.collaterals.delete` | Delete collateral |

## Controllers Used

### Authentication Controllers
- `App\Http\Controllers\Auth\LoginController`
- `App\Http\Controllers\Auth\RegisterController`
- `App\Http\Controllers\Auth\ForgotPasswordController`
- `App\Http\Controllers\Auth\ResetPasswordController`

### Note on Route Handlers
Most routes currently use closure functions directly in the routes file. For better organization, consider moving complex logic to dedicated controllers:

**Recommended Controllers to Create:**
- `App\Http\Controllers\Admin\DashboardController`
- `App\Http\Controllers\Admin\UserController`
- `App\Http\Controllers\Admin\BranchController`
- `App\Http\Controllers\Admin\AccountController`
- `App\Http\Controllers\Admin\CollateralController`
- `App\Http\Controllers\UserController`
- `App\Http\Controllers\ProfileController`

## Middleware Groups

### `auth`
- Requires user to be logged in
- Applied to user and admin routes

### `admin`
- Requires user to be logged in AND have admin privileges
- Applied to all admin routes

### `guest`
- Requires user to NOT be logged in
- Applied to authentication routes (login, register, etc.)

## Route Naming Convention

- **Public routes**: Simple names (`home`, `about`)
- **Auth routes**: Prefixed with `password.` for password-related routes
- **User routes**: Prefixed with `profile.` for profile-related routes
- **Admin routes**: Prefixed with `admin.{module}.` (e.g., `admin.users.index`)

## Security Features

1. **CSRF Protection**: All POST/PUT/DELETE routes are protected by CSRF middleware
2. **Admin Middleware**: Prevents non-admin users from accessing admin routes
3. **Self-Protection**: Admins cannot delete their own accounts or remove their own admin privileges
4. **Guest Middleware**: Prevents logged-in users from accessing auth forms

## Testing Routes

Use these Artisan commands to test routes:

```bash
# List all routes
php artisan route:list

# List admin routes only
php artisan route:list --name=admin

# List user profile routes
php artisan route:list --name=profile

# Test route resolution
php artisan route:list --path=admin
```
