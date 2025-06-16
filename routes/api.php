<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ============================================================================
// PUBLIC API ROUTES (No Authentication Required)
// ============================================================================

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/2fa/verify', [AuthController::class, 'verify2FA']);
    Route::post('/2fa/resend', [AuthController::class, 'resend2FA']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Alternative 2FA verification route (as requested)
Route::post('/verify/2fa', [AuthController::class, 'verify2FA']);

// ============================================================================
// PROTECTED API ROUTES (Authentication Required)
// ============================================================================

Route::middleware('auth:sanctum')->group(function () {
    
    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
    });

    // User Routes
    Route::prefix('user')->group(function () {
        // User profile management
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'updatePassword']);
        Route::post('/avatar', [AuthController::class, 'updateAvatar']);
        Route::put('/preferences', [AuthController::class, 'updatePreferences']);
        Route::delete('/account', [AuthController::class, 'deleteAccount']);
    });

    // User Management Routes (Enhanced)
    Route::prefix('user')->group(function () {
        // Profile management
        Route::get('/profile', [App\Http\Controllers\Api\UserController::class, 'profile']);
        Route::put('/profile', [App\Http\Controllers\Api\UserController::class, 'updateProfile']);
        Route::put('/password', [App\Http\Controllers\Api\UserController::class, 'updatePassword']);
        Route::post('/avatar', [App\Http\Controllers\Api\UserController::class, 'updateAvatar']);
        Route::delete('/avatar', [App\Http\Controllers\Api\UserController::class, 'removeAvatar']);
        Route::put('/preferences', [App\Http\Controllers\Api\UserController::class, 'updatePreferences']);
        Route::delete('/account', [App\Http\Controllers\Api\UserController::class, 'deleteAccount']);
        
        // Activity and statistics
        Route::get('/bidding-activity', [App\Http\Controllers\Api\UserController::class, 'biddingActivity']);
        Route::get('/watchlist', [App\Http\Controllers\Api\UserController::class, 'watchlist']);
    });

    // Bidding Management Routes
    Route::prefix('bids')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\BidController::class, 'index']);
        Route::post('/', [App\Http\Controllers\Api\BidController::class, 'store']);
        Route::get('/active', [App\Http\Controllers\Api\BidController::class, 'activeBids']);
        Route::get('/statistics', [App\Http\Controllers\Api\BidController::class, 'statistics']);
        Route::post('/{bid}/cancel', [App\Http\Controllers\Api\BidController::class, 'cancel']);
    });

    // Auction and Collateral Routes for Bidding
    Route::prefix('auctions')->group(function () {
        Route::get('/active', [App\Http\Controllers\Api\BidController::class, 'activeAuctions']);
        Route::get('/collaterals/{collateral}', [App\Http\Controllers\Api\BidController::class, 'collateralDetails']);
        Route::get('/collaterals/{collateral}/live-updates', [App\Http\Controllers\Api\BidController::class, 'liveUpdates']);
    });

    // Address Management Routes
    Route::prefix('addresses')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\AddressController::class, 'index']);
        Route::post('/', [App\Http\Controllers\Api\AddressController::class, 'store']);
        Route::get('/statistics', [App\Http\Controllers\Api\AddressController::class, 'getStatistics']);
        Route::get('/export', [App\Http\Controllers\Api\AddressController::class, 'export']);
        Route::get('/suggestions', [App\Http\Controllers\Api\AddressController::class, 'getSuggestions']);
        Route::get('/{address}', [App\Http\Controllers\Api\AddressController::class, 'show']);
        Route::put('/{address}', [App\Http\Controllers\Api\AddressController::class, 'update']);
        Route::delete('/{address}', [App\Http\Controllers\Api\AddressController::class, 'destroy']);
        Route::post('/{address}/set-primary', [App\Http\Controllers\Api\AddressController::class, 'setPrimary']);
        
        // Utility routes
        Route::get('/states/list', [App\Http\Controllers\Api\AddressController::class, 'getStates']);
        Route::get('/validation/rules', [App\Http\Controllers\Api\AddressController::class, 'getValidationRules']);
        Route::post('/validate/postcode', [App\Http\Controllers\Api\AddressController::class, 'validatePostcode']);
    });

    // Admin Routes (Admin Users Only)
    Route::middleware('admin')->prefix('admin')->group(function () {

        // Dashboard Monitoring
        Route::get('/dashboard/overview', [DashboardController::class, 'overview']);
        Route::get('/dashboard/user-analytics', [DashboardController::class, 'userAnalytics']);
        Route::get('/dashboard/auction-analytics', [DashboardController::class, 'auctionAnalytics']);
        Route::get('/dashboard/system-metrics', [DashboardController::class, 'systemMetrics']);
        Route::get('/dashboard/activity-feed', [DashboardController::class, 'activityFeed']);
        Route::get('/dashboard/alerts', [DashboardController::class, 'alerts']);

        // System Monitoring
        Route::get('/system/status', [AdminController::class, 'systemStatus']);
        Route::get('/system/performance', [AdminController::class, 'performanceMetrics']);
        Route::get('/system/activities', [AdminController::class, 'recentActivities']);

        // Error Logs and Debugging
        Route::get('/logs/errors', [AdminController::class, 'errorLogs']);

        // System Maintenance
        Route::post('/system/clear-caches', [AdminController::class, 'clearCaches']);

        // Address Management (Admin)
        Route::prefix('addresses')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\AdminAddressController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Api\AdminAddressController::class, 'store']);
            Route::get('/statistics', [App\Http\Controllers\Api\AdminAddressController::class, 'getStatistics']);
            Route::get('/export', [App\Http\Controllers\Api\AdminAddressController::class, 'export']);
            Route::get('/filter-options', [App\Http\Controllers\Api\AdminAddressController::class, 'getFilterOptions']);
            Route::post('/bulk-action', [App\Http\Controllers\Api\AdminAddressController::class, 'bulkAction']);
            Route::get('/users/{user}', [App\Http\Controllers\Api\AdminAddressController::class, 'getUserAddresses']);
            Route::get('/{address}', [App\Http\Controllers\Api\AdminAddressController::class, 'show']);
            Route::put('/{address}', [App\Http\Controllers\Api\AdminAddressController::class, 'update']);
            Route::delete('/{address}', [App\Http\Controllers\Api\AdminAddressController::class, 'destroy']);
            Route::post('/{address}/set-primary', [App\Http\Controllers\Api\AdminAddressController::class, 'setPrimary']);
        });

        // System Settings (Admin)
        Route::prefix('settings')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'getSettings']);
            Route::post('/update', [App\Http\Controllers\Admin\SettingsController::class, 'updateSettings']);
            Route::post('/toggle-2fa', [App\Http\Controllers\Admin\SettingsController::class, 'toggle2FA']);
            Route::post('/reset', [App\Http\Controllers\Admin\SettingsController::class, 'resetToDefaults']);
            Route::get('/system-status', [App\Http\Controllers\Admin\SettingsController::class, 'getSystemStatus']);
        });

        // TODO: Add more admin-specific API routes here
        // Route::apiResource('/users', AdminUserController::class);
        // Route::apiResource('/branches', BranchController::class);
        // Route::apiResource('/accounts', AccountController::class);
        // Route::apiResource('/collaterals', CollateralController::class);
        // Route::apiResource('/auctions', AuctionController::class);
    });
});

