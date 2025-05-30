<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Branch Management - Arrahnu Auction</title>

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
                    <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        User Management
                    </a>

                    <!-- Branch Management -->
                    <a href="{{ route('admin.branches') }}" class="flex items-center px-3 py-2 text-sm font-medium text-brand bg-brand/5 rounded-lg">
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
                            <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Branch Management</h1>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage auction house locations and operations</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Add Branch Button -->
                            <button onclick="openAddBranchModal()" class="px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                                Add New Branch
                            </button>
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

                    <!-- Branch Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Total Branches -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Branches</p>
                                    <p class="text-3xl font-bold text-brand">{{ $totalBranches }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Locations worldwide</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Active Branches -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Active Branches</p>
                                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeBranches }}</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">Currently operational</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Inactive Branches -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Inactive Branches</p>
                                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $totalBranches - $activeBranches }}</p>
                                    <p class="text-sm text-red-600 dark:text-red-400">Under maintenance</p>
                                </div>
                                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branches List -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">All Branches</h3>
                        </div>

                        <div class="overflow-x-auto border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <table class="min-w-full divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Branch
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Location
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Manager
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Contact
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-[#161615] divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                    @foreach($branches as $branch)
                                        <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center mr-4">
                                                        <span class="text-brand font-medium text-sm">
                                                            {{ strtoupper(substr($branch->code, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                            {{ $branch->name }}
                                                        </div>
                                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                            {{ $branch->code }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    {{ $branch->city }}, {{ $branch->state }}
                                                </div>
                                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                    {{ $branch->country }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                @if($branch->manager)
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 bg-brand/10 rounded-full flex items-center justify-center mr-3">
                                                            <span class="text-brand font-medium text-xs">
                                                                {{ strtoupper(substr($branch->manager->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                                {{ $branch->manager->name }}
                                                            </div>
                                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                                {{ $branch->manager->email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">No manager assigned</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                @if($branch->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3"/>
                                                        </svg>
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3"/>
                                                        </svg>
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    {{ $branch->phone ?? 'No phone' }}
                                                </div>
                                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                    {{ $branch->email ?? 'No email' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center space-x-2">
                                                    <!-- View Details -->
                                                    <button onclick="viewBranchDetails({{ $branch->id }})"
                                                            class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                                        View
                                                    </button>

                                                    <!-- Toggle Status -->
                                                    <form method="POST" action="{{ route('admin.branches.toggle-status', $branch) }}" class="inline">
                                                        @csrf
                                                        @if($branch->is_active)
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/40 transition-colors"
                                                                    onclick="return confirm('Deactivate {{ $branch->name }}?')">
                                                                Deactivate
                                                            </button>
                                                        @else
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors"
                                                                    onclick="return confirm('Activate {{ $branch->name }}?')">
                                                                Activate
                                                            </button>
                                                        @endif
                                                    </form>

                                                    <!-- Delete Branch -->
                                                    <form method="POST" action="{{ route('admin.branches.delete', $branch) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                                onclick="return confirm('Are you sure you want to delete {{ $branch->name }}? This action cannot be undone.')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($branches->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No branches</h3>
                                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">Get started by creating a new branch location.</p>
                                <div class="mt-6">
                                    <button onclick="openAddBranchModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover">
                                        Add Branch
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

            function openAddBranchModal() {
                // Placeholder for add branch modal
                alert('Add Branch Modal - To be implemented');
            }

            function viewBranchDetails(branchId) {
                // Placeholder for branch details modal
                alert('Branch Details Modal - Branch ID: ' + branchId);
            }
        </script>
    </body>
</html>
