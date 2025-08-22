# ArRahnu Auction Online - Project Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Technology Stack](#technology-stack)
4. [Database Design](#database-design)
5. [Core Models](#core-models)
6. [API Documentation](#api-documentation)
7. [Web Routes](#web-routes)
8. [Authentication & Authorization](#authentication--authorization)
9. [Business Logic](#business-logic)
10. [Admin System](#admin-system)
11. [Monitoring & Analytics](#monitoring--analytics)
12. [Security Features](#security-features)
13. [Testing](#testing)
14. [Deployment & Maintenance](#deployment--maintenance)
15. [Development Guidelines](#development-guidelines)

## Project Overview

**ArRahnu Auction Online** is a comprehensive Laravel-based auction management system designed for Islamic pawnbroking (ArRahnu) operations. The system facilitates the auctioning of collateral items (typically gold and jewelry) that have been pledged as security for loans.

### Key Features
- **Multi-role User Management**: Maker, Checker, and Bidder roles with approval workflows
- **Auction Management**: Complete auction lifecycle from creation to completion
- **Collateral Management**: Item tracking with images, valuations, and status management
- **Bidding System**: Real-time bidding with validation and tracking
- **Branch & Account Management**: Multi-branch operations with account segregation
- **Admin Dashboard**: Comprehensive monitoring and analytics
- **API-First Design**: RESTful API for mobile and third-party integrations
- **Audit Trail**: Complete activity logging and compliance tracking

### Business Domain
The system operates in the Islamic finance sector, specifically ArRahnu (Islamic pawnbroking), where:
- Customers pledge gold/jewelry as collateral for loans
- If loans are not repaid, collateral items are auctioned
- Proceeds are used to settle outstanding loans
- Remaining funds are returned to customers

## System Architecture

### High-Level Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Web Frontend  │    │  Mobile App     │    │  Third-party    │
│   (Blade Views) │    │  (Flutter)      │    │  Integrations   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         └───────────────────────┼───────────────────────┘
                                 │
                    ┌─────────────────┐
                    │   Laravel API   │
                    │   (REST/JSON)   │
                    └─────────────────┘
                                 │
                    ┌─────────────────┐
                    │   Database      │
                    │   (PostgreSQL)   │
                    └─────────────────┘
```

### Application Layers
1. **Presentation Layer**: Blade templates, API responses
2. **Controller Layer**: HTTP controllers, API controllers
3. **Service Layer**: Business logic, external integrations
4. **Model Layer**: Eloquent models, relationships
5. **Data Layer**: Database, migrations, seeders

## Technology Stack

### Backend
- **Framework**: Laravel 12.x (PHP 8.2+)
- **Database**: PostgreSQL with UUID primary keys
- **Authentication**: Laravel Sanctum for API tokens
- **Caching**: Redis (configurable)
- **Queue System**: Laravel Queue with database driver
- **File Storage**: Laravel Storage with configurable drivers

### Frontend
- **Templates**: Blade templating engine
- **Styling**: Tailwind CSS with custom color system
- **JavaScript**: Alpine.js for interactive components
- **Build Tool**: Vite for asset compilation

### Development Tools
- **Testing**: PHPUnit for unit and feature tests
- **Code Quality**: Laravel Pint for code formatting
- **Development Server**: Laravel Sail (Docker-based)
- **Logging**: Laravel's built-in logging with Pail viewer

## Database Design

### Core Tables

#### Users Table
```sql
users (
    id (UUID, Primary Key),
    username (Unique),
    email (Unique),
    full_name,
    phone_number,
    role (maker|checker|bidder),
    status (draft|pending_approval|active|inactive|rejected),
    is_admin (Boolean),
    is_staff (Boolean),
    primary_address_id (FK to addresses),
    email_verification_required (Boolean),
    requires_admin_approval (Boolean),
    created_by_user_id (FK to users),
    approved_by_user_id (FK to users),
    created_at, updated_at
)
```

#### Branches Table
```sql
branches (
    id (UUID, Primary Key),
    name,
    branch_address_id (FK to branch_addresses),
    phone_number,
    status (draft|pending_approval|active|inactive|rejected),
    created_by_user_id (FK to users),
    approved_by_user_id (FK to users),
    created_at, updated_at
)
```

#### Accounts Table
```sql
accounts (
    id (UUID, Primary Key),
    branch_id (FK to branches),
    account_title,
    description,
    status (draft|pending_approval|active|inactive|rejected),
    created_by_user_id (FK to users),
    approved_by_user_id (FK to users),
    created_at, updated_at
)
```

#### Auctions Table
```sql
auctions (
    id (UUID, Primary Key),
    auction_title,
    description,
    start_datetime,
    end_datetime,
    status (draft|pending_approval|scheduled|active|completed|cancelled|rejected),
    created_by_user_id (FK to users),
    approved_by_user_id (FK to users),
    created_at, updated_at
)
```

#### Collaterals Table
```sql
collaterals (
    id (UUID, Primary Key),
    account_id (FK to accounts),
    auction_id (FK to auctions),
    item_type,
    description,
    weight_grams,
    purity,
    estimated_value_rm,
    starting_bid_rm,
    current_highest_bid_rm,
    highest_bidder_user_id (FK to users),
    status (draft|pending_approval|active|inactive|rejected),
    created_by_user_id (FK to users),
    approved_by_user_id (FK to users),
    created_at, updated_at
)
```

#### Bids Table
```sql
bids (
    id (UUID, Primary Key),
    collateral_id (FK to collaterals),
    user_id (FK to users),
    bid_amount_rm,
    bid_time,
    status (active|outbid|winning|cancelled|successful|unsuccessful),
    ip_address,
    created_at, updated_at
)
```

#### Addresses Table
```sql
addresses (
    id (UUID, Primary Key),
    user_id (FK to users),
    address_line_1,
    address_line_2,
    city,
    state,
    postcode,
    country (default: Malaysia),
    is_primary (Boolean),
    created_at, updated_at
)
```

### Key Relationships
- **User** → **Addresses** (One-to-Many)
- **Branch** → **Accounts** (One-to-Many)
- **Account** → **Collaterals** (One-to-Many)
- **Auction** → **Collaterals** (One-to-Many)
- **Collateral** → **Bids** (One-to-Many)
- **User** → **Bids** (One-to-Many)

## Core Models

### User Model
The central user model with role-based access control and approval workflows.

**Key Features:**
- UUID-based identification
- Role-based permissions (Maker, Checker, Bidder)
- Email verification system
- Admin approval workflow
- Login tracking and security features
- Profile completion tracking

**Methods:**
```php
// Role checking
$user->isAdmin()
$user->isMaker()
$user->isChecker()
$user->isBidder()

// Approval workflow
$user->canApprove($model)
$user->canMake()
$user->requiresAdminApproval()
$user->isApprovedByAdmin()

// Security
$user->canLogin()
$user->isAccountLocked()
$user->updateLoginTracking($source, $ip, $userAgent)
```

### Auction Model
Manages auction lifecycle from scheduling to completion.

**Key Features:**
- Status management (draft, scheduled, active, completed, cancelled)
- Automatic start/end based on datetime
- Collateral management within auctions
- Result processing and winner determination

**Methods:**
```php
// Status checking
$auction->isActive()
$auction->isScheduled()
$auction->hasEnded()

// Lifecycle management
$auction->start()
$auction->complete()
$auction->cancel()

// Time calculations
$auction->time_until_start
$auction->time_remaining
```

### Collateral Model
Represents items pledged as loan security.

**Key Features:**
- Item details (type, weight, purity, estimated value)
- Bidding status and current highest bid
- Image management
- Approval workflow

**Methods:**
```php
// Status management
$collateral->isActive()
$collateral->isPendingApproval()
$collateral->approve($approver)
$collateral->reject($approver)

// Bidding
$collateral->bids()
$collateral->highestBidder()
```

### Bid Model
Tracks individual bids on collateral items.

**Key Features:**
- Bid amount and timing
- Status tracking (active, outbid, winning, etc.)
- IP address logging for security
- Relationship to collateral and bidder

**Methods:**
```php
// Status management
$bid->isActive()
$bid->isWinning()
$bid->markAsOutbid()
$bid->markAsWinning()
```

## API Documentation

### Authentication Endpoints

#### POST /api/auth/register
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
    "message": "Registration successful. Please check your email to verify your account...",
    "data": {
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "role": "bidder",
            "status": "pending_approval"
        },
        "verification_email_sent": true
    }
}
```

#### POST /api/auth/login
Authenticate user and receive access token.

**Request Body:**
```json
{
    "username": "johndoe",
    "password": "password123"
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
            "username": "johndoe",
            "email": "john@example.com",
            "role": "bidder"
        },
        "token": "sanctum_token_here",
        "token_type": "Bearer"
    }
}
```

### Protected Endpoints

#### GET /api/user/profile
Get authenticated user's profile with statistics.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": "uuid",
            "full_name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "role": "bidder",
            "profile_completion": 85
        },
        "statistics": {
            "total_bids": 15,
            "active_bids": 3,
            "winning_bids": 2,
            "total_bid_amount": 2500.00
        }
    }
}
```

#### POST /api/bids
Place a new bid on collateral.

**Headers:**
```
Authorization: Bearer {token}
```

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
    "message": "Bid placed successfully",
    "data": {
        "bid": {
            "id": "uuid",
            "amount": 1500.00,
            "status": "active",
            "bid_time": "2024-01-15T10:30:00Z"
        }
    }
}
```

### Admin Endpoints

#### GET /api/admin/dashboard/overview
Get admin dashboard overview (admin only).

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "total_revenue": 50000.00,
        "active_auctions": 5,
        "total_users": 150,
        "commission_earned": 5000.00,
        "active_bidders": 120,
        "new_registrations": 25,
        "pending_verifications": 8
    }
}
```

### Public Endpoints

#### GET /api/health
API health check.

**Response:**
```json
{
    "success": true,
    "message": "API is running",
    "timestamp": "2024-01-15T10:30:00Z",
    "version": "1.0.0"
}
```

#### GET /api/auctions/active
Get list of active auctions.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": "uuid",
            "auction_title": "Gold Jewelry Auction",
            "start_datetime": "2024-01-15T09:00:00Z",
            "end_datetime": "2024-01-15T17:00:00Z",
            "collaterals_count": 25,
            "total_estimated_value": 75000.00
        }
    ]
}
```

## Web Routes

### Public Routes
- `/` - Homepage
- `/how-it-works` - How the system works
- `/about` - About page
- `/auctions` - Public auction listings
- `/auctions/{collateral}` - Auction details

### Authentication Routes
- `/login` - User login
- `/register` - User registration
- `/forgot-password` - Password reset request
- `/reset-password/{token}` - Password reset form
- `/verify-email/{token}` - Email verification

### User Routes (Authenticated)
- `/dashboard` - User dashboard
- `/profile/*` - Profile management
- `/addresses/*` - Address management

### Admin Routes (Admin Only)
- `/admin/dashboard` - Admin dashboard
- `/admin/users/*` - User management
- `/admin/branches/*` - Branch management
- `/admin/accounts/*` - Account management
- `/admin/collaterals/*` - Collateral management
- `/admin/auctions/*` - Auction management
- `/admin/addresses/*` - Address management
- `/admin/settings/*` - System settings

## Authentication & Authorization

### Authentication System
- **Laravel Sanctum**: API token authentication
- **Session-based**: Web interface authentication
- **Multi-factor**: Email verification required
- **Security features**: Account locking, login attempt tracking

### Authorization Model
- **Role-based Access Control (RBAC)**:
  - **Maker**: Create/edit items, submit for approval
  - **Checker**: Approve/reject items created by others
  - **Bidder**: Place bids, view auctions
  - **Admin**: Full system access

### Approval Workflow
1. **Creation**: Maker creates item (draft status)
2. **Submission**: Maker submits for approval
3. **Review**: Checker reviews and approves/rejects
4. **Activation**: Approved items become active

## Business Logic

### Auction Lifecycle
1. **Draft**: Auction created but not scheduled
2. **Scheduled**: Auction scheduled with start/end times
3. **Active**: Auction running, accepting bids
4. **Completed**: Auction ended, winners determined
5. **Cancelled**: Auction cancelled (if needed)

### Bidding Process
1. **Bid Placement**: User places bid on collateral
2. **Validation**: System validates bid amount and user eligibility
3. **Status Update**: Previous highest bid marked as "outbid"
4. **Real-time Updates**: Current highest bid updated
5. **Winner Determination**: Highest bidder at auction end wins

### Collateral Management
1. **Item Registration**: Collateral item registered with details
2. **Valuation**: Estimated value and starting bid set
3. **Auction Assignment**: Item assigned to specific auction
4. **Status Tracking**: Item status tracked through lifecycle
5. **Result Processing**: Winner and final price recorded

## Admin System

### Dashboard Features
- **Overview Statistics**: Revenue, users, auctions, commissions
- **Recent Activities**: Audit trail of system activities
- **Active Auctions**: Current running auctions
- **Pending Approvals**: Items awaiting admin approval

### User Management
- **User Registration**: Approve/reject new user registrations
- **Role Management**: Assign and modify user roles
- **Status Control**: Activate/deactivate user accounts
- **Email Verification**: Manage email verification status

### System Monitoring
- **API Health**: Monitor API endpoint status
- **Performance Metrics**: System performance tracking
- **Error Logs**: Error tracking and debugging
- **Resource Usage**: System resource monitoring

## Monitoring & Analytics

### API Monitoring
The system includes comprehensive API monitoring through `ApiMonitoringService`:

- **Endpoint Health**: Real-time status of all API endpoints
- **Performance Metrics**: Response times and throughput
- **Error Tracking**: Error rates and patterns
- **System Resources**: Database, cache, queue, storage status

### Dashboard Analytics
- **User Analytics**: Registration trends, activity patterns
- **Auction Analytics**: Success rates, revenue trends
- **System Metrics**: Performance and resource utilization
- **Activity Feed**: Real-time system activity

### Audit Trail
All system activities are logged through the `HasAuditTrail` trait:
- **User Actions**: Login, profile updates, bidding
- **Admin Actions**: Approvals, rejections, system changes
- **Business Events**: Auction starts, bid placements, completions

## Security Features

### Data Protection
- **UUID Primary Keys**: Prevents enumeration attacks
- **Input Validation**: Comprehensive request validation
- **SQL Injection Protection**: Eloquent ORM with parameter binding
- **XSS Protection**: Blade template escaping

### Authentication Security
- **Account Locking**: Automatic lockout after failed attempts
- **IP Tracking**: Login attempt IP address logging
- **Device Tracking**: Device identifier management
- **Session Security**: Secure session handling

### Authorization Security
- **Role-based Access**: Strict role-based permissions
- **Approval Workflows**: Multi-level approval processes
- **Audit Logging**: Complete activity tracking
- **Admin Controls**: Restricted admin access

## Testing

### Test Structure
```
tests/
├── Feature/          # Feature tests
│   ├── Auth/        # Authentication tests
│   └── DatabaseSchemaTest.php
├── Unit/            # Unit tests
│   ├── Api/         # API controller tests
│   ├── Auth/        # Authentication tests
│   └── Services/    # Service layer tests
└── TestCase.php     # Base test case
```

### Test Coverage
- **API Controllers**: All API endpoints tested
- **Authentication**: Login, registration, verification
- **Business Logic**: Bidding, auction management
- **Database Schema**: Migration and relationship tests

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## Deployment & Maintenance

### Environment Setup
1. **Requirements**: PHP 8.2+, PostgreSQL, Redis (optional)
2. **Configuration**: Environment variables in `.env`
3. **Database**: Run migrations and seeders
4. **Storage**: Configure file storage drivers
5. **Queue**: Set up queue workers

### Cron Jobs
The system includes automated tasks:

```bash
# Update auction statuses
* * * * * php artisan auctions:update-statuses

# Clear expired sessions
0 0 * * * php artisan session:gc
```

### Monitoring Commands
```bash
# Check system health
php artisan system:health

# Monitor API status
php artisan api:monitor

# View audit logs
php artisan audit:logs
```

## Development Guidelines

### Code Standards
- **PSR-12**: PHP coding standards
- **Laravel Conventions**: Follow Laravel best practices
- **Type Hints**: Use strict typing where possible
- **Documentation**: PHPDoc for all public methods

### Database Conventions
- **UUID Primary Keys**: Use UUIDs for all tables
- **Timestamps**: Include created_at and updated_at
- **Foreign Keys**: Proper foreign key constraints
- **Indexes**: Performance indexes on frequently queried columns

### API Design Principles
- **RESTful**: Follow REST conventions
- **Consistent Responses**: Standardized response format
- **Error Handling**: Proper HTTP status codes
- **Validation**: Comprehensive input validation

### Security Best Practices
- **Authentication**: Always verify user identity
- **Authorization**: Check permissions before actions
- **Input Validation**: Validate all user inputs
- **Audit Logging**: Log all important actions

## Conclusion

The ArRahnu Auction Online system is a robust, feature-rich auction management platform built with modern Laravel practices. It provides comprehensive functionality for Islamic pawnbroking operations while maintaining security, scalability, and maintainability.

The system's architecture supports both web and mobile interfaces, with a strong API foundation that enables future integrations and expansions. The comprehensive monitoring and audit capabilities ensure compliance and operational transparency.

For developers working with this system, the documentation provides a solid foundation for understanding the codebase, implementing new features, and maintaining existing functionality.

---

## Quick Reference

### Key Commands
```bash
# Development
php artisan serve
php artisan migrate
php artisan db:seed
php artisan test

# Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:work

# Monitoring
php artisan api:monitor
php artisan system:health
```

### Important Files
- **Routes**: `routes/web.php`, `routes/api.php`
- **Controllers**: `app/Http/Controllers/`
- **Models**: `app/Models/`
- **Services**: `app/Services/`
- **Migrations**: `database/migrations/`
- **Tests**: `tests/`

### Environment Variables
```env
APP_NAME="ArRahnu Auction Online"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=arrahnu_auction
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=database
SESSION_DRIVER=file
```
