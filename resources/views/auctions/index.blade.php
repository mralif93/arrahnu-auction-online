<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Live Auctions - Arrahnu Auction</title>

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
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('auctions.index') }}" class="text-brand font-medium">
                            Auctions
                        </a>
                        <a href="{{ route('how-it-works') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            How It Works
                        </a>
                        <a href="{{ route('about') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            About
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-4">
                        @guest
                            <a
                                href="{{ route('login') }}"
                                class="inline-block px-5 py-2 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm font-medium transition-colors">
                                Log in
                            </a>
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-2 bg-brand bg-brand-hover text-white rounded-sm text-sm font-medium transition-colors">
                                Get Started
                            </a>
                        @else
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

                                        <a href="{{ route('auctions.index') }}" class="flex items-center px-4 py-2 text-sm text-brand bg-brand/5 hover:bg-brand/10 transition-colors">
                                            <svg class="w-4 h-4 mr-3 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            Browse Auctions
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
                        @endguest
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
                        Live Auctions
                    </h1>
                    <p class="text-xl text-[#706f6c] dark:text-[#A1A09A] max-w-3xl">
                        Discover authentic collectibles, luxury items, and rare treasures from verified sellers worldwide.
                    </p>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Search Auctions
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="search"
                                    placeholder="Search by title, description, or category..."
                                    class="w-full pl-10 pr-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-colors"
                                >
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Category
                            </label>
                            <select id="category" class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-colors">
                                <option value="">All Categories</option>
                                <option value="watches">Watches</option>
                                <option value="jewelry">Jewelry</option>
                                <option value="art">Art & Collectibles</option>
                                <option value="vintage">Vintage Items</option>
                                <option value="luxury">Luxury Goods</option>
                                <option value="coins">Coins & Currency</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Sort By
                            </label>
                            <select id="sort" class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-colors">
                                <option value="ending_soon">Ending Soon</option>
                                <option value="newest">Newest First</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="most_bids">Most Bids</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-brand mb-1">127</div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Live Auctions</div>
                    </div>
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-brand mb-1">2,847</div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Active Bidders</div>
                    </div>
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-brand mb-1">$1.2M</div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Total Value</div>
                    </div>
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-brand mb-1">24</div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Ending Today</div>
                    </div>
                </div>

                <!-- Auction Grid -->
                <div class="auction-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Auction Item 1 -->
                    <div class="auction-card bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow" data-category="watches" data-price="15750" data-bids="23" data-time-left="135">
                        <!-- Image -->
                        <div class="aspect-square bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center">
                            <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-brand bg-brand/10 px-2 py-1 rounded-full">
                                    Live Auction
                                </span>
                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Ends in 2h 15m
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="auction-title font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Vintage Rolex Submariner 1965
                            </h3>

                            <!-- Description -->
                            <p class="auction-description text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                Rare vintage timepiece in excellent condition with original box and papers.
                            </p>

                            <!-- Bid Info -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current Bid</p>
                                    <p class="text-xl font-bold text-brand">$15,750</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Bids</p>
                                    <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">23</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button class="w-full px-4 py-3 bg-brand bg-brand-hover text-white font-medium rounded-lg transition-colors">
                                Place Bid
                            </button>
                        </div>
                    </div>

                    <!-- Auction Item 2 -->
                    <div class="auction-card bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow" data-category="jewelry" data-price="8250" data-bids="17" data-time-left="342">
                        <!-- Image -->
                        <div class="aspect-square bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center">
                            <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-brand bg-brand/10 px-2 py-1 rounded-full">
                                    Live Auction
                                </span>
                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Ends in 5h 42m
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="auction-title font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Diamond Tennis Bracelet
                            </h3>

                            <!-- Description -->
                            <p class="auction-description text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                18K white gold bracelet with 5.2 carats of VS1 diamonds, certified by GIA.
                            </p>

                            <!-- Bid Info -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current Bid</p>
                                    <p class="text-xl font-bold text-brand">$8,250</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Bids</p>
                                    <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">17</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button class="w-full px-4 py-3 bg-brand bg-brand-hover text-white font-medium rounded-lg transition-colors">
                                Place Bid
                            </button>
                        </div>
                    </div>

                    <!-- Auction Item 3 -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Image -->
                        <div class="aspect-square bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center">
                            <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-brand bg-brand/10 px-2 py-1 rounded-full">
                                    Live Auction
                                </span>
                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Ends in 1d 3h
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Original Picasso Sketch
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                Authenticated pencil sketch from 1962, provenance documented and verified.
                            </p>

                            <!-- Bid Info -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current Bid</p>
                                    <p class="text-xl font-bold text-brand">$45,000</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Bids</p>
                                    <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">8</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button class="w-full px-4 py-3 bg-brand bg-brand-hover text-white font-medium rounded-lg transition-colors">
                                Place Bid
                            </button>
                        </div>
                    </div>

                    <!-- Auction Item 4 -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Image -->
                        <div class="aspect-square bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center">
                            <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-orange-600 bg-orange-100 dark:bg-orange-900/20 px-2 py-1 rounded-full">
                                    Ending Soon
                                </span>
                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Ends in 45m
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                1909 S VDB Lincoln Cent
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                Extremely rare coin in MS-65 condition, professionally graded by PCGS.
                            </p>

                            <!-- Bid Info -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current Bid</p>
                                    <p class="text-xl font-bold text-brand">$3,200</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Bids</p>
                                    <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">31</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button class="w-full px-4 py-3 bg-brand bg-brand-hover text-white font-medium rounded-lg transition-colors">
                                Place Bid
                            </button>
                        </div>
                    </div>

                    <!-- Auction Item 5 -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Image -->
                        <div class="aspect-square bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center">
                            <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-brand bg-brand/10 px-2 py-1 rounded-full">
                                    Live Auction
                                </span>
                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Ends in 6h 20m
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Hermès Birkin Bag 35cm
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                Togo leather in Etoupe color with palladium hardware, excellent condition.
                            </p>

                            <!-- Bid Info -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current Bid</p>
                                    <p class="text-xl font-bold text-brand">$12,500</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Bids</p>
                                    <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">14</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button class="w-full px-4 py-3 bg-brand bg-brand-hover text-white font-medium rounded-lg transition-colors">
                                Place Bid
                            </button>
                        </div>
                    </div>

                    <!-- Auction Item 6 -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Image -->
                        <div class="aspect-square bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center">
                            <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-brand bg-brand/10 px-2 py-1 rounded-full">
                                    Live Auction
                                </span>
                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Ends in 2d 8h
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Tiffany Studios Lamp
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                Authentic Dragonfly pattern table lamp, circa 1905, signed base.
                            </p>

                            <!-- Bid Info -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current Bid</p>
                                    <p class="text-xl font-bold text-brand">$85,000</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Bids</p>
                                    <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">6</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button class="w-full px-4 py-3 bg-brand bg-brand-hover text-white font-medium rounded-lg transition-colors">
                                Place Bid
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Showing 1-6 of 127 auctions
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-sm hover:border-brand hover:text-brand transition-colors">
                            Previous
                        </button>
                        <button class="px-3 py-2 bg-brand text-white rounded-sm">1</button>
                        <button class="px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-sm hover:border-brand hover:text-brand transition-colors">2</button>
                        <button class="px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-sm hover:border-brand hover:text-brand transition-colors">3</button>
                        <span class="px-3 py-2 text-[#706f6c] dark:text-[#A1A09A]">...</span>
                        <button class="px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-sm hover:border-brand hover:text-brand transition-colors">22</button>
                        <button class="px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-sm hover:border-brand hover:text-brand transition-colors">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="sticky-footer bg-[#1b1b18] dark:bg-[#0a0a0a] text-white py-16">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div class="md:col-span-2">
                        <h3 class="text-xl font-bold text-brand mb-4">
                            Arrahnu Auction
                        </h3>
                        <p class="text-[#A1A09A] mb-6 max-w-md">
                            The world's premier online auction platform for authentic collectibles,
                            luxury items, and rare treasures. Trusted by collectors worldwide.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-[#A1A09A]">
                            <li><a href="{{ route('auctions.index') }}" class="hover:text-white transition-colors">Browse Auctions</a></li>
                            <li><a href="{{ route('how-it-works') }}" class="hover:text-white transition-colors">How It Works</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h4 class="font-semibold mb-4">Legal</h4>
                        <ul class="space-y-2 text-[#A1A09A]">
                            <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-[#3E3E3A] mt-12 pt-8 text-center text-[#A1A09A]">
                    <p>&copy; {{ date('Y') }} Arrahnu Auction. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- JavaScript for Interactive Features -->
        <script>
            // Search functionality
            document.getElementById('search').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const auctionCards = document.querySelectorAll('.auction-card');

                auctionCards.forEach(card => {
                    const title = card.querySelector('.auction-title').textContent.toLowerCase();
                    const description = card.querySelector('.auction-description').textContent.toLowerCase();

                    if (title.includes(searchTerm) || description.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Category filter
            document.getElementById('category').addEventListener('change', function(e) {
                const selectedCategory = e.target.value;
                const auctionCards = document.querySelectorAll('.auction-card');

                auctionCards.forEach(card => {
                    const category = card.dataset.category;

                    if (selectedCategory === '' || category === selectedCategory) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Sort functionality
            document.getElementById('sort').addEventListener('change', function(e) {
                const sortBy = e.target.value;
                const auctionGrid = document.querySelector('.auction-grid');
                const auctionCards = Array.from(document.querySelectorAll('.auction-card'));

                auctionCards.sort((a, b) => {
                    switch(sortBy) {
                        case 'price_low':
                            return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                        case 'price_high':
                            return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                        case 'most_bids':
                            return parseInt(b.dataset.bids) - parseInt(a.dataset.bids);
                        case 'ending_soon':
                            return parseInt(a.dataset.timeLeft) - parseInt(b.dataset.timeLeft);
                        default:
                            return 0;
                    }
                });

                // Re-append sorted cards
                auctionCards.forEach(card => auctionGrid.appendChild(card));
            });

            // Countdown timers (demo)
            function updateCountdowns() {
                const countdowns = document.querySelectorAll('.countdown');
                countdowns.forEach(countdown => {
                    // This is a demo - in real app, you'd calculate from actual end time
                    const currentText = countdown.textContent;
                    if (currentText.includes('45m')) {
                        countdown.textContent = 'Ends in 44m';
                    }
                });
            }

            // Update countdowns every minute
            setInterval(updateCountdowns, 60000);

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
        </script>
    </body>
</html>
