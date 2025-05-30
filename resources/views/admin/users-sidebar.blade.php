<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>User Management - Arrahnu Auction</title>

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
        <!-- Admin Layout with Sidebar -->
        <div class="flex h-screen">
            <!-- Sidebar -->
            <aside class="w-64 bg-white dark:bg-[#161615] border-r border-[#e3e3e0] dark:border-[#3E3E3A] flex flex-col">
                <!-- Logo -->
                <div class="p-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-sm">A</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Arrahnu</h1>
                            <p class="text-xs text-brand">Admin Panel</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 p-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 00-2-2m0 0V9a2 2 0 012-2h2a2 2 0 00-2-2"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2 text-sm font-medium text-brand bg-brand/5 rounded-lg">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        User Management
                    </a>

                    <!-- Branch Management -->
                    <a href="{{ route('admin.branches') }}" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Branch Management
                    </a>

                    <!-- Auction Management -->
                    <a href="{{ route('auctions.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Auction Management
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] my-4"></div>

                    <!-- Reports & Analytics -->
                    <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 00-2-2m0 0V9a2 2 0 012-2h2a2 2 0 00-2-2"></path>
                        </svg>
                        Reports & Analytics
                    </a>

                    <!-- Settings -->
                    <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        System Settings
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] my-4"></div>

                    <!-- User Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        User Dashboard
                    </a>
                </nav>

                <!-- User Profile Section -->
                <div class="p-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="relative">
                        <button id="adminSidebarDropdownButton" class="flex items-center w-full px-3 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                            <div class="w-8 h-8 bg-brand rounded-full flex items-center justify-center mr-3">
                                <span class="text-white font-medium text-sm">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-sm font-medium">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Administrator</p>
                            </div>
                            <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Sidebar Dropdown Menu -->
                        <div id="adminSidebarDropdownMenu" class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-[#161615] shadow-lg border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg py-2 z-50 hidden">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                <svg class="w-4 h-4 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile Settings
                            </a>
                            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header -->
                <header class="bg-white dark:bg-[#161615] border-b border-[#e3e3e0] dark:border-[#3E3E3A] px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">User Management</h1>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage user accounts, roles, and permissions</p>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto p-6">
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

                    <!-- User Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Total Users -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Users</p>
                                    <p class="text-3xl font-bold text-brand">{{ $users->count() }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Registered accounts</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Users -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Admin Users</p>
                                    <p class="text-3xl font-bold text-brand">{{ $users->where('is_admin', true)->count() }}</p>
                                    <p class="text-sm text-brand">Administrative access</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Regular Users -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Regular Users</p>
                                    <p class="text-3xl font-bold text-[#706f6c] dark:text-[#A1A09A]">{{ $users->where('is_admin', false)->count() }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Standard accounts</p>
                                </div>
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User List -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">All Users</h3>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage user accounts and permissions</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- Filter Buttons -->
                                <div class="flex bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg p-1">
                                    <button onclick="filterUsers('all')" id="filter-all" class="px-3 py-1 text-sm font-medium rounded-md transition-colors bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] shadow-sm">
                                        All
                                    </button>
                                    <button onclick="filterUsers('admin')" id="filter-admin" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                        Admins
                                    </button>
                                    <button onclick="filterUsers('user')" id="filter-user" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                        Users
                                    </button>
                                </div>

                                <!-- Add User Button -->
                                <button onclick="openAddUserModal()" class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add User
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <table class="min-w-full divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            User
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Role
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Joined
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-[#161615] divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                    @foreach($users->sortBy(function($user) { return [$user->is_admin ? 0 : 1, $user->name]; }) as $user)
                                        <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors user-row" data-role="{{ $user->is_admin ? 'admin' : 'user' }}">
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 {{ $user->is_admin ? 'bg-brand/10' : 'bg-gray-100 dark:bg-gray-800' }} rounded-full flex items-center justify-center mr-4">
                                                        <span class="{{ $user->is_admin ? 'text-brand' : 'text-[#706f6c] dark:text-[#A1A09A]' }} font-medium text-sm">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                            {{ $user->name }}
                                                        </div>
                                                        @if($user->is_admin)
                                                            <div class="text-xs text-brand">
                                                                @if($user->email === 'admin@arrahnu.com')
                                                                    Primary Administrator
                                                                @elseif($user->email === 'superadmin@arrahnu.com')
                                                                    Super Administrator
                                                                @elseif(in_array($user->email, ['john.mitchell@arrahnu.com', 'sarah.johnson@arrahnu.com', 'michael.chen@arrahnu.com', 'emily.rodriguez@arrahnu.com', 'david.thompson@arrahnu.com', 'lisa.anderson@arrahnu.com', 'robert.wilson@arrahnu.com']))
                                                                    Branch Manager
                                                                @else
                                                                    Administrative Staff
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                                Regular User
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    {{ $user->email }}
                                                </div>
                                                @if($user->email_verified_at)
                                                    <div class="text-xs text-green-600 dark:text-green-400">
                                                        ✓ Verified
                                                    </div>
                                                @else
                                                    <div class="text-xs text-red-600 dark:text-red-400">
                                                        ✗ Unverified
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                @if($user->is_admin)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand/10 text-brand">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Admin
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-[#706f6c] dark:text-[#A1A09A]">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        User
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                @if($user->email_verified_at)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3"/>
                                                        </svg>
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3"/>
                                                        </svg>
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#706f6c] dark:text-[#A1A09A] border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                {{ $user->created_at->format('M j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Edit User -->
                                                    <button onclick="editUser({{ $user->id }})"
                                                            class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                                        Edit
                                                    </button>

                                                    <!-- Toggle Admin Status -->
                                                    @if($user->id !== Auth::id())
                                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                                            @csrf
                                                            @if($user->is_admin)
                                                                <button type="submit"
                                                                        class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/40 transition-colors"
                                                                        onclick="return confirm('Remove admin privileges from {{ $user->name }}?')">
                                                                    Remove Admin
                                                                </button>
                                                            @else
                                                                <button type="submit"
                                                                        class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors"
                                                                        onclick="return confirm('Grant admin privileges to {{ $user->name }}?')">
                                                                    Make Admin
                                                                </button>
                                                            @endif
                                                        </form>
                                                    @else
                                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs font-medium rounded-full">
                                                            You
                                                        </span>
                                                    @endif

                                                    <!-- Delete User -->
                                                    @if($user->id !== Auth::id())
                                                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                                    onclick="return confirm('Are you sure you want to delete {{ $user->name }}? This action cannot be undone.')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($users->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No users</h3>
                                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">Get started by creating a new user account.</p>
                                <div class="mt-6">
                                    <button onclick="openAddUserModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover">
                                        Add User
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </main>
            </div>
        </div>

        <!-- JavaScript for Sidebar Dropdown -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const adminSidebarDropdownButton = document.getElementById('adminSidebarDropdownButton');
                const adminSidebarDropdownMenu = document.getElementById('adminSidebarDropdownMenu');

                if (adminSidebarDropdownButton && adminSidebarDropdownMenu) {
                    // Toggle dropdown
                    adminSidebarDropdownButton.addEventListener('click', function(e) {
                        e.stopPropagation();
                        adminSidebarDropdownMenu.classList.toggle('hidden');
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!adminSidebarDropdownButton.contains(e.target) && !adminSidebarDropdownMenu.contains(e.target)) {
                            adminSidebarDropdownMenu.classList.add('hidden');
                        }
                    });

                    // Close dropdown with Escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            adminSidebarDropdownMenu.classList.add('hidden');
                        }
                    });
                }
            });

            // User filtering functionality
            function filterUsers(role) {
                const rows = document.querySelectorAll('.user-row');
                const filterButtons = document.querySelectorAll('[id^="filter-"]');

                // Update button states
                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-white', 'dark:bg-[#161615]', 'text-[#1b1b18]', 'dark:text-[#EDEDEC]', 'shadow-sm');
                    btn.classList.add('text-[#706f6c]', 'dark:text-[#A1A09A]');
                });

                const activeButton = document.getElementById(`filter-${role}`);
                activeButton.classList.remove('text-[#706f6c]', 'dark:text-[#A1A09A]');
                activeButton.classList.add('bg-white', 'dark:bg-[#161615]', 'text-[#1b1b18]', 'dark:text-[#EDEDEC]', 'shadow-sm');

                // Filter rows
                rows.forEach(row => {
                    const userRole = row.getAttribute('data-role');
                    if (role === 'all' || userRole === role) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Placeholder functions for modals and actions
            function openAddUserModal() {
                alert('Add User Modal - To be implemented');
            }

            function editUser(userId) {
                alert('Edit User Modal - User ID: ' + userId);
            }
        </script>
    </body>
</html>
