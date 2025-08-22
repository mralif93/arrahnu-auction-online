@extends('layouts.app')

@section('title', $collateral->item_type . ' - Auction')

@section('main-class', 'bg-[#f8f8f7] dark:bg-[#0a0a0a]')

@section('no-footer')
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <div class="flex items-center space-x-2 text-sm text-[#706f6c] dark:text-[#A1A09A] mb-6">
                <a href="{{ route('auctions.index') }}" class="hover:text-brand transition-colors">Auctions</a>
                <span>›</span>
                <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->item_type }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Auction Header -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ $collateral->item_type }}</h1>
                                <div class="flex items-center space-x-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    <span>ID: {{ substr($collateral->id, 0, 8) }}</span>
                                    <span>•</span>
                                    <span>{{ ucfirst($collateral->item_type) }}</span>
                                    <span>•</span>
                                    <span>{{ $collateral->account->branch->name }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($collateral->status === 'active' && $collateral->auction->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                        Live Auction
                                    </span>
                                @elseif($collateral->status === 'inactive')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                        Auction Ended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                        {{ ucfirst(str_replace('_', ' ', $collateral->status)) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Auction Image -->
                        <div class="aspect-video bg-gradient-to-br from-brand/10 to-brand/5 rounded-lg flex items-center justify-center mb-6">
                            @if($collateral->images->where('is_thumbnail', true)->first())
                                <img src="{{ $collateral->images->where('is_thumbnail', true)->first()->image_url }}" alt="{{ $collateral->item_type }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <div class="w-24 h-24 bg-brand/20 rounded-lg flex items-center justify-center">
                                    <span class="text-brand font-bold text-3xl">{{ strtoupper(substr($collateral->item_type, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Description</h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">{{ $collateral->description }}</p>
                        </div>

                        <!-- Item Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Item Details</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Type:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ ucfirst($collateral->item_type) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Weight:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->weight_grams }}g</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Purity:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->purity }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Status:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($collateral->status === 'active') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200
                                            @elseif($collateral->status === 'pending_approval') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200
                                            @elseif($collateral->status === 'inactive') bg-gray-100 dark:bg-gray-900/20 text-gray-800 dark:text-gray-200
                                            @else bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $collateral->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Estimated Value:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">RM {{ number_format($collateral->estimated_value_rm, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Starting Bid:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">RM {{ number_format($collateral->starting_bid_rm, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Auction Details</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Auction:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->auction->auction_title }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Start Time:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->auction->start_datetime->format('M j, Y g:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">End Time:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->auction->end_datetime->format('M j, Y g:i A') }}</span>
                                    </div>
                                    @if($collateral->auction->status === 'active' && $collateral->auction->end_datetime->isFuture())
                                        <div class="flex justify-between">
                                            <span class="text-[#706f6c] dark:text-[#A1A09A]">Time Remaining:</span>
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                {{ $collateral->auction->end_datetime->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Total Bids:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->bids->count() }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Branch:</span>
                                        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $collateral->account->branch->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Images -->
                    @if($collateral->images->count() > 1)
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Additional Images</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($collateral->images->where('is_thumbnail', false) as $image)
                                    <div class="aspect-square bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
                                        <img src="{{ $image->image_url }}" alt="Additional image" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Bidding Section -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Current Bid</h3>

                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold text-brand mb-2">RM {{ number_format($collateral->current_highest_bid_rm ?? $collateral->starting_bid_rm, 0) }}</div>
                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                {{ $collateral->bids->count() }} bids
                                @if($collateral->highestBidder)
                                    • Highest bidder: {{ $collateral->highestBidder->full_name }}
                                @endif
                            </div>
                        </div>

                        @if($collateral->status === 'active' && $collateral->auction->status === 'active')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Your Bid</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#706f6c] dark:text-[#A1A09A]">RM</span>
                                        <input type="number"
                                               class="w-full pl-12 pr-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
                                               placeholder="{{ number_format(($collateral->current_highest_bid_rm ?? $collateral->starting_bid_rm) + 10, 0) }}"
                                               min="{{ ($collateral->current_highest_bid_rm ?? $collateral->starting_bid_rm) + 10 }}">
                                    </div>
                                    <div class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                                        Minimum bid: RM {{ number_format(($collateral->current_highest_bid_rm ?? $collateral->starting_bid_rm) + 10, 0) }}
                                    </div>
                                </div>

                                @auth
                                    <button class="w-full bg-brand hover:bg-brand-hover text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                        Place Bid
                                    </button>
                                @else
                                    <div class="text-center">
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Please sign in to place a bid</p>
                                        <a href="{{ route('login') }}" class="inline-block bg-brand hover:bg-brand-hover text-white py-3 px-6 rounded-lg font-medium transition-colors">
                                            Sign In to Bid
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        @elseif($collateral->auction->status === 'completed')
                            <div class="text-center py-4">
                                <div class="text-lg font-semibold text-green-600 dark:text-green-400 mb-2">Auction Completed!</div>
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Final bid: RM {{ number_format($collateral->current_highest_bid_rm, 2) }}
                                </div>
                                @if($collateral->highestBidder)
                                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        Highest bidder: {{ $collateral->highestBidder->full_name }}
                                    </div>
                                @endif
                            </div>
                        @elseif($collateral->status === 'inactive')
                            <div class="text-center py-4">
                                <div class="text-lg font-semibold text-gray-600 dark:text-gray-400 mb-2">Auction Ended</div>
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Final bid: RM {{ number_format($collateral->current_highest_bid_rm ?? $collateral->starting_bid_rm, 2) }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-2">Coming Soon</div>
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Starting bid: RM {{ number_format($collateral->starting_bid_rm, 2) }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Account Information -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Account Information</h3>

                        <div class="space-y-3">
                            <div>
                                <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Account Title</div>
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->account->account_title }}</div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Account ID</div>
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ substr($collateral->account->id, 0, 8) }}</div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Branch</div>
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->account->branch->name }}</div>
                            </div>

                            @if($collateral->account->description)
                                <div>
                                    <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Description</div>
                                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $collateral->account->description }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Related Auctions -->
                    @if($relatedAuctions->count() > 0)
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Related Auctions</h3>

                            <div class="space-y-4">
                                @foreach($relatedAuctions as $related)
                                    <a href="{{ route('auctions.show', $related) }}" class="block group">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-brand/10 to-brand/5 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-brand font-bold text-sm">{{ strtoupper(substr($related->item_type, 0, 1)) }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-brand transition-colors truncate">
                                                    {{ $related->item_type }}
                                                </div>
                                                <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                    RM {{ number_format($related->current_highest_bid_rm ?? $related->starting_bid_rm, 0) }} • {{ $related->bids->count() }} bids
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    </div>
@endsection
