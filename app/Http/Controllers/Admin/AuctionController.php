<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Collateral;
use App\Models\Bid;
use App\Models\AuctionResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Get all auctions with their collaterals (paginated)
        $auctions = Auction::with(['creator', 'approvedBy', 'collaterals.bids', 'collaterals.highestBidder'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.auctions.index', compact(
            'activeAuctions',
            'completedAuctions',
            'soldAuctions',
            'totalBidValue',
            'auctions'
        ));
    }

    /**
     * Show the form for creating a new auction.
     */
    public function create()
    {
        return view('admin.auctions.create');
    }

    /**
     * Store a newly created auction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'auction_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        $auction = Auction::create([
            'auction_title' => $request->auction_title,
            'description' => $request->description,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'status' => 'pending_approval',
            'created_by_user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Auction created and submitted for approval successfully.');
    }

    /**
     * Display auction details with collaterals and bids.
     */
    public function show(Auction $auction)
    {
        $auction->load([
            'creator',
            'approvedBy',
            'collaterals.account.branch',
            'collaterals.bids.user',
            'collaterals.highestBidder',
            'collaterals.auctionResult'
        ]);

        return view('admin.auctions.show', compact('auction'));
    }

    /**
     * Show the form for editing the specified auction.
     */
    public function edit(Auction $auction)
    {
        // Only allow editing of draft auctions
        if (!in_array($auction->status, ['draft', 'rejected'])) {
            return redirect()->route('admin.auctions.show', $auction)
                ->with('error', 'Only draft or rejected auctions can be edited.');
        }

        return view('admin.auctions.edit', compact('auction'));
    }

    /**
     * Update the specified auction.
     */
    public function update(Request $request, Auction $auction)
    {
        // Only allow updating of rejected auctions
        if ($auction->status !== 'rejected') {
            return redirect()->route('admin.auctions.show', $auction)
                ->with('error', 'Only rejected auctions can be updated.');
        }

        $request->validate([
            'auction_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        $auction->update([
            'auction_title' => $request->auction_title,
            'description' => $request->description,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'status' => 'pending_approval',
        ]);

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Auction updated and submitted for approval successfully.');
    }

    /**
     * Remove the specified auction.
     */
    public function destroy(Auction $auction)
    {
        // Only allow deletion of scheduled or cancelled auctions with no collaterals
        if (!in_array($auction->status, ['scheduled', 'cancelled'])) {
            return redirect()->route('admin.auctions.index')
                ->with('error', 'Only scheduled or cancelled auctions can be deleted.');
        }

        if ($auction->collaterals()->count() > 0) {
            return redirect()->route('admin.auctions.index')
                ->with('error', 'Cannot delete auction with associated collaterals.');
        }

        $auctionTitle = $auction->auction_title;
        $auction->delete();

        return redirect()->route('admin.auctions.index')
            ->with('success', "Auction '{$auctionTitle}' has been deleted successfully.");
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

    /**
     * Approve an auction (Checker function).
     */
    public function approve(Auction $auction, Request $request)
    {
        return $this->handleAction(function () use ($auction) {
            if (!Auth::user()->canApprove($auction)) {
                throw new \Exception('You do not have permission to approve this auction.');
            }

            if ($auction->status !== 'pending_approval') {
                throw new \Exception('Auction is not pending approval.');
            }

            $auction->update([
                'status' => 'scheduled',
                'approved_by_user_id' => Auth::id(),
            ]);

            $message = "Auction '{$auction->auction_title}' has been approved and scheduled.";

            if ($request->expectsJson()) {
                return $auction->fresh(['creator', 'approvedBy', 'collaterals']);
            }

            return redirect()->back()->with('success', $message);
        }, $request);
    }

    /**
     * Reject an auction (Checker function).
     */
    public function reject(Auction $auction, Request $request)
    {
        return $this->handleAction(function () use ($auction) {
            if (!Auth::user()->canApprove($auction)) {
                throw new \Exception('You do not have permission to reject this auction.');
            }

            if ($auction->status !== 'pending_approval') {
                throw new \Exception('Auction is not pending approval.');
            }

            $auction->update([
                'status' => 'rejected',
                'approved_by_user_id' => Auth::id(),
            ]);

            $message = "Auction '{$auction->auction_title}' has been rejected.";

            if ($request->expectsJson()) {
                return $auction->fresh(['creator', 'approvedBy', 'collaterals']);
            }

            return redirect()->back()->with('success', $message);
        }, $request);
    }


}
