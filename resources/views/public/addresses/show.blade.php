@extends('layouts.app')

@section('title', 'Address Details')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-[#0a0a09] py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Address Details</h1>
            <p class="mt-2 text-[#706f6c] dark:text-[#A1A09A]">
                View your address information
            </p>
        </div>

        <!-- Address Card -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class='bx bx-map text-red-600 dark:text-red-400 text-xl'></i>
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Address Information</h3>
                    </div>
                    @if($address->is_primary)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                            <i class='bx bx-star text-sm mr-1'></i>
                            Primary Address
                        </span>
                    @endif
                </div>
            </div>

            <!-- Address Content -->
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Full Address Display -->
                    <div class="bg-gray-50 dark:bg-[#1a1a19] rounded-lg p-4">
                        <h4 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Complete Address</h4>
                        <div class="space-y-2">
                            <p class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $address->address_line_1 }}
                            </p>
                            @if($address->address_line_2)
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                                    {{ $address->address_line_2 }}
                                </p>
                            @endif
                            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                                {{ $address->city }}, {{ $address->state }} {{ $address->postcode }}
                            </p>
                            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                                {{ $address->country }}
                            </p>
                        </div>
                        
                        <!-- Copy Address Button -->
                        <button onclick="copyAddress()" 
                                class="mt-3 inline-flex items-center px-3 py-1.5 text-sm bg-gray-100 dark:bg-[#2a2a28] hover:bg-gray-200 dark:hover:bg-[#3a3a38] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg transition-colors">
                            <i class='bx bx-copy text-sm mr-1'></i>
                            Copy Address
                        </button>
                    </div>

                    <!-- Address Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Street Address -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Street Address
                            </label>
                            <div class="space-y-1">
                                <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->address_line_1 }}</p>
                                @if($address->address_line_2)
                                    <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">{{ $address->address_line_2 }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                City
                            </label>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->city }}</p>
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                State
                            </label>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->state }}</p>
                        </div>

                        <!-- Postcode -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Postcode
                            </label>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->postcode }}</p>
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Country
                            </label>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->country }}</p>
                        </div>

                        <!-- Address Type -->
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Address Type
                            </label>
                            <div class="flex items-center">
                                @if($address->is_primary)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                        <i class='bx bx-star text-xs mr-1'></i>
                                        Primary
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                        <i class='bx bx-map-pin text-xs mr-1'></i>
                                        Secondary
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 mt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('addresses.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                        <i class='bx bx-arrow-back text-lg mr-2'></i>
                        Back to Addresses
                    </a>

                    <div class="flex items-center space-x-3">
                        @if(!$address->is_primary)
                            <form action="{{ route('addresses.set-primary', $address) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/20 hover:bg-blue-200 dark:hover:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-lg transition-colors">
                                    <i class='bx bx-star text-lg mr-2'></i>
                                    Set as Primary
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('addresses.edit', $address) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-[#2a2a28] hover:bg-gray-200 dark:hover:bg-[#3a3a38] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg transition-colors">
                            <i class='bx bx-edit text-lg mr-2'></i>
                            Edit Address
                        </a>

                        @if(auth()->user()->addresses()->count() > 1)
                            <button onclick="confirmDelete()" 
                                    class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-900/20 hover:bg-red-200 dark:hover:bg-red-900/30 text-red-800 dark:text-red-200 rounded-lg transition-colors">
                                <i class='bx bx-trash text-lg mr-2'></i>
                                Delete Address
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Metadata -->
        <div class="mt-6 bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <h4 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Address Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h5 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Created</h5>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->created_at->format('F j, Y') }}</p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $address->created_at->format('g:i A') }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Last Updated</h5>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $address->updated_at->format('F j, Y') }}</p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $address->updated_at->format('g:i A') }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Address ID</h5>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-mono text-sm">{{ $address->id }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">Status</h5>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 max-w-md mx-4">
        <div class="flex items-center mb-4">
            <i class='bx bx-error-circle text-red-600 dark:text-red-400 text-2xl mr-3'></i>
            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Confirm Delete</h3>
        </div>
        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
            Are you sure you want to delete this address? This action cannot be undone.
        </p>
        <div class="flex items-center justify-end space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                Cancel
            </button>
            <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Delete Address
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function copyAddress() {
    const address = `{{ $address->full_address }}`;
    navigator.clipboard.writeText(address).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="bx bx-check text-sm mr-1"></i>Copied!';
        button.classList.add('bg-green-100', 'dark:bg-green-900/20', 'text-green-800', 'dark:text-green-200');
        button.classList.remove('bg-gray-100', 'dark:bg-[#2a2a28]', 'text-[#1b1b18]', 'dark:text-[#EDEDEC]');

        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('bg-green-100', 'dark:bg-green-900/20', 'text-green-800', 'dark:text-green-200');
            button.classList.add('bg-gray-100', 'dark:bg-[#2a2a28]', 'text-[#1b1b18]', 'dark:text-[#EDEDEC]');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy address: ', err);
        alert('Failed to copy address to clipboard');
    });
}

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection 