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
        <!-- Navigation -->
        <header class="w-full border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-[#161615]/80 backdrop-blur-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <nav class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-brand">
                            Arrahnu Auction
                        </a>
                        <span class="ml-3 px-2 py-1 bg-brand/10 text-brand text-xs font-medium rounded-full">
                            Admin
                        </span>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('admin.dashboard') }}" class="text-brand font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('auctions.index') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Auctions
                        </a>
                        <a href="{{ route('admin.users') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Users
                        </a>
                        <a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Reports
                        </a>
                    </div>

                    <!-- Admin User Dropdown -->
                    <div class="relative">
                        <button id="adminDropdownButton" class="flex items-center gap-2 px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-sm transition-colors">
                            <div class="w-8 h-8 bg-brand rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-sm">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
                                <span class="px-2 py-1 bg-brand text-white text-xs font-medium rounded-full">
                                    Admin
                                </span>
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>

                        <!-- Admin Dropdown Menu -->
                        <div id="adminDropdownMenu" class="absolute right-0 mt-2 w-64 bg-white dark:bg-[#161615] shadow-lg border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg py-2 z-50 hidden">
                            <!-- Admin Info -->
                            <div class="px-4 py-3 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-brand rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">
                                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ Auth::user()->email ?? 'admin@arrahnu.com' }}</p>
                                        <span class="inline-block mt-1 px-2 py-1 bg-brand text-white text-xs font-medium rounded-full">
                                            Administrator
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Menu Items -->
                            <div class="py-1">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 00-2-2m0 0V9a2 2 0 012-2h2a2 2 0 00-2-2"></path>
                                    </svg>
                                    Admin Dashboard
                                </a>

                                <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    User Management
                                </a>

                                <a href="{{ route('auctions.index') }}" class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Auction Management
                                </a>

                                <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] my-1"></div>

                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                    </svg>
                                    User Dashboard
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
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl lg:text-5xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Admin Dashboard
                    </h1>
                    <p class="text-xl text-[#706f6c] dark:text-[#A1A09A]">
                        Manage your auction platform with comprehensive analytics and controls.
                    </p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Revenue -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Revenue</p>
                                <p class="text-3xl font-bold text-brand" data-metric="revenue">$2.4M</p>
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

                    <!-- Commission Earned -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Commission Earned</p>
                                <p class="text-3xl font-bold text-brand">$240K</p>
                                <p class="text-sm text-green-600 dark:text-green-400">10% avg commission</p>
                            </div>
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Analytics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Revenue Chart -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Revenue Overview</h3>
                        <div class="h-64 bg-gradient-to-t from-brand/5 to-brand/20 rounded-lg flex items-end justify-center p-4">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-brand mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 00-2-2m0 0V9a2 2 0 012-2h2a2 2 0 00-2-2"></path>
                                </svg>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Chart placeholder</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Integrate with Chart.js or similar</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Activity -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">User Activity</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Active Bidders</span>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">2,847</span>
                            </div>
                            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-2">
                                <div class="bg-brand h-2 rounded-full" style="width: 75%"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">New Registrations</span>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">1,203</span>
                            </div>
                            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-2">
                                <div class="bg-brand h-2 rounded-full" style="width: 60%"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Verified Sellers</span>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">456</span>
                            </div>
                            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-2">
                                <div class="bg-brand h-2 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Sections -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Recent Auctions -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Recent Auctions</h3>
                            <a href="{{ route('auctions.index') }}" class="text-sm text-brand hover:text-brand-hover">View All</a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Rolex Submariner</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current: $15,750 • 23 bids</p>
                                </div>
                                <span class="text-xs text-green-600 dark:text-green-400">Live</span>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Diamond Bracelet</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current: $8,250 • 17 bids</p>
                                </div>
                                <span class="text-xs text-green-600 dark:text-green-400">Live</span>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Picasso Sketch</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current: $45,000 • 8 bids</p>
                                </div>
                                <span class="text-xs text-orange-600 dark:text-orange-400">Ending Soon</span>
                            </div>
                        </div>
                    </div>

                    <!-- User Management -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">User Management</h3>
                            <a href="#" class="text-sm text-brand hover:text-brand-hover">Manage</a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-[#fff2f2] dark:bg-[#1D0002] rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Pending Verifications</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Seller applications</p>
                                </div>
                                <span class="text-lg font-bold text-brand">12</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Reported Users</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Requires review</p>
                                </div>
                                <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">3</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">New Registrations</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Last 24 hours</p>
                                </div>
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">47</span>
                            </div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">System Status</h3>
                            <span class="text-xs text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/20 px-2 py-1 rounded-full">All Systems Operational</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Server Status</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-green-600 dark:text-green-400">Online</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Database</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-green-600 dark:text-green-400">Connected</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Payment Gateway</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-green-600 dark:text-green-400">Active</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Email Service</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-green-600 dark:text-green-400">Operational</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Create Auction -->
                        <button class="quick-action-btn flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group" data-action="create-auction">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Create Auction</span>
                        </button>

                        <!-- Manage Users -->
                        <a href="{{ route('admin.users') }}" class="quick-action-btn flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Users</span>
                        </a>

                        <!-- View Reports -->
                        <button class="quick-action-btn flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group" data-action="view-reports">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 00-2-2m0 0V9a2 2 0 012-2h2a2 2 0 00-2-2"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">View Reports</span>
                        </button>

                        <!-- Manage Categories -->
                        <button class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Categories</span>
                        </button>

                        <!-- System Settings -->
                        <button class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Settings</span>
                        </button>

                        <!-- Send Notifications -->
                        <button class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h10V9H4v2z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Notifications</span>
                        </button>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Recent Activity</h3>
                        <button class="text-sm text-brand hover:text-brand-hover">View All</button>
                    </div>
                    <div class="space-y-4">
                        <!-- Activity Item 1 -->
                        <div class="flex items-start space-x-4 p-4 bg-[#fff2f2] dark:bg-[#1D0002] rounded-lg">
                            <div class="w-8 h-8 bg-brand rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">New auction created</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Vintage Rolex Submariner 1965 by John Smith</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">2 minutes ago</p>
                            </div>
                            <span class="text-xs text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/20 px-2 py-1 rounded-full">New</span>
                        </div>

                        <!-- Activity Item 2 -->
                        <div class="flex items-start space-x-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">User verification completed</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Sarah Johnson verified as premium seller</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">15 minutes ago</p>
                            </div>
                            <span class="text-xs text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/20 px-2 py-1 rounded-full">Verified</span>
                        </div>

                        <!-- Activity Item 3 -->
                        <div class="flex items-start space-x-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Dispute reported</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Payment issue for auction #12847</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">1 hour ago</p>
                            </div>
                            <span class="text-xs text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/20 px-2 py-1 rounded-full">Pending</span>
                        </div>

                        <!-- Activity Item 4 -->
                        <div class="flex items-start space-x-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Payment processed</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">$45,000 for Picasso Sketch auction</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">2 hours ago</p>
                            </div>
                            <span class="text-xs text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/20 px-2 py-1 rounded-full">Completed</span>
                        </div>
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

        <!-- JavaScript for Dashboard Functionality -->
        <script>
            // Real-time clock
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString();
                const dateString = now.toLocaleDateString();

                // Update if clock element exists
                const clockElement = document.getElementById('admin-clock');
                if (clockElement) {
                    clockElement.textContent = `${dateString} ${timeString}`;
                }
            }

            // Update clock every second
            setInterval(updateClock, 1000);
            updateClock(); // Initial call

            // Simulate real-time data updates
            function simulateDataUpdates() {
                // Simulate revenue growth
                const revenueElement = document.querySelector('[data-metric="revenue"]');
                if (revenueElement) {
                    const currentValue = parseFloat(revenueElement.textContent.replace('$', '').replace('M', ''));
                    const newValue = (currentValue + Math.random() * 0.01).toFixed(2);
                    revenueElement.textContent = `$${newValue}M`;
                }

                // Simulate active bidders fluctuation
                const biddersElement = document.querySelector('[data-metric="bidders"]');
                if (biddersElement) {
                    const currentValue = parseInt(biddersElement.textContent.replace(',', ''));
                    const change = Math.floor(Math.random() * 10) - 5; // -5 to +5
                    const newValue = Math.max(0, currentValue + change);
                    biddersElement.textContent = newValue.toLocaleString();
                }
            }

            // Update data every 30 seconds
            setInterval(simulateDataUpdates, 30000);

            // Quick action handlers
            document.addEventListener('DOMContentLoaded', function() {
                // Add click handlers for quick action buttons
                const quickActionButtons = document.querySelectorAll('.quick-action-btn');
                quickActionButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const action = this.dataset.action;
                        handleQuickAction(action);
                    });
                });
            });

            function handleQuickAction(action) {
                switch(action) {
                    case 'create-auction':
                        alert('Create Auction functionality would be implemented here');
                        break;
                    case 'verify-users':
                        alert('User Verification panel would open here');
                        break;
                    case 'view-reports':
                        alert('Reports dashboard would be displayed here');
                        break;
                    case 'manage-categories':
                        alert('Category management interface would open here');
                        break;
                    case 'system-settings':
                        alert('System settings panel would be displayed here');
                        break;
                    case 'send-notifications':
                        alert('Notification composer would open here');
                        break;
                    default:
                        console.log('Unknown action:', action);
                }
            }

            // Activity feed auto-refresh simulation
            function refreshActivityFeed() {
                const activities = [
                    {
                        type: 'auction',
                        title: 'New auction created',
                        description: 'Vintage Omega Speedmaster by Mike Wilson',
                        time: 'Just now',
                        status: 'New',
                        color: 'green'
                    },
                    {
                        type: 'user',
                        title: 'User verification completed',
                        description: 'Emma Davis verified as premium seller',
                        time: '5 minutes ago',
                        status: 'Verified',
                        color: 'blue'
                    },
                    {
                        type: 'payment',
                        title: 'Payment processed',
                        description: '$12,500 for Hermès Birkin auction',
                        time: '10 minutes ago',
                        status: 'Completed',
                        color: 'green'
                    }
                ];

                // This would typically fetch from an API
                console.log('Activity feed refreshed with new data');
            }

            // Refresh activity feed every 2 minutes
            setInterval(refreshActivityFeed, 120000);

            // Add smooth scrolling for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading states for buttons
            function addLoadingState(button) {
                const originalText = button.textContent;
                button.textContent = 'Loading...';
                button.disabled = true;

                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 1000);
            }

            // Add click handlers for action buttons
            document.querySelectorAll('button[class*="bg-brand"]').forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.disabled) {
                        addLoadingState(this);
                    }
                });
            });

            // Admin Dropdown functionality
            const adminDropdownButton = document.getElementById('adminDropdownButton');
            const adminDropdownMenu = document.getElementById('adminDropdownMenu');

            if (adminDropdownButton && adminDropdownMenu) {
                // Toggle dropdown
                adminDropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    adminDropdownMenu.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!adminDropdownButton.contains(e.target) && !adminDropdownMenu.contains(e.target)) {
                        adminDropdownMenu.classList.add('hidden');
                    }
                });

                // Close dropdown with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        adminDropdownMenu.classList.add('hidden');
                    }
                });
            }
        </script>
    </body>
</html>
