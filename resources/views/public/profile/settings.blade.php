@extends('layouts.app')

@section('title', 'Profile Settings - Arrahnu Auction')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success/Error Messages -->
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

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-brand rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                        Preferences & Settings
                    </h1>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Customize your account preferences and notification settings
                    </p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                Back to Profile
            </a>
        </div>
    </div>

    <!-- Profile Navigation Tabs -->
    <div class="mb-8">
        <nav class="flex space-x-8 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <a href="{{ route('profile.edit') }}" class="py-2 px-1 border-b-2 border-transparent text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-gray-300 font-medium text-sm transition-colors">
                Profile Information
            </a>
            <a href="{{ route('profile.settings') }}" class="py-2 px-1 border-b-2 border-brand text-brand font-medium text-sm">
                Preferences
            </a>
            <a href="{{ route('addresses.index') }}" class="py-2 px-1 border-b-2 border-transparent text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-gray-300 font-medium text-sm transition-colors">
                Addresses
            </a>
        </nav>
    </div>

    <form method="POST" action="{{ route('profile.settings.update') }}">
        @csrf
        @method('PUT')

        <!-- Notification Preferences -->
        <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2zM4 7h8V5H4v2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Notification Preferences</h2>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Choose how you want to receive notifications</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Email Notifications -->
                <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Email Notifications</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Receive notifications about auctions, bids, and account updates</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" {{ ($user->preferences['email_notifications'] ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/20 dark:peer-focus:ring-brand/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-brand"></div>
                    </label>
                </div>

                <!-- SMS Notifications -->
                <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">SMS Notifications</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Receive text messages for urgent auction updates</p>
                            @if(!$user->phone_number)
                                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">⚠ Phone number required</p>
                            @endif
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_notifications" value="1" class="sr-only peer" {{ ($user->preferences['sms_notifications'] ?? false) ? 'checked' : '' }} {{ !$user->phone_number ? 'disabled' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/20 dark:peer-focus:ring-brand/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-brand peer-disabled:opacity-50 peer-disabled:cursor-not-allowed"></div>
                    </label>
                </div>

                <!-- Marketing Emails -->
                <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Marketing Emails</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Receive promotional emails about new features and special auctions</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="marketing_emails" value="1" class="sr-only peer" {{ ($user->preferences['marketing_emails'] ?? false) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/20 dark:peer-focus:ring-brand/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-brand"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Regional Preferences -->
        <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Regional Preferences</h2>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Set your timezone and language preferences</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Timezone -->
                <div>
                    <label for="timezone" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Timezone
                    </label>
                    <select id="timezone" name="timezone" class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200">
                        <option value="UTC" {{ ($user->preferences['timezone'] ?? 'UTC') === 'UTC' ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                        <option value="Asia/Kuala_Lumpur" {{ ($user->preferences['timezone'] ?? 'UTC') === 'Asia/Kuala_Lumpur' ? 'selected' : '' }}>Asia/Kuala Lumpur (Malaysia Time)</option>
                        <option value="Asia/Singapore" {{ ($user->preferences['timezone'] ?? 'UTC') === 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore (Singapore Time)</option>
                        <option value="Asia/Jakarta" {{ ($user->preferences['timezone'] ?? 'UTC') === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (Indonesia Time)</option>
                        <option value="Asia/Bangkok" {{ ($user->preferences['timezone'] ?? 'UTC') === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok (Thailand Time)</option>
                        <option value="Asia/Manila" {{ ($user->preferences['timezone'] ?? 'UTC') === 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila (Philippines Time)</option>
                        <option value="America/New_York" {{ ($user->preferences['timezone'] ?? 'UTC') === 'America/New_York' ? 'selected' : '' }}>America/New York (Eastern Time)</option>
                        <option value="America/Los_Angeles" {{ ($user->preferences['timezone'] ?? 'UTC') === 'America/Los_Angeles' ? 'selected' : '' }}>America/Los Angeles (Pacific Time)</option>
                        <option value="Europe/London" {{ ($user->preferences['timezone'] ?? 'UTC') === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT/BST)</option>
                    </select>
                    <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">All auction times will be displayed in your selected timezone</p>
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Language
                    </label>
                    <select id="language" name="language" class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200">
                        <option value="en" {{ ($user->preferences['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                        <option value="ms" {{ ($user->preferences['language'] ?? 'en') === 'ms' ? 'selected' : '' }}>Bahasa Malaysia</option>
                        <option value="zh" {{ ($user->preferences['language'] ?? 'en') === 'zh' ? 'selected' : '' }}>中文 (Chinese)</option>
                        <option value="ta" {{ ($user->preferences['language'] ?? 'en') === 'ta' ? 'selected' : '' }}>தமிழ் (Tamil)</option>
                    </select>
                    <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">Interface language (requires page refresh)</p>
                </div>
            </div>
        </div>

        <!-- Privacy & Security -->
        <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Privacy & Security</h2>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage your privacy and security settings</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Account Status -->
                <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Account Status</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Your account is {{ ucfirst(str_replace('_', ' ', $user->status)) }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                        @elseif($user->status === 'pending_approval') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $user->status)) }}
                    </span>
                </div>

                <!-- Email Verification -->
                <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Email Verification</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email }}</p>
                        </div>
                    </div>
                    @if($user->is_email_verified)
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 rounded-full">
                            Verified
                        </span>
                    @else
                        <button type="button" class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/30 transition-colors">
                            Verify Email
                        </button>
                    @endif
                </div>

                <!-- Phone Verification -->
                @if($user->phone_number)
                    <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Phone Verification</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->phone_number }}</p>
                            </div>
                        </div>
                        @if($user->is_phone_verified)
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 rounded-full">
                                Verified
                            </span>
                        @else
                            <button type="button" class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/30 transition-colors">
                                Verify Phone
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Save Settings -->
        <div class="flex justify-between items-center">
            <a href="{{ route('profile.edit') }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                Save Preferences
            </button>
        </div>
    </form>
</div>
@endsection 