@extends('layouts.admin')

@section('header-content')
    <div>
        <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Address Management</h1>
        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Manage user addresses across the platform</p>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.addresses.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-colors">
            <i class='bx bx-plus text-lg mr-2'></i>
            Add Address
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Addresses -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Total Addresses</p>
                    <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <i class='bx bx-map text-blue-600 dark:text-blue-400 text-xl'></i>
                </div>
            </div>
        </div>

        <!-- Primary Addresses -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Primary Addresses</p>
                    <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ number_format($stats['primary']) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg flex items-center justify-center">
                    <i class='bx bx-star text-yellow-600 dark:text-yellow-400 text-xl'></i>
                </div>
            </div>
        </div>

        <!-- Recent Addresses -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Recent (30 days)</p>
                    <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ number_format($stats['recent']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <i class='bx bx-time text-green-600 dark:text-green-400 text-xl'></i>
                </div>
            </div>
        </div>

        <!-- Top State -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Top State</p>
                    <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">
                        {{ $stats['by_state']->first()->state ?? 'N/A' }}
                    </p>
                    @if($stats['by_state']->first())
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $stats['by_state']->first()->count }} addresses</p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                    <i class='bx bx-map-pin text-purple-600 dark:text-purple-400 text-xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
        <form method="GET" action="{{ route('admin.addresses.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                        Search
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search addresses, users..."
                           class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent">
                </div>

                <!-- State Filter -->
                <div>
                    <label for="state" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                        State
                    </label>
                    <select id="state" 
                            name="state"
                            class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent">
                        <option value="">All States</option>
                        @foreach($states as $state)
                            <option value="{{ $state }}" {{ request('state') === $state ? 'selected' : '' }}>
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Primary Status Filter -->
                <div>
                    <label for="is_primary" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                        Primary Status
                    </label>
                    <select id="is_primary" 
                            name="is_primary"
                            class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent">
                        <option value="">All Addresses</option>
                        <option value="1" {{ request('is_primary') === '1' ? 'selected' : '' }}>Primary Only</option>
                        <option value="0" {{ request('is_primary') === '0' ? 'selected' : '' }}>Secondary Only</option>
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                        User
                    </label>
                    <select id="user_id" 
                            name="user_id"
                            class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->full_name ?? $user->username }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-colors">
                        <i class='bx bx-search text-lg mr-2'></i>
                        Search
                    </button>
                    <a href="{{ route('admin.addresses.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                        <i class='bx bx-refresh text-lg mr-2'></i>
                        Reset
                    </a>
                </div>
                
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Showing {{ $addresses->firstItem() ?? 0 }} to {{ $addresses->lastItem() ?? 0 }} of {{ $addresses->total() }} results
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    @if($addresses->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-4">
            <form id="bulkActionForm" method="POST" action="{{ route('admin.addresses.bulk-action') }}">
                @csrf
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-brand focus:ring-brand">
                            <span class="ml-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">Select All</span>
                        </label>
                        <span id="selectedCount" class="text-sm text-[#706f6c] dark:text-[#A1A09A]">0 selected</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <select name="action" id="bulkAction" class="px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent">
                            <option value="">Select Action</option>
                            <option value="delete">Delete Selected</option>
                            <option value="set_primary">Set as Primary</option>
                            <option value="unset_primary">Unset Primary</option>
                        </select>
                        <button type="submit" 
                                id="bulkActionBtn"
                                class="inline-flex items-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg transition-colors cursor-not-allowed"
                                disabled>
                            Apply Action
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Addresses Table -->
    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
        @if($addresses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                <input type="checkbox" class="rounded border-gray-300 text-brand focus:ring-brand">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                Address
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                        @foreach($addresses as $address)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a19] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" 
                                           name="addresses[]" 
                                           value="{{ $address->id }}" 
                                           class="address-checkbox rounded border-gray-300 text-brand focus:ring-brand"
                                           form="bulkActionForm">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-brand rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-medium text-sm">
                                                {{ strtoupper(substr($address->user->full_name ?? $address->user->username ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                {{ $address->user->full_name ?? $address->user->username }}
                                            </div>
                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                {{ $address->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                        {{ $address->address_line_1 }}
                                    </div>
                                    @if($address->address_line_2)
                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                            {{ $address->address_line_2 }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                        {{ $address->city }}, {{ $address->state }}
                                    </div>
                                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        {{ $address->postcode }}, {{ $address->country }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    {{ $address->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.addresses.show', $address) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('admin.addresses.edit', $address) }}" 
                                           class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200 transition-colors">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        @if(!$address->is_primary)
                                            <form action="{{ route('admin.addresses.set-primary', $address) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 transition-colors"
                                                        title="Set as Primary">
                                                    <i class='bx bx-star text-lg'></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.addresses.destroy', $address) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this address?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 transition-colors">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                {{ $addresses->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class='bx bx-map text-6xl text-[#706f6c] dark:text-[#A1A09A] mb-4'></i>
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    No Addresses Found
                </h3>
                <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
                    @if(request()->hasAny(['search', 'state', 'is_primary', 'user_id']))
                        No addresses match your current filters. Try adjusting your search criteria.
                    @else
                        No addresses have been created yet. Create the first address to get started.
                    @endif
                </p>
                <a href="{{ route('admin.addresses.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-colors">
                    <i class='bx bx-plus text-lg mr-2'></i>
                    Add First Address
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const addressCheckboxes = document.querySelectorAll('.address-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActionSelect = document.getElementById('bulkAction');
    const bulkActionBtn = document.getElementById('bulkActionBtn');

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.address-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCountSpan.textContent = `${count} selected`;
        
        // Update bulk action button state
        if (count > 0 && bulkActionSelect.value) {
            bulkActionBtn.disabled = false;
            bulkActionBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            bulkActionBtn.classList.add('bg-brand', 'hover:bg-brand-hover');
        } else {
            bulkActionBtn.disabled = true;
            bulkActionBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            bulkActionBtn.classList.remove('bg-brand', 'hover:bg-brand-hover');
        }
    }

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        addressCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox change
    addressCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Update select all checkbox
            const checkedCount = document.querySelectorAll('.address-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === addressCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < addressCheckboxes.length;
            
            updateSelectedCount();
        });
    });

    // Bulk action select change
    bulkActionSelect.addEventListener('change', updateSelectedCount);

    // Bulk action form submission
    document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.address-checkbox:checked');
        const action = bulkActionSelect.value;
        
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one address.');
            return;
        }
        
        if (!action) {
            e.preventDefault();
            alert('Please select an action.');
            return;
        }
        
        const actionText = action.replace('_', ' ');
        if (!confirm(`Are you sure you want to ${actionText} ${checkedBoxes.length} address(es)?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection 