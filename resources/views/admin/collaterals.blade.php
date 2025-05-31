@extends('layouts.admin')

@section('title', 'Collateral Management')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Collateral Management</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Welcome back, {{ Auth::user()->full_name ?? 'Administrator' }}</p>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-4">
        <div class="text-right">
            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ now()->format('l, F j, Y') }}</div>
            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ now()->format('g:i A') }}</div>
        </div>
    </div>
@endsection

@section('content')

                    <!-- Collateral Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <!-- Total Collaterals -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Items</p>
                                    <p class="text-3xl font-bold text-brand">{{ $totalCollaterals }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">All collateral items</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Active Collaterals -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Active Items</p>
                                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeCollaterals }}</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">Currently held</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Value -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Value</p>
                                    <p class="text-3xl font-bold text-brand">${{ number_format($totalValue, 0) }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Estimated value</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Categories</p>
                                    <p class="text-3xl font-bold text-[#706f6c] dark:text-[#A1A09A]">{{ $collaterals->pluck('item_type')->unique()->count() }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Item types</p>
                                </div>
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Collaterals List -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">All Collateral Items</h3>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage collateral items and their status</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- Filter Buttons -->
                                <div class="flex bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg p-1">
                                    <button onclick="filterCollaterals('all')" id="filter-all" class="px-3 py-1 text-sm font-medium rounded-md transition-colors bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] shadow-sm">
                                        All
                                    </button>
                                    <button onclick="filterCollaterals('active')" id="filter-active" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                        Active
                                    </button>
                                    <button onclick="filterCollaterals('auctioning')" id="filter-auctioning" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                        Auctioning
                                    </button>
                                    <button onclick="filterCollaterals('sold')" id="filter-sold" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                        Sold
                                    </button>
                                </div>

                                <!-- Add Collateral Button -->
                                <button onclick="openAddCollateralModal()" class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Collateral
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <table class="min-w-full divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Item Details
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Account & Branch
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Specifications
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Auction Values
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Status & Timing
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Created & Approved
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-[#161615] divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                    @foreach($collaterals as $collateral)
                                        <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors collateral-row" data-status="{{ $collateral->status }}">
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center mr-4">
                                                        <span class="text-brand font-medium text-sm">
                                                            {{ strtoupper(substr($collateral->item_type, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                            {{ $collateral->item_type }}
                                                        </div>
                                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                            {{ Str::limit($collateral->description, 30) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    {{ $collateral->account->account_title }}
                                                </div>
                                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                    {{ $collateral->account->branch->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="space-y-1">
                                                    @if($collateral->weight_grams)
                                                        <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                            Weight: {{ number_format($collateral->weight_grams, 2) }}g
                                                        </div>
                                                    @endif
                                                    @if($collateral->purity)
                                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                            Purity: {{ $collateral->purity }}
                                                        </div>
                                                    @endif
                                                    @if(!$collateral->weight_grams && !$collateral->purity)
                                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                            No specifications
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="space-y-1">
                                                    @if($collateral->estimated_value_rm)
                                                        <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                            Est: RM {{ number_format($collateral->estimated_value_rm, 2) }}
                                                        </div>
                                                    @endif
                                                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                        Start: RM {{ number_format($collateral->starting_bid_rm, 2) }}
                                                    </div>
                                                    @if($collateral->current_highest_bid_rm > 0)
                                                        <div class="text-sm font-medium text-green-600">
                                                            Current: RM {{ number_format($collateral->current_highest_bid_rm, 2) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="space-y-1">
                                                    <div>
                                                        @if($collateral->status === 'active')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                                                Active
                                                            </span>
                                                        @elseif($collateral->status === 'auctioning')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                                                Auctioning
                                                            </span>
                                                        @elseif($collateral->status === 'ready_for_auction')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200">
                                                                Ready for Auction
                                                            </span>
                                                        @elseif($collateral->status === 'sold')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200">
                                                                Sold
                                                            </span>
                                                        @elseif($collateral->status === 'pending_approval')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                                                Pending Approval
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                                                {{ ucfirst(str_replace('_', ' ', $collateral->status)) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($collateral->auction && $collateral->auction->start_datetime)
                                                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                            Auction: {{ $collateral->auction->start_datetime->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                    @if($collateral->auction && $collateral->auction->end_datetime)
                                                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                            Ends: {{ $collateral->auction->end_datetime->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="space-y-1">
                                                    <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                        {{ $collateral->created_at->format('M d, Y') }}
                                                    </div>
                                                    @if($collateral->creator)
                                                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                            By: {{ $collateral->creator->full_name }}
                                                        </div>
                                                    @endif
                                                    @if($collateral->approvedBy)
                                                        <div class="text-xs text-green-600">
                                                            Approved by: {{ $collateral->approvedBy->full_name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center space-x-2">
                                                    <!-- View Collateral -->
                                                    <button onclick="viewCollateral({{ $collateral->id }})"
                                                            class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                                        View
                                                    </button>

                                                    <!-- Approval Actions -->
                                                    @if($collateral->status === 'pending_approval')
                                                        <form method="POST" action="{{ route('admin.collaterals.approve', $collateral) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors"
                                                                    onclick="return confirm('Approve {{ $collateral->item_type }}?')">
                                                                Approve
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.collaterals.reject', $collateral) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                                    onclick="return confirm('Reject {{ $collateral->item_type }}?')">
                                                                Reject
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Auction Actions -->
                                                    @if(in_array($collateral->status, ['active', 'ready_for_auction']))
                                                        <form method="POST" action="{{ route('admin.collaterals.start-auction', $collateral) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200 text-xs font-medium rounded-full hover:bg-purple-200 dark:hover:bg-purple-900/40 transition-colors"
                                                                    onclick="return confirm('Start auction for {{ $collateral->item_type }}?')">
                                                                Start Auction
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($collateral->status === 'auctioning')
                                                        <form method="POST" action="{{ route('admin.collaterals.end-auction', $collateral) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-200 text-xs font-medium rounded-full hover:bg-orange-200 dark:hover:bg-orange-900/40 transition-colors"
                                                                    onclick="return confirm('End auction for {{ $collateral->item_type }}?')">
                                                                End Auction
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Delete Collateral -->
                                                    @if(!in_array($collateral->status, ['auctioning', 'sold']))
                                                        <form method="POST" action="{{ route('admin.collaterals.destroy', $collateral) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                                    onclick="return confirm('Are you sure you want to delete {{ $collateral->item_type }}? This action cannot be undone.')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($collaterals->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No collateral items</h3>
                                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">Get started by adding a new collateral item.</p>
                                <div class="mt-6">
                                    <button onclick="openAddCollateralModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover">
                                        Add Collateral
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
@endsection

@push('scripts')
<script>
    // Collateral filtering functionality
    function filterCollaterals(status) {
        const rows = document.querySelectorAll('.collateral-row');
        const filterButtons = document.querySelectorAll('[id^="filter-"]');

        // Update button states
        filterButtons.forEach(btn => {
            btn.classList.remove('bg-white', 'dark:bg-[#161615]', 'text-[#1b1b18]', 'dark:text-[#EDEDEC]', 'shadow-sm');
            btn.classList.add('text-[#706f6c]', 'dark:text-[#A1A09A]');
        });

        const activeButton = document.getElementById(`filter-${status}`);
        activeButton.classList.remove('text-[#706f6c]', 'dark:text-[#A1A09A]');
        activeButton.classList.add('bg-white', 'dark:bg-[#161615]', 'text-[#1b1b18]', 'dark:text-[#EDEDEC]', 'shadow-sm');

        // Filter rows
        rows.forEach(row => {
            const collateralStatus = row.getAttribute('data-status');
            if (status === 'all' || collateralStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Placeholder functions for modals and actions
    function openAddCollateralModal() {
        alert('Add Collateral Modal - To be implemented');
    }

    function viewCollateral(collateralId) {
        alert('View Collateral Details - Collateral ID: ' + collateralId);
    }
</script>
@endpush