// ============================================================================
// API HEALTH CHECK
// ============================================================================

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

// ============================================================================
// API DOCUMENTATION INFO
// ============================================================================

Route::get('/info', function () {
    return response()->json([
        'success' => true,
        'data' => [
            'api_name' => 'ArRahnu Auction API',
            'version' => '1.0.0',
            'description' => 'API for ArRahnu Auction Online Platform',
            'endpoints' => [
                'auth' => [
                    'POST /api/auth/register' => 'Register new user',
                    'POST /api/auth/login' => 'Login user',
                    'POST /api/auth/2fa/verify' => 'Verify 2FA code',
                    'POST /api/verify/2fa' => 'Verify 2FA code (alternative route)',
                    'POST /api/auth/2fa/resend' => 'Resend 2FA code',
                    'POST /api/auth/forgot-password' => 'Send password reset link',
                    'POST /api/auth/reset-password' => 'Reset password with token',
                    'POST /api/auth/logout' => 'Logout user (requires auth)',
                    'GET /api/auth/profile' => 'Get user profile (requires auth)',
                ],
                'user' => [
                    'GET /api/user/profile' => 'Get user profile with statistics (requires auth)',
                    'PUT /api/user/profile' => 'Update user profile (requires auth)',
                    'PUT /api/user/password' => 'Update user password (requires auth)',
                    'POST /api/user/avatar' => 'Update user avatar (requires auth)',
                    'DELETE /api/user/avatar' => 'Remove user avatar (requires auth)',
                    'PUT /api/user/preferences' => 'Update user preferences (requires auth)',
                    'DELETE /api/user/account' => 'Delete user account (requires auth)',
                    'GET /api/user/bidding-activity' => 'Get user bidding activity (requires auth)',
                    'GET /api/user/watchlist' => 'Get user watchlist (requires auth)',
                ],
                'bidding' => [
                    'GET /api/bids' => 'Get user bidding history with pagination (requires auth)',
                    'POST /api/bids' => 'Place a new bid on collateral (requires auth)',
                    'GET /api/bids/active' => 'Get user active bids (requires auth)',
                    'GET /api/bids/statistics' => 'Get user bidding statistics (requires auth)',
                    'POST /api/bids/{id}/cancel' => 'Cancel a bid (requires auth)',
                ],
                'auctions' => [
                    'GET /api/auctions/active' => 'Get active auctions for bidding (requires auth)',
                    'GET /api/auctions/collaterals/{id}' => 'Get collateral details for bidding (requires auth)',
                    'GET /api/auctions/collaterals/{id}/live-updates' => 'Get real-time bidding updates (requires auth)',
                ],
                'addresses' => [
                    'GET /api/addresses' => 'Get user addresses with filtering (requires auth)',
                    'POST /api/addresses' => 'Create new address (requires auth)',
                    'GET /api/addresses/statistics' => 'Get user address statistics (requires auth)',
                    'GET /api/addresses/export' => 'Export user addresses (requires auth)',
                    'GET /api/addresses/suggestions' => 'Get address suggestions (requires auth)',
                    'GET /api/addresses/{id}' => 'Get specific address with formatted versions (requires auth)',
                    'PUT /api/addresses/{id}' => 'Update address (requires auth)',
                    'DELETE /api/addresses/{id}' => 'Delete address (requires auth)',
                    'POST /api/addresses/{id}/set-primary' => 'Set address as primary (requires auth)',
                    'GET /api/addresses/states/list' => 'Get Malaysian states list (requires auth)',
                    'GET /api/addresses/validation/rules' => 'Get address validation rules (requires auth)',
                    'POST /api/addresses/validate/postcode' => 'Validate postcode format (requires auth)',
                ],
                'admin' => [
                    'GET /api/admin/dashboard/overview' => 'Get dashboard overview (admin only)',
                    'GET /api/admin/dashboard/user-analytics' => 'Get user analytics (admin only)',
                    'GET /api/admin/dashboard/auction-analytics' => 'Get auction analytics (admin only)',
                    'GET /api/admin/dashboard/system-metrics' => 'Get system metrics (admin only)',
                    'GET /api/admin/dashboard/activity-feed' => 'Get activity feed (admin only)',
                    'GET /api/admin/dashboard/alerts' => 'Get system alerts (admin only)',
                    'GET /api/admin/system/status' => 'Get system status (admin only)',
                    'GET /api/admin/system/performance' => 'Get performance metrics (admin only)',
                    'GET /api/admin/system/activities' => 'Get recent activities (admin only)',
                    'GET /api/admin/logs/errors' => 'Get error logs (admin only)',
                    'POST /api/admin/system/clear-caches' => 'Clear system caches (admin only)',
                    'GET /api/admin/addresses' => 'Get all addresses with advanced filtering (admin only)',
                    'POST /api/admin/addresses' => 'Create address for any user (admin only)',
                    'GET /api/admin/addresses/statistics' => 'Get global address statistics (admin only)',
                    'GET /api/admin/addresses/export' => 'Export all addresses (admin only)',
                    'GET /api/admin/addresses/filter-options' => 'Get filter options (admin only)',
                    'POST /api/admin/addresses/bulk-action' => 'Perform bulk operations (admin only)',
                    'GET /api/admin/addresses/users/{user}' => 'Get user addresses (admin only)',
                    'GET /api/admin/addresses/{id}' => 'Get address with full details (admin only)',
                    'PUT /api/admin/addresses/{id}' => 'Update address with user transfer (admin only)',
                    'DELETE /api/admin/addresses/{id}' => 'Delete address (admin only)',
                    'POST /api/admin/addresses/{id}/set-primary' => 'Set address as primary (admin only)',
                ],
                'utility' => [
                    'GET /api/health' => 'API health check',
                    'GET /api/info' => 'API information',
                ]
            ],
            'authentication' => [
                'type' => 'Bearer Token (Laravel Sanctum)',
                'header' => 'Authorization: Bearer {token}',
                '2fa' => 'Two-Factor Authentication supported'
            ]
        ]
    ]);
});
