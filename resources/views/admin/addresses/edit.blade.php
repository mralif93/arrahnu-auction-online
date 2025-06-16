@extends('layouts.admin')

@section('title', 'Edit Address - ' . $address->address_line_1)

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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#706f6c]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.addresses.show', $address) }}" class="ml-1 text-sm font-medium text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] md:ml-2">{{ Str::limit($address->address_line_1, 30) }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#706f6c]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] md:ml-2">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Edit Address</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->address_line_1 }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->user->full_name ?? $address->user->username }}</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">ID: {{ Str::limit($address->id, 8) }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.addresses.show', $address) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Address
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
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->city }}, {{ $address->state }}</span>
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
        <!-- Edit Form -->
        <form action="{{ route('admin.addresses.update', $address) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- User Information Section -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">User Information</h3>
                        <span class="text-sm text-red-500">* Required fields</span>
                    </div>
                </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current User Display -->
                    <div class="md:col-span-2">
                        <div class="bg-gray-50 dark:bg-[#1a1a19] rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-brand rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-medium text-lg">
                                        {{ strtoupper(substr($address->user->full_name ?? $address->user->username ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                        {{ $address->user->full_name ?? $address->user->username }}
                                    </h4>
                                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $address->user->email }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        User has {{ $address->user->addresses()->count() }} address(es)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Selection (Allow changing user) -->
                    <div class="md:col-span-2">
                        <label for="user_id" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Change User (Optional) <span class="text-red-500">*</span>
                        </label>
                        <select id="user_id" 
                                name="user_id" 
                                class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
                                required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $address->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->full_name ?? $user->username }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                            ⚠️ Changing the user will transfer this address to the selected user.
                        </p>
                    </div>

                    <!-- User Address Count (Dynamic) -->
                    <div id="userAddressInfo" class="md:col-span-2 hidden">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class='bx bx-info-circle text-blue-600 dark:text-blue-400 text-lg mr-2'></i>
                                <div>
                                    <p class="text-blue-800 dark:text-blue-200 text-sm font-medium">New User Address Information</p>
                                    <p id="userAddressCount" class="text-blue-700 dark:text-blue-300 text-sm"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center">
                    <i class='bx bx-map text-brand text-xl mr-2'></i>
                    Address Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Address Line 1 -->
                    <div class="md:col-span-2">
                        <label for="address_line_1" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Address Line 1 <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="address_line_1"
                               name="address_line_1"
                               value="{{ old('address_line_1', $address->address_line_1) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
                               placeholder="Enter street address"
                               required>
                        @error('address_line_1')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Address Line 2 -->
                    <div class="md:col-span-2">
                        <label for="address_line_2" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Address Line 2
                        </label>
                        <input type="text"
                               id="address_line_2"
                               name="address_line_2"
                               value="{{ old('address_line_2', $address->address_line_2) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
                               placeholder="Apartment, suite, unit, building, floor, etc. (optional)">
                        @error('address_line_2')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="city"
                               name="city"
                               value="{{ old('city', $address->city) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
                               placeholder="Enter city"
                               required>
                        @error('city')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            State <span class="text-red-500">*</span>
                        </label>
                        <select id="state"
                                name="state"
                                class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
                                required>
                            <option value="">Select State</option>
                            @php
                                $states = ['Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Labuan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Putrajaya', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu'];
                                $selectedState = old('state', $address->state);
                            @endphp
                            @foreach($states as $state)
                                <option value="{{ $state }}" {{ $selectedState == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                        @error('state')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Postcode -->
                    <div>
                        <label for="postcode" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Postcode <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="postcode"
                               name="postcode"
                               value="{{ old('postcode', $address->postcode) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
                               placeholder="Enter postcode"
                               pattern="[0-9]{5}"
                               maxlength="5"
                               required>
                        @error('postcode')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="country"
                               name="country"
                               value="{{ old('country', $address->country) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
                               placeholder="Enter country"
                               required>
                        @error('country')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Primary Address -->
                    <div class="md:col-span-2">
                        @if(!$address->is_primary)
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="is_primary"
                                       name="is_primary"
                                       value="1"
                                       {{ old('is_primary') ? 'checked' : '' }}
                                       class="h-4 w-4 text-brand focus:ring-brand border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
                                <label for="is_primary" class="ml-2 block text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Set as primary address
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Setting this as primary will unset the current primary address for this user.
                            </p>
                        @else
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class='bx bx-info-circle text-blue-600 dark:text-blue-400 text-lg mr-2'></i>
                                        <div>
                                            <p class="text-blue-800 dark:text-blue-200 text-sm font-medium">Primary Address</p>
                                            <p class="text-blue-700 dark:text-blue-300 text-sm">This is the user's primary address for deliveries and billing.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               id="is_primary"
                                               name="is_primary"
                                               value="1"
                                               {{ old('is_primary', $address->is_primary) ? 'checked' : '' }}
                                               class="h-4 w-4 text-brand focus:ring-brand border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
                                        <label for="is_primary" class="ml-2 block text-sm text-blue-800 dark:text-blue-200">
                                            Keep as primary
                                        </label>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-blue-700 dark:text-blue-300">
                                    Unchecking this will automatically set another address as primary (if user has multiple addresses).
                                </p>
                            </div>
                        @endif
                        @error('is_primary')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Address History -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <h4 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 flex items-center">
                <i class='bx bx-time text-brand text-xl mr-2'></i>
                Address History
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Created:</span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] ml-2 font-medium">{{ $address->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <div>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Last Updated:</span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] ml-2 font-medium">{{ $address->updated_at->format('M j, Y g:i A') }}</span>
                </div>
                <div>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Address ID:</span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] ml-2 font-mono text-xs">{{ $address->id }}</span>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button type="reset" 
                            class="px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                        Reset Changes
                    </button>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.addresses.show', $address) }}" 
                       class="px-6 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-colors">
                        <i class='bx bx-save text-lg mr-2'></i>
                        Update Address
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    const userAddressInfo = document.getElementById('userAddressInfo');
    const userAddressCount = document.getElementById('userAddressCount');
    const postcodeInput = document.getElementById('postcode');
    const originalUserId = '{{ $address->user_id }}';

    // Auto-format postcode input
    postcodeInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 5);
    });

    // User selection change handler
    userSelect.addEventListener('change', function() {
        const userId = this.value;
        
        if (userId && userId !== originalUserId) {
            // Fetch user address information
            fetch(`{{ route('admin.addresses.user-addresses', ':userId') }}`.replace(':userId', userId))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const addressCount = data.data.total;
                        const primaryCount = data.data.primary_count;
                        
                        userAddressCount.textContent = `The selected user has ${addressCount} address(es), ${primaryCount} primary. Transferring this address will give them ${addressCount + 1} address(es).`;
                        userAddressInfo.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching user addresses:', error);
                    userAddressInfo.classList.add('hidden');
                });
        } else {
            userAddressInfo.classList.add('hidden');
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const postcode = postcodeInput.value;
        
        if (postcode.length !== 5) {
            e.preventDefault();
            alert('Please enter a valid 5-digit postcode.');
            postcodeInput.focus();
            return false;
        }

        // Confirm user change
        const selectedUserId = userSelect.value;
        if (selectedUserId !== originalUserId) {
            const selectedUserText = userSelect.options[userSelect.selectedIndex].text;
            if (!confirm(`Are you sure you want to transfer this address to ${selectedUserText}?`)) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endsection