<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Admin Dashboard - Arrahnu Auction</title>

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
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-brand bg-brand/5 rounded-lg">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 00-2-2m0 0V9a2 2 0 012-2h2a2 2 0 00-2-2"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
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
                            <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Admin Dashboard</h1>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Welcome back, {{ Auth::user()->name ?? 'Administrator' }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="p-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.5a6 6 0 0 1 9 9l-9-9zM13.5 13.5a6 6 0 0 1-9-9l9 9z"></path>
                                </svg>
                            </button>
                            <!-- Quick Actions -->
                            <button class="px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                                Quick Action
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto p-6">
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Revenue -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Revenue</p>
                                    <p class="text-3xl font-bold text-brand">$2.4M</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">+12.5% from last month</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Active Auctions -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Active Auctions</p>
                                    <p class="text-3xl font-bold text-brand">127</p>
                                    <p class="text-sm text-blue-600 dark:text-blue-400">24 ending today</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 00-2-2m0 0V9a2 2 0 012-2h2a2 2 0 00-2-2"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Users -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Users</p>
                                    <p class="text-3xl font-bold text-brand">15,847</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">+8.2% new users</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Branches -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Branches</p>
                                    <p class="text-3xl font-bold text-brand">8</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">6 active locations</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Quick Actions</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- User Management -->
                            <a href="{{ route('admin.users') }}" class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                                <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Users</span>
                            </a>

                            <!-- Branch Management -->
                            <a href="{{ route('admin.branches') }}" class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                                <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Branches</span>
                            </a>

                            <!-- Auction Management -->
                            <a href="{{ route('auctions.index') }}" class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                                <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Auctions</span>
                            </a>

                            <!-- System Settings -->
                            <a href="#" class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                                <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">System Settings</span>
                            </a>
                        </div>
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
        </script>
    </body>
</html>
