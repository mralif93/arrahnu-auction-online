@extends('layouts.admin')

@section('title', 'Auction Management')

@section('header-content')
    <div>
        <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Auction Management</h1>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">Manage active and completed auctions</p>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Active Auctions -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Active Auctions</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $activeAuctions }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Auctions -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Completed</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $completedAuctions }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sold Items -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Sold Items</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $soldAuctions }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Bid Value -->
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Total Bid Value</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">RM {{ number_format($totalBidValue, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auctions Table -->
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
                            <button onclick="filterAuctions('active')" id="filter-active" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                Active
                            </button>
                            <button onclick="filterAuctions('completed')" id="filter-completed" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                Completed
                            </button>
                            <button onclick="filterAuctions('scheduled')" id="filter-scheduled" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                Scheduled
                            </button>
                        </div>

                        <!-- View Results Button -->
                        <a href="{{ route('admin.auctions.results') }}" class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 00-2-2m0 0V9a2 2 0 012-2h2a2 2 0 00-2-2"></path>
                            </svg>
                            View Results
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                Auction Event
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                Branch & Items
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                Total Values
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                Schedule & Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                Bidding Activity
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
                                    <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                        {{ $auction->branch->name }}
                                    </div>
                                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        {{ $auction->collaterals->count() }} collateral items
                                    </div>
                                    <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                        {{ $auction->collaterals->pluck('item_type')->unique()->implode(', ') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    <div class="space-y-1">
                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                            Estimated: RM {{ number_format($auction->collaterals->sum('estimated_value_rm'), 2) }}
                                        </div>
                                        @if($auction->collaterals->sum('current_highest_bid_rm') > 0)
                                            <div class="text-sm font-medium text-green-600">
                                                Current: RM {{ number_format($auction->collaterals->sum('current_highest_bid_rm'), 2) }}
                                            </div>
                                        @else
                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                No bids yet
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    <div class="space-y-1">
                                        <div>
                                            @if($auction->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                                    Live Auction
                                                </span>
                                            @elseif($auction->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200">
                                                    Completed
                                                </span>
                                            @elseif($auction->status === 'scheduled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                                    Scheduled
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                                    {{ ucfirst(str_replace('_', ' ', $auction->status)) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($auction->start_datetime)
                                            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                @if($auction->status === 'scheduled')
                                                    Starts: {{ $auction->start_datetime->format('M d, H:i') }}
                                                @else
                                                    Started: {{ $auction->start_datetime->format('M d, H:i') }}
                                                @endif
                                            </div>
                                        @endif
                                        @if($auction->end_datetime)
                                            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                @if($auction->status === 'active')
                                                    Ends: {{ $auction->end_datetime->format('M d, H:i') }}
                                                @else
                                                    Ended: {{ $auction->end_datetime->format('M d, H:i') }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    @php
                                        $totalBids = $auction->collaterals->sum(function($c) { return $c->bids->count(); });
                                        $topBidder = $auction->collaterals->flatMap->bids->sortByDesc('bid_amount_rm')->first()?->user;
                                        $activeBidders = $auction->collaterals->flatMap->bids->pluck('user_id')->unique()->count();
                                    @endphp
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                            {{ $totalBids }} total bids
                                        </div>
                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                            {{ $activeBidders }} active bidders
                                        </div>
                                        @if($topBidder)
                                            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                Top: {{ $topBidder->full_name }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-2">
                                        <!-- View Details -->
                                        <a href="{{ route('admin.auctions.show', $auction) }}"
                                           class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                            View Details
                                        </a>

                                        @if($auction->status === 'active')
                                            <!-- Extend Auction -->
                                            <button onclick="extendAuction({{ $auction->id }})"
                                                    class="px-3 py-1 bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200 text-xs font-medium rounded-full hover:bg-purple-200 dark:hover:bg-purple-900/40 transition-colors">
                                                Extend
                                            </button>

                                            <!-- Cancel Auction -->
                                            <form method="POST" action="{{ route('admin.auctions.cancel', $auction) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="px-3 py-1 bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-200 text-xs font-medium rounded-full hover:bg-orange-200 dark:hover:bg-orange-900/40 transition-colors"
                                                        onclick="return confirm('Cancel auction {{ $auction->auction_title }}?')">
                                                    Cancel
                                                </button>
                                            </form>
                                        @elseif($auction->status === 'cancelled')
                                            <!-- Restart Auction -->
                                            <button onclick="restartAuction({{ $auction->id }})"
                                                    class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors">
                                                Restart
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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

        function extendAuction(auctionId) {
            const hours = prompt('Extend auction by how many hours?', '24');
            if (hours && !isNaN(hours) && hours > 0) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/auctions/${auctionId}/extend`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const hoursInput = document.createElement('input');
                hoursInput.type = 'hidden';
                hoursInput.name = 'extend_hours';
                hoursInput.value = hours;

                form.appendChild(csrfToken);
                form.appendChild(hoursInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function restartAuction(auctionId) {
            const days = prompt('Restart auction for how many days?', '7');
            if (days && !isNaN(days) && days > 0) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/auctions/${auctionId}/restart`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const daysInput = document.createElement('input');
                daysInput.type = 'hidden';
                daysInput.name = 'auction_days';
                daysInput.value = days;

                form.appendChild(csrfToken);
                form.appendChild(daysInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
