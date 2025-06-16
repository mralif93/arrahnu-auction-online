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
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 bg-brand rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    Profile Settings
                </h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    Manage your account information and security settings
                </p>
            </div>
        </div>

        <!-- Profile Completion -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Profile Completion</h3>
                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $user->profile_completion }}%</span>
            </div>
            <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-3">
                <div class="bg-blue-600 dark:bg-blue-400 h-3 rounded-full transition-all duration-300" style="width: {{ $user->profile_completion }}%"></div>
            </div>
            <p class="text-sm text-blue-800 dark:text-blue-200 mt-2">
                Complete your profile to unlock all features and improve your auction experience.
            </p>
        </div>
    </div>

    <!-- Profile Navigation Tabs -->
    <div class="mb-8">
        <nav class="flex space-x-8 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <a href="{{ route('profile.edit') }}" class="py-2 px-1 border-b-2 border-brand text-brand font-medium text-sm">
                Profile Information
            </a>
            <a href="{{ route('profile.settings') }}" class="py-2 px-1 border-b-2 border-transparent text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-gray-300 font-medium text-sm transition-colors">
                Preferences
            </a>
            <a href="{{ route('addresses.index') }}" class="py-2 px-1 border-b-2 border-transparent text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-gray-300 font-medium text-sm transition-colors">
                Addresses
            </a>
        </nav>
    </div>

    <!-- Profile Information -->
    <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Profile Information</h2>
            @if($user->is_admin)
                <span class="px-3 py-1 bg-brand text-white text-sm font-medium rounded-full">
                    Administrator
                </span>
            @else
                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-full">
                    {{ ucfirst($user->role) }}
                </span>
            @endif
        </div>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Avatar -->
                <div class="md:col-span-2 flex items-center gap-6">
                    <div class="relative">
                        @if($user->avatar_path)
                            <img src="{{ $user->avatar_url }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-white dark:border-[#3E3E3A] shadow-lg">
                        @else
                            <div class="w-20 h-20 bg-brand/10 rounded-full flex items-center justify-center border-4 border-white dark:border-[#3E3E3A] shadow-lg">
                                <span class="text-brand font-bold text-2xl">
                                    {{ $user->initials }}
                                </span>
                            </div>
                        @endif
                        <button type="button" onclick="document.getElementById('avatar-upload').click()" class="absolute -bottom-1 -right-1 w-8 h-8 bg-brand hover:bg-brand-hover text-white rounded-full flex items-center justify-center shadow-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->display_name }}</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email }}</p>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                            Member since {{ $user->created_at->format('F Y') }}
                        </p>
                        <button type="button" onclick="document.getElementById('avatar-upload').click()" class="mt-2 text-sm text-brand hover:text-brand-hover font-medium">
                            Change Profile Picture
                        </button>
                    </div>
                </div>

                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Full Name
                    </label>
                    <input type="text"
                           id="full_name"
                           name="full_name"
                           value="{{ old('full_name', $user->full_name) }}"
                           class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200"
                           required>
                    @error('full_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Username
                    </label>
                    <input type="text"
                           id="username"
                           name="username"
                           value="{{ old('username', $user->username) }}"
                           class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200"
                           required>
                    @error('username')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200"
                               required>
                        @if($user->is_email_verified)
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    @if($user->is_email_verified)
                        <p class="mt-1 text-sm text-green-600 dark:text-green-400">✓ Email verified</p>
                    @else
                        <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">⚠ Email not verified</p>
                    @endif
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Phone Number
                    </label>
                    <div class="relative">
                        <input type="tel"
                               id="phone_number"
                               name="phone_number"
                               value="{{ old('phone_number', $user->phone_number) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200"
                               placeholder="+60123456789">
                        @if($user->is_phone_verified)
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    @if($user->is_phone_verified)
                        <p class="mt-1 text-sm text-green-600 dark:text-green-400">✓ Phone verified</p>
                    @elseif($user->phone_number)
                        <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">⚠ Phone not verified</p>
                    @endif
                    @error('phone_number')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Hidden Avatar Upload Form -->
    <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="file" id="avatar-upload" name="avatar" accept="image/*" onchange="document.getElementById('avatar-form').submit();">
    </form>

    <!-- Password Change -->
    <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8 mb-8">
        <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Change Password</h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Current Password
                    </label>
                    <input type="password"
                           id="current_password"
                           name="current_password"
                           class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200">
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div></div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        New Password
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Confirm New Password
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200">
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Account Status -->
    <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8 mb-8">
        <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Account Status</h2>

        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                <div class="flex items-center">
                    @if($user->is_admin)
                        <svg class="w-5 h-5 text-brand mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Administrator Account</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">You have full administrative privileges</p>
                        </div>
                    @else
                        <svg class="w-5 h-5 text-[#706f6c] dark:text-[#A1A09A] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ ucfirst($user->role) }} Account</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ ucfirst(str_replace('_', ' ', $user->status)) }} status</p>
                        </div>
                    @endif
                </div>
                <span class="text-xs px-2 py-1 rounded-full border
                    @if($user->status === 'active') text-green-600 bg-green-50 border-green-200 dark:text-green-400 dark:bg-green-900/20 dark:border-green-800
                    @elseif($user->status === 'pending_approval') text-yellow-600 bg-yellow-50 border-yellow-200 dark:text-yellow-400 dark:bg-yellow-900/20 dark:border-yellow-800
                    @else text-gray-600 bg-gray-50 border-gray-200 dark:text-gray-400 dark:bg-gray-900/20 dark:border-gray-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $user->status)) }}
                </span>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('profile.settings') }}" class="text-center px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Preferences
                </a>
                <a href="{{ route('addresses.index') }}" class="text-center px-4 py-3 bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-900/30 transition-colors">
                    <i class='bx bx-map w-5 h-5 mx-auto mb-1 text-xl'></i>
                    My Addresses
                </a>
                <a href="{{ route('profile.show') }}" class="text-center px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8 border-l-4 border-red-500">
        <h2 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-6">Danger Zone</h2>

        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-medium text-red-900 dark:text-red-100 mb-2">Delete Account</h3>
                    <p class="text-sm text-red-800 dark:text-red-200 mb-4">
                        Once you delete your account, all of your data will be permanently removed. This action cannot be undone.
                    </p>
                </div>
                <button type="button" 
                        onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-[#161615] rounded-xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Delete Account</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Are you sure you want to delete your account? This action cannot be undone.
                </p>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="mb-6">
                    <label for="delete_password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Enter your password to confirm
                    </label>
                    <input type="password"
                           id="delete_password"
                           name="password"
                           class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           required>
                </div>

                <div class="flex gap-4">
                    <button type="button" 
                            onclick="document.getElementById('delete-modal').classList.add('hidden')"
                            class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
