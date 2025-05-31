@extends('layouts.app')

@section('title', 'Arrahnu Auction - Premium Online Auctions')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-[#FDFDFC] via-[#fff2f2] to-[#FDFDFC] dark:from-[#0a0a0a] dark:via-[#1D0002] dark:to-[#0a0a0a] py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl lg:text-6xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 leading-tight">
                        Discover Rare
                        <span class="text-brand">Treasures</span>
                        at Premium Auctions
                    </h1>
                    <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-8 max-w-xl mx-auto lg:mx-0">
                        Join thousands of collectors and enthusiasts in our exclusive online auction platform.
                        Bid on authentic artifacts, luxury items, and unique collectibles from around the world.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand hover:bg-brand-hover text-white font-semibold rounded-lg transition-colors shadow-lg">
                                Start Bidding Today
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('auctions.index') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] font-semibold rounded-lg hover:border-brand hover:text-brand transition-colors">
                            Browse Auctions
                        </a>
                    </div>
                </div>

                <!-- Hero Image/Illustration -->
                <div class="relative">
                    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-2xl p-8 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <!-- Auction Item Preview -->
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-brand bg-brand/10 px-3 py-1 rounded-full">
                                    Live Auction
                                </span>
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Ends in 2h 15m
                                </span>
                            </div>

                            <div class="aspect-square bg-gradient-to-br from-brand/10 to-brand/5 rounded-xl flex items-center justify-center">
                                <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    Vintage Gold Jewelry
                                </h3>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                    Rare collection in excellent condition
                                </p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current Bid</p>
                                        <p class="text-xl font-bold text-brand">RM 15,750</p>
                                    </div>
                                    <button class="px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                                        Place Bid
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="how-it-works" class="py-20 bg-white dark:bg-[#161615]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    How It Works
                </h2>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                    Getting started with Arrahnu Auction is simple. Follow these easy steps to begin your collecting journey.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Create Account
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Sign up for free and verify your identity to start participating in our exclusive auctions.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Browse & Research
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Explore our curated collection of authentic items with detailed descriptions and provenance.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Bid & Win
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Place your bids in real-time and compete with collectors worldwide for unique treasures.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-[#fff2f2] dark:bg-[#1D0002]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-3xl lg:text-4xl font-bold text-brand mb-2">
                        10K+
                    </div>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Active Bidders</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl lg:text-4xl font-bold text-brand mb-2">
                        500+
                    </div>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Monthly Auctions</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl lg:text-4xl font-bold text-brand mb-2">
                        RM 2M+
                    </div>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Items Sold</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl lg:text-4xl font-bold text-brand mb-2">
                        99%
                    </div>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Satisfaction Rate</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="auctions" class="py-20 bg-white dark:bg-[#161615]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Ready to Start Your Collection?
            </h2>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-8 max-w-2xl mx-auto">
                Join thousands of collectors who trust Arrahnu Auction for authentic, high-quality items.
                Start bidding today and discover your next treasure.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand hover:bg-brand-hover text-white font-semibold rounded-lg transition-colors shadow-lg">
                        Create Free Account
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] font-semibold rounded-lg hover:border-brand hover:text-brand transition-colors">
                        Sign In
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection
