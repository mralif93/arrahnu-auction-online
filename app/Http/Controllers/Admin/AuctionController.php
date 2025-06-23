<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionResult;
use App\Models\Bid;
use App\Models\Collateral;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuctionController extends Controller
{
    /**
     * Get validation rules for auction.
     *
     * @param string $action The action being performed (store/update)
     * @return array
     */
    private function getValidationRules($action = 'store')
    {
        $rules = [
            'auction_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'status' => 'required|in:draft,pending_approval,scheduled,active,completed,cancelled'
        ];

        if ($action === 'store') {
            $rules['created_by_user_id'] = Auth::id();
        }

        return $rules;
    }

    /**
     * Check if there are any active auctions except the current one.
     *
     * @param string|int|null $excludeAuctionId
     * @return bool
     */
    private function hasActiveAuctions($excludeAuctionId = null): bool
    {
        $query = Auction::where('status', 'active');
        
        if ($excludeAuctionId) {
            $query->where('id', '!=', (int) $excludeAuctionId);
        }

        return $query->exists();
    }

    /**
     * Display a listing of auctions.
     */
    public function index()
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load auctions. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Show the form for creating a new auction.
     */
    public function create()
    {
        try {
            return view('admin.auctions.create');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load create form. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Store a newly created auction.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate($this->getValidationRules('store'));
            
            $auction = Auction::create($validatedData);

            return redirect()->route('admin.auctions.show', $auction)
                ->with('success', 'Auction created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create auction. ' . ($this->isDebug() ? $e->getMessage() : ''))
                ->withInput();
        }
    }

    /**
     * Display auction details with collaterals and bids.
     */
    public function show(Auction $auction)
    {
        try {
            $auction->load([
                'creator',
                'approvedBy',
                'collaterals.account.branch',
                'collaterals.bids.user',
                'collaterals.highestBidder',
                'collaterals.auctionResult'
            ]);

            return view('admin.auctions.show', compact('auction'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load auction details. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Show the form for editing the specified auction.
     */
    public function edit(Auction $auction)
    {
        try {
            return view('admin.auctions.edit', compact('auction'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load edit form. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Update the specified auction.
     */
    public function update(Request $request, Auction $auction)
    {
        try {
            $validatedData = $request->validate($this->getValidationRules('update'));
            
            // Check if trying to set status to active
            if ($validatedData['status'] === 'active' && $auction->status !== 'active') {
                // Check if there are other active auctions
                if ($this->hasActiveAuctions($auction->id)) {
                    return redirect()->back()
                        ->with('error', 'Cannot activate this auction. Another auction is currently active. Please check active auctions first.')
                        ->withInput();
                }
            }

            $auction->update($validatedData);

            return redirect()->route('admin.auctions.show', $auction)
                ->with('success', 'Auction updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update auction. ' . ($this->isDebug() ? $e->getMessage() : ''))
                ->withInput();
        }
    }

    /**
     * Remove the specified auction.
     */
    public function destroy(Auction $auction)
    {
        try {
            $auction->delete();

            return redirect()->route('admin.auctions.index')
                ->with('success', 'Auction deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete auction. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Display auction results.
     */
    public function results()
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load auction results. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Update payment status for auction result.
     */
    public function updatePaymentStatus(AuctionResult $auctionResult, Request $request)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:pending,paid,failed,refunded'
            ]);

            $auctionResult->update([
                'payment_status' => $request->payment_status
            ]);

            return redirect()->back()->with('success', 'Payment status updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update payment status. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Update delivery status for auction result.
     */
    public function updateDeliveryStatus(AuctionResult $auctionResult, Request $request)
    {
        try {
            $request->validate([
                'delivery_status' => 'required|in:pending,shipped,delivered,cancelled'
            ]);

            $auctionResult->update([
                'delivery_status' => $request->delivery_status
            ]);

            return redirect()->back()->with('success', 'Delivery status updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update delivery status. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Get live auction data for AJAX updates.
     */
    public function liveData(Auction $auction)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get live auction data. ' . ($this->isDebug() ? $e->getMessage() : '')
            ], 500);
        }
    }

    /**
     * Extend auction end time.
     */
    public function extendAuction(Auction $auction, Request $request)
    {
        try {
            $request->validate([
                'extension_hours' => 'required|integer|min:1|max:72'
            ]);

            $auction->end_datetime = $auction->end_datetime->addHours($request->extension_hours);
            $auction->save();

            return redirect()->back()->with('success', 'Auction extended successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to extend auction. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Cancel an auction.
     */
    public function cancelAuction(Auction $auction)
    {
        try {
            $auction->update(['status' => 'cancelled']);
            
            return redirect()->back()->with('success', 'Auction cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel auction. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Restart a cancelled auction.
     */
    public function restartAuction(Auction $auction, Request $request)
    {
        try {
            $request->validate([
                'start_datetime' => 'required|date|after:now',
                'end_datetime' => 'required|date|after:start_datetime'
            ]);

            $auction->update([
                'status' => 'scheduled',
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime
            ]);

            return redirect()->back()->with('success', 'Auction restarted successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to restart auction. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Approve an auction.
     */
    public function approve(Auction $auction, Request $request)
    {
        try {
            if ($auction->status !== 'pending_approval') {
                throw new \Exception('Auction is not pending approval.');
            }

            $auction->update([
                'status' => 'scheduled',
                'approved_by_user_id' => Auth::id(),
                'approved_at' => now()
            ]);

            return redirect()->back()->with('success', 'Auction approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve auction. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Reject an auction.
     */
    public function reject(Auction $auction, Request $request)
    {
        try {
            if ($auction->status !== 'pending_approval') {
                throw new \Exception('Auction is not pending approval.');
            }

            $request->validate([
                'rejection_reason' => 'required|string|max:1000'
            ]);

            $auction->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_by_user_id' => Auth::id(),
                'rejected_at' => now()
            ]);

            return redirect()->back()->with('success', 'Auction rejected successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject auction. ' . ($this->isDebug() ? $e->getMessage() : ''));
        }
    }

    /**
     * Get available collaterals for auction.
     */
    public function getAvailableCollaterals()
    {
        try {
            $collaterals = Collateral::whereDoesntHave('auction')
                ->orWhereHas('auction', function($query) {
                    $query->whereNotIn('status', ['active', 'scheduled', 'pending_approval']);
                })
                ->with(['account.branch', 'images'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $collaterals
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get available collaterals. ' . ($this->isDebug() ? $e->getMessage() : '')
            ], 500);
        }
    }

    /**
     * Preview auction details.
     */
    public function preview(Request $request)
    {
        try {
            $request->validate([
                'auction_title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date|after:start_datetime',
                'collateral_ids' => 'required|array|min:1'
            ]);

            $collaterals = Collateral::whereIn('id', $request->collateral_ids)
                ->with(['account.branch', 'images'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'auction' => $request->all(),
                    'collaterals' => $collaterals
                ]
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to preview auction. ' . ($this->isDebug() ? $e->getMessage() : '')
            ], 500);
        }
    }

    /**
     * Check if application is in debug mode.
     */
    private function isDebug()
    {
        return config('app.debug');
    }
}
