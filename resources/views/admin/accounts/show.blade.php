@extends('layouts.admin')

@section('title', 'Account Details')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Account Details</h1>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">View account information and collaterals</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.accounts.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Accounts
                </a>
                
                @if($account->status === 'draft' || (auth()->user()->isAdmin() && $account->status !== 'active'))
                    <a href="{{ route('admin.accounts.edit', $account) }}" 
                       class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Account
                    </a>
                @endif
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white dark:bg-[#2A2A27] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->account_title }}</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Account ID: {{ Str::limit($account->id, 8) }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Status Badge -->
                        @if($account->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Active
                            </span>
                        @elseif($account->status === 'pending_approval')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Pending Approval
                            </span>
                        @elseif($account->status === 'draft')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-900/20 text-gray-800 dark:text-gray-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Draft
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                {{ ucfirst($account->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] border-b border-[#e3e3e0] dark:border-[#3E3E3A] pb-2">
                            Basic Information
                        </h4>
                        
                        <div>
                            <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Branch</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->branch->name }}</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $account->branch->address }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Account Title</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->account_title }}</p>
                        </div>

                        @if($account->description)
                            <div>
                                <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Description</label>
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Status Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] border-b border-[#e3e3e0] dark:border-[#3E3E3A] pb-2">
                            Status Information
                        </h4>
                        
                        <div>
                            <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Created</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->created_at->format('M d, Y \a\t g:i A') }}</p>
                            @if($account->creator)
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">By: {{ $account->creator->full_name }}</p>
                            @endif
                        </div>

                        @if($account->approvedBy)
                            <div>
                                <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Approved</label>
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                <p class="text-xs text-green-600">By: {{ $account->approvedBy->full_name }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Collaterals</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->collaterals->count() }} items</p>
                        </div>

                        @if($account->collaterals->count() > 0)
                            <div>
                                <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Estimated Value</label>
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">RM {{ number_format($account->collaterals->sum('estimated_value_rm'), 2) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Collaterals Section -->
        @if($account->collaterals->count() > 0)
            <div class="bg-white dark:bg-[#2A2A27] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Collaterals</h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Items associated with this account</p>
                        </div>
                        <a href="{{ route('admin.accounts.collaterals', $account) }}" 
                           class="text-sm text-brand hover:text-brand-hover transition-colors">
                            View All Collaterals →
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($account->collaterals->take(6) as $collateral)
                            <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <div class="w-12 h-12 bg-[#f8f8f6] dark:bg-[#3E3E3A] rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] truncate">
                                            {{ $collateral->item_name }}
                                        </p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                            {{ $collateral->category }}
                                        </p>
                                        <p class="text-sm font-medium text-brand mt-1">
                                            RM {{ number_format($collateral->estimated_value_rm, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($account->collaterals->count() > 6)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.accounts.collaterals', $account) }}" 
                               class="text-sm text-brand hover:text-brand-hover transition-colors">
                                View {{ $account->collaterals->count() - 6 }} more collaterals →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-[#2A2A27] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No collaterals</h3>
                    <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">This account doesn't have any collaterals yet.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
