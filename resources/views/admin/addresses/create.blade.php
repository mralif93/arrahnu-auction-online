@extends('layouts.admin')

@section('title', 'Create New Address')

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
                        <span class="ml-1 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] md:ml-2">Create New Address</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Create New Address</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Add a new address to the system</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Fill in the required information below</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.addresses.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Addresses
                </a>
            </div>
        </div>

        <!-- Create Form -->
        <form action="{{ route('admin.addresses.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- User Selection Section -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">User Selection</h3>
                        <span class="text-sm text-red-500">* Required fields</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- User Selection -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                User <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" id="user_id" required
                                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('user_id') border-red-500 @enderror">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->full_name ?? $user->username }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Select the user this address belongs to
                            </p>
                        </div>

                        <!-- User Address Count (Dynamic) -->
                        <div id="userAddressInfo" class="hidden">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-blue-800 dark:text-blue-200 text-sm font-medium">User Address Information</p>
                                        <p id="userAddressCount" class="text-blue-700 dark:text-blue-300 text-sm"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information Section -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Address Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Address Line 1 -->
                        <div>
                            <label for="address_line_1" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Address Line 1 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address_line_1" id="address_line_1" required maxlength="255"
                                   value="{{ old('address_line_1') }}"
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('address_line_1') border-red-500 @enderror"
                                   placeholder="Enter street address">
                            @error('address_line_1')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Primary street address or building name
                            </p>
                        </div>

                        <!-- Address Line 2 -->
                        <div>
                            <label for="address_line_2" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Address Line 2
                            </label>
                            <input type="text" name="address_line_2" id="address_line_2" maxlength="255"
                                   value="{{ old('address_line_2') }}"
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                   placeholder="Apartment, suite, unit, building, floor, etc. (optional)">
                            @error('address_line_2')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Additional address information (optional)
                            </p>
                        </div>

                        <!-- City and State -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="city" id="city" required maxlength="100"
                                       value="{{ old('city') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('city') border-red-500 @enderror"
                                       placeholder="Enter city">
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- State -->
                            <div>
                                <label for="state" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <select name="state" id="state" required
                                        class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('state') border-red-500 @enderror">
                                    <option value="">Select State</option>
                                    @php
                                        $states = ['Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Labuan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Putrajaya', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu'];
                                    @endphp
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                                @error('state')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Postcode and Country -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Postcode -->
                            <div>
                                <label for="postcode" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Postcode <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="postcode" id="postcode" required pattern="[0-9]{5}" maxlength="5"
                                       value="{{ old('postcode') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('postcode') border-red-500 @enderror"
                                       placeholder="Enter 5-digit postcode">
                                @error('postcode')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Malaysian postcode (5 digits)
                                </p>
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="country" id="country" required maxlength="100"
                                       value="{{ old('country', 'Malaysia') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('country') border-red-500 @enderror"
                                       placeholder="Enter country">
                                @error('country')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Settings Section -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Address Settings</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Primary Address -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_primary" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
                                <label for="is_primary" class="ml-2 block text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Set as primary address
                                </label>
                            </div>
                            @error('is_primary')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Primary address is used for billing and delivery by default. If user has no addresses, this will automatically be set as primary.
                            </p>
                        </div>

                        <!-- Primary Address Warning -->
                        <div id="primaryWarning" class="hidden">
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-yellow-800 dark:text-yellow-200 text-sm font-medium">Primary Address Notice</p>
                                        <p id="primaryWarningText" class="text-yellow-700 dark:text-yellow-300 text-sm"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button type="reset" 
                                class="px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Reset Form
                        </button>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.addresses.index') }}" 
                           class="px-6 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer"
                                style="background-color: #FE5000; position: relative; z-index: 10;"
                                onmouseover="this.style.backgroundColor='#E5470A'"
                                onmouseout="this.style.backgroundColor='#FE5000'">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Address
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
    const isPrimaryCheckbox = document.getElementById('is_primary');
    const primaryWarning = document.getElementById('primaryWarning');
    const primaryWarningText = document.getElementById('primaryWarningText');

    // Auto-format postcode input
    postcodeInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 5);
    });

    // User selection change handler
    userSelect.addEventListener('change', function() {
        const userId = this.value;
        
        if (userId) {
            // Fetch user address information
            fetch(`{{ route('admin.addresses.user-addresses', ':userId') }}`.replace(':userId', userId))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const addressCount = data.data.total;
                        const primaryCount = data.data.primary_count;
                        
                        userAddressCount.textContent = `The selected user has ${addressCount} address(es), ${primaryCount} primary.`;
                        userAddressInfo.classList.remove('hidden');

                        // Handle primary address logic
                        if (addressCount === 0) {
                            isPrimaryCheckbox.checked = true;
                            isPrimaryCheckbox.disabled = true;
                            primaryWarningText.textContent = 'This will be the user\'s first address and will automatically be set as primary.';
                            primaryWarning.classList.remove('hidden');
                        } else {
                            isPrimaryCheckbox.disabled = false;
                            if (isPrimaryCheckbox.checked && primaryCount > 0) {
                                primaryWarningText.textContent = 'Setting this as primary will unset the current primary address for this user.';
                                primaryWarning.classList.remove('hidden');
                            } else {
                                primaryWarning.classList.add('hidden');
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching user addresses:', error);
                    userAddressInfo.classList.add('hidden');
                    primaryWarning.classList.add('hidden');
                });
        } else {
            userAddressInfo.classList.add('hidden');
            primaryWarning.classList.add('hidden');
            isPrimaryCheckbox.disabled = false;
        }
    });

    // Primary checkbox change handler
    isPrimaryCheckbox.addEventListener('change', function() {
        const userId = userSelect.value;
        if (userId && this.checked) {
            // Check if user has existing primary address
            fetch(`{{ route('admin.addresses.user-addresses', ':userId') }}`.replace(':userId', userId))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.primary_count > 0) {
                        primaryWarningText.textContent = 'Setting this as primary will unset the current primary address for this user.';
                        primaryWarning.classList.remove('hidden');
                    } else {
                        primaryWarning.classList.add('hidden');
                    }
                });
        } else {
            primaryWarning.classList.add('hidden');
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
    });
});
</script>
@endsection 