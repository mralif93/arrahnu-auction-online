@extends('layouts.app')

@section('title', 'How It Works - Arrahnu Auction')

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl lg:text-5xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                How It Works
            </h1>
            <p class="text-xl text-[#706f6c] dark:text-[#A1A09A] max-w-3xl mx-auto">
                Discover how easy it is to buy and sell authentic collectibles, luxury items, and rare treasures on Arrahnu Auction.
            </p>
        </div>

        <!-- Process Steps -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-brand rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-white">1</span>
                </div>
                <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Browse & Discover
                </h3>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    Explore our curated collection of authentic items. Use filters to find exactly what you're looking for, from vintage collectibles to luxury goods.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-brand rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-white">2</span>
                </div>
                <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Place Your Bid
                </h3>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    Found something you love? Place your bid and watch the auction unfold. Set maximum bids to automatically compete even when you're away.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-brand rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-white">3</span>
                </div>
                <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Win & Collect
                </h3>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    Win the auction and complete your purchase securely. We'll handle shipping and ensure your item arrives safely at your door.
                </p>
            </div>
        </div>

        <!-- Detailed Sections -->
        <div class="space-y-16">
            <!-- For Buyers -->
            <section class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-8 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-8 text-center">
                    For Buyers
                </h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                            🔍 Discovery & Research
                        </h3>
                        <ul class="space-y-2 text-[#706f6c] dark:text-[#A1A09A]">
                            <li>• Browse categories and featured auctions</li>
                            <li>• View detailed item descriptions and photos</li>
                            <li>• Check authenticity certificates</li>
                            <li>• Review seller ratings and history</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                            💰 Bidding & Winning
                        </h3>
                        <ul class="space-y-2 text-[#706f6c] dark:text-[#A1A09A]">
                            <li>• Place manual bids or set maximum amounts</li>
                            <li>• Receive real-time notifications</li>
                            <li>• Track your bidding activity</li>
                            <li>• Secure payment processing</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- For Sellers -->
            <section class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-8 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-8 text-center">
                    For Sellers
                </h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                            📸 List Your Items
                        </h3>
                        <ul class="space-y-2 text-[#706f6c] dark:text-[#A1A09A]">
                            <li>• Upload high-quality photos</li>
                            <li>• Write detailed descriptions</li>
                            <li>• Set starting prices and reserves</li>
                            <li>• Choose auction duration</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                            🚀 Manage & Sell
                        </h3>
                        <ul class="space-y-2 text-[#706f6c] dark:text-[#A1A09A]">
                            <li>• Monitor bidding activity</li>
                            <li>• Communicate with bidders</li>
                            <li>• Receive payments securely</li>
                            <li>• Ship with tracking</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Safety & Security -->
            <section class="bg-[#fff2f2] dark:bg-[#1D0002] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-8">
                <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-8 text-center">
                    🛡️ Safety & Security
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Verified Items</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">All items are authenticated by our experts</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Secure Payments</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Protected transactions with escrow service</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Trusted Community</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Verified users with rating system</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">24/7 Support</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Round-the-clock customer assistance</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- CTA Section -->
        <section class="text-center py-16">
            <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Ready to Get Started?
            </h2>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-8 max-w-2xl mx-auto">
                Join thousands of collectors and dealers who trust Arrahnu Auction for their buying and selling needs.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand hover:bg-brand-hover text-white font-semibold rounded-lg transition-colors shadow-lg">
                        Create Free Account
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="{{ route('auctions.index') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] font-semibold rounded-lg hover:border-brand hover:text-brand transition-colors">
                        Browse Auctions
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand hover:bg-brand-hover text-white font-semibold rounded-lg transition-colors shadow-lg">
                        Go to Dashboard
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                @endguest
            </div>
        </section>
    </div>
@endsection
