@extends('layouts.app')

@section('title', 'Add New Address')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-[#0a0a09] py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Add New Address</h1>
            <p class="mt-2 text-[#706f6c] dark:text-[#A1A09A]">
                Add a new delivery or billing address to your account
            </p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center space-x-2">
                    <i class='bx bx-map text-red-600 dark:text-red-400 text-xl'></i>
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Address Information</h3>
                </div>
            </div>

            <form action="{{ route('addresses.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Address Line 1 -->
                    <div>
                        <label for="address_line_1" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Address Line 1 <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="address_line_1"
                               name="address_line_1"
                               value="{{ old('address_line_1') }}"
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
                               value="{{ old('address_line_2') }}"
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
                                   value="{{ old('city') }}"
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
                                <option value="Johor" {{ old('state') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                <option value="Kedah" {{ old('state') == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                <option value="Kelantan" {{ old('state') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                <option value="Kuala Lumpur" {{ old('state') == 'Kuala Lumpur' ? 'selected' : '' }}>Kuala Lumpur</option>
                                <option value="Labuan" {{ old('state') == 'Labuan' ? 'selected' : '' }}>Labuan</option>
                                <option value="Melaka" {{ old('state') == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                <option value="Negeri Sembilan" {{ old('state') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                <option value="Pahang" {{ old('state') == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                <option value="Penang" {{ old('state') == 'Penang' ? 'selected' : '' }}>Penang</option>
                                <option value="Perak" {{ old('state') == 'Perak' ? 'selected' : '' }}>Perak</option>
                                <option value="Perlis" {{ old('state') == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                <option value="Putrajaya" {{ old('state') == 'Putrajaya' ? 'selected' : '' }}>Putrajaya</option>
                                <option value="Sabah" {{ old('state') == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                <option value="Sarawak" {{ old('state') == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                <option value="Selangor" {{ old('state') == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                <option value="Terengganu" {{ old('state') == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
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
                                   value="{{ old('postcode') }}"
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
                                   value="{{ old('country', 'Malaysia') }}"
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
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 mt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('addresses.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                        <i class='bx bx-arrow-back text-lg mr-2'></i>
                        Back to Addresses
                    </a>

                    <div class="flex items-center space-x-3">
                        <button type="reset" 
                                class="px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Reset Form
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            <i class='bx bx-save text-lg mr-2'></i>
                            Save Address
                        </button>
                    </div>
                </div>
            </form>
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