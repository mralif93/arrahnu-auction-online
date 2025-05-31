<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
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

// Development/Testing Routes
Route::get('/test-password-reset', function () {
    return view('test-password-reset');
})->name('test.password.reset');

Route::get('/color-test', function () {
    return view('color-test');
})->name('color-test');

// ============================================================================
// ADMIN ROUTES (Admin Users Only)
// ============================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Profile Settings
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

    // ========================================================================
    // USER MANAGEMENT
    // ========================================================================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::post('/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('toggle-admin');
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
        Route::post('/', [CollateralController::class, 'store'])->name('store');
        Route::put('/{collateral}', [CollateralController::class, 'update'])->name('update');
        Route::post('/{collateral}/approve', [CollateralController::class, 'approve'])->name('approve');
        Route::post('/{collateral}/reject', [CollateralController::class, 'reject'])->name('reject');
        Route::post('/{collateral}/start-auction', [CollateralController::class, 'startAuction'])->name('start-auction');
        Route::post('/{collateral}/end-auction', [CollateralController::class, 'endAuction'])->name('end-auction');
        Route::delete('/{collateral}', [CollateralController::class, 'destroy'])->name('destroy');
    });

    // ========================================================================
    // AUCTION MANAGEMENT
    // ========================================================================
    Route::prefix('auctions')->name('auctions.')->group(function () {
        Route::get('/', [AuctionController::class, 'index'])->name('index');
        Route::get('/{auction}', [AuctionController::class, 'show'])->name('show');
        Route::get('/results/all', [AuctionController::class, 'results'])->name('results');
        Route::get('/{auction}/live-data', [AuctionController::class, 'liveData'])->name('live-data');
        Route::post('/{auction}/extend', [AuctionController::class, 'extendAuction'])->name('extend');
        Route::post('/{auction}/cancel', [AuctionController::class, 'cancelAuction'])->name('cancel');
        Route::post('/{auction}/restart', [AuctionController::class, 'restartAuction'])->name('restart');
        Route::post('/results/{auctionResult}/payment-status', [AuctionController::class, 'updatePaymentStatus'])->name('update-payment-status');
        Route::post('/results/{auctionResult}/delivery-status', [AuctionController::class, 'updateDeliveryStatus'])->name('update-delivery-status');
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
        Route::put('/', [UserController::class, 'updateProfile'])->name('update');
        Route::delete('/', [UserController::class, 'deleteAccount'])->name('destroy');
    });
});
