<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Collateral;
use App\Models\Bid;
use App\Models\AuctionResult;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display a listing of auctions.
     */
    public function index()
    {
        // Get auction statistics
        $activeAuctions = Auction::where('status', 'active')->count();
        $completedAuctions = Auction::where('status', 'completed')->count();
        $soldAuctions = Collateral::where('status', 'sold')->count();
        $totalBidValue = Collateral::sum('current_highest_bid_rm');

        // Get all auctions with their collaterals
        $auctions = Auction::with(['branch', 'creator', 'approvedBy', 'collaterals.bids', 'collaterals.highestBidder'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.auctions', compact(
            'activeAuctions',
            'completedAuctions',
            'soldAuctions',
            'totalBidValue',
            'auctions'
        ));
    }

    /**
     * Display auction details with collaterals and bids.
     */
    public function show(Auction $auction)
    {
        $auction->load([
            'branch',
            'creator',
            'approvedBy',
            'collaterals.account',
            'collaterals.bids.user',
            'collaterals.highestBidder',
            'collaterals.auctionResult'
        ]);

        return view('admin.auction-details', compact('auction'));
    }

    /**
     * Display auction results.
     */
    public function results()
    {
        $results = AuctionResult::with([
            'collateral.account.branch',
            'collateral.auction',
            'winner',
            'winningBid'
        ])
            ->orderBy('auction_end_time', 'desc')
            ->get();

        $totalSales = $results->sum('winning_bid_amount');
        $pendingPayments = $results->where('payment_status', 'pending')->count();
        $pendingDeliveries = $results->where('delivery_status', 'pending')->count();

        return view('admin.auction-results', compact(
            'results',
            'totalSales',
            'pendingPayments',
            'pendingDeliveries'
        ));
    }

    /**
     * Update payment status for auction result.
     */
    public function updatePaymentStatus(AuctionResult $auctionResult, Request $request)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $auctionResult->update([
            'payment_status' => $request->payment_status
        ]);

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }

    /**
     * Update delivery status for auction result.
     */
    public function updateDeliveryStatus(AuctionResult $auctionResult, Request $request)
    {
        $request->validate([
            'delivery_status' => 'required|in:pending,shipped,delivered,cancelled'
        ]);

        $auctionResult->update([
            'delivery_status' => $request->delivery_status
        ]);

        return redirect()->back()->with('success', 'Delivery status updated successfully.');
    }

    /**
     * Get live auction data for AJAX updates.
     */
    public function liveData(Auction $auction)
    {
        if ($auction->status !== 'active') {
            return response()->json(['error' => 'Auction is not active'], 400);
        }

        $totalBids = $auction->collaterals->sum(function ($collateral) {
            return $collateral->bids->count();
        });

        $totalValue = $auction->collaterals->sum('current_highest_bid_rm');

        return response()->json([
            'status' => $auction->status,
            'total_items' => $auction->collaterals->count(),
            'total_bids' => $totalBids,
            'total_value' => $totalValue,
            'time_remaining' => $auction->end_datetime->diffInSeconds(now()),
            'items' => $auction->collaterals->map(function ($collateral) {
                $latestBid = $collateral->bids()->orderBy('bid_time', 'desc')->first();
                return [
                    'id' => $collateral->id,
                    'item_type' => $collateral->item_type,
                    'current_bid' => $collateral->current_highest_bid_rm,
                    'bid_count' => $collateral->bids->count(),
                    'latest_bid' => $latestBid ? [
                        'amount' => $latestBid->bid_amount_rm,
                        'bidder' => $latestBid->user->full_name,
                        'time' => $latestBid->bid_time->format('H:i:s')
                    ] : null
                ];
            })
        ]);
    }

    /**
     * Extend auction time.
     */
    public function extendAuction(Auction $auction, Request $request)
    {
        if ($auction->status !== 'active') {
            return redirect()->back()->with('error', 'Auction is not active.');
        }

        $request->validate([
            'extend_hours' => 'required|integer|min:1|max:168' // Max 1 week
        ]);

        $auction->update([
            'end_datetime' => $auction->end_datetime->addHours($request->extend_hours)
        ]);

        return redirect()->back()->with('success', "Auction '{$auction->auction_title}' extended by {$request->extend_hours} hours.");
    }

    /**
     * Cancel an active auction.
     */
    public function cancelAuction(Auction $auction)
    {
        if (!in_array($auction->status, ['scheduled', 'active'])) {
            return redirect()->back()->with('error', 'Auction cannot be cancelled.');
        }

        $auction->cancel();

        return redirect()->back()->with('success', "Auction '{$auction->auction_title}' has been cancelled.");
    }

    /**
     * Restart a cancelled auction.
     */
    public function restartAuction(Auction $auction, Request $request)
    {
        if ($auction->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Only cancelled auctions can be restarted.');
        }

        $request->validate([
            'auction_days' => 'required|integer|min:1|max:30'
        ]);

        $auction->update([
            'status' => 'scheduled',
            'start_datetime' => now()->addHour(),
            'end_datetime' => now()->addDays($request->auction_days),
        ]);

        // Reset all collaterals in this auction
        $auction->collaterals()->update([
            'status' => 'ready_for_auction',
            'current_highest_bid_rm' => 0.00,
            'highest_bidder_user_id' => null,
        ]);

        return redirect()->back()->with('success', "Auction '{$auction->auction_title}' has been restarted.");
    }
}
