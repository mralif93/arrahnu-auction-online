@extends('layouts.admin')

@section('title', 'Create New Branch')

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
                        <span class="ml-1 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] md:ml-2">Create New Branch</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Create New Branch</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Add a new branch location to the system</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Fill in the required information below</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.branches.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Branches
                </a>
            </div>
        </div>

        <!-- Create Form -->
        <form action="{{ route('admin.branches.store') }}" method="POST" id="createBranchForm" class="space-y-6">
            @csrf

            <!-- Basic Information Section -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Basic Information</h3>
                        <span class="text-sm text-red-500">* Required fields</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Branch Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Branch Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="Enter branch name (e.g., Arrahnu Kuala Lumpur)"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Branch name must be unique across all branches
                            </p>
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Phone Number
                            </label>
                            <input type="tel"
                                   id="phone_number"
                                   name="phone_number"
                                   value="{{ old('phone_number') }}"
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="Enter phone number (e.g., +603-2141-8888)">
                            @error('phone_number')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Optional contact number for the branch
                            </p>
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
                        <!-- Address Lines -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Address Line 1 -->
                            <div>
                                <label for="address_line_1" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Address Line 1 <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="address_line_1"
                                       name="address_line_1"
                                       value="{{ old('address_line_1') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter street address"
                                       required>
                                @error('address_line_1')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Primary street address of the branch
                                </p>
                            </div>

                            <!-- Address Line 2 -->
                            <div>
                                <label for="address_line_2" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Address Line 2
                                </label>
                                <input type="text"
                                       id="address_line_2"
                                       name="address_line_2"
                                       value="{{ old('address_line_2') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter additional address details (optional)">
                                @error('address_line_2')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Optional additional address information
                                </p>
                            </div>
                        </div>

                        <!-- City, State, Postcode -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="city"
                                       name="city"
                                       value="{{ old('city') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter city"
                                       required>
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    City where the branch is located
                                </p>
                            </div>

                            <!-- State -->
                            <div>
                                <label for="state" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="state"
                                       name="state"
                                       value="{{ old('state') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter state"
                                       required>
                                @error('state')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    State or province
                                </p>
                            </div>

                            <!-- Postcode -->
                            <div>
                                <label for="postcode" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Postcode <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="postcode"
                                       name="postcode"
                                       value="{{ old('postcode') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter postcode"
                                       required>
                                @error('postcode')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Postal code for the area
                                </p>
                            </div>
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="country"
                                   name="country"
                                   value="{{ old('country', 'Malaysia') }}"
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                   placeholder="Enter country"
                                   required>
                            @error('country')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Country where the branch is located
                            </p>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Form Actions -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Review & Submit</h3>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Guidelines -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h5 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-1">Before Creating Branch</h5>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                    <li>• Ensure all required fields are filled correctly</li>
                                    <li>• Verify the branch name is unique</li>
                                    <li>• Check that the address is complete and accurate</li>
                                    <li>• Review all information before submitting</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <span class="text-red-500">*</span> Required fields must be completed
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.branches.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
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
                                Create Branch
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Form Guidelines -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Branch Creation Guidelines</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Branch Information Requirements</h4>
                        <ul class="text-sm text-[#706f6c] dark:text-[#A1A09A] space-y-1">
                            <li>• Branch names must be unique across the system</li>
                            <li>• Use descriptive names that identify the location</li>
                            <li>• Include company name if applicable</li>
                            <li>• Phone number is optional but recommended</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Address Guidelines</h4>
                        <ul class="text-sm text-[#706f6c] dark:text-[#A1A09A] space-y-1">
                            <li>• Provide complete and accurate address information</li>
                            <li>• Address Line 2 is optional for additional details</li>
                            <li>• Ensure city, state, and postcode are correct</li>
                            <li>• Country defaults to Malaysia but can be changed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
