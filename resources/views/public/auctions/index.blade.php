@extends('layouts.app')

@section('title', 'Live Auctions - Arrahnu Auction')

@section('content')
    <div class="main-content px-6 lg:px-8 py-12">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl lg:text-5xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Live Auctions
                    </h1>
                    <p class="text-xl text-[#706f6c] dark:text-[#A1A09A] max-w-3xl">
                        Discover authentic collectibles, luxury items, and rare treasures from verified sellers worldwide.
                    </p>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Search Auctions
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="search"
                                    placeholder="Search by title, description, or category..."
                                    class="w-full pl-10 pr-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-colors"
                                >
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Category
                            </label>
                            <select id="category" class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-colors">
                                <option value="">All Categories</option>
                                <option value="watches">Watches</option>
                                <option value="jewelry">Jewelry</option>
                                <option value="art">Art & Collectibles</option>
                                <option value="vintage">Vintage Items</option>
                                <option value="luxury">Luxury Goods</option>
                                <option value="coins">Coins & Currency</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Sort By
                            </label>
                            <select id="sort" class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-colors">
                                <option value="ending_soon">Ending Soon</option>
                                <option value="newest">Newest First</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="most_bids">Most Bids</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-brand mb-1">127</div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Live Auctions</div>
                    </div>
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-brand mb-1">2,847</div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Active Bidders</div>
                    </div>
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-brand mb-1">$1.2M</div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Total Value</div>
                    </div>
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-brand mb-1">24</div>
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Ending Today</div>
                    </div>
                </div>

                <!-- Live Auctions Section -->
                @if($activeAuctions->count() > 0)
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Live Auctions</h2>
                        <div class="auction-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($activeAuctions as $auction)
                                @foreach($auction->collaterals as $collateral)
                                <div class="auction-card bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow" data-category="{{ $collateral->item_type }}" data-price="{{ $collateral->current_highest_bid_rm }}" data-bids="{{ $collateral->bids->count() }}">
                                    <!-- Image -->
                                    <div class="aspect-square bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center">
                                        @if($collateral->images->where('is_thumbnail', true)->first())
                                            <img src="{{ $collateral->images->where('is_thumbnail', true)->first()->image_url }}" alt="{{ $collateral->item_type }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-16 h-16 bg-brand/20 rounded-lg flex items-center justify-center">
                                                <span class="text-brand font-bold text-xl">{{ strtoupper(substr($collateral->item_type, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6">
                                        <!-- Status Badge -->
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-medium text-green-800 dark:text-green-200 bg-green-100 dark:bg-green-900/20 px-2 py-1 rounded-full">
                                                Live Auction
                                            </span>
                                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                @if($collateral->auction && $collateral->auction->end_datetime)
                                                    Ends {{ $collateral->auction->end_datetime->diffForHumans() }}
                                                @else
                                                    Live Now
                                                @endif
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="auction-title font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                            {{ $collateral->item_type }}
                                        </h3>

                                        <!-- Description -->
                                        <p class="auction-description text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                            {{ Str::limit($collateral->description, 80) }}
                                        </p>

                                        <!-- Bid Info -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Current Bid</p>
                                                <p class="text-xl font-bold text-brand">RM {{ number_format($collateral->current_highest_bid_rm, 0) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Bids</p>
                                                <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->bids->count() }}</p>
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <a href="{{ route('auctions.show', $collateral) }}" class="block w-full px-4 py-3 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-colors text-center">
                                            View Auction
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recently Ended Auctions Section -->
                @if($completedAuctions->count() > 0)
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Recently Ended Auctions</h2>
                        <div class="auction-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($completedAuctions->take(6) as $auction)
                                @foreach($auction->collaterals as $collateral)
                                <div class="auction-card bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow opacity-75" data-category="{{ $collateral->item_type }}" data-price="{{ $collateral->current_highest_bid_rm }}" data-bids="{{ $collateral->bids->count() }}">
                                    <!-- Image -->
                                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center">
                                        @if($collateral->images->where('is_thumbnail', true)->first())
                                            <img src="{{ $collateral->images->where('is_thumbnail', true)->first()->image_url }}" alt="{{ $collateral->item_type }}" class="w-full h-full object-cover grayscale">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-500 dark:text-gray-400 font-bold text-xl">{{ strtoupper(substr($collateral->item_type, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6">
                                        <!-- Status Badge -->
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-full">
                                                {{ $collateral->status === 'sold' ? 'Sold' : 'Unsold' }}
                                            </span>
                                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                {{ $collateral->updated_at->format('M j, Y') }}
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="auction-title font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                            {{ $collateral->item_type }}
                                        </h3>

                                        <!-- Description -->
                                        <p class="auction-description text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                            {{ Str::limit($collateral->description, 80) }}
                                        </p>

                                        <!-- Bid Info -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Final Price</p>
                                                <p class="text-xl font-bold text-gray-600 dark:text-gray-400">RM {{ number_format($collateral->current_highest_bid_rm, 0) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Total Bids</p>
                                                <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->bids->count() }}</p>
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <a href="{{ route('auctions.show', $collateral) }}" class="block w-full px-4 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors text-center">
                                            View Results
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upcoming Auctions Section -->
                @if($scheduledAuctions->count() > 0)
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Upcoming Auctions</h2>
                        <div class="auction-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($scheduledAuctions as $auction)
                                @foreach($auction->collaterals as $collateral)
                                <div class="auction-card bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition-shadow" data-category="{{ $collateral->item_type }}" data-price="{{ $collateral->starting_bid_rm }}" data-bids="0">
                                    <!-- Image -->
                                    <div class="aspect-square bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 flex items-center justify-center">
                                        @if($collateral->images->where('is_thumbnail', true)->first())
                                            <img src="{{ $collateral->images->where('is_thumbnail', true)->first()->image_url }}" alt="{{ $collateral->item_type }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-16 h-16 bg-blue-200 dark:bg-blue-700 rounded-lg flex items-center justify-center">
                                                <span class="text-blue-600 dark:text-blue-300 font-bold text-xl">{{ strtoupper(substr($collateral->item_type, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6">
                                        <!-- Status Badge -->
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-medium text-blue-800 dark:text-blue-200 bg-blue-100 dark:bg-blue-900/20 px-2 py-1 rounded-full">
                                                Coming Soon
                                            </span>
                                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                Ready for Auction
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="auction-title font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                            {{ $collateral->item_type }}
                                        </h3>

                                        <!-- Description -->
                                        <p class="auction-description text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                            {{ Str::limit($collateral->description, 80) }}
                                        </p>

                                        <!-- Starting Bid Info -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Starting Bid</p>
                                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400">RM {{ number_format($collateral->starting_bid_rm, 0) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Weight</p>
                                                <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->weight_grams }}g</p>
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <a href="{{ route('auctions.show', $collateral) }}" class="block w-full px-4 py-3 bg-blue-100 dark:bg-blue-900/20 hover:bg-blue-200 dark:hover:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium rounded-lg transition-colors text-center">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- No Auctions Message -->
                @if($activeAuctions->count() === 0 && $completedAuctions->count() === 0 && $scheduledAuctions->count() === 0)
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No auctions available</h3>
                        <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">Check back soon for new auction listings.</p>
                    </div>
                @endif

            </div>
    </div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const auctionCards = document.querySelectorAll('.auction-card');

        auctionCards.forEach(card => {
            const title = card.querySelector('.auction-title').textContent.toLowerCase();
            const description = card.querySelector('.auction-description').textContent.toLowerCase();

            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Category filter
    document.getElementById('category').addEventListener('change', function(e) {
        const selectedCategory = e.target.value;
        const auctionCards = document.querySelectorAll('.auction-card');

        auctionCards.forEach(card => {
            const category = card.dataset.category;

            if (selectedCategory === '' || category === selectedCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Sort functionality
    document.getElementById('sort').addEventListener('change', function(e) {
        const sortBy = e.target.value;
        const auctionGrid = document.querySelector('.auction-grid');
        const auctionCards = Array.from(document.querySelectorAll('.auction-card'));

        auctionCards.sort((a, b) => {
            switch(sortBy) {
                case 'price_low':
                    return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                case 'price_high':
                    return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                case 'most_bids':
                    return parseInt(b.dataset.bids) - parseInt(a.dataset.bids);
                case 'ending_soon':
                    return parseInt(a.dataset.timeLeft) - parseInt(b.dataset.timeLeft);
                default:
                    return 0;
            }
        });

        // Re-append sorted cards
        auctionCards.forEach(card => auctionGrid.appendChild(card));
    });

    // Countdown timers (demo)
    function updateCountdowns() {
        const countdowns = document.querySelectorAll('.countdown');
        countdowns.forEach(countdown => {
            // This is a demo - in real app, you'd calculate from actual end time
            const currentText = countdown.textContent;
            if (currentText.includes('45m')) {
                countdown.textContent = 'Ends in 44m';
            }
        });
    }

    // Update countdowns every minute
    setInterval(updateCountdowns, 60000);
</script>
@endpush
