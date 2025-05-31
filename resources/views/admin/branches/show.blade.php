@extends('layouts.admin')

@section('title', 'Branch Details - ' . $branch->name)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->name }}</h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">Branch Details and Statistics</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.branches.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Branches
                </a>
                
                @if($branch->status !== 'active' && (auth()->user()->canMake() || $branch->created_by_user_id === auth()->id()))
                    <a href="{{ route('admin.branches.edit', $branch) }}" 
                       class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Branch
                    </a>
                @endif
            </div>
        </div>

        <!-- Branch Information Card -->
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] border-b border-[#e3e3e0] dark:border-[#3E3E3A] pb-2">
                            Basic Information
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Branch Name</label>
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $branch->name }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Address</label>
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->address }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Phone Number</label>
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->phone_number ?? 'Not provided' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Status</label>
                                <div class="mt-1">
                                    @if($branch->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="3"/>
                                            </svg>
                                            Active
                                        </span>
                                    @elseif($branch->status === 'pending_approval')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="3"/>
                                            </svg>
                                            Pending Approval
                                        </span>
                                    @elseif($branch->status === 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="3"/>
                                            </svg>
                                            Draft
                                        </span>
                                    @elseif($branch->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="3"/>
                                            </svg>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="3"/>
                                            </svg>
                                            {{ ucfirst($branch->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tracking Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] border-b border-[#e3e3e0] dark:border-[#3E3E3A] pb-2">
                            Tracking Information
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Created Date</label>
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->created_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Created By</label>
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->creator ? $branch->creator->full_name : 'System' }}</p>
                            </div>
                            
                            @if($branch->approvedBy)
                                <div>
                                    <label class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Approved By</label>
                                    <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->approvedBy->full_name }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <label class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Last Updated</label>
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->updated_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Total Accounts</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $statistics['total_accounts'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Active Accounts</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $statistics['active_accounts'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Total Collaterals</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $statistics['total_collaterals'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Total Value</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">RM {{ number_format($statistics['total_estimated_value'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Accounts -->
        @if($branch->accounts->count() > 0)
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="p-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Recent Accounts</h3>
                        <a href="{{ route('admin.accounts.index') }}?branch={{ $branch->id }}" 
                           class="text-sm text-brand hover:text-brand-hover">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($branch->accounts->take(5) as $account)
                            <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg">
                                <div>
                                    <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->account_title }}</h4>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $account->collaterals->count() }} collaterals</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($account->status === 'active') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200
                                        @elseif($account->status === 'pending_approval') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200
                                        @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 @endif">
                                        {{ ucfirst($account->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
