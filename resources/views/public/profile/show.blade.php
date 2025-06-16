@extends('layouts.app')

@section('title', 'My Profile - Arrahnu Auction')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-brand rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                        My Profile
                    </h1>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        View your account information and activity
                    </p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-colors">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Profile Overview -->
    <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl overflow-hidden mb-8">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-8 py-12">
            <div class="flex items-center gap-8">
                <!-- Avatar -->
                <div class="relative">
                    @if($user->avatar_path)
                        <img src="{{ $user->avatar_url }}" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-[#3E3E3A] shadow-lg">
                    @else
                        <div class="w-32 h-32 bg-white dark:bg-[#161615] rounded-full flex items-center justify-center border-4 border-white dark:border-[#3E3E3A] shadow-lg">
                            <span class="text-blue-600 dark:text-blue-400 font-bold text-4xl">
                                {{ $user->initials }}
                            </span>
                        </div>
                    @endif
                    @if($user->is_admin)
                        <div class="absolute -top-2 -right-2 w-10 h-10 bg-brand rounded-full flex items-center justify-center border-4 border-white dark:border-[#3E3E3A] shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-4">
                        <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->display_name }}</h2>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                            @elseif($user->status === 'pending_approval') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $user->status)) }}
                        </span>
                    </div>
                    <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-2">@{{ $user->username }}</p>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">{{ $user->email }}</p>
                    
                    <div class="flex items-center gap-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a1 1 0 001 1h8a1 1 0 001-1V8a1 1 0 00-1-1h-1"></path>
                            </svg>
                            <span>{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a1 1 0 001 1h8a1 1 0 001-1V8a1 1 0 00-1-1h-1"></path>
                            </svg>
                            <span>Member since {{ $user->created_at->format('F Y') }}</span>
                        </div>
                        @if($user->last_login_at)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Last active {{ $user->last_login_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Stats -->
        <div class="px-8 py-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Profile Completion -->
                <div class="text-center">
                    <div class="relative w-16 h-16 mx-auto mb-3">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200 dark:text-gray-700" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                            <path class="text-blue-600 dark:text-blue-400" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="{{ $user->profile_completion }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $user->profile_completion }}%</span>
                        </div>
                    </div>
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Profile Complete</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Keep it updated</p>
                </div>

                <!-- Verification Status -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-3 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                        @if($user->is_email_verified && $user->is_phone_verified)
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        @endif
                    </div>
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                        @if($user->is_email_verified && $user->is_phone_verified)
                            Fully Verified
                        @else
                            Partially Verified
                        @endif
                    </h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        {{ $user->is_email_verified ? 'Email ✓' : 'Email ✗' }} 
                        {{ $user->phone_number ? ($user->is_phone_verified ? 'Phone ✓' : 'Phone ✗') : 'No Phone' }}
                    </p>
                </div>

                <!-- Account Type -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-3 bg-brand/10 rounded-full flex items-center justify-center">
                        @if($user->is_admin)
                            <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        @endif
                    </div>
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                        {{ $user->is_admin ? 'Administrator' : ucfirst($user->role) }}
                    </h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Account type</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8">
            <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Personal Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Full Name</label>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $user->full_name ?: 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Username</label>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">@{{ $user->username }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Email Address</label>
                    <div class="flex items-center gap-2">
                        <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $user->email }}</p>
                        @if($user->is_email_verified)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 rounded-full">
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 rounded-full">
                                Unverified
                            </span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Phone Number</label>
                    @if($user->phone_number)
                        <div class="flex items-center gap-2">
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $user->phone_number }}</p>
                            @if($user->is_phone_verified)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 rounded-full">
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 rounded-full">
                                    Unverified
                                </span>
                            @endif
                        </div>
                    @else
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">Not provided</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8">
            <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Account Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Account Status</label>
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full
                        @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                        @elseif($user->status === 'pending_approval') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $user->status)) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Role</label>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">
                        {{ $user->is_admin ? 'Administrator' : ucfirst($user->role) }}
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Member Since</label>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $user->created_at->format('F j, Y') }}</p>
                </div>
                
                @if($user->last_login_at)
                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Last Login</label>
                        <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $user->last_login_at->format('F j, Y g:i A') }}</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $user->last_login_at->diffForHumans() }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8">
        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Quick Actions</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-center p-4 bg-brand/5 border border-brand/20 rounded-lg hover:bg-brand/10 transition-colors group">
                <div class="text-center">
                    <svg class="w-8 h-8 text-brand mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Edit Profile</p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Update your information</p>
                </div>
            </a>
            
            <a href="{{ route('profile.settings') }}" class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors group">
                <div class="text-center">
                    <svg class="w-8 h-8 text-gray-600 dark:text-gray-400 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Preferences</p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage settings</p>
                </div>
            </a>
            
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                <div class="text-center">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0H8v0z"></path>
                    </svg>
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Dashboard</p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Go to dashboard</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection 