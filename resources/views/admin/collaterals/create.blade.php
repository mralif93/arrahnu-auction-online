@extends('layouts.admin')

@section('title', 'Create New Collateral')

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
                        <span class="ml-1 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] md:ml-2">Create New Collateral</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Create New Collateral</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Add a new collateral item to the system</p>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">•</span>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Fill in the required information below</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.collaterals.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Collaterals
                </a>
            </div>
        </div>

        <!-- Create Form -->
        <form action="{{ route('admin.collaterals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Basic Information Section -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Basic Information</h3>
                        <span class="text-sm text-red-500">* Required fields</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Account and Auction Selection -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Account Selection -->
                            <div>
                                <label for="account_id" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Account <span class="text-red-500">*</span>
                                </label>
                                <select name="account_id" id="account_id" required
                                        class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('account_id') border-red-500 @enderror">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_title }} - {{ $account->branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Select the account this collateral belongs to
                                </p>
                            </div>

                            <!-- Auction Selection -->
                            <div>
                                <label for="auction_id" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Auction <span class="text-red-500">*</span>
                                </label>
                                <select name="auction_id" id="auction_id" required
                                        class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('auction_id') border-red-500 @enderror">
                                    <option value="">Select Auction</option>
                                    @foreach($auctions as $auction)
                                        <option value="{{ $auction->id }}" {{ old('auction_id') == $auction->id ? 'selected' : '' }}>
                                            {{ $auction->auction_title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('auction_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Select the auction where this collateral will be listed
                                </p>
                            </div>
                        </div>

                        <!-- Item Type -->
                        <div>
                            <label for="item_type" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Item Type <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="item_type" id="item_type" required maxlength="50"
                                   value="{{ old('item_type') }}"
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('item_type') border-red-500 @enderror"
                                   placeholder="Enter item type (e.g., Gold Ring, Diamond Necklace)">
                            @error('item_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Be specific about the type of collateral item
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Details Section -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Item Details</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Weight and Purity -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Weight -->
                            <div>
                                <label for="weight_grams" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Weight (grams)
                                </label>
                                <input type="number"
                                       id="weight_grams"
                                       name="weight_grams"
                                       step="0.01"
                                       min="0"
                                       value="{{ old('weight_grams') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter weight in grams (e.g., 15.50)">
                                @error('weight_grams')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Weight of the item in grams (optional)
                                </p>
                            </div>

                            <!-- Purity -->
                            <div>
                                <label for="purity" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Purity
                                </label>
                                <input type="text"
                                       id="purity"
                                       name="purity"
                                       maxlength="20"
                                       value="{{ old('purity') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter purity (e.g., 18K, 22K, 999)">
                                @error('purity')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Purity level of the material (optional)
                                </p>
                            </div>
                        </div>

                        <!-- Estimated Value and Starting Bid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Estimated Value -->
                            <div>
                                <label for="estimated_value_rm" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Estimated Value (RM)
                                </label>
                                <input type="number"
                                       id="estimated_value_rm"
                                       name="estimated_value_rm"
                                       step="0.01"
                                       min="0"
                                       value="{{ old('estimated_value_rm') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter estimated value in RM (e.g., 5000.00)">
                                @error('estimated_value_rm')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Estimated market value in Malaysian Ringgit
                                </p>
                            </div>

                            <!-- Starting Bid -->
                            <div>
                                <label for="starting_bid_rm" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    Starting Bid (RM) <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="starting_bid_rm"
                                       name="starting_bid_rm"
                                       step="0.01"
                                       min="1"
                                       value="{{ old('starting_bid_rm') }}"
                                       class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                       placeholder="Enter starting bid amount (e.g., 1000.00)"
                                       required>
                                @error('starting_bid_rm')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    Minimum bid amount to start the auction
                                </p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                      placeholder="Enter detailed description of the collateral item"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Provide detailed description including condition and special features
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Images</h3>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Images -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Upload Images (Max 5)
                        </label>
                        <input type="file"
                               id="images"
                               name="images[]"
                               multiple
                               accept="image/*"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors">
                        @error('images')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                            Upload up to 5 images (JPEG, PNG, JPG, GIF). Maximum 2MB each. Clear photos from multiple angles are recommended.
                        </p>
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
                                <h5 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-1">Before Creating Collateral</h5>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                    <li>• Ensure all required fields are filled correctly</li>
                                    <li>• Select the appropriate account and auction</li>
                                    <li>• Be specific about the item type and description</li>
                                    <li>• Upload clear images from multiple angles</li>
                                    <li>• Set a reasonable starting bid amount</li>
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
                            <a href="{{ route('admin.collaterals.index') }}"
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
                                Create Collateral
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Guidelines -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Collateral Creation Guidelines</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Item Information Requirements</h4>
                            <ul class="text-sm text-[#706f6c] dark:text-[#A1A09A] space-y-1">
                                <li>• Select the appropriate account and auction</li>
                                <li>• Be specific about the item type (e.g., Gold Ring, Diamond Necklace)</li>
                                <li>• Provide detailed description including condition</li>
                                <li>• Include weight and purity if applicable</li>
                                <li>• Set a reasonable starting bid amount</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Image Guidelines</h4>
                            <ul class="text-sm text-[#706f6c] dark:text-[#A1A09A] space-y-1">
                                <li>• Upload clear, high-quality images</li>
                                <li>• Take photos from multiple angles</li>
                                <li>• Maximum 5 images, 2MB each</li>
                                <li>• Supported formats: JPEG, PNG, JPG, GIF</li>
                                <li>• Good lighting improves image quality</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    // File input validation
    document.getElementById('images').addEventListener('change', function(event) {
        const files = event.target.files;
        if (files.length > 5) {
            alert('You can only upload a maximum of 5 images.');
            event.target.value = '';
            return;
        }
        
        for (let file of files) {
            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert(`File ${file.name} is too large. Maximum size is 2MB.`);
                event.target.value = '';
                return;
            }
        }
    });
</script>
@endpush
