@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-[#0a0a09] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">My Addresses</h1>
                    <p class="mt-2 text-[#706f6c] dark:text-[#A1A09A]">
                        Manage your delivery and billing addresses
                    </p>
                </div>
                <a href="{{ route('addresses.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    <i class='bx bx-plus text-lg mr-2'></i>
                    Add New Address
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class='bx bx-check-circle text-green-600 dark:text-green-400 text-xl mr-3'></i>
                    <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class='bx bx-error-circle text-red-600 dark:text-red-400 text-xl mr-3'></i>
                    <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Addresses Grid -->
        @if($addresses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($addresses as $address)
                    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Address Header -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class='bx bx-map text-red-600 dark:text-red-400 text-xl'></i>
                                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                        Address {{ $loop->iteration }}
                                    </h3>
                                </div>
                                @if($address->is_primary)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                        <i class='bx bx-star text-xs mr-1'></i>
                                        Primary
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Address Content -->
                        <div class="p-6">
                            <div class="space-y-2 mb-4">
                                <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
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
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    {{ $address->country }}
                                </p>
                            </div>

                            <!-- Address Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('addresses.edit', $address) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-sm bg-gray-100 dark:bg-[#2a2a28] hover:bg-gray-200 dark:hover:bg-[#3a3a38] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg transition-colors">
                                        <i class='bx bx-edit text-sm mr-1'></i>
                                        Edit
                                    </a>
                                    <a href="{{ route('addresses.show', $address) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-sm bg-gray-100 dark:bg-[#2a2a28] hover:bg-gray-200 dark:hover:bg-[#3a3a38] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg transition-colors">
                                        <i class='bx bx-show text-sm mr-1'></i>
                                        View
                                    </a>
                                </div>

                                <div class="flex items-center space-x-2">
                                    @if(!$address->is_primary)
                                        <form action="{{ route('addresses.set-primary', $address) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 text-sm bg-blue-100 dark:bg-blue-900/20 hover:bg-blue-200 dark:hover:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-lg transition-colors">
                                                <i class='bx bx-star text-sm mr-1'></i>
                                                Set Primary
                                            </button>
                                        </form>
                                    @endif

                                    @if($addresses->count() > 1)
                                        <button onclick="confirmDelete('{{ $address->id }}', '{{ $address->address_line_1 }}')" 
                                                class="inline-flex items-center px-3 py-1.5 text-sm bg-red-100 dark:bg-red-900/20 hover:bg-red-200 dark:hover:bg-red-900/30 text-red-800 dark:text-red-200 rounded-lg transition-colors">
                                            <i class='bx bx-trash text-sm mr-1'></i>
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-8">
                    <i class='bx bx-map text-6xl text-[#706f6c] dark:text-[#A1A09A] mb-4'></i>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        No Addresses Found
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
                        You haven't added any addresses yet. Add your first address to get started.
                    </p>
                    <a href="{{ route('addresses.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <i class='bx bx-plus text-lg mr-2'></i>
                        Add Your First Address
                    </a>
                </div>
            </div>
        @endif

        <!-- Back to Profile -->
        <div class="mt-8 text-center">
            <a href="{{ route('profile.edit') }}" 
               class="inline-flex items-center text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                <i class='bx bx-arrow-back text-lg mr-2'></i>
                Back to Profile
            </a>
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
            Are you sure you want to delete the address "<span id="addressToDelete"></span>"? This action cannot be undone.
        </p>
        <div class="flex items-center justify-end space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="inline">
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
function confirmDelete(addressId, addressLine) {
    document.getElementById('addressToDelete').textContent = addressLine;
    document.getElementById('deleteForm').action = `/addresses/${addressId}`;
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