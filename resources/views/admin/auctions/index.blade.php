@extends('layouts.admin')

@section('title', 'Auction Management')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Auction Management</h1>
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

                    <!-- Auction Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <!-- Total Auctions -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Auctions</p>
                                    <p class="text-3xl font-bold text-brand">{{ $auctions->count() }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">All auction events</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Active Auctions -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Active Auctions</p>
                                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeAuctions }}</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">Currently live</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Bid Value -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Bid Value</p>
                                    <p class="text-3xl font-bold text-brand">RM {{ number_format($totalBidValue, 0) }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">All bids placed</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Auctions -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Completed Auctions</p>
                                    <p class="text-3xl font-bold text-[#706f6c] dark:text-[#A1A09A]">{{ $completedAuctions }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Successfully finished</p>
                                </div>
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Auctions List -->
                    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">All Auctions</h3>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Monitor auction progress and results</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <!-- Filter Buttons -->
                                    <div class="flex bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg p-1">
                                        <button onclick="filterAuctions('all')" id="filter-all" class="px-3 py-1 text-sm font-medium rounded-md transition-colors bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] shadow-sm">
                                            All
                                        </button>
                                        <button onclick="filterAuctions('draft')" id="filter-draft" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                            Draft
                                        </button>
                                        <button onclick="filterAuctions('pending_approval')" id="filter-pending_approval" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                            Pending
                                        </button>
                                        <button onclick="filterAuctions('active')" id="filter-active" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                            Active
                                        </button>
                                        <button onclick="filterAuctions('completed')" id="filter-completed" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                            Completed
                                        </button>
                                    </div>

                                    <!-- Add Auction Button -->
                                    <a href="{{ route('admin.auctions.create') }}" class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer" style="background-color: #FE5000; position: relative; z-index: 10;" onmouseover="this.style.backgroundColor='#E5470A'" onmouseout="this.style.backgroundColor='#FE5000'">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add Auction
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Auction Details
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Items & Creator
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Values & Bids
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Auction Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Schedule & Activity
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                    <tbody class="bg-white dark:bg-[#161615] divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                        @foreach($auctions as $auction)
                            <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors auction-row" data-status="{{ $auction->status }}">
                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center mr-4">
                                            <span class="text-brand font-medium text-sm">
                                                {{ strtoupper(substr($auction->auction_title, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                {{ $auction->auction_title }}
                                            </div>
                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                {{ Str::limit($auction->description ?? 'Auction Event', 40) }}
                                            </div>
                                            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                ID: {{ Str::limit($auction->id, 8) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                        {{ $auction->collaterals->count() }} collateral items
                                    </div>
                                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        @if($auction->collaterals->count() > 0)
                                            {{ $auction->collaterals->pluck('item_type')->unique()->take(3)->implode(', ') }}
                                            @if($auction->collaterals->pluck('item_type')->unique()->count() > 3)
                                                <span class="text-xs">+{{ $auction->collaterals->pluck('item_type')->unique()->count() - 3 }} more</span>
                                            @endif
                                        @else
                                            No items yet
                                        @endif
                                    </div>
                                    <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                        Created by {{ $auction->creator->full_name ?? 'System' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                            RM {{ number_format($auction->collaterals->sum('estimated_value_rm'), 2) }}
                                        </div>
                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                            @if($auction->collaterals->sum('current_highest_bid_rm') > 0)
                                                Current: RM {{ number_format($auction->collaterals->sum('current_highest_bid_rm'), 2) }}
                                            @else
                                                No bids yet
                                            @endif
                                        </div>
                                        <div class="text-xs">
                                            @if($auction->collaterals->count() > 0)
                                                <a href="{{ route('admin.auctions.show', $auction) }}"
                                                   class="text-brand hover:text-brand-hover transition-colors">
                                                    View Items →
                                                </a>
                                            @else
                                                <span class="text-[#706f6c] dark:text-[#A1A09A]">No items added</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    @if($auction->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Live Auction
                                        </span>
                                    @elseif($auction->status === 'pending_approval')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Pending
                                        </span>
                                    @elseif($auction->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-900/20 text-gray-800 dark:text-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            {{ ucfirst(str_replace('_', ' ', $auction->status)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    <div class="space-y-1">
                                        @if($auction->start_datetime)
                                            <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                {{ $auction->start_datetime->format('M d, Y') }}
                                            </div>
                                        @else
                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                Not scheduled
                                            </div>
                                        @endif
                                        @php
                                            $totalBids = $auction->collaterals->sum(function($c) { return $c->bids->count(); });
                                            $activeBidders = $auction->collaterals->flatMap->bids->pluck('user_id')->unique()->count();
                                        @endphp
                                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                            {{ $totalBids }} bids from {{ $activeBidders }} bidders
                                        </div>
                                        @if($auction->creator)
                                            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                Created by: {{ $auction->creator->full_name }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-2">
                                        <!-- View -->
                                        <a href="{{ route('admin.auctions.show', $auction) }}"
                                           class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                            View
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.auctions.edit', $auction) }}"
                                           class="px-3 py-1 bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200 text-xs font-medium rounded-full hover:bg-purple-200 dark:hover:bg-purple-900/40 transition-colors">
                                            Edit
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.auctions.destroy', $auction) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                    onclick="return confirm('Are you sure you want to delete {{ $auction->auction_title }}? This action cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                        </div>

                        @if($auctions->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No auctions</h3>
                                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">Get started by creating a new auction event.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.auctions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover">
                                        Add Auction
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
@endsection

@push('scripts')
<script>
    function filterAuctions(status) {
        const rows = document.querySelectorAll('.auction-row');
        const buttons = document.querySelectorAll('[id^="filter-"]');

        // Update button styles
        buttons.forEach(btn => {
            btn.classList.remove('bg-white', 'dark:bg-[#161615]', 'text-[#1b1b18]', 'dark:text-[#EDEDEC]', 'shadow-sm');
            btn.classList.add('text-[#706f6c]', 'dark:text-[#A1A09A]');
        });

        document.getElementById(`filter-${status}`).classList.add('bg-white', 'dark:bg-[#161615]', 'text-[#1b1b18]', 'dark:text-[#EDEDEC]', 'shadow-sm');
        document.getElementById(`filter-${status}`).classList.remove('text-[#706f6c]', 'dark:text-[#A1A09A]');

        // Filter rows
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
