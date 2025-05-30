<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>User Management - Arrahnu Auction Admin</title>

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
                        <a href="{{ route('admin.dashboard') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Dashboard
                        </a>
                        <a href="{{ route('auctions.index') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Auctions
                        </a>
                        <a href="{{ route('admin.users') }}" class="text-brand font-medium">
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

                                <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-2 text-sm text-brand bg-brand/5 hover:bg-brand/10 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        User Management
                    </h1>
                    <p class="text-xl text-[#706f6c] dark:text-[#A1A09A]">
                        Manage user accounts, roles, and permissions.
                    </p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Users</p>
                                <p class="text-3xl font-bold text-brand">{{ $users->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Admin Users</p>
                                <p class="text-3xl font-bold text-brand">{{ $users->where('is_admin', true)->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Regular Users</p>
                                <p class="text-3xl font-bold text-brand">{{ $users->where('is_admin', false)->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">All Users</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        User
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Joined
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                @foreach($users as $user)
                                    <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-brand/10 rounded-full flex items-center justify-center">
                                                    <span class="text-brand font-medium">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                        {{ $user->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                {{ $user->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->isAdmin())
                                                <span class="px-2 py-1 bg-brand text-white text-xs font-medium rounded-full">
                                                    Admin
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs font-medium rounded-full">
                                                    User
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->email_verified_at)
                                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full">
                                                    Unverified
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center space-x-2">
                                                <!-- Edit User -->
                                                <button type="button"
                                                        class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors"
                                                        onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', {{ $user->isAdmin() ? 'true' : 'false' }})">
                                                    Edit
                                                </button>

                                                <!-- Toggle Admin Status -->
                                                @if($user->id !== Auth::id())
                                                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                                        @csrf
                                                        @if($user->isAdmin())
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/40 transition-colors"
                                                                    onclick="return confirm('Remove admin privileges from {{ $user->name }}?')">
                                                                Remove Admin
                                                            </button>
                                                        @else
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-brand/10 text-brand text-xs font-medium rounded-full hover:bg-brand/20 transition-colors"
                                                                    onclick="return confirm('Grant admin privileges to {{ $user->name }}?')">
                                                                Make Admin
                                                            </button>
                                                        @endif
                                                    </form>
                                                @else
                                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs font-medium rounded-full">
                                                        Current User
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
                </div>
            </div>
        </main>

        <!-- Edit User Modal -->
        <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Edit User</h3>
                </div>

                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-4 space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Name
                            </label>
                            <input type="text"
                                   id="edit_name"
                                   name="name"
                                   class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Email
                            </label>
                            <input type="email"
                                   id="edit_email"
                                   name="email"
                                   class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                                   required>
                        </div>

                        <!-- Admin Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox"
                                       id="edit_is_admin"
                                       name="is_admin"
                                       value="1"
                                       class="w-4 h-4 text-brand bg-[#FDFDFC] dark:bg-[#161615] border-[#e3e3e0] dark:border-[#3E3E3A] rounded focus:ring-brand focus:ring-2">
                                <span class="ml-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Admin privileges</span>
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] flex justify-end space-x-3">
                        <button type="button"
                                onclick="closeEditModal()"
                                class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm hover:border-[#1915014a] dark:hover:border-[#62605b] transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-brand bg-brand-hover text-white rounded-sm transition-colors">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-[#1b1b18] dark:bg-[#0a0a0a] text-white py-8">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <p class="text-[#A1A09A]">&copy; {{ date('Y') }} Arrahnu Auction. All rights reserved.</p>
            </div>
        </footer>

        <!-- JavaScript -->
        <script>
            function openEditModal(userId, userName, userEmail, isAdmin) {
                // Set form action
                document.getElementById('editUserForm').action = `/admin/users/${userId}`;

                // Populate form fields
                document.getElementById('edit_name').value = userName;
                document.getElementById('edit_email').value = userEmail;
                document.getElementById('edit_is_admin').checked = isAdmin;

                // Show modal
                document.getElementById('editUserModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editUserModal').classList.add('hidden');
            }

            // Close modal when clicking outside
            document.getElementById('editUserModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEditModal();
                }
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

                // Close dropdown with Escape key (but don't interfere with modal)
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !document.getElementById('editUserModal').classList.contains('hidden')) {
                        // Modal is open, let modal handle escape
                        return;
                    }
                    if (e.key === 'Escape') {
                        adminDropdownMenu.classList.add('hidden');
                    }
                });
            }
        </script>
    </body>
</html>
