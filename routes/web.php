<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DashboardMonitoringController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CollateralController;
use App\Http\Controllers\Admin\AuctionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============================================================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================================================

// Homepage
Route::get('/', [PublicController::class, 'home'])->name('home');

// Static Pages
Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');
Route::get('/about', [PublicController::class, 'about'])->name('about');

// Public Auctions
Route::get('/auctions', [PublicController::class, 'auctions'])->name('auctions.index');
Route::get('/auctions/{collateral}', [PublicController::class, 'auctionDetails'])->name('auctions.show');

// ============================================================================
// ADMIN ROUTES (Admin Users Only)
// ============================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard Monitoring
    Route::get('/dashboard/monitoring', [DashboardMonitoringController::class, 'index'])->name('dashboard.monitoring');
    Route::get('/dashboard/monitoring/overview', [DashboardMonitoringController::class, 'getOverview'])->name('dashboard.monitoring.overview');
    Route::get('/dashboard/monitoring/user-analytics', [DashboardMonitoringController::class, 'getUserAnalytics'])->name('dashboard.monitoring.user-analytics');
    Route::get('/dashboard/monitoring/auction-analytics', [DashboardMonitoringController::class, 'getAuctionAnalytics'])->name('dashboard.monitoring.auction-analytics');
    Route::get('/dashboard/monitoring/system-metrics', [DashboardMonitoringController::class, 'getSystemMetrics'])->name('dashboard.monitoring.system-metrics');
    Route::get('/dashboard/monitoring/activity-feed', [DashboardMonitoringController::class, 'getActivityFeed'])->name('dashboard.monitoring.activity-feed');
    Route::get('/dashboard/monitoring/alerts', [DashboardMonitoringController::class, 'getAlerts'])->name('dashboard.monitoring.alerts');

    // Admin Profile Settings
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
        Route::get('/data', [App\Http\Controllers\Admin\SettingsController::class, 'getSettings'])->name('data');
        Route::post('/update', [App\Http\Controllers\Admin\SettingsController::class, 'updateSettings'])->name('update');
    
        Route::post('/reset', [App\Http\Controllers\Admin\SettingsController::class, 'resetToDefaults'])->name('reset');
        Route::get('/system-status', [App\Http\Controllers\Admin\SettingsController::class, 'getSystemStatus'])->name('system-status');
    });

    // ========================================================================
    // USER MANAGEMENT
    // ========================================================================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::post('/{user}/approve', [AdminUserController::class, 'approve'])->name('approve');
        Route::post('/{user}/reject', [AdminUserController::class, 'reject'])->name('reject');
        Route::post('/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('toggle-admin');
        
        // Email verification management
        Route::post('/{user}/verify-email', [AdminUserController::class, 'verifyEmail'])->name('verify-email');
        Route::post('/{user}/send-verification-email', [AdminUserController::class, 'sendVerificationEmail'])->name('send-verification-email');
        Route::post('/{user}/reset-email-verification', [AdminUserController::class, 'resetEmailVerification'])->name('reset-email-verification');
        Route::get('/{user}/verification-status', [AdminUserController::class, 'getVerificationStatus'])->name('verification-status');

        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('delete');
    });

    // ========================================================================
    // BRANCH MANAGEMENT
    // ========================================================================
    Route::prefix('branches')->name('branches.')->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('index');
        Route::get('/create', [BranchController::class, 'create'])->name('create');
        Route::post('/', [BranchController::class, 'store'])->name('store');
        Route::get('/{branch}', [BranchController::class, 'show'])->name('show');
        Route::get('/{branch}/edit', [BranchController::class, 'edit'])->name('edit');
        Route::put('/{branch}', [BranchController::class, 'update'])->name('update');
        Route::delete('/{branch}', [BranchController::class, 'destroy'])->name('destroy');

        // Status management
        Route::post('/{branch}/toggle-status', [BranchController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{branch}/submit-for-approval', [BranchController::class, 'submitForApproval'])->name('submit-for-approval');

        // Approval workflow
        Route::post('/{branch}/approve', [BranchController::class, 'approve'])->name('approve');
        Route::post('/{branch}/reject', [BranchController::class, 'reject'])->name('reject');

        // Bulk operations
        Route::post('/bulk-action', [BranchController::class, 'bulkAction'])->name('bulk-action');
    });

    // ========================================================================
    // ACCOUNT MANAGEMENT
    // ========================================================================
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->name('create');
        Route::post('/', [AccountController::class, 'store'])->name('store');
        Route::get('/{account}', [AccountController::class, 'show'])->name('show');
        Route::get('/{account}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::put('/{account}', [AccountController::class, 'update'])->name('update');
        Route::delete('/{account}', [AccountController::class, 'destroy'])->name('destroy');

        // Status management
        Route::post('/{account}/toggle-status', [AccountController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{account}/submit-for-approval', [AccountController::class, 'submitForApproval'])->name('submit-for-approval');
        Route::post('/{account}/approve', [AccountController::class, 'approve'])->name('approve');
        Route::post('/{account}/reject', [AccountController::class, 'reject'])->name('reject');

        // Collaterals
        Route::get('/{account}/collaterals', [AccountController::class, 'collaterals'])->name('collaterals');
    });

    // ========================================================================
    // COLLATERAL MANAGEMENT
    // ========================================================================
    Route::prefix('collaterals')->name('collaterals.')->group(function () {
        Route::get('/', [CollateralController::class, 'index'])->name('index');
        Route::get('/create', [CollateralController::class, 'create'])->name('create');
        Route::post('/', [CollateralController::class, 'store'])->name('store');
        Route::get('/{collateral}', [CollateralController::class, 'show'])->name('show');
        Route::get('/{collateral}/edit', [CollateralController::class, 'edit'])->name('edit');
        Route::put('/{collateral}', [CollateralController::class, 'update'])->name('update');
        Route::delete('/{collateral}', [CollateralController::class, 'destroy'])->name('destroy');

        // Status management
        Route::post('/{collateral}/toggle-status', [CollateralController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{collateral}/submit-for-approval', [CollateralController::class, 'submitForApproval'])->name('submit-for-approval');

        // Approval workflow
        Route::post('/{collateral}/approve', [CollateralController::class, 'approve'])->name('approve');
        Route::post('/{collateral}/reject', [CollateralController::class, 'reject'])->name('reject');

        // Auction management
        Route::post('/{collateral}/start-auction', [CollateralController::class, 'startAuction'])->name('start-auction');
        Route::post('/{collateral}/end-auction', [CollateralController::class, 'endAuction'])->name('end-auction');

        // Bulk operations
        Route::post('/bulk-action', [CollateralController::class, 'bulkAction'])->name('bulk-action');
    });

    // ========================================================================
    // AUCTION MANAGEMENT
    // ========================================================================
    Route::prefix('auctions')->name('auctions.')->group(function () {
        Route::get('/', [AuctionController::class, 'index'])->name('index');
        Route::get('/create', [AuctionController::class, 'create'])->name('create');
        Route::post('/', [AuctionController::class, 'store'])->name('store');
        Route::get('/{auction}', [AuctionController::class, 'show'])->name('show');
        Route::get('/{auction}/edit', [AuctionController::class, 'edit'])->name('edit');
        Route::put('/{auction}', [AuctionController::class, 'update'])->name('update');
        Route::delete('/{auction}', [AuctionController::class, 'destroy'])->name('destroy');

        // Approval workflow
        Route::post('/{auction}/approve', [AuctionController::class, 'approve'])->name('approve');
        Route::post('/{auction}/reject', [AuctionController::class, 'reject'])->name('reject');

        // Auction Results
        Route::get('/results/all', [AuctionController::class, 'results'])->name('results');

        // Live Auction Management
        Route::get('/{auction}/live-data', [AuctionController::class, 'liveData'])->name('live-data');
        Route::post('/{auction}/extend', [AuctionController::class, 'extendAuction'])->name('extend');
        Route::post('/{auction}/cancel', [AuctionController::class, 'cancelAuction'])->name('cancel');
        Route::post('/{auction}/restart', [AuctionController::class, 'restartAuction'])->name('restart');

        // Auction Results Management
        Route::post('/results/{auctionResult}/payment-status', [AuctionController::class, 'updatePaymentStatus'])->name('update-payment-status');
        Route::post('/results/{auctionResult}/delivery-status', [AuctionController::class, 'updateDeliveryStatus'])->name('update-delivery-status');
    });

    // ========================================================================
    // ADDRESS MANAGEMENT
    // ========================================================================
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AddressController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AddressController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AddressController::class, 'store'])->name('store');
        Route::get('/{address}', [App\Http\Controllers\Admin\AddressController::class, 'show'])->name('show');
        Route::get('/{address}/edit', [App\Http\Controllers\Admin\AddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [App\Http\Controllers\Admin\AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [App\Http\Controllers\Admin\AddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/set-primary', [App\Http\Controllers\Admin\AddressController::class, 'setPrimary'])->name('set-primary');
        Route::get('/users/{user}/addresses', [App\Http\Controllers\Admin\AddressController::class, 'getUserAddresses'])->name('user-addresses');
        Route::post('/bulk-action', [App\Http\Controllers\Admin\AddressController::class, 'bulkAction'])->name('bulk-action');
    });

});

// ============================================================================
// AUTHENTICATION ROUTES (Guest Only)
// ============================================================================

Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Email verification routes
    Route::get('/verify-email/{token}', function ($token) {
        $emailVerificationService = app(\App\Services\EmailVerificationService::class);
        $result = $emailVerificationService->verifyEmail($token);
        
        if ($result['success']) {
            return redirect()->route('login')->with('success', $result['message']);
        } else {
            return redirect()->route('login')->withErrors(['email' => $result['message']]);
        }
    })->name('verify-email');

    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

});



