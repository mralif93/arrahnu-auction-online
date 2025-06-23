<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Collateral;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BidController extends Controller
{
    /**
     * Get user's bidding history with pagination.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = min($request->get('per_page', 20), 50);
            
            $query = $user->bids()->with([
                'collateral.auction',
                'collateral.images'
            ]);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->whereDate('bid_time', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('bid_time', '<=', $request->date_to);
            }

            $bids = $query->orderBy('bid_time', 'desc')->paginate($perPage);

            // Calculate statistics
            $statistics = [
                'total_bids' => $user->bids()->count(),
                'active_bids' => $user->bids()->where('status', Bid::STATUS_ACTIVE)->count(),
                'winning_bids' => $user->bids()->where('status', Bid::STATUS_WINNING)->count(),
                'successful_bids' => $user->bids()->where('status', Bid::STATUS_SUCCESSFUL)->count(),
                'total_bid_amount' => $user->bids()->sum('bid_amount_rm'),
                'average_bid_amount' => $user->bids()->avg('bid_amount_rm'),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Bidding history retrieved successfully.',
                'data' => [
                    'bids' => $bids->items(),
                    'pagination' => [
                        'current_page' => $bids->currentPage(),
                        'last_page' => $bids->lastPage(),
                        'per_page' => $bids->perPage(),
                        'total' => $bids->total(),
                        'from' => $bids->firstItem(),
                        'to' => $bids->lastItem(),
                    ],
                    'statistics' => $statistics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bidding history.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Place a new bid on a collateral.
     */
    public function store(Request $request)
    {
        $request->validate([
            'collateral_id' => 'required|exists:collaterals,id',
            'bid_amount' => 'required|numeric|min:0.01'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $user = Auth::user();
                $collateral = Collateral::with(['auction', 'bids'])->findOrFail($request->collateral_id);

                // Validate user can bid
                if ($user->role !== User::ROLE_BIDDER) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only bidders can place bids.',
                        'error_code' => 'INVALID_USER_ROLE'
                    ], 403);
                }

                if ($user->status !== User::STATUS_ACTIVE) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account must be active to place bids.',
                        'error_code' => 'INACTIVE_USER'
                    ], 403);
                }

                // Validate auction status
                if ($collateral->auction->status !== 'active') {
                    return response()->json([
                        'success' => false,
                        'message' => 'This auction is not currently active.',
                        'error_code' => 'AUCTION_NOT_ACTIVE'
                    ], 400);
                }

                // Check if auction has ended
                if (now() > $collateral->auction->end_datetime) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This auction has ended.',
                        'error_code' => 'AUCTION_ENDED'
                    ], 400);
                }

                // Validate bid amount
                $minimumBid = max($collateral->starting_bid_rm, $collateral->current_highest_bid_rm + 1);
                if ($request->bid_amount < $minimumBid) {
                    return response()->json([
                        'success' => false,
                        'message' => "Bid must be at least RM " . number_format($minimumBid, 2),
                        'error_code' => 'BID_TOO_LOW',
                        'data' => [
                            'minimum_bid' => $minimumBid,
                            'current_highest_bid' => $collateral->current_highest_bid_rm
                        ]
                    ], 400);
                }

                // Check if user is already the highest bidder
                if ($collateral->highest_bidder_user_id === $user->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are already the highest bidder on this item.',
                        'error_code' => 'ALREADY_HIGHEST_BIDDER'
                    ], 400);
                }

                // Mark previous highest bid as outbid
                if ($collateral->highest_bidder_user_id) {
                    $previousHighestBid = $collateral->bids()
                        ->where('user_id', $collateral->highest_bidder_user_id)
                        ->where('status', Bid::STATUS_WINNING)
                        ->first();
                    
                    if ($previousHighestBid) {
                        $previousHighestBid->markAsOutbid();
                    }
                }

                // Create new bid
                $bid = Bid::create([
                    'id' => Str::uuid(),
                    'collateral_id' => $collateral->id,
                    'user_id' => $user->id,
                    'bid_amount_rm' => $request->bid_amount,
                    'bid_time' => now(),
                    'status' => Bid::STATUS_WINNING,
                    'ip_address' => $request->ip(),
                ]);

                // Update collateral with new highest bid
                $collateral->update([
                    'current_highest_bid_rm' => $request->bid_amount,
                    'highest_bidder_user_id' => $user->id,
                ]);

                // Load relationships for response
                $bid->load(['collateral.auction', 'collateral.images', 'user']);

                return response()->json([
                    'success' => true,
                    'message' => 'Bid placed successfully!',
                    'data' => [
                        'bid' => $bid,
                        'collateral' => [
                            'id' => $collateral->id,
                            'item_type' => $collateral->item_type,
                            'current_highest_bid' => $collateral->current_highest_bid_rm,
                            'bid_count' => $collateral->bids()->count(),
                            'auction_end_time' => $collateral->auction->end_datetime,
                            'time_remaining' => $collateral->auction->end_datetime->diffInSeconds(now())
                        ]
                    ]
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to place bid.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active auctions for public viewing.
     */
    public function activeAuctions(Request $request)
    {
        try {
            $perPage = min($request->get('per_page', 20), 50);
            
            $query = Auction::select([
                'id', 
                'auction_title', 
                'description', 
                'start_datetime', 
                'end_datetime', 
                'status'
            ])
            ->where('status', 'active')
            ->where('start_datetime', '<=', now())
            ->where('end_datetime', '>', now());

            // Optional search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('auction_title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $auctions = $query->orderBy('end_datetime', 'asc')->paginate($perPage);

            // Add computed time remaining
            $auctions->getCollection()->transform(function ($auction) {
                $auction->time_remaining = $auction->end_datetime->diffInSeconds(now());
                return $auction;
            });

            return response()->json([
                'success' => true,
                'message' => 'Active auctions retrieved successfully.',
                'data' => [
                    'auctions' => $auctions->items(),
                    'pagination' => [
                        'current_page' => $auctions->currentPage(),
                        'last_page' => $auctions->lastPage(),
                        'per_page' => $auctions->perPage(),
                        'total' => $auctions->total(),
                        'from' => $auctions->firstItem(),
                        'to' => $auctions->lastItem(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active auctions.',
                'error' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred'
            ], 500);
        }
    }

    /**
     * Get collateral details for bidding.
     */
    public function collateralDetails(Collateral $collateral)
    {
        try {
            $collateral->load([
                'auction',
                'account.branch',
                'images',
                'bids' => function($q) {
                    $q->with('user')->orderBy('bid_time', 'desc')->limit(10);
                },
                'highestBidder'
            ]);

            // Check if auction is active
            if ($collateral->auction->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'This auction is not currently active.',
                    'error_code' => 'AUCTION_NOT_ACTIVE'
                ], 400);
            }

            // Calculate bidding information
            $user = Auth::user();
            $userBids = $collateral->bids->where('user_id', $user->id);
            $userHighestBid = $userBids->max('bid_amount_rm');
            $isUserHighestBidder = $collateral->highest_bidder_user_id === $user->id;
            $minimumBid = max($collateral->starting_bid_rm, $collateral->current_highest_bid_rm + 1);

            // Time calculations
            $timeRemaining = $collateral->auction->end_datetime->diffInSeconds(now());
            $isEndingSoon = $timeRemaining <= 3600; // 1 hour

            return response()->json([
                'success' => true,
                'message' => 'Collateral details retrieved successfully.',
                'data' => [
                    'collateral' => $collateral,
                    'bidding_info' => [
                        'minimum_bid' => $minimumBid,
                        'current_highest_bid' => $collateral->current_highest_bid_rm,
                        'starting_bid' => $collateral->starting_bid_rm,
                        'bid_count' => $collateral->bids->count(),
                        'user_highest_bid' => $userHighestBid,
                        'is_user_highest_bidder' => $isUserHighestBidder,
                        'user_bid_count' => $userBids->count(),
                    ],
                    'auction_info' => [
                        'id' => $collateral->auction->id,
                        'title' => $collateral->auction->auction_title,
                        'status' => $collateral->auction->status,
                        'end_datetime' => $collateral->auction->end_datetime,
                        'time_remaining' => $timeRemaining,
                        'is_ending_soon' => $isEndingSoon,
                    ],
                    'recent_bids' => $collateral->bids->map(function($bid) {
                        return [
                            'id' => $bid->id,
                            'amount' => $bid->bid_amount_rm,
                            'bidder_name' => $bid->user->full_name,
                            'bid_time' => $bid->bid_time,
                            'status' => $bid->status,
                            'is_current_user' => $bid->user_id === Auth::id()
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve collateral details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time bidding updates for a collateral.
     */
    public function liveUpdates(Collateral $collateral)
    {
        try {
            $collateral->load(['auction', 'bids' => function($q) {
                $q->with('user')->orderBy('bid_time', 'desc')->limit(5);
            }]);

            $timeRemaining = $collateral->auction->end_datetime->diffInSeconds(now());
            
            return response()->json([
                'success' => true,
                'message' => 'Live updates retrieved successfully.',
                'data' => [
                    'collateral_id' => $collateral->id,
                    'current_highest_bid' => $collateral->current_highest_bid_rm,
                    'bid_count' => $collateral->bids()->count(),
                    'time_remaining' => max(0, $timeRemaining),
                    'auction_status' => $collateral->auction->status,
                    'is_ending_soon' => $timeRemaining <= 300, // 5 minutes
                    'latest_bids' => $collateral->bids->map(function($bid) {
                        return [
                            'id' => $bid->id,
                            'amount' => $bid->bid_amount_rm,
                            'bidder_name' => $bid->user->full_name,
                            'bid_time' => $bid->bid_time,
                            'is_current_user' => $bid->user_id === Auth::id()
                        ];
                    }),
                    'minimum_next_bid' => max($collateral->starting_bid_rm, $collateral->current_highest_bid_rm + 1),
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve live updates.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's current active bids.
     */
    public function activeBids()
    {
        try {
            $user = Auth::user();
            
            $activeBids = $user->bids()
                ->with(['collateral.auction', 'collateral.images'])
                ->whereIn('status', [Bid::STATUS_ACTIVE, Bid::STATUS_WINNING])
                ->whereHas('collateral.auction', function($q) {
                    $q->where('status', 'active')
                      ->where('end_datetime', '>', now());
                })
                ->orderBy('bid_time', 'desc')
                ->get();

            // Add computed fields
            $activeBids->transform(function($bid) {
                $bid->time_remaining = $bid->collateral->auction->end_datetime->diffInSeconds(now());
                $bid->is_winning = $bid->collateral->highest_bidder_user_id === $bid->user_id;
                $bid->current_highest_bid = $bid->collateral->current_highest_bid_rm;
                $bid->thumbnail = $bid->collateral->images->first()?->image_url;
                return $bid;
            });

            return response()->json([
                'success' => true,
                'message' => 'Active bids retrieved successfully.',
                'data' => [
                    'active_bids' => $activeBids,
                    'total_active' => $activeBids->count(),
                    'winning_count' => $activeBids->where('is_winning', true)->count(),
                    'total_bid_value' => $activeBids->sum('bid_amount_rm')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active bids.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bidding statistics for user.
     */
    public function statistics()
    {
        try {
            $user = Auth::user();
            
            $stats = [
                'total_bids' => $user->bids()->count(),
                'active_bids' => $user->bids()->where('status', Bid::STATUS_ACTIVE)->count(),
                'winning_bids' => $user->bids()->where('status', Bid::STATUS_WINNING)->count(),
                'successful_bids' => $user->bids()->where('status', Bid::STATUS_SUCCESSFUL)->count(),
                'total_spent' => $user->bids()->where('status', Bid::STATUS_SUCCESSFUL)->sum('bid_amount_rm'),
                'total_bid_amount' => $user->bids()->sum('bid_amount_rm'),
                'average_bid' => $user->bids()->avg('bid_amount_rm'),
                'highest_bid' => $user->bids()->max('bid_amount_rm'),
                'first_bid_date' => $user->bids()->min('bid_time'),
                'last_bid_date' => $user->bids()->max('bid_time'),
            ];

            // Monthly bidding activity - Database agnostic approach
            $monthlyActivity = $user->bids()
                ->where('bid_time', '>=', now()->subMonths(12))
                ->get()
                ->groupBy(function($bid) {
                    return $bid->bid_time->format('Y-m');
                })
                ->map(function($bids, $month) {
                    return [
                        'month' => $month,
                        'bid_count' => $bids->count(),
                        'total_amount' => $bids->sum('bid_amount_rm')
                    ];
                })
                ->values()
                ->sortBy('month');

            return response()->json([
                'success' => true,
                'message' => 'Bidding statistics retrieved successfully.',
                'data' => [
                    'statistics' => $stats,
                    'monthly_activity' => $monthlyActivity
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bidding statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a bid (if allowed).
     */
    public function cancel(Bid $bid)
    {
        try {
            // Ensure user owns the bid
            if ($bid->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only cancel your own bids.',
                    'error_code' => 'UNAUTHORIZED'
                ], 403);
            }

            // Check if bid can be cancelled
            if (!in_array($bid->status, [Bid::STATUS_ACTIVE, Bid::STATUS_WINNING])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This bid cannot be cancelled.',
                    'error_code' => 'BID_NOT_CANCELLABLE'
                ], 400);
            }

            // Check if auction is still active
            if ($bid->collateral->auction->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel bid on inactive auction.',
                    'error_code' => 'AUCTION_NOT_ACTIVE'
                ], 400);
            }

            return DB::transaction(function () use ($bid) {
                $bid->update(['status' => Bid::STATUS_CANCELLED]);

                // If this was the winning bid, find the next highest bid
                if ($bid->status === Bid::STATUS_WINNING) {
                    $nextHighestBid = $bid->collateral->bids()
                        ->where('id', '!=', $bid->id)
                        ->where('status', '!=', Bid::STATUS_CANCELLED)
                        ->orderBy('bid_amount_rm', 'desc')
                        ->first();

                    if ($nextHighestBid) {
                        $nextHighestBid->markAsWinning();
                        $bid->collateral->update([
                            'current_highest_bid_rm' => $nextHighestBid->bid_amount_rm,
                            'highest_bidder_user_id' => $nextHighestBid->user_id,
                        ]);
                    } else {
                        $bid->collateral->update([
                            'current_highest_bid_rm' => $bid->collateral->starting_bid_rm,
                            'highest_bidder_user_id' => null,
                        ]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Bid cancelled successfully.',
                    'data' => [
                        'cancelled_bid_id' => $bid->id,
                        'new_highest_bid' => $bid->collateral->fresh()->current_highest_bid_rm
                    ]
                ]);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel bid.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 