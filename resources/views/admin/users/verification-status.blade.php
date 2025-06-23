@extends('layouts.admin')

@section('title', 'User Verification Status - ' . ($user->full_name ?? $user->username))

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
                        <a href="{{ route('admin.users.index') }}" class="ml-1 text-sm font-medium text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] md:ml-2">Users</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#706f6c]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.users.show', $user) }}" class="ml-1 text-sm font-medium text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] md:ml-2">{{ $user->full_name ?? $user->username }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#706f6c]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] md:ml-2">Verification Status</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Email Verification Status</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $user->full_name ?? $user->username }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->isEmailVerified() ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200' : 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200' }}">
                        {{ $user->isEmailVerified() ? 'Verified' : 'Not Verified' }}
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.show', $user) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to User Details
                </a>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Verification Status Overview -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 {{ $user->isEmailVerified() ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($user->isEmailVerified())
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            @endif
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Verification Status</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Status Badge -->
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 {{ $user->isEmailVerified() ? 'bg-green-100 dark:bg-green-900/20' : 'bg-yellow-100 dark:bg-yellow-900/20' }} rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 {{ $user->isEmailVerified() ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($user->isEmailVerified())
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    @endif
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                {{ $user->isEmailVerified() ? 'Email Verified' : 'Email Verification Required' }}
                            </h4>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                @if($user->isEmailVerified())
                                    Email was verified on {{ $user->email_verified_at->format('M j, Y \a\t g:i A') }}
                                @else
                                    User needs to verify their email address before accessing the system
                                @endif
                            </p>
                        </div>

                        <!-- Status Details -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 {{ $user->isEmailVerified() ? 'bg-green-100 dark:bg-green-900/20' : 'bg-yellow-100 dark:bg-yellow-900/20' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 {{ $user->isEmailVerified() ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            @if($user->isEmailVerified())
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            @else
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Email Status</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-medium {{ $user->isEmailVerified() ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                    {{ $user->isEmailVerified() ? 'Verified' : 'Not Verified' }}
                                </span>
                            </div>

                            @if($user->email_verified_at)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Verified At</p>
                                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email_verified_at->format('M j, Y \a\t g:i A') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-blue-600 dark:text-blue-400">{{ $user->email_verified_at->diffForHumans() }}</span>
                                </div>
                            @endif

                            @if($user->verification_token_expires_at)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Token Expires</p>
                                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->verification_token_expires_at->format('M j, Y \a\t g:i A') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs {{ $user->verification_token_expires_at->isPast() ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }}">
                                        {{ $user->verification_token_expires_at->diffForHumans() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">User Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Full Name</label>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $user->full_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Username</label>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-mono">@{{ $user->username }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Email Address</label>
                            <div class="flex items-center space-x-3">
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->email }}</p>
                                @if($user->isEmailVerified())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Unverified
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">User Role</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium
                                @if($user->role === 'maker') bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-800
                                @elseif($user->role === 'checker') bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200 border border-purple-200 dark:border-purple-800
                                @else bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Account Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium
                                @if($user->status === 'active') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800
                                @elseif($user->status === 'pending_approval') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800
                                @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $user->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification History -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Verification History</h3>
                </div>
            </div>
            <div class="p-6">
                @if($user->failed_verification_attempts > 0 || $user->email_verification_sent_at || $user->isEmailVerified())
                    <div class="space-y-3">
                        @if($user->isEmailVerified())
                            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/10 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Email Successfully Verified</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email_verified_at->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-green-600 dark:text-green-400">{{ $user->email_verified_at->diffForHumans() }}</span>
                            </div>
                        @endif

                        @if($user->email_verification_sent_at)
                            <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/10 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Last Verification Email Sent</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email_verification_sent_at->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-blue-600 dark:text-blue-400">{{ $user->email_verification_sent_at->diffForHumans() }}</span>
                            </div>
                        @endif

                        @if($user->failed_verification_attempts > 0)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/10 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Failed Verification Attempts</p>
                                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">User has {{ $user->failed_verification_attempts }} failed attempts</p>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400">{{ $user->failed_verification_attempts }} attempts</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No verification history available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Admin Actions</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap items-center gap-3" style="display: flex !important; visibility: visible !important;">
                    @if(!$user->isEmailVerified())
                        <!-- Manually Verify Email -->
                        <form method="POST" action="{{ route('admin.users.verify-email', $user) }}" class="inline-block" style="display: inline-block !important;">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer transition-colors"
                                    style="background-color: #FE5000 !important; display: inline-flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;"
                                    onmouseover="this.style.backgroundColor='#E5470A'"
                                    onmouseout="this.style.backgroundColor='#FE5000'"
                                    onclick="return confirm('Manually verify email for {{ $user->full_name }}?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verify Email
                            </button>
                        </form>

                        <!-- Send Verification Email -->
                        <form method="POST" action="{{ route('admin.users.send-verification-email', $user) }}" class="inline-block" style="display: inline-block !important;">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                                    style="background-color: #2563eb !important; display: inline-flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;"
                                    onmouseover="this.style.backgroundColor='#1d4ed8'"
                                    onmouseout="this.style.backgroundColor='#2563eb'">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Verification Email
                            </button>
                        </form>
                    @else
                        <!-- Reset Email Verification -->
                        <form method="POST" action="{{ route('admin.users.reset-email-verification', $user) }}" class="inline-block" style="display: inline-block !important;">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                                    style="background-color: #d97706 !important; display: inline-flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;"
                                    onmouseover="this.style.backgroundColor='#b45309'"
                                    onmouseout="this.style.backgroundColor='#d97706'"
                                    onclick="return confirm('Reset email verification for {{ $user->full_name }}? They will need to verify their email again.')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset Verification
                            </button>
                        </form>
                    @endif
                    
                    <!-- Back to User Details -->
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="inline-flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors"
                       style="background-color: #f3f4f6 !important; display: inline-flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;"
                       onmouseover="this.style.backgroundColor='#e5e7eb'"
                       onmouseout="this.style.backgroundColor='#f3f4f6'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to User Details
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection 