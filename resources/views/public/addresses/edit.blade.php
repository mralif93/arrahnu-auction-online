@extends('layouts.app')

@section('title', 'Edit Address')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-[#0a0a09] py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Edit Address</h1>
            <p class="mt-2 text-[#706f6c] dark:text-[#A1A09A]">
                Update your address information
            </p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class='bx bx-map text-red-600 dark:text-red-400 text-xl'></i>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Address Information</h3>
                    </div>
                    @if($address->is_primary)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                            <i class='bx bx-star text-xs mr-1'></i>
                            Primary Address
                        </span>
                    @endif
                </div>
            </div>

            <form action="{{ route('addresses.update', $address) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Address Line 1 -->
                    <div>
                        <label for="address_line_1" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Address Line 1 <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="address_line_1"
                               name="address_line_1"
                               value="{{ old('address_line_1', $address->address_line_1) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors"
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
                    <div>
                        <label for="address_line_2" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Address Line 2
                        </label>
                        <input type="text"
                               id="address_line_2"
                               name="address_line_2"
                               value="{{ old('address_line_2', $address->address_line_2) }}"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors"
                               placeholder="Apartment, suite, unit, building, floor, etc. (optional)">
                        @error('address_line_2')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- City and State -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="city"
                                   name="city"
                                   value="{{ old('city', $address->city) }}"
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors"
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
                                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors"
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
                    </div>

                    <!-- Postcode and Country -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Postcode -->
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Postcode <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="postcode"
                                   name="postcode"
                                   value="{{ old('postcode', $address->postcode) }}"
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors"
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
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors"
                                   placeholder="Enter country"
                                   required>
                            @error('country')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class='bx bx-error-circle mr-1'></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Primary Address -->
                    @if(!$address->is_primary)
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="is_primary"
                                       name="is_primary"
                                       value="1"
                                       {{ old('is_primary') ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
                                <label for="is_primary" class="ml-2 block text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Set as primary address
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                Your primary address will be used as the default for deliveries and billing.
                            </p>
                        </div>
                    @else
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class='bx bx-info-circle text-blue-600 dark:text-blue-400 text-lg mr-2'></i>
                                <p class="text-blue-800 dark:text-blue-200 text-sm">
                                    This is your primary address. It will be used as the default for deliveries and billing.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 mt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('addresses.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                        <i class='bx bx-arrow-back text-lg mr-2'></i>
                        Back to Addresses
                    </a>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('addresses.show', $address) }}" 
                           class="px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            View Address
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            <i class='bx bx-save text-lg mr-2'></i>
                            Update Address
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Address History -->
        <div class="mt-6 bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Address Details</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Created:</span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] ml-2">{{ $address->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <div>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Last Updated:</span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] ml-2">{{ $address->updated_at->format('M j, Y g:i A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-format postcode input
document.getElementById('postcode').addEventListener('input', function(e) {
    // Remove any non-digit characters
    this.value = this.value.replace(/\D/g, '');
    
    // Limit to 5 digits
    if (this.value.length > 5) {
        this.value = this.value.slice(0, 5);
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const postcode = document.getElementById('postcode').value;
    
    if (postcode.length !== 5) {
        e.preventDefault();
        alert('Please enter a valid 5-digit postcode.');
        document.getElementById('postcode').focus();
        return false;
    }
});
</script>
@endsection 