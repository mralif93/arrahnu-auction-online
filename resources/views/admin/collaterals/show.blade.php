@extends('layouts.admin')

@section('title', 'Collateral Details - ' . ($collateral->item_name ?? $collateral->item_type))

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC]">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#706f6c]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.collaterals.index') }}" class="ml-1 text-sm font-medium text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] md:ml-2">Collaterals</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#706f6c]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] md:ml-2">{{ $collateral->item_name ?? $collateral->item_type }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->item_name ?? $collateral->item_type }}</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->category ?? 'General' }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">ID: {{ Str::limit($collateral->id, 8) }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Created {{ $collateral->created_at->format('M j, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if($collateral->status !== 'active' && (auth()->user()->canMake() || $collateral->created_by_user_id === auth()->id()))
                    <a href="{{ route('admin.collaterals.edit', $collateral) }}"
                       class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer"
                       style="background-color: #FE5000; position: relative; z-index: 10;"
                       onmouseover="this.style.backgroundColor='#E5470A'"
                       onmouseout="this.style.backgroundColor='#FE5000'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Collateral
                    </a>
                @endif
                <a href="{{ route('admin.collaterals.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Collaterals
                </a>
            </div>
        </div>

        <!-- Collateral Profile Overview -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-8">
                <div class="grid grid-cols-12 gap-8">
                    <!-- Avatar -->
                    <div class="col-span-2">
                        <div class="w-24 h-24 bg-white dark:bg-[#161615] rounded-xl shadow-lg flex items-center justify-center border-4 border-white dark:border-[#3E3E3A]">
                            <span class="text-blue-600 dark:text-blue-400 font-bold text-3xl">
                                {{ strtoupper(substr($collateral->item_name ?? $collateral->item_type, 0, 2)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Collateral Info -->
                    <div class="col-span-10">
                        <div class="flex items-center space-x-3 mb-3">
                            <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $collateral->item_name ?? $collateral->item_type }}
                            </h2>

                            <!-- Status Badge -->
                            @if($collateral->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Active
                                </span>
                            @elseif($collateral->status === 'pending_approval')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Pending Approval
                                </span>
                            @elseif($collateral->status === 'draft')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Draft
                                </span>
                            @elseif($collateral->status === 'inactive')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 001-1V9a1 1 0 00-1-1H8a1 1 0 00-1 1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Inactive
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ ucfirst(str_replace('_', ' ', $collateral->status)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Collateral Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->category ?? 'General' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">RM {{ number_format($collateral->estimated_value_rm ?? 0, 2) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->account->account_title }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($collateral->status === 'pending_approval' && auth()->user()->canApprove($collateral))
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Approval Required</h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">This collateral is waiting for approval to become active.</p>
                        </div>
                        <div class="flex space-x-3">
                            <form method="POST" action="{{ route('admin.collaterals.approve', $collateral) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors"
                                        onclick="return confirm('Approve collateral {{ $collateral->item_name ?? $collateral->item_type }}?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.collaterals.reject', $collateral) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors"
                                        onclick="return confirm('Reject collateral {{ $collateral->item_name ?? $collateral->item_type }}?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Collateral Information -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Collateral Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Item Name</label>
                            <div class="flex items-center space-x-2">
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $collateral->item_name ?? $collateral->item_type }}</p>
                            </div>
                        </div>

                        @if($collateral->description)
                            <div>
                                <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Description</label>
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->description }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Physical Properties</label>
                            <div class="space-y-2">
                                @if($collateral->weight_grams)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Weight:</span>
                                        <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($collateral->weight_grams, 2) }} grams</p>
                                    </div>
                                @endif
                                @if($collateral->purity)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Purity:</span>
                                        <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->purity }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Collateral ID</label>
                            <div class="flex items-center space-x-2">
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-mono text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">{{ $collateral->id }}</p>
                                <button onclick="copyToClipboard('{{ $collateral->id }}')" class="text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial & Status Information -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Financial & Status Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Current Status -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Collateral Status</label>
                            <div class="flex items-center space-x-3">
                                @if($collateral->status === 'active')
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $collateral->status)) }}
                                    </span>
                                @elseif($collateral->status === 'pending_approval')
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $collateral->status)) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $collateral->status)) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Financial Summary</label>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Estimated Value</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                        RM {{ number_format($collateral->estimated_value_rm ?? 0, 2) }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Starting Bid</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                        RM {{ number_format($collateral->starting_bid_rm ?? 0, 2) }}
                                    </span>
                                </div>

                                @if($collateral->current_highest_bid_rm > 0)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Highest Bid</span>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                            RM {{ number_format($collateral->current_highest_bid_rm, 2) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity & Timeline -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Collateral Activity -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Collateral Activity</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-[#e3e3e0] dark:border-[#3E3E3A] last:border-b-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Collateral Created</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Collateral registration completed</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->created_at->format('M j, Y') }}</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->created_at->format('g:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Last Updated</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Collateral modification</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->updated_at->format('M j, Y') }}</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->updated_at->format('g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Workflow & System Information -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Workflow & System Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @if($collateral->creator)
                            <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Created By</p>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->creator->full_name }} (@{{ $collateral->creator->username }})</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->created_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($collateral->approvedBy)
                            <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/10 rounded-lg border border-green-200 dark:border-green-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Approved By</p>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->approvedBy->full_name }} (@{{ $collateral->approvedBy->username }})</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Approved</p>
                                </div>
                            </div>
                        @endif

                        <!-- Account & Auction Information -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/10 rounded-lg border border-gray-200 dark:border-gray-800">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-900/20 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Account</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->account->account_title }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('admin.accounts.show', $collateral->account) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View Account</a>
                            </div>
                        </div>

                        @if($collateral->auction)
                            <div class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/10 rounded-lg border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Auction</p>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->auction->auction_title }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('admin.auctions.show', $collateral->auction) }}" class="text-xs text-purple-600 dark:text-purple-400 hover:underline">View Auction</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Section -->
        @if($collateral->images->count() > 0)
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Collateral Images</h3>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                {{ $collateral->images->count() }} {{ Str::plural('image', $collateral->images->count()) }}
                            </span>
                        </div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Click images to view full size
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @php
                        $mainImage = $collateral->images->where('is_thumbnail', true)->first();
                        $otherImages = $collateral->images->where('is_thumbnail', false);
                    @endphp

                    <!-- Main Image Display -->
                    @if($mainImage)
                        <div class="mb-6">
                            <div class="flex items-center space-x-2 mb-3">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <h4 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Main Image</h4>
                            </div>
                            <div class="flex justify-center">
                                <div class="relative group cursor-pointer w-64 h-64"
                                     onclick="openImageModal('{{ $mainImage->image_url }}', '{{ $collateral->item_name ?? $collateral->item_type }} - Main Image')">
                                    <div class="w-full h-full overflow-hidden rounded-lg border-2 border-blue-200 dark:border-blue-800 shadow-md">
                                        <img src="{{ $mainImage->image_url }}"
                                             alt="{{ $collateral->item_name ?? $collateral->item_type }} - Main Image"
                                             class="w-full h-full object-cover transition-all duration-300 group-hover:scale-105">
                                    </div>

                                    <!-- Main Image Badge -->
                                    <div class="absolute top-2 left-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-600 text-white shadow-md">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Main
                                        </span>
                                    </div>

                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-25 transition-all duration-300 rounded-lg flex items-center justify-center">
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="bg-white bg-opacity-90 dark:bg-gray-900 dark:bg-opacity-90 rounded-full p-2 shadow-md">
                                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Click Hint -->
                                    <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                            Click to enlarge
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Images -->
                    @if($otherImages->count() > 0)
                        <div>
                            <div class="flex items-center space-x-2 mb-4">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <h4 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Additional Images</h4>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200">
                                    {{ $otherImages->count() }} {{ Str::plural('image', $otherImages->count()) }}
                                </span>
                            </div>
                            <div class="flex justify-center">
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3 max-w-4xl">
                                    @foreach($otherImages as $image)
                                        <div class="relative group cursor-pointer"
                                             onclick="openImageModal('{{ $image->image_url }}', '{{ $collateral->item_name ?? $collateral->item_type }} - Image {{ $loop->iteration + 1 }}')">
                                            <div class="w-20 h-20 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-600 transition-colors duration-200 shadow-sm hover:shadow-md">
                                                <img src="{{ $image->image_url }}"
                                                     alt="{{ $collateral->item_name ?? $collateral->item_type }} - Image {{ $loop->iteration + 1 }}"
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                            </div>

                                            <!-- Image Number -->
                                            <div class="absolute top-1 left-1">
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-medium bg-gray-900 bg-opacity-75 text-white">
                                                    {{ $loop->iteration + 1 }}
                                                </span>
                                            </div>

                                            <!-- Hover Overlay -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-25 transition-all duration-300 rounded-lg flex items-center justify-center">
                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <div class="bg-white bg-opacity-90 dark:bg-gray-900 dark:bg-opacity-90 rounded-full p-1">
                                                        <svg class="w-3 h-3 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Image Guidelines -->
                    <div class="mt-6 p-3 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h5 class="text-sm font-medium text-blue-900 dark:text-blue-100">Image Viewing Tips</h5>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-blue-800 dark:text-blue-200">
                            <div>• Click any image to view full size</div>
                            <div>• Use arrow keys to navigate</div>
                            <div>• Main image has blue border</div>
                            <div>• Press ESC to close viewer</div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="p-8 text-center">
                    <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">No Images Available</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4 max-w-md mx-auto">This collateral doesn't have any images yet. Images help provide visual documentation of the item.</p>
                    @if($collateral->status !== 'active' && (auth()->user()->canMake() || $collateral->created_by_user_id === auth()->id()))
                        <a href="{{ route('admin.collaterals.edit', $collateral) }}"
                           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer"
                           style="background-color: #FE5000; text-decoration: none;"
                           onmouseover="this.style.backgroundColor='#E5470A'"
                           onmouseout="this.style.backgroundColor='#FE5000'">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Images
                        </a>
                    @endif
                </div>
            </div>
        @endif

    </div>

    <!-- JavaScript for Copy Functionality -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const button = event.target.closest('button');
                const originalContent = button.innerHTML;
                button.innerHTML = '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';

                setTimeout(() => {
                    button.innerHTML = originalContent;
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>

    <!-- Enhanced Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
        <div class="relative w-full h-full flex items-center justify-center p-4">
            <!-- Close Button -->
            <button onclick="closeImageModal()"
                    class="absolute top-4 right-4 z-20 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Previous Button -->
            <button id="prevImageBtn" onclick="previousImage()"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-20 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-3 transition-all duration-200 hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Next Button -->
            <button id="nextImageBtn" onclick="nextImage()"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-20 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-3 transition-all duration-200 hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Image Container -->
            <div class="relative max-w-5xl max-h-full">
                <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">

                <!-- Image Info -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6">
                    <div id="modalTitle" class="text-white text-lg font-medium mb-2"></div>
                    <div class="flex items-center justify-between text-white text-sm">
                        <div id="imageCounter" class="bg-black bg-opacity-50 px-3 py-1 rounded-full"></div>
                        <div class="flex items-center space-x-4">
                            <button onclick="downloadImage()" class="flex items-center space-x-1 bg-black bg-opacity-50 hover:bg-opacity-75 px-3 py-1 rounded-full transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Download</span>
                            </button>
                            <button onclick="toggleFullscreen()" class="flex items-center space-x-1 bg-black bg-opacity-50 hover:bg-opacity-75 px-3 py-1 rounded-full transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                                <span>Fullscreen</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="imageLoading" class="absolute inset-0 flex items-center justify-center hidden">
                <div class="bg-black bg-opacity-50 rounded-lg p-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Image modal functionality
let currentImageIndex = 0;
let imageList = [];

// Initialize image list
document.addEventListener('DOMContentLoaded', function() {
    // Collect all images
    imageList = [];

    @if($collateral->images->count() > 0)
        @php
            $mainImage = $collateral->images->where('is_thumbnail', true)->first();
            $otherImages = $collateral->images->where('is_thumbnail', false);
        @endphp

        @if($mainImage)
            imageList.push({
                url: '{{ $mainImage->image_url }}',
                title: '{{ $collateral->item_name ?? $collateral->item_type }} - Main Image',
                isMain: true
            });
        @endif

        @foreach($otherImages as $image)
            imageList.push({
                url: '{{ $image->image_url }}',
                title: '{{ $collateral->item_name ?? $collateral->item_type }} - Image {{ $loop->iteration + 1 }}',
                isMain: false
            });
        @endforeach
    @endif
});

function openImageModal(imageUrl, title) {
    // Find the index of the clicked image
    currentImageIndex = imageList.findIndex(img => img.url === imageUrl);
    if (currentImageIndex === -1) currentImageIndex = 0;

    showImage(currentImageIndex);
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Show navigation buttons if there are multiple images
    updateNavigationButtons();
}

function showImage(index) {
    if (index < 0 || index >= imageList.length) return;

    const image = imageList[index];
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const imageCounter = document.getElementById('imageCounter');
    const loading = document.getElementById('imageLoading');

    // Show loading
    loading.classList.remove('hidden');
    modalImage.style.opacity = '0';

    // Update image
    modalImage.onload = function() {
        loading.classList.add('hidden');
        modalImage.style.opacity = '1';
    };

    modalImage.src = image.url;
    modalImage.alt = image.title;
    modalTitle.textContent = image.title;
    imageCounter.textContent = `${index + 1} of ${imageList.length}`;

    currentImageIndex = index;
}

function previousImage() {
    if (currentImageIndex > 0) {
        showImage(currentImageIndex - 1);
        updateNavigationButtons();
    }
}

function nextImage() {
    if (currentImageIndex < imageList.length - 1) {
        showImage(currentImageIndex + 1);
        updateNavigationButtons();
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevImageBtn');
    const nextBtn = document.getElementById('nextImageBtn');

    if (imageList.length > 1) {
        prevBtn.classList.remove('hidden');
        nextBtn.classList.remove('hidden');

        // Update button states
        prevBtn.style.opacity = currentImageIndex > 0 ? '1' : '0.5';
        nextBtn.style.opacity = currentImageIndex < imageList.length - 1 ? '1' : '0.5';
    } else {
        prevBtn.classList.add('hidden');
        nextBtn.classList.add('hidden');
    }
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';

    // Exit fullscreen if active
    if (document.fullscreenElement) {
        document.exitFullscreen();
    }
}

function downloadImage() {
    const currentImage = imageList[currentImageIndex];
    if (!currentImage) return;

    const link = document.createElement('a');
    link.href = currentImage.url;
    link.download = currentImage.title.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.jpg';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function toggleFullscreen() {
    const modal = document.getElementById('imageModal');

    if (!document.fullscreenElement) {
        modal.requestFullscreen().catch(err => {
            console.log('Error attempting to enable fullscreen:', err);
        });
    } else {
        document.exitFullscreen();
    }
}

// Event listeners
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('imageModal');
    if (!modal.classList.contains('hidden')) {
        switch(e.key) {
            case 'Escape':
                closeImageModal();
                break;
            case 'ArrowLeft':
                e.preventDefault();
                previousImage();
                break;
            case 'ArrowRight':
                e.preventDefault();
                nextImage();
                break;
            case 'f':
            case 'F':
                e.preventDefault();
                toggleFullscreen();
                break;
            case 'd':
            case 'D':
                e.preventDefault();
                downloadImage();
                break;
        }
    }
});

// Touch/swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

document.getElementById('imageModal').addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.getElementById('imageModal').addEventListener('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            // Swipe left - next image
            nextImage();
        } else {
            // Swipe right - previous image
            previousImage();
        }
    }
}

// Prevent image dragging
document.getElementById('modalImage').addEventListener('dragstart', function(e) {
    e.preventDefault();
});
</script>
@endpush
