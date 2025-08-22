@extends('layouts.app')

@section('title', 'Dashboard - Arrahnu Auction')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

    <!-- Success Messages -->
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

    <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                My Dashboard
            </h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A] text-lg">
                Welcome back, {{ Auth::user()->name }}!
            </p>
        </div>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Active Bids Card -->
            <div class="bg-gradient-to-br from-brand/5 to-brand/10 border border-brand/20 rounded-xl p-6 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-brand rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wide">Active Bids</h3>
                        <p class="text-3xl font-bold text-brand mt-1">0</p>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">Currently bidding</p>
                    </div>
                </div>
            </div>

            <!-- Won Auctions Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wide">Won Auctions</h3>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">0</p>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">Successfully won</p>
                    </div>
                </div>
            </div>

            <!-- Total Spent Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wide">Total Spent</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">$0</p>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">All time spending</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Activity -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Recent Activity</h2>
                    <a href="{{ route('auctions.index') }}" class="text-sm text-brand hover:text-brand-hover transition-colors">
                        View All Auctions →
                    </a>
                </div>
                <div class="bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-[#706f6c] dark:text-[#A1A09A] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
                            No recent activity yet
                        </p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Start bidding on auctions to see your activity here!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div>
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Quick Actions</h2>
                <div class="space-y-4">
                    <a href="{{ route('auctions.index') }}"
                       class="flex items-center justify-between p-4 bg-gradient-to-r from-brand to-brand-hover text-white rounded-xl hover:shadow-lg transition-all duration-200 group">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium">Browse Auctions</h3>
                                <p class="text-sm text-white/80">Discover amazing items</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center justify-between p-4 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl hover:border-brand/30 hover:shadow-md transition-all duration-200 group">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Profile Settings</h3>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage your account</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 group">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <div>
                                    <h3 class="font-medium">Admin Dashboard</h3>
                                    <p class="text-sm text-white/80">Manage the system</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