// Logout Route (Authenticated Users Only)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================================
// USER ROUTES (Authenticated Users)
// ============================================================================

Route::middleware('auth')->group(function () {

    // User Dashboard
    Route::get('/dashboard', [PublicController::class, 'dashboard'])->name('dashboard');

    // User Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserController::class, 'editProfile'])->name('edit');
        Route::get('/view', [UserController::class, 'showProfile'])->name('show');
        Route::put('/', [UserController::class, 'updateProfile'])->name('update');
        Route::post('/avatar', [UserController::class, 'updateAvatar'])->name('avatar.update');
        Route::get('/settings', [UserController::class, 'settings'])->name('settings');
        Route::put('/settings', [UserController::class, 'updateSettings'])->name('settings.update');
        Route::delete('/', [UserController::class, 'deleteAccount'])->name('destroy');
        

    });

    // Address Management
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [App\Http\Controllers\AddressController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AddressController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\AddressController::class, 'store'])->name('store');
        Route::get('/{address}', [App\Http\Controllers\AddressController::class, 'show'])->name('show');
        Route::get('/{address}/edit', [App\Http\Controllers\AddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [App\Http\Controllers\AddressController::class, 'update'])->name('update');
        Route::post('/{address}/set-primary', [App\Http\Controllers\AddressController::class, 'setPrimary'])->name('set-primary');
        Route::delete('/{address}', [App\Http\Controllers\AddressController::class, 'destroy'])->name('destroy');
        
        // Utility routes
        Route::get('/api/states', [App\Http\Controllers\AddressController::class, 'getStates'])->name('states');
    });
});
