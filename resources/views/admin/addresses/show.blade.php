@extends('layouts.admin')

@section('title', 'Address Details - ' . $address->address_line_1)

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
                        <a href="{{ route('admin.addresses.index') }}" class="ml-1 text-sm font-medium text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] md:ml-2">Addresses</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#706f6c]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] md:ml-2">{{ $address->address_line_1 }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->address_line_1 }}</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->city }}, {{ $address->state }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">ID: {{ Str::limit($address->id, 8) }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Created {{ $address->created_at->format('M j, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.addresses.edit', $address) }}"
                   class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer"
                   style="background-color: #FE5000; position: relative; z-index: 10;"
                   onmouseover="this.style.backgroundColor='#E5470A'"
                   onmouseout="this.style.backgroundColor='#FE5000'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Address
                </a>
                <a href="{{ route('admin.addresses.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Addresses
                </a>
            </div>
        </div>

        <!-- Address Profile Overview -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-8">
                <div class="grid grid-cols-12 gap-8">
                    <!-- Avatar -->
                    <div class="col-span-2">
                        <div class="w-24 h-24 bg-white dark:bg-[#161615] rounded-xl shadow-lg flex items-center justify-center border-4 border-white dark:border-[#3E3E3A]">
                            <span class="text-blue-600 dark:text-blue-400 font-bold text-3xl">
                                {{ strtoupper(substr($address->user->full_name ?? $address->user->username ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Address Info -->
                    <div class="col-span-10">
                        <div class="flex items-center space-x-3 mb-3">
                            <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $address->address_line_1 }}
                            </h2>

                            <!-- Status Badge -->
                            @if($address->is_primary)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Primary Address
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Secondary Address
                                </span>
                            @endif
                        </div>

                        <!-- Address Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->user->full_name ?? $address->user->username }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->city }}, {{ $address->state }} {{ $address->postcode }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Profile -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">
                                {{ strtoupper(substr($address->user->full_name ?? $address->user->username ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $address->user->full_name ?? $address->user->username }}
                            </h4>
                            <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->user->email }}</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    <i class='bx bx-calendar mr-1'></i>
                                    Joined {{ $address->user->created_at->format('M j, Y') }}
                                </span>
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    <i class='bx bx-map mr-1'></i>
                                    {{ $address->user->addresses()->count() }} address(es)
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('admin.users.show', $address->user) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/20 hover:bg-blue-200 dark:hover:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-sm font-medium rounded-lg transition-colors">
                                <i class='bx bx-user text-sm mr-1'></i>
                                View User
                            </a>
                            <a href="{{ route('admin.addresses.index', ['user' => $address->user->id]) }}" 
                               class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                                <i class='bx bx-list-ul text-sm mr-1'></i>
                                User Addresses
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Stats -->
                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-[#1a1a19] rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class='bx bx-map text-blue-600 dark:text-blue-400'></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->user->addresses()->count() }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Total Addresses</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-[#1a1a19] rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class='bx bx-star text-green-600 dark:text-green-400'></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->user->addresses()->where('is_primary', true)->count() }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Primary Address</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-[#1a1a19] rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class='bx bx-time text-purple-600 dark:text-purple-400'></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->user->addresses()->where('created_at', '>=', now()->subDays(30))->count() }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Recent (30d)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- Address Details -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Complete Address</h3>
                        <button onclick="copyAddress()" 
                                class="ml-auto inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Copy Address
                        </button>
                    </div>
                </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Address Lines -->
                <div class="md:col-span-2">
                    <div class="bg-gray-50 dark:bg-[#1a1a19] rounded-lg p-4">
                        <div id="fullAddress" class="space-y-1">
                            <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->address_line_1 }}</p>
                            @if($address->address_line_2)
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->address_line_2 }}</p>
                            @endif
                            <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->city }}, {{ $address->state }} {{ $address->postcode }}</p>
                            <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->country }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Components -->
                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Address Line 1</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $address->address_line_1 }}</p>
                </div>

                @if($address->address_line_2)
                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Address Line 2</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $address->address_line_2 }}</p>
                </div>
                @endif

                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">City</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $address->city }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">State</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $address->state }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Postcode</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $address->postcode }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Country</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $address->country }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Actions -->
    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center">
                <i class='bx bx-cog text-brand text-xl mr-2'></i>
                Address Actions
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(!$address->is_primary)
                    <form action="{{ route('admin.addresses.set-primary', $address) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to set this as the primary address? This will unset the current primary address for this user.')"
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-100 dark:bg-blue-900/20 hover:bg-blue-200 dark:hover:bg-blue-900/30 text-blue-800 dark:text-blue-200 font-medium rounded-lg transition-colors">
                            <i class='bx bx-star text-lg mr-2'></i>
                            Set as Primary Address
                        </button>
                    </form>
                @else
                    <div class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 font-medium rounded-lg">
                        <i class='bx bx-check-circle text-lg mr-2'></i>
                        Primary Address
                    </div>
                @endif

                <a href="{{ route('admin.addresses.edit', $address) }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                    <i class='bx bx-edit text-lg mr-2'></i>
                    Edit Address
                </a>
            </div>
        </div>
    </div>

    <!-- Address Metadata -->
    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center">
                <i class='bx bx-info-circle text-brand text-xl mr-2'></i>
                Address Information
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Address ID</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-mono text-sm">{{ $address->id }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Created Date</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $address->created_at->format('M j, Y') }}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $address->created_at->format('g:i A') }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Last Updated</h4>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ $address->updated_at->format('M j, Y') }}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $address->updated_at->format('g:i A') }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Status</h4>
                    <div class="flex items-center">
                        @if($address->is_primary)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                <i class='bx bx-star text-xs mr-1'></i>
                                Primary
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                <i class='bx bx-map-pin text-xs mr-1'></i>
                                Secondary
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Other User Addresses -->
    @if($address->user->addresses()->where('id', '!=', $address->id)->count() > 0)
    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center">
                <i class='bx bx-list-ul text-brand text-xl mr-2'></i>
                Other User Addresses
                <span class="ml-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">({{ $address->user->addresses()->where('id', '!=', $address->id)->count() }})</span>
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($address->user->addresses()->where('id', '!=', $address->id)->get() as $otherAddress)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-[#1a1a19] rounded-lg">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <i class='bx bx-map-pin text-gray-600 dark:text-gray-300 text-sm'></i>
                            </div>
                            <div>
                                <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $otherAddress->address_line_1 }}</p>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $otherAddress->city }}, {{ $otherAddress->state }} {{ $otherAddress->postcode }}</p>
                            </div>
                            @if($otherAddress->is_primary)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                    <i class='bx bx-star text-xs mr-1'></i>
                                    Primary
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.addresses.show', $otherAddress) }}" 
                           class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/20 hover:bg-blue-200 dark:hover:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-sm font-medium rounded-lg transition-colors">
                            <i class='bx bx-show text-sm mr-1'></i>
                            View
                        </a>
                        <a href="{{ route('admin.addresses.edit', $otherAddress) }}" 
                           class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            <i class='bx bx-edit text-sm mr-1'></i>
                            Edit
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 max-w-md mx-4">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mr-4">
                <i class='bx bx-trash text-red-600 dark:text-red-400 text-xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Delete Address</h3>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">This action cannot be undone</p>
            </div>
        </div>
        
        <div class="mb-6">
            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                Are you sure you want to delete this address? This will permanently remove the address from the user's account.
            </p>
            @if($address->is_primary && $address->user->addresses()->count() > 1)
                <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                        <i class='bx bx-warning mr-1'></i>
                        This is the user's primary address. Another address will automatically become primary.
                    </p>
                </div>
            @elseif($address->user->addresses()->count() === 1)
                <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-red-800 dark:text-red-200 text-sm">
                        <i class='bx bx-error mr-1'></i>
                        This is the user's only address. Deleting it will leave them without any address.
                    </p>
                </div>
            @endif
        </div>
        
        <div class="flex items-center justify-end space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    Delete Address
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function deleteAddress(addressId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    
    form.action = `{{ route('admin.addresses.destroy', ':id') }}`.replace(':id', addressId);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function copyAddress() {
    const addressElement = document.getElementById('fullAddress');
    const addressText = addressElement.innerText;
    
    navigator.clipboard.writeText(addressText).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bx bx-check text-sm mr-1"></i>Copied!';
        button.classList.add('bg-green-100', 'dark:bg-green-900/20', 'text-green-800', 'dark:text-green-200');
        button.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300');
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-100', 'dark:bg-green-900/20', 'text-green-800', 'dark:text-green-200');
            button.classList.add('bg-gray-100', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy address to clipboard');
    });
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection