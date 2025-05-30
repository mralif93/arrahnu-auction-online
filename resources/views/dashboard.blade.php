<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dashboard - Arrahnu Auction</title>

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
                        <a href="{{ url('/') }}" class="text-xl font-bold text-[#f53003] dark:text-[#FF4433]">
                            Arrahnu Auction
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ url('/') }}#auctions" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Auctions
                        </a>
                        <a href="{{ url('/') }}#how-it-works" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            How It Works
                        </a>
                        <a href="{{ url('/') }}#about" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
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

                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="max-w-7xl mx-auto">
                <!-- Error Messages -->
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

                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            Dashboard
                        </h1>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">
                            Welcome to your auction dashboard, {{ Auth::user()->name }}!
                        </p>
                    </div>

        <!-- Dashboard Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Stats Card 1 -->
            <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-[#f53003] dark:bg-[#FF4433] rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Active Bids</h3>
                        <p class="text-2xl font-bold text-[#f53003] dark:text-[#FF4433]">0</p>
                    </div>
                </div>
            </div>

            <!-- Stats Card 2 -->
            <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-[#f53003] dark:bg-[#FF4433] rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Won Auctions</h3>
                        <p class="text-2xl font-bold text-[#f53003] dark:text-[#FF4433]">0</p>
                    </div>
                </div>
            </div>

            <!-- Stats Card 3 -->
            <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-[#f53003] dark:bg-[#FF4433] rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Total Spent</h3>
                        <p class="text-2xl font-bold text-[#f53003] dark:text-[#FF4433]">$0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
            <h2 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Recent Activity</h2>
            <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-6">
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-center py-8">
                    No recent activity. Start bidding on auctions to see your activity here!
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <button class="flex-1 px-6 py-3 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-[#1b1b18] dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white hover:border-black dark:hover:border-white rounded-sm font-medium transition-colors">
                Browse Auctions
            </button>
            <button class="flex-1 px-6 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-sm font-medium transition-colors">
                View Profile
            </button>
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
        </script>
    </body>
</html>
