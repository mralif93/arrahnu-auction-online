<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});

// Test page for password reset functionality
Route::get('/test-password-reset', function () {
    return view('test-password-reset');
})->name('test.password.reset');

// Static pages
Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/color-test', function () {
    return view('color-test');
})->name('color-test');

Route::get('/auctions', function () {
    return view('auctions.index');
})->name('auctions.index');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard-sidebar');
    })->name('admin.dashboard');

    Route::get('/admin/users', function () {
        $users = \App\Models\User::orderBy('created_at', 'desc')->get();
        return view('admin.users-sidebar', compact('users'));
    })->name('admin.users');

    Route::post('/admin/users/{user}/toggle-admin', function (\App\Models\User $user) {
        // Prevent admin from removing their own admin status
        if ($user->id === Auth::id() && $user->isAdmin()) {
            return redirect()->back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $action = $user->is_admin ? 'granted' : 'removed';
        $message = "Admin privileges {$action} for {$user->name}.";

        return redirect()->back()->with('success', $message);
    })->name('admin.users.toggle-admin');

    Route::put('/admin/users/{user}', function (\App\Models\User $user, \Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
        ]);

        // Prevent admin from removing their own admin status
        if ($user->id === Auth::id() && $user->isAdmin() && !$request->has('is_admin')) {
            return redirect()->back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->back()->with('success', "User {$user->name} has been updated successfully.");
    })->name('admin.users.update');

    Route::delete('/admin/users/{user}', function (\App\Models\User $user) {
        // Prevent admin from deleting their own account
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "User {$userName} has been deleted.");
    })->name('admin.users.delete');

    // Admin Profile Settings
    Route::get('/admin/profile', function () {
        $totalUsers = \App\Models\User::count();
        $totalAdmins = \App\Models\User::where('is_admin', true)->count();
        $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.profile', compact('totalUsers', 'totalAdmins', 'recentUsers'));
    })->name('admin.profile');

    // Branch Management Routes
    Route::get('/admin/branches', function () {
        $branches = \App\Models\Branch::with('manager')->orderBy('created_at', 'desc')->get();
        $totalBranches = $branches->count();
        $activeBranches = $branches->where('is_active', true)->count();
        $users = \App\Models\User::where('is_admin', true)->get();

        return view('admin.branches', compact('branches', 'totalBranches', 'activeBranches', 'users'));
    })->name('admin.branches');

    Route::post('/admin/branches', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:branches,code',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $operatingHours = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            if ($request->input("{$day}_closed")) {
                $operatingHours[$day] = ['closed' => true];
            } else {
                $operatingHours[$day] = [
                    'open' => $request->input("{$day}_open", '09:00'),
                    'close' => $request->input("{$day}_close", '17:00'),
                ];
            }
        }

        \App\Models\Branch::create([
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager_id' => $request->manager_id,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'operating_hours' => $operatingHours,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Branch created successfully.');
    })->name('admin.branches.store');

    Route::put('/admin/branches/{branch}', function (\App\Models\Branch $branch, \Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:branches,code,' . $branch->id,
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $operatingHours = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            if ($request->input("{$day}_closed")) {
                $operatingHours[$day] = ['closed' => true];
            } else {
                $operatingHours[$day] = [
                    'open' => $request->input("{$day}_open", '09:00'),
                    'close' => $request->input("{$day}_close", '17:00'),
                ];
            }
        }

        $branch->update([
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager_id' => $request->manager_id,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'operating_hours' => $operatingHours,
        ]);

        return redirect()->back()->with('success', 'Branch updated successfully.');
    })->name('admin.branches.update');

    Route::post('/admin/branches/{branch}/toggle-status', function (\App\Models\Branch $branch) {
        $branch->update(['is_active' => !$branch->is_active]);

        $status = $branch->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Branch {$branch->name} has been {$status}.");
    })->name('admin.branches.toggle-status');

    Route::delete('/admin/branches/{branch}', function (\App\Models\Branch $branch) {
        $branchName = $branch->name;
        $branch->delete();

        return redirect()->back()->with('success', "Branch {$branchName} has been deleted.");
    })->name('admin.branches.delete');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');

    Route::put('/profile', function (\Illuminate\Http\Request $request) {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update basic info
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    })->name('profile.update');

    Route::delete('/profile', function (\Illuminate\Http\Request $request) {
        $user = Auth::user();

        $request->validate([
            'password' => 'required|current_password',
        ]);

        // Prevent admin from deleting their own account if they're the only admin
        if ($user->isAdmin()) {
            $adminCount = \App\Models\User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Cannot delete account. You are the only administrator.');
            }
        }

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully.');
    })->name('profile.destroy');
});
