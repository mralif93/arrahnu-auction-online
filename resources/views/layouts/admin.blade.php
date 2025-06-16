<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Admin Panel') - Arrahnu Auction</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        
        <!-- Boxicons -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Fallback CSS for when Vite assets are not available */
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
                .flex { display: flex; }
                .flex-col { flex-direction: column; }
                .flex-1 { flex: 1; }
                .items-center { align-items: center; }
                .justify-between { justify-content: space-between; }
                .space-x-3 > * + * { margin-left: 0.75rem; }
                .space-y-2 > * + * { margin-top: 0.5rem; }
                .space-y-6 > * + * { margin-top: 1.5rem; }
                .w-64 { width: 16rem; }
                .w-full { width: 100%; }
                .h-screen { height: 100vh; }
                .p-4 { padding: 1rem; }
                .p-6 { padding: 1.5rem; }
                .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
                .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
                .px-4 { padding-left: 1rem; padding-right: 1rem; }
                .mb-2 { margin-bottom: 0.5rem; }
                .mb-4 { margin-bottom: 1rem; }
                .mr-2 { margin-right: 0.5rem; }
                .mr-3 { margin-right: 0.75rem; }
                .bg-white { background-color: #ffffff; }
                .bg-gray-100 { background-color: #f3f4f6; }
                .bg-blue-500 { background-color: #3b82f6; }
                .bg-green-500 { background-color: #10b981; }
                .text-white { color: #ffffff; }
                .text-sm { font-size: 0.875rem; }
                .text-lg { font-size: 1.125rem; }
                .text-2xl { font-size: 1.5rem; }
                .font-medium { font-weight: 500; }
                .font-bold { font-weight: 700; }
                .border { border: 1px solid #d1d5db; }
                .border-r { border-right: 1px solid #d1d5db; }
                .border-b { border-bottom: 1px solid #d1d5db; }
                .border-t { border-top: 1px solid #d1d5db; }
                .rounded-lg { border-radius: 0.5rem; }
                .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
                .overflow-hidden { overflow: hidden; }
                .overflow-y-auto { overflow-y: auto; }
                .hidden { display: none; }
                .block { display: block; }
                .inline-flex { display: inline-flex; }
                .transition-colors { transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out; }
                .hover\:bg-gray-200:hover { background-color: #e5e7eb; }
                .hover\:bg-blue-600:hover { background-color: #2563eb; }
                .hover\:bg-green-600:hover { background-color: #059669; }
                .focus\:ring-2:focus { outline: none; box-shadow: 0 0 0 2px #3b82f6; }
                .focus\:border-transparent:focus { border-color: transparent; }
                input, textarea, select { padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; width: 100%; }
                button { cursor: pointer; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; font-weight: 500; transition: all 0.15s ease-in-out; }
                .btn-primary { background-color: #3b82f6; color: white; }
                .btn-primary:hover { background-color: #2563eb; }
                .btn-secondary { background-color: #6b7280; color: white; }
                .btn-secondary:hover { background-color: #4b5563; }
                .text-brand { color: #FE5000; }
                .bg-brand { background-color: #FE5000; }
                .bg-brand:hover { background-color: #E5470A; }
            </style>
        @endif

        @stack('styles')
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
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-grid-alt text-xl mr-3'></i>
                        Dashboard
                    </a>

                    <!-- Monitoring -->
                    <a href="{{ route('admin.dashboard.monitoring') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard.monitoring') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-bar-chart-alt-2 text-xl mr-3'></i>
                        Monitoring
                    </a>

                    <!-- User -->
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-user text-xl mr-3'></i>
                        User
                    </a>

                    <!-- Auction -->
                    <a href="{{ route('admin.auctions.index') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.auctions.*') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-purchase-tag text-xl mr-3'></i>
                        Auction
                    </a>

                    <!-- Branch -->
                    <a href="{{ route('admin.branches.index') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.branches.*') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-buildings text-xl mr-3'></i>
                        Branch
                    </a>

                    <!-- Account -->
                    <a href="{{ route('admin.accounts.index') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.accounts.*') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-wallet text-xl mr-3'></i>
                        Account
                    </a>

                    <!-- Collateral -->
                    <a href="{{ route('admin.collaterals.index') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.collaterals.*') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-package text-xl mr-3'></i>
                        Collateral
                    </a>

                    <!-- Addresses -->
                    <a href="{{ route('admin.addresses.index') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.addresses.*') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-map text-xl mr-3'></i>
                        Addresses
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'text-brand bg-brand/5' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19]' }} rounded-lg transition-colors">
                        <i class='bx bx-cog text-xl mr-3'></i>
                        Settings
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] my-4"></div>

                    <!-- User Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                        <i class='bx bx-tachometer text-xl mr-3'></i>
                        User Dashboard
                    </a>
                </nav>

                <!-- User Profile Section -->
                <div class="p-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="relative">
                        <button id="adminSidebarDropdownButton" class="flex items-center w-full px-3 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] rounded-lg transition-colors">
                            <div class="w-8 h-8 bg-brand rounded-full flex items-center justify-center mr-3">
                                <span class="text-white font-medium text-sm">
                                    {{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->username ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-sm font-medium">{{ Auth::user()->full_name ?? Auth::user()->username ?? 'Administrator' }}</p>
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
                            @yield('header-content')
                        </div>
                        @yield('header-actions')
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

                    @yield('content')
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

        @stack('scripts')
    </body>
</html>
