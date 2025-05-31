@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Admin Dashboard</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Welcome back, {{ Auth::user()->full_name ?? 'Administrator' }}</p>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-4">
        <div class="text-right">
            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ now()->format('l, F j, Y') }}</div>
            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ now()->format('g:i A') }}</div>
        </div>
    </div>
@endsection

@section('content')

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Revenue -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Revenue</p>
                                <p class="text-3xl font-bold text-brand" data-metric="revenue">RM {{ number_format($stats['total_revenue'], 2) }}</p>
                                <p class="text-sm text-green-600 dark:text-green-400">From completed auctions</p>
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
                                <p class="text-3xl font-bold text-brand">{{ $stats['active_auctions'] }}</p>
                                <p class="text-sm text-blue-600 dark:text-blue-400">{{ $stats['auctions_ending_today'] }} ending today</p>
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
                                <p class="text-3xl font-bold text-brand">{{ number_format($stats['total_users']) }}</p>
                                <p class="text-sm text-green-600 dark:text-green-400">{{ $stats['new_registrations'] }} new this month</p>
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
                                <p class="text-3xl font-bold text-brand">RM {{ number_format($stats['commission_earned'], 2) }}</p>
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
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($stats['active_bidders']) }}</span>
                            </div>
                            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-2">
                                <div class="bg-brand h-2 rounded-full" style="width: {{ min(100, ($stats['active_bidders'] / max($stats['total_users'], 1)) * 100) }}%"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">New Registrations (30 days)</span>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($stats['new_registrations']) }}</span>
                            </div>
                            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-2">
                                <div class="bg-brand h-2 rounded-full" style="width: {{ min(100, ($stats['new_registrations'] / max($stats['total_users'], 1)) * 100) }}%"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Pending Verifications</span>
                                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($stats['pending_verifications']) }}</span>
                            </div>
                            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ min(100, ($stats['pending_verifications'] / max($stats['total_users'], 1)) * 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Sections -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Recent Auctions -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Active Auctions</h3>
                            <a href="{{ route('admin.auctions.index') }}" class="text-sm text-brand hover:text-brand-hover">View All</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($activeAuctions as $auction)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ Str::limit($auction['title'], 30) }}</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current: RM {{ number_format($auction['current_bid'], 2) }} • {{ $auction['bid_count'] }} bids</p>
                                    </div>
                                    <span class="text-xs {{ $auction['status'] === 'Live' ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">{{ $auction['status'] }}</span>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">No active auctions</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- User Management -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Pending Approvals</h3>
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-brand hover:text-brand-hover">Manage</a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-[#fff2f2] dark:bg-[#1D0002] rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Pending Users</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">User applications</p>
                                </div>
                                <span class="text-lg font-bold text-brand">{{ $pendingApprovals['users'] }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Pending Branches</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Branch applications</p>
                                </div>
                                <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingApprovals['branches'] }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Pending Accounts</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Account applications</p>
                                </div>
                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $pendingApprovals['accounts'] }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Scheduled Auctions</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Auction events</p>
                                </div>
                                <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $pendingApprovals['auctions'] ?? 0 }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Pending Collaterals</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Collateral applications</p>
                                </div>
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $pendingApprovals['collaterals'] }}</span>
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
                        <a href="{{ route('admin.users.index') }}" class="quick-action-btn flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Users</span>
                        </a>

                        <!-- Manage Branches -->
                        <a href="{{ route('admin.branches.index') }}" class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Branches</span>
                        </a>

                        <!-- Manage Accounts -->
                        <a href="{{ route('admin.accounts.index') }}" class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Accounts</span>
                        </a>

                        <!-- Manage Collaterals -->
                        <a href="{{ route('admin.collaterals.index') }}" class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Collaterals</span>
                        </a>

                        <!-- Manage Auctions -->
                        <a href="{{ route('admin.auctions.index') }}" class="flex flex-col items-center p-4 bg-brand/5 hover:bg-brand/10 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-brand/10 group-hover:bg-brand/20 rounded-lg flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Manage Auctions</span>
                        </a>



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
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start space-x-4 p-4 bg-{{ $activity['color'] }}-50 dark:bg-{{ $activity['color'] }}-900/20 rounded-lg">
                                <div class="w-8 h-8 bg-{{ $activity['color'] }}-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    @if($activity['type'] === 'create')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    @elseif($activity['type'] === 'update')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $activity['description'] }}</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $activity['time'] }} by {{ $activity['user'] }}</p>
                                </div>
                                <span class="text-xs text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400 bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/20 px-2 py-1 rounded-full">{{ $activity['status'] }}</span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">No recent activity</p>
                            </div>
                        @endforelse
                    </div>
                </div>
@endsection

@push('scripts')
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
</script>
@endpush
