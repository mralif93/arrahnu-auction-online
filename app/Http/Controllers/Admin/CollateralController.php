<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collateral;
use App\Models\CollateralImage;
use App\Models\Account;
use App\Models\Auction;
use App\Models\AuctionResult;
use App\Models\User;
use App\Http\Requests\StoreCollateralRequest;
use App\Http\Requests\UpdateCollateralRequest;
use App\Services\CollateralService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CollateralController extends Controller
{
    protected CollateralService $collateralService;

    /**
     * Create a new controller instance.
     */
    public function __construct(CollateralService $collateralService)
    {
        $this->collateralService = $collateralService;
        // Middleware is applied at route level
    }

    /**
     * Display a listing of collaterals.
     */
    public function index()
    {
        // Calculate statistics from all collaterals
        $totalCollaterals = Collateral::count();
        $activeCollaterals = Collateral::where('status', 'active')->count();
        $pendingCollaterals = Collateral::where('status', 'pending_approval')->count();
        $inactiveCollaterals = Collateral::where('status', 'inactive')->count();
        $rejectedCollaterals = Collateral::where('status', 'rejected')->count();
        $draftCollaterals = Collateral::where('status', 'draft')->count();

        // Get paginated collaterals
        $collaterals = Collateral::with(['account.branch', 'creator', 'approvedBy', 'images', 'highestBidder'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        return view('admin.collaterals.index', compact(
            'collaterals',
            'totalCollaterals',
            'activeCollaterals',
            'pendingCollaterals',
            'inactiveCollaterals',
            'rejectedCollaterals',
            'draftCollaterals'
        ));
    }

    /**
     * Show the form for creating a new collateral.
     */
    public function create()
    {
        $accounts = Account::with('branch')->orderBy('account_title')->get();
        $auctions = Auction::orderBy('auction_title')->get();

        return view('admin.collaterals.create', compact('accounts', 'auctions'));
    }

    /**
     * Show the specified collateral.
     */
    public function show(Collateral $collateral)
    {
        $collateral->load(['account.branch', 'auction', 'creator', 'approvedBy', 'images', 'highestBidder']);

        return view('admin.collaterals.show', compact('collateral'));
    }

    /**
     * Show the form for editing the specified collateral.
     */
    public function edit(Collateral $collateral)
    {
        // Check if user can edit this collateral
        if ($collateral->status === 'active') {
            return redirect()->route('admin.collaterals.show', $collateral)
                ->with('error', 'Active collaterals cannot be edited.');
        }

        if (!auth()->user()->canMake() && $collateral->created_by_user_id !== auth()->id()) {
            return redirect()->route('admin.collaterals.show', $collateral)
                ->with('error', 'You can only edit your own collaterals.');
        }

        $accounts = Account::with('branch')->orderBy('account_title')->get();
        $auctions = Auction::orderBy('auction_title')->get();

        return view('admin.collaterals.edit', compact('collateral', 'accounts', 'auctions'));
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
            'description' => 'required|string|max:1000',
            'weight_grams' => 'nullable|numeric|min:0',
            'purity' => 'nullable|string|max:20',
            'estimated_value_rm' => 'nullable|numeric|min:0',
            'starting_bid_rm' => 'required|numeric|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $collateral = Collateral::create([
            'account_id' => $request->account_id,
            'auction_id' => $request->auction_id,
            'item_type' => $request->item_type,
            'description' => $request->description,
            'weight_grams' => $request->weight_grams,
            'purity' => $request->purity,
            'estimated_value_rm' => $request->estimated_value_rm,
            'starting_bid_rm' => $request->starting_bid_rm,
            'current_highest_bid_rm' => 0.00,
            'status' => 'pending_approval',
            'created_by_user_id' => auth()->id(),
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('collateral-images', 'public');

                CollateralImage::create([
                    'collateral_id' => $collateral->id,
                    'image_url' => Storage::url($imagePath),
                    'is_thumbnail' => $index === 0, // First image as thumbnail
                    'order_index' => $index,
                ]);
            }
        }

        return redirect()->route('admin.collaterals.index')
            ->with('success', 'Collateral created and submitted for approval successfully.');
    }

    /**
     * Update the specified collateral.
     */
    public function update(Request $request, Collateral $collateral)
    {
        // Check if user can edit this collateral
        if ($collateral->status === 'active') {
            return redirect()->route('admin.collaterals.show', $collateral)
                ->with('error', 'Active collaterals cannot be edited.');
        }

        if (!auth()->user()->canMake() && $collateral->created_by_user_id !== auth()->id()) {
            return redirect()->route('admin.collaterals.show', $collateral)
                ->with('error', 'You can only edit your own collaterals.');
        }

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'auction_id' => 'required|exists:auctions,id',
            'item_type' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'weight_grams' => 'nullable|numeric|min:0',
            'purity' => 'nullable|string|max:20',
            'estimated_value_rm' => 'nullable|numeric|min:0',
            'starting_bid_rm' => 'required|numeric|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $collateral->update([
            'account_id' => $request->account_id,
            'auction_id' => $request->auction_id,
            'item_type' => $request->item_type,
            'description' => $request->description,
            'weight_grams' => $request->weight_grams,
            'purity' => $request->purity,
            'estimated_value_rm' => $request->estimated_value_rm,
            'starting_bid_rm' => $request->starting_bid_rm,
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('collateral-images', 'public');

                CollateralImage::create([
                    'collateral_id' => $collateral->id,
                    'image_url' => Storage::url($imagePath),
                    'is_thumbnail' => $collateral->images()->count() === 0 && $index === 0, // First image as thumbnail if no existing images
                    'order_index' => $collateral->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('admin.collaterals.show', $collateral)
            ->with('success', 'Collateral updated successfully.');
    }

    /**
     * Approve a collateral for auction.
     */
    public function approve(Collateral $collateral)
    {
        if (!$collateral->approve(auth()->user())) {
            return redirect()->back()->with('error', 'Collateral cannot be approved at this time.');
        }

        return redirect()->back()->with('success', "Collateral '{$collateral->item_type}' has been approved.");
    }

    /**
     * Reject a collateral.
     */
    public function reject(Collateral $collateral)
    {
        if (!$collateral->reject(auth()->user())) {
            return redirect()->back()->with('error', 'Collateral cannot be rejected at this time.');
        }

        return redirect()->back()->with('success', "Collateral '{$collateral->item_type}' has been rejected.");
    }

    /**
     * Submit collateral for approval.
     */
    public function submitForApproval(Collateral $collateral)
    {
        if ($collateral->status !== 'draft') {
            return redirect()->back()->with('error', 'Only draft collaterals can be submitted for approval.');
        }

        if ($collateral->created_by_user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You can only submit your own collaterals for approval.');
        }

        $collateral->update(['status' => 'pending_approval']);

        return redirect()->back()->with('success', "Collateral '{$collateral->item_type}' has been submitted for approval.");
    }

    /**
     * Toggle collateral status between active and inactive.
     */
    public function toggleStatus(Collateral $collateral)
    {
        if (!in_array($collateral->status, ['active', 'inactive'])) {
            return redirect()->back()->with('error', 'Only active or inactive collaterals can have their status toggled.');
        }

        $newStatus = $collateral->status === 'active' ? 'inactive' : 'active';
        $collateral->update(['status' => $newStatus]);

        $action = $newStatus === 'active' ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Collateral '{$collateral->item_type}' has been {$action}.");
    }

    /**
     * Start auction for a collateral (now handled at auction level).
     */
    public function startAuction(Collateral $collateral)
    {
        if ($collateral->status !== 'active') {
            return redirect()->back()->with('error', 'Collateral is not active and ready for auction.');
        }

        // Check if the auction is active
        if ($collateral->auction && $collateral->auction->status === 'active') {
            // Keep status as active during auction
            return redirect()->back()->with('success', "Collateral {$collateral->item_type} is now available for bidding.");
        }

        return redirect()->back()->with('error', 'The auction for this collateral is not active.');
    }

    /**
     * End auction for a collateral (now handled at auction level).
     */
    public function endAuction(Collateral $collateral)
    {
        if ($collateral->status !== 'active') {
            return redirect()->back()->with('error', 'Collateral is not currently active for auction.');
        }

        // Get the highest bid
        $highestBid = $collateral->bids()->orderBy('bid_amount_rm', 'desc')->first();

        if ($highestBid) {
            // Mark as inactive and create auction result
            $collateral->update([
                'status' => 'inactive',
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
            // No bids - mark as inactive
            $collateral->update(['status' => 'inactive']);

            return redirect()->back()->with('info', "Auction ended. {$collateral->item_type} received no bids.");
        }
    }

    /**
     * Remove the specified collateral.
     */
    public function destroy(Collateral $collateral, Request $request)
    {
        try {
            $itemType = $collateral->item_type;
            $this->collateralService->deleteCollateral($collateral);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Collateral {$itemType} has been deleted."
                ]);
            }

            return redirect()->back()->with('success', "Collateral {$itemType} has been deleted.");
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete collateral.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete collateral: ' . $e->getMessage());
        }
    }
}
