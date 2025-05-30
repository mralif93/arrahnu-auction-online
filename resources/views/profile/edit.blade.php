<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Profile Settings - Arrahnu Auction</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Fallback CSS */
            </style>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
        <!-- Navigation -->
        <header class="w-full border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-[#161615]/80 backdrop-blur-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <nav class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-brand">
                            Arrahnu Auction
                        </a>
                        @if(Auth::user()->isAdmin())
                            <span class="ml-3 px-2 py-1 bg-brand/10 text-brand text-xs font-medium rounded-full">
                                Admin
                            </span>
                        @endif
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('auctions.index') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Auctions
                        </a>
                        <a href="{{ route('how-it-works') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            How It Works
                        </a>
                        <a href="{{ route('about') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            About
                        </a>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button id="userDropdownButton" class="flex items-center gap-2 px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-sm transition-colors">
                            <div class="w-8 h-8 bg-brand/10 rounded-full flex items-center justify-center">
                                <span class="text-brand font-medium text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>{{ Auth::user()->name }}</span>
                                @if(Auth::user()->isAdmin())
                                    <span class="px-2 py-1 bg-brand/10 text-brand text-xs font-medium rounded-full border border-brand/20">
                                        Admin
                                    </span>
                                @endif
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="userDropdownMenu" class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#161615] shadow-lg border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg py-2 z-50 hidden">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-brand/10 rounded-full flex items-center justify-center">
                                        <span class="text-brand font-medium">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ Auth::user()->email }}</p>
                                        @if(Auth::user()->isAdmin())
                                            <span class="inline-block mt-1 px-2 py-1 bg-brand/10 text-brand text-xs font-medium rounded-full border border-brand/20">
                                                Administrator
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                    </svg>
                                    Dashboard
                                </a>

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        Admin Panel
                                    </a>
                                @endif

                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-brand bg-brand/5 hover:bg-brand/10 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile Settings
                                </a>

                                <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content px-6 lg:px-8 py-12">
            <div class="max-w-4xl mx-auto">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl lg:text-5xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Profile Settings
                    </h1>
                    <p class="text-xl text-[#706f6c] dark:text-[#A1A09A]">
                        Manage your account information and security settings.
                    </p>
                </div>

                <!-- Profile Information -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Profile Information</h2>
                        @if(Auth::user()->isAdmin())
                            <span class="px-3 py-1 bg-brand text-white text-sm font-medium rounded-full">
                                Administrator
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-full">
                                User
                            </span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Profile Avatar -->
                            <div class="md:col-span-2 flex items-center gap-6">
                                <div class="w-20 h-20 bg-brand/10 rounded-full flex items-center justify-center">
                                    <span class="text-brand font-bold text-2xl">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ Auth::user()->name }}</h3>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ Auth::user()->email }}</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                                        Member since {{ Auth::user()->created_at->format('F Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    Full Name
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', Auth::user()->name) }}"
                                       class="w-full px-3 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    Email Address
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', Auth::user()->email) }}"
                                       class="w-full px-3 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-brand bg-brand-hover text-white font-medium rounded-lg transition-colors">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Change Password</h2>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Password -->
                            <div class="md:col-span-2">
                                <label for="current_password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    Current Password
                                </label>
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       class="w-full px-3 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    New Password
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="w-full px-3 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    Confirm New Password
                                </label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="w-full px-3 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                        </div>

                        <!-- Hidden fields to maintain profile data -->
                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Account Security -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Account Security</h2>

                    <div class="space-y-4">
                        <!-- Account Status -->
                        <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">Email Verified</p>
                                    <p class="text-xs text-green-600 dark:text-green-400">Your email address has been verified</p>
                                </div>
                            </div>
                            <span class="text-xs text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/40 px-2 py-1 rounded-full">
                                Active
                            </span>
                        </div>

                        <!-- Account Type -->
                        <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <div class="flex items-center">
                                @if(Auth::user()->isAdmin())
                                    <svg class="w-5 h-5 text-brand mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Administrator Account</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">You have full administrative privileges</p>
                                    </div>
                                @else
                                    <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Standard User Account</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Regular user with bidding privileges</p>
                                    </div>
                                @endif
                            </div>
                            @if(Auth::user()->isAdmin())
                                <span class="text-xs text-brand bg-brand/10 px-2 py-1 rounded-full border border-brand/20">
                                    Admin
                                </span>
                            @else
                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-full">
                                    User
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 border-l-4 border-red-500">
                    <h2 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-6">Danger Zone</h2>

                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Delete Account</h3>
                                <p class="text-sm text-red-600 dark:text-red-400 mb-4">
                                    Once you delete your account, all of your data will be permanently removed. This action cannot be undone.
                                </p>
                                @if(Auth::user()->isAdmin())
                                    <p class="text-xs text-red-600 dark:text-red-400 mb-4">
                                        <strong>Warning:</strong> As an administrator, deleting your account may affect system operations.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <button type="button"
                                onclick="openDeleteModal()"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Delete Account
                        </button>
                    </div>
                </div>

                <!-- Delete Account Modal -->
                <div id="deleteAccountModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-xl max-w-md w-full mx-4">
                        <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">Delete Account</h3>
                        </div>

                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('DELETE')

                            <div class="px-6 py-4">
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                                    Are you sure you want to delete your account? This action cannot be undone and will permanently remove all your data.
                                </p>

                                <div class="mb-4">
                                    <label for="delete_password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                        Confirm your password to continue
                                    </label>
                                    <input type="password"
                                           id="delete_password"
                                           name="password"
                                           class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                           required>
                                </div>
                            </div>

                            <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] flex justify-end space-x-3">
                                <button type="button"
                                        onclick="closeDeleteModal()"
                                        class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm hover:border-[#1915014a] dark:hover:border-[#62605b] transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-sm transition-colors">
                                    Delete Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="sticky-footer bg-[#1b1b18] dark:bg-[#0a0a0a] text-white py-8">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <p class="text-[#A1A09A]">&copy; {{ date('Y') }} Arrahnu Auction. All rights reserved.</p>
            </div>
        </footer>

        <!-- JavaScript for User Dropdown -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropdownButton = document.getElementById('userDropdownButton');
                const dropdownMenu = document.getElementById('userDropdownMenu');

                // Toggle dropdown
                dropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });

                // Close dropdown with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            });

            // Delete Account Modal
            function openDeleteModal() {
                document.getElementById('deleteAccountModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteAccountModal').classList.add('hidden');
                document.getElementById('delete_password').value = '';
            }

            // Close modal when clicking outside
            document.getElementById('deleteAccountModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        </script>
    </body>
</html>
