<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Arrahnu Auction - Premium Online Auctions')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

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
                .w-full { width: 100%; }
                .h-16 { height: 4rem; }
                .min-h-screen { min-height: 100vh; }
                .max-w-7xl { max-width: 80rem; margin: 0 auto; }
                .p-4 { padding: 1rem; }
                .p-6 { padding: 1.5rem; }
                .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
                .px-4 { padding-left: 1rem; padding-right: 1rem; }
                .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
                .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
                .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
                .py-12 { padding-top: 3rem; padding-bottom: 3rem; }
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
                .text-xl { font-size: 1.25rem; }
                .text-2xl { font-size: 1.5rem; }
                .font-medium { font-weight: 500; }
                .font-bold { font-weight: 700; }
                .border { border: 1px solid #d1d5db; }
                .border-b { border-bottom: 1px solid #d1d5db; }
                .border-t { border-top: 1px solid #d1d5db; }
                .rounded-lg { border-radius: 0.5rem; }
                .rounded-md { border-radius: 0.375rem; }
                .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
                .hidden { display: none; }
                .block { display: block; }
                .inline-flex { display: inline-flex; }
                .sticky { position: sticky; }
                .top-0 { top: 0; }
                .z-50 { z-index: 50; }
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
                .text-brand { color: #FE5000; }
                .bg-brand { background-color: #FE5000; }
                .bg-brand:hover { background-color: #E5470A; }
                header { background-color: white; border-bottom: 1px solid #e5e7eb; }
                nav { display: flex; align-items: center; justify-content: space-between; height: 4rem; }
                footer { background-color: #1f2937; color: white; padding: 3rem 0; }
                @media (min-width: 1024px) {
                    .lg\:flex { display: flex; }
                    .lg\:hidden { display: none; }
                    .lg\:px-8 { padding-left: 2rem; padding-right: 2rem; }
                }
                @media (max-width: 1023px) {
                    .lg\:hidden { display: block; }
                    .lg\:flex { display: none; }
                }
            </style>
        @endif

        @stack('head')
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex flex-col">
        <!-- Header Navigation -->
        <header class="w-full border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/95 dark:bg-[#161615]/95 backdrop-blur-md sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center justify-between h-16">
                    <!-- Brand Logo Section -->
                    <div class="flex items-center flex-shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                            <!-- Logo Icon -->
                            <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center group-hover:bg-brand-hover transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <!-- Brand Text -->
                            <div class="hidden sm:block">
                                <span class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-brand transition-colors">
                                    Arrahnu
                                </span>
                                <span class="text-xl font-bold text-brand">
                                    Auction
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- Primary Navigation Links -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('home') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'bg-brand/10 text-brand' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5' }}">
                            Home
                        </a>
                        <a href="{{ route('auctions.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ request()->routeIs('auctions.*') ? 'bg-brand/10 text-brand' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5' }}">
                            Auctions
                        </a>
                        <a href="{{ route('how-it-works') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ request()->routeIs('how-it-works') ? 'bg-brand/10 text-brand' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5' }}">
                            How It Works
                        </a>
                        <a href="{{ route('about') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ request()->routeIs('about') ? 'bg-brand/10 text-brand' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5' }}">
                            About
                        </a>
                    </div>

                    <!-- User Actions Section -->
                    <div class="flex items-center space-x-3">
                        @auth
                            <!-- Authenticated User Menu - Desktop Only -->
                            <div class="hidden lg:block relative">
                                <button id="userDropdownButton"
                                        class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-all duration-200 border border-transparent hover:border-[#e3e3e0] dark:hover:border-[#3E3E3A]">
                                    <!-- User Avatar -->
                                    <div class="w-6 h-6 bg-brand rounded-full flex items-center justify-center">
                                        <span class="text-xs font-semibold text-white">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <!-- User Name -->
                                    <span class="max-w-24 truncate">{{ Auth::user()->name }}</span>
                                    <!-- Dropdown Arrow -->
                                    <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- User Dropdown Menu -->
                                <div id="userDropdownMenu"
                                     class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-[#161615] rounded-xl shadow-lg border border-[#e3e3e0] dark:border-[#3E3E3A] py-2 z-50 animate-in slide-in-from-top-2 duration-200">
                                    <!-- User Info Header -->
                                    <div class="px-4 py-3 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] truncate">{{ Auth::user()->email }}</p>
                                        @if(Auth::user()->isAdmin())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-brand/10 text-brand mt-1">
                                                Administrator
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Menu Items -->
                                    <div class="py-1">
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('admin.dashboard') }}"
                                               class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                                <svg class="w-4 h-4 mr-3 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                                Admin Dashboard
                                            </a>
                                        @endif
                                        <a href="{{ route('dashboard') }}"
                                           class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                            <svg class="w-4 h-4 mr-3 text-[#706f6c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                            </svg>
                                            My Dashboard
                                        </a>
                                        <a href="{{ route('profile.edit') }}"
                                           class="flex items-center px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                            <svg class="w-4 h-4 mr-3 text-[#706f6c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Profile Settings
                                        </a>
                                    </div>

                                    <!-- Logout Section -->
                                    <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Guest User Actions - Desktop Only -->
                            <div class="hidden lg:flex items-center space-x-3">
                                <a href="{{ route('login') }}"
                                   class="px-4 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-brand transition-colors">
                                    Sign In
                                </a>
                                <a href="{{ route('register') }}"
                                   class="px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                    Get Started
                                </a>
                            </div>
                        @endauth

                        <!-- Mobile Menu Button -->
                        <button id="mobileMenuButton"
                                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </nav>

                <!-- Mobile Menu -->
                <div id="mobileMenu" class="hidden lg:hidden border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#f8f8f7] dark:bg-[#1a1a19]">
                    <div class="px-4 py-4 space-y-2">
                        <!-- Navigation Links -->
                        <div class="space-y-1">
                            <a href="{{ route('home') }}"
                               class="block px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'bg-brand/10 text-brand' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5' }}">
                                Home
                            </a>
                            <a href="{{ route('auctions.index') }}"
                               class="block px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ request()->routeIs('auctions.*') ? 'bg-brand/10 text-brand' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5' }}">
                                Auctions
                            </a>
                            <a href="{{ route('how-it-works') }}"
                               class="block px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ request()->routeIs('how-it-works') ? 'bg-brand/10 text-brand' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5' }}">
                                How It Works
                            </a>
                            <a href="{{ route('about') }}"
                               class="block px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ request()->routeIs('about') ? 'bg-brand/10 text-brand' : 'text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5' }}">
                                About
                            </a>
                        </div>

                        @auth
                            <!-- Authenticated User Mobile Menu -->
                            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-4 mt-4">
                                <!-- User Info -->
                                <div class="flex items-center space-x-3 px-3 py-2 mb-3">
                                    <div class="w-8 h-8 bg-brand rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-white">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>

                                <!-- User Menu Items -->
                                <div class="space-y-1">
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}"
                                           class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Admin Dashboard
                                        </a>
                                    @endif
                                    <a href="{{ route('dashboard') }}"
                                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                        </svg>
                                        My Dashboard
                                    </a>
                                    <a href="{{ route('profile.edit') }}"
                                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profile Settings
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="flex items-center w-full px-3 py-2 rounded-md text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Guest User Mobile Menu -->
                            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-4 mt-4 space-y-2">
                                <a href="{{ route('login') }}"
                                   class="block px-3 py-2 rounded-md text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-brand hover:bg-brand/5 transition-all duration-200">
                                    Sign In
                                </a>
                                <a href="{{ route('register') }}"
                                   class="block px-3 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-md transition-all duration-200 text-center">
                                    Get Started
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow @yield('main-class', '')">
            @yield('content')
        </main>

        <!-- Footer -->
        @hasSection('no-footer')
            <!-- No footer for this page -->
        @else
            <footer class="bg-[#1b1b18] dark:bg-[#0a0a0a] text-white py-12">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Company Info -->
                        <div class="md:col-span-2">
                            <h3 class="text-xl font-bold text-brand mb-4">Arrahnu Auction</h3>
                            <p class="text-[#A1A09A] mb-4 max-w-md">
                                Your trusted partner for premium online auctions. Discover unique items, place bids, and find treasures from the comfort of your home.
                            </p>
                            <div class="flex space-x-4">
                                <a href="#" class="text-[#A1A09A] hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-[#A1A09A] hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-[#A1A09A] hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h4 class="font-semibold text-white mb-4">Quick Links</h4>
                            <ul class="space-y-2">
                                <li><a href="{{ route('auctions.index') }}" class="text-[#A1A09A] hover:text-white transition-colors">Browse Auctions</a></li>
                                <li><a href="{{ route('how-it-works') }}" class="text-[#A1A09A] hover:text-white transition-colors">How It Works</a></li>
                                <li><a href="{{ route('about') }}" class="text-[#A1A09A] hover:text-white transition-colors">About Us</a></li>
                                @auth
                                    <li><a href="{{ route('dashboard') }}" class="text-[#A1A09A] hover:text-white transition-colors">My Dashboard</a></li>
                                @else
                                    <li><a href="{{ route('register') }}" class="text-[#A1A09A] hover:text-white transition-colors">Sign Up</a></li>
                                @endauth
                            </ul>
                        </div>

                        <!-- Support -->
                        <div>
                            <h4 class="font-semibold text-white mb-4">Support</h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-[#A1A09A] hover:text-white transition-colors">Help Center</a></li>
                                <li><a href="#" class="text-[#A1A09A] hover:text-white transition-colors">Contact Us</a></li>
                                <li><a href="#" class="text-[#A1A09A] hover:text-white transition-colors">Terms of Service</a></li>
                                <li><a href="#" class="text-[#A1A09A] hover:text-white transition-colors">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-[#3E3E3A] mt-8 pt-8 text-center">
                        <p class="text-[#A1A09A]">&copy; {{ date('Y') }} Arrahnu Auction. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        @endif

        <!-- JavaScript -->
        <script>
            // User Dropdown functionality
            const userDropdownButton = document.getElementById('userDropdownButton');
            const userDropdownMenu = document.getElementById('userDropdownMenu');

            if (userDropdownButton && userDropdownMenu) {
                // Toggle dropdown
                userDropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdownMenu.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userDropdownButton.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                        userDropdownMenu.classList.add('hidden');
                    }
                });

                // Close dropdown with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        userDropdownMenu.classList.add('hidden');
                    }
                });
            }

            // Mobile Menu functionality
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        </script>

        @stack('scripts')
    </body>
</html>
