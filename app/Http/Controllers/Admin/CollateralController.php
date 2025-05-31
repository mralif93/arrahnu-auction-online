<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collateral;
use App\Models\AuctionResult;
use Illuminate\Http\Request;

class CollateralController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Display a listing of collaterals.
     */
    public function index()
    {
        $collaterals = Collateral::with(['account.branch', 'creator', 'approvedBy', 'images', 'highestBidder'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCollaterals = $collaterals->count();
        $activeCollaterals = $collaterals->where('status', 'active')->count();
        $auctioningCollaterals = $collaterals->where('status', 'auctioning')->count();
        $readyForAuction = $collaterals->where('status', 'ready_for_auction')->count();
        $totalValue = $collaterals->sum('estimated_value_rm') ?? 0;
        $totalBidValue = $collaterals->sum('current_highest_bid_rm') ?? 0;

        return view('admin.collaterals', compact(
            'collaterals',
            'totalCollaterals',
            'activeCollaterals',
            'auctioningCollaterals',
            'readyForAuction',
            'totalValue',
            'totalBidValue'
        ));
    }

    /**
     * Store a newly created collateral.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'auction_id' => 'required|exists:auctions,id',
            'item_type' => 'required|string|max:50',
            'description' => 'required|string',
            'weight_grams' => 'nullable|numeric|min:0',
            'purity' => 'nullable|string|max:20',
            'estimated_value_rm' => 'nullable|numeric|min:0',
            'starting_bid_rm' => 'required|numeric|min:0',
        ]);

        Collateral::create([
            'account_id' => $request->account_id,
            'auction_id' => $request->auction_id,
            'item_type' => $request->item_type,
            'description' => $request->description,
            'weight_grams' => $request->weight_grams,
            'purity' => $request->purity,
            'estimated_value_rm' => $request->estimated_value_rm,
            'starting_bid_rm' => $request->starting_bid_rm,
            'current_highest_bid_rm' => 0.00,
            'status' => 'draft',
            'created_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Collateral created successfully.');
    }

    /**
     * Update the specified collateral.
     */
    public function update(Collateral $collateral, Request $request)
    {
        $request->validate([
            'item_type' => 'required|string|max:50',
            'description' => 'required|string',
            'weight_grams' => 'nullable|numeric|min:0',
            'purity' => 'nullable|string|max:20',
            'estimated_value_rm' => 'nullable|numeric|min:0',
            'starting_bid_rm' => 'required|numeric|min:0',
        ]);

        $collateral->update([
            'item_type' => $request->item_type,
            'description' => $request->description,
            'weight_grams' => $request->weight_grams,
            'purity' => $request->purity,
            'estimated_value_rm' => $request->estimated_value_rm,
            'starting_bid_rm' => $request->starting_bid_rm,
        ]);

        return redirect()->back()->with('success', 'Collateral updated successfully.');
    }

    /**
     * Approve a collateral for auction.
     */
    public function approve(Collateral $collateral)
    {
        if ($collateral->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Collateral is not pending approval.');
        }

        $collateral->update([
            'status' => 'active',
            'approved_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Collateral {$collateral->item_type} has been approved.");
    }

    /**
     * Reject a collateral.
     */
    public function reject(Collateral $collateral)
    {
        if ($collateral->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Collateral is not pending approval.');
        }

        $collateral->update([
            'status' => 'rejected',
            'approved_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Collateral {$collateral->item_type} has been rejected.");
    }

    /**
     * Start auction for a collateral (now handled at auction level).
     */
    public function startAuction(Collateral $collateral)
    {
        if (!in_array($collateral->status, ['active', 'ready_for_auction'])) {
            return redirect()->back()->with('error', 'Collateral is not ready for auction.');
        }

        // Check if the auction is active
        if ($collateral->auction && $collateral->auction->status === 'active') {
            $collateral->update(['status' => 'auctioning']);
            return redirect()->back()->with('success', "Collateral {$collateral->item_type} is now auctioning.");
        }

        return redirect()->back()->with('error', 'The auction for this collateral is not active.');
    }

    /**
     * End auction for a collateral (now handled at auction level).
     */
    public function endAuction(Collateral $collateral)
    {
        if ($collateral->status !== 'auctioning') {
            return redirect()->back()->with('error', 'Collateral is not currently being auctioned.');
        }

        // Get the highest bid
        $highestBid = $collateral->bids()->orderBy('bid_amount_rm', 'desc')->first();

        if ($highestBid) {
            // Mark as sold and create auction result
            $collateral->update([
                'status' => 'sold',
                'highest_bidder_user_id' => $highestBid->user_id,
                'current_highest_bid_rm' => $highestBid->bid_amount_rm,
            ]);

            // Create auction result
            AuctionResult::create([
                'collateral_id' => $collateral->id,
                'winner_user_id' => $highestBid->user_id,
                'winning_bid_amount' => $highestBid->bid_amount_rm,
                'winning_bid_id' => $highestBid->id,
                'auction_end_time' => $collateral->auction->end_datetime,
                'payment_status' => 'pending',
                'delivery_status' => 'pending',
                'result_status' => 'completed',
            ]);

            return redirect()->back()->with('success', "Auction ended. {$collateral->item_type} sold for RM " . number_format($highestBid->bid_amount_rm, 2));
        } else {
            // No bids - mark as unsold
            $collateral->update(['status' => 'unsold']);

            return redirect()->back()->with('info', "Auction ended. {$collateral->item_type} received no bids.");
        }
    }

    /**
     * Remove the specified collateral.
     */
    public function destroy(Collateral $collateral)
    {
        $itemType = $collateral->item_type;
        $collateral->delete();

        return redirect()->back()->with('success', "Collateral {$itemType} has been deleted.");
    }
}
