@extends('layouts.admin')

@section('title', 'Branch Details - ' . $branch->name)

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
                        <a href="{{ route('admin.branches.index') }}" class="ml-1 text-sm font-medium text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] md:ml-2">Branches</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#706f6c]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] md:ml-2">{{ $branch->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->name }}</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $branch->accounts->count() }} accounts</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">ID: {{ Str::limit($branch->id, 8) }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Created {{ $branch->created_at->format('M j, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if($branch->status !== 'active' && (auth()->user()->canMake() || $branch->created_by_user_id === auth()->id()))
                    <a href="{{ route('admin.branches.edit', $branch) }}"
                       class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer"
                       style="background-color: #FE5000; position: relative; z-index: 10;"
                       onmouseover="this.style.backgroundColor='#E5470A'"
                       onmouseout="this.style.backgroundColor='#FE5000'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Branch
                    </a>
                @endif
                <a href="{{ route('admin.branches.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Branches
                </a>
            </div>
        </div>

        <!-- Branch Profile Overview -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-8">
                <div class="grid grid-cols-12 gap-8">
                    <!-- Avatar -->
                    <div class="col-span-2">
                        <div class="w-24 h-24 bg-white dark:bg-[#161615] rounded-xl shadow-lg flex items-center justify-center border-4 border-white dark:border-[#3E3E3A]">
                            <span class="text-blue-600 dark:text-blue-400 font-bold text-3xl">
                                {{ strtoupper(substr($branch->name, 0, 2)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Branch Info -->
                    <div class="col-span-10">
                        <div class="flex items-center space-x-3 mb-3">
                            <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $branch->name }}
                            </h2>

                            <!-- Status Badge -->
                            @if($branch->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Active
                                </span>
                            @elseif($branch->status === 'pending_approval')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Pending Approval
                                </span>
                            @elseif($branch->status === 'draft')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Draft
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ ucfirst(str_replace('_', ' ', $branch->status)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Branch Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $branch->accounts->count() }} accounts</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">
                                    @if($branch->branchAddress)
                                        {{ $branch->branchAddress->city ?? 'Location' }}
                                    @else
                                        No location
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $branch->phone_number ?? 'No phone' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($branch->status === 'pending_approval' && auth()->user()->canApprove($branch))
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Approval Required</h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">This branch is waiting for approval to become active.</p>
                        </div>
                        <div class="flex space-x-3">
                            <form method="POST" action="{{ route('admin.branches.approve', $branch) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors"
                                        onclick="return confirm('Approve branch {{ $branch->name }}?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.branches.reject', $branch) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors"
                                        onclick="return confirm('Reject branch {{ $branch->name }}?')">
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
            <!-- Branch Information -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Branch Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Branch Name</label>
                            <div class="flex items-center space-x-2">
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $branch->name }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Address</label>
                            <div class="flex items-center space-x-3">
                                @if($branch->branchAddress)
                                    <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->branchAddress->full_address }}</p>
                                @else
                                    <p class="text-[#706f6c] dark:text-[#A1A09A] italic">No address provided</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Contact Information</label>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->phone_number ?? 'No phone number' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Branch ID</label>
                            <div class="flex items-center space-x-2">
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-mono text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">{{ $branch->id }}</p>
                                <button onclick="copyToClipboard('{{ $branch->id }}')" class="text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status & Activity -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Status & Activity</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Current Status -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Branch Status</label>
                            <div class="flex items-center space-x-3">
                                @if($branch->status === 'active')
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $branch->status)) }}
                                    </span>
                                @elseif($branch->status === 'pending_approval')
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $branch->status)) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $branch->status)) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Branch Summary -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Branch Summary</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Accounts</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                        {{ $statistics['total_accounts'] }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Total Value</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                        RM {{ number_format($statistics['total_estimated_value'], 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity & Timeline -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Branch Activity -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Branch Activity</h3>
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
                                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Branch Created</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Branch registration completed</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->created_at->format('M j, Y') }}</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $branch->created_at->format('g:i A') }}</p>
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
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Branch modification</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $branch->updated_at->format('M j, Y') }}</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $branch->updated_at->format('g:i A') }}</p>
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
                        @if($branch->creator)
                            <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Created By</p>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $branch->creator->full_name }} (@{{ $branch->creator->username }})</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $branch->created_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($branch->approvedBy)
                            <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/10 rounded-lg border border-green-200 dark:border-green-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Approved By</p>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $branch->approvedBy->full_name }} (@{{ $branch->approvedBy->username }})</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Approved</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Accounts Section -->
        @if($branch->accounts->count() > 0)
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Account Information</h3>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                            {{ $branch->accounts->count() }} {{ Str::plural('account', $branch->accounts->count()) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($branch->accounts as $account)
                            <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-[#1a1a19] transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span>{{ $account->account_title }}</span>
                                    </h4>
                                    @if($account->status === 'active')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-900/20 text-gray-800 dark:text-gray-200">
                                            {{ ucfirst($account->status) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A] space-y-1">
                                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->collaterals->count() }} collaterals</p>
                                    <p class="text-brand font-semibold">RM {{ number_format($account->collaterals->sum('estimated_value_rm'), 2) }}</p>
                                    <p class="text-xs">Created: {{ $account->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.accounts.show', $account) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No accounts</h3>
                    <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">This branch doesn't have any accounts yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.accounts.create', ['branch_id' => $branch->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover">
                            Add Account
                        </a>
                    </div>
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
@endsection
