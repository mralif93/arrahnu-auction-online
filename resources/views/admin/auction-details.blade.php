@extends('layouts.admin')

@section('title', 'Auction Details')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Auction Details</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Welcome back, {{ Auth::user()->full_name ?? Auth::user()->username ?? 'Administrator' }}</p>
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
    <!-- Back Navigation -->
    <div class="flex items-center space-x-2 mb-6">
        <a href="{{ route('admin.collaterals.index') }}" class="text-sm text-brand hover:text-brand-hover transition-colors">
            ← Back to Auctions
        </a>
    </div>

    <!-- Auction Header -->
    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 bg-brand/10 rounded-lg flex items-center justify-center">
                    <span class="text-brand font-bold text-xl">
                        {{ strtoupper(substr($auction->category, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ $auction->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <span>{{ $auction->auction_number }}</span>
                        <span>•</span>
                        <span>{{ ucfirst($auction->category) }}</span>
                        <span>•</span>
                        <span>{{ $auction->branch->name }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Status Badge -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($auction->status === 'live') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200
                    @elseif($auction->status === 'ended') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200
                    @elseif($auction->status === 'draft') bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200
                    @elseif($auction->status === 'cancelled') bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200
                    @elseif($auction->status === 'sold') bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200
                    @else bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 @endif">
                    {{ ucfirst($auction->status) }}
                </span>

                @if($auction->featured)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-brand/10 text-brand">
                        Featured
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Auction Information -->
            <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Auction Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Description</label>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->description }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Condition</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($auction->condition === 'excellent') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200
                            @elseif($auction->condition === 'good') bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200
                            @elseif($auction->condition === 'fair') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200
                            @else bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 @endif">
                            {{ ucfirst($auction->condition) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Pricing & Bidding</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Starting Price</label>
                        <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">${{ number_format($auction->starting_price, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Reserve Price</label>
                        <p class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                            @if($auction->reserve_price)
                                ${{ number_format($auction->reserve_price, 2) }}
                                @if($auction->current_bid >= $auction->reserve_price)
                                    <span class="text-green-600 dark:text-green-400 text-sm">(Met)</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400 text-sm">(Not Met)</span>
                                @endif
                            @else
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">No Reserve</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Current Bid</label>
                        <p class="text-lg font-semibold text-brand">${{ number_format($auction->current_bid, 2) }}</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $auction->total_bids }} bids from {{ $auction->unique_bidders }} bidders</p>
                    </div>
                </div>
            </div>

            <!-- Timing Information -->
            <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Timing</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Start Time</label>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->start_time->format('M j, Y g:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">End Time</label>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->end_time->format('M j, Y g:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Time Remaining</label>
                        <p class="text-sm font-semibold
                            @if($auction->has_ended) text-red-600 dark:text-red-400
                            @elseif($auction->is_live) text-green-600 dark:text-green-400
                            @else text-[#1b1b18] dark:text-[#EDEDEC] @endif">
                            {{ $auction->time_remaining }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Terms & Information</h3>

                <div class="space-y-4">
                    @if($auction->terms_conditions)
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Terms & Conditions</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->terms_conditions }}</p>
                        </div>
                    @endif

                    @if($auction->shipping_info)
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Shipping Information</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->shipping_info }}</p>
                        </div>
                    @endif

                    @if($auction->payment_info)
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Payment Information</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->payment_info }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <!-- Toggle Featured -->
                    <form method="POST" action="{{ route('admin.auctions.toggle-featured', $auction) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 {{ $auction->featured ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200' : 'bg-brand/10 text-brand' }} text-sm font-medium rounded-lg hover:opacity-80 transition-colors">
                            {{ $auction->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                        </button>
                    </form>

                    <!-- Status Actions -->
                    @if($auction->status === 'draft')
                        <form method="POST" action="{{ route('admin.auctions.update-status', $auction) }}">
                            @csrf
                            <input type="hidden" name="status" value="live">
                            <button type="submit" class="w-full px-4 py-2 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm font-medium rounded-lg hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors" onclick="return confirm('Start this auction?')">
                                Start Auction
                            </button>
                        </form>
                    @elseif($auction->status === 'live')
                        <form method="POST" action="{{ route('admin.auctions.update-status', $auction) }}">
                            @csrf
                            <input type="hidden" name="status" value="ended">
                            <button type="submit" class="w-full px-4 py-2 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-sm font-medium rounded-lg hover:bg-yellow-200 dark:hover:bg-yellow-900/40 transition-colors" onclick="return confirm('End this auction?')">
                                End Auction
                            </button>
                        </form>
                    @endif

                    <!-- Delete Auction -->
                    @if(in_array($auction->status, ['draft', 'cancelled']))
                        <form method="POST" action="{{ route('admin.auctions.delete', $auction) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-sm font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors" onclick="return confirm('Are you sure you want to delete this auction? This action cannot be undone.')">
                                Delete Auction
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Seller Information -->
            <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Seller Information</h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Seller</label>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->seller->name }}</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $auction->seller->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Branch</label>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->branch->name }}</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $auction->branch->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Collateral Information -->
            <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Collateral Details</h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Item</label>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->collateral->item_name }}</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $auction->collateral->collateral_number }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Estimated Value</label>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">${{ number_format($auction->collateral->estimated_value, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Loan Amount</label>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">${{ number_format($auction->collateral->loan_amount, 2) }}</p>
                    </div>
                </div>
            </div>

            @if($auction->status === 'sold' && $auction->winningBidder)
                <!-- Winner Information -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Winning Bidder</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Winner</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $auction->winningBidder->name }}</p>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $auction->winningBidder->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Winning Bid</label>
                            <p class="text-lg font-semibold text-brand">${{ number_format($auction->winning_bid, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Commission Earned</label>
                            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">${{ number_format($auction->commission_earned, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
