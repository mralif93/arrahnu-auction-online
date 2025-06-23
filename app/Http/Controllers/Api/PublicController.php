<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Auction;
use App\Models\Branch;
use App\Models\Collateral;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    /**
     * Get list of auctions with basic information and statistics
     */
    public function auctionsList(Request $request)
    {
        $perPage = min($request->get('per_page', 20), 50);
        
        $query = Auction::query();

        // Select specific fields
        $query->select([
            'id',
            'auction_title',
            'description',
            'start_datetime',
            'end_datetime',
            'status'
        ]);

        // Load relationships count
        $query->withCount(['collaterals', 'bids']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('auction_title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Advanced Filtering
        if ($request->filled('min_bids')) {
            $query->has('bids', '>=', $request->min_bids);
        }
        if ($request->filled('min_collaterals')) {
            $query->has('collaterals', '>=', $request->min_collaterals);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->whereIn('status', explode(',', $request->status));
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('start_datetime', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_datetime', '<=', $request->end_date);
        }

        // Sort
        $sortField = $request->get('sort_by', 'start_datetime');
        $sortOrder = $request->get('sort_order', 'desc');
        if (in_array($sortField, ['auction_title', 'start_datetime', 'end_datetime', 'status', 'collaterals_count', 'bids_count'])) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        // Get statistics
        $statistics = [
            'total_auctions' => Auction::count(),
            'status_counts' => Auction::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'total_collaterals' => Collateral::count(),
            'total_bids' => Bid::count(),
            'average_collaterals_per_auction' => number_format(
                Collateral::count() / (Auction::count() ?: 1),
                2
            )
        ];

        // Handle export request
        if ($request->has('export')) {
            $auctions = $query->get();
            return response()->json([
                'success' => true,
                'data' => [
                    'auctions' => $auctions,
                    'statistics' => $statistics,
                    'generated_at' => now()->toISOString()
                ]
            ]);
        }

        // Get paginated results
        $auctions = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'auctions' => $auctions->items(),
                'pagination' => [
                    'current_page' => $auctions->currentPage(),
                    'last_page' => $auctions->lastPage(),
                    'per_page' => $auctions->perPage(),
                    'total' => $auctions->total()
                ],
                'filters' => [
                    'available_statuses' => ['active', 'pending', 'completed', 'cancelled'],
                    'sort_fields' => ['auction_title', 'start_datetime', 'end_datetime', 'status', 'collaterals_count', 'bids_count']
                ],
                'statistics' => $statistics
            ]
        ]);
    }

    /**
     * Get list of accounts with basic information and statistics
     */
    public function accountsList(Request $request)
    {
        $perPage = min($request->get('per_page', 20), 50);
        
        $query = Account::query();

        // Select specific fields
        $query->select([
            'id',
            'account_title',
            'branch_id',
            'status',
            'created_at'
        ]);

        // Load relationships
        $query->with(['branch:id,name', 'collaterals:id,account_id']);

        // Load counts
        $query->withCount(['collaterals', 'collaterals as active_collaterals_count' => function($query) {
            $query->where('status', 'active');
        }]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_title', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->whereIn('status', explode(',', $request->status));
        }

        // Filter by date range
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        // Sort
        $sortField = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        if (in_array($sortField, ['account_title', 'created_at', 'status', 'collaterals_count'])) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $accounts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'accounts' => $accounts->items(),
                'pagination' => [
                    'current_page' => $accounts->currentPage(),
                    'last_page' => $accounts->lastPage(),
                    'per_page' => $accounts->perPage(),
                    'total' => $accounts->total()
                ],
                'filters' => [
                    'available_statuses' => ['active', 'pending', 'suspended', 'closed'],
                    'sort_fields' => ['account_title', 'created_at', 'status', 'collaterals_count']
                ],
                'statistics' => [
                    'total_accounts' => Account::count(),
                    'active_accounts' => Account::where('status', 'active')->count(),
                    'total_collaterals' => Collateral::count(),
                    'active_collaterals' => Collateral::where('status', 'active')->count()
                ]
            ]
        ]);
    }

    /**
     * Get list of collaterals with basic information and statistics
     */
    public function collateralsList(Request $request)
    {
        try {
            $perPage = min($request->get('per_page', 20), 50);
            
            $query = Collateral::select([
                'id',
                'item_type',
                'description',
                'status',
                'created_at'
            ])
            ->withCount(['images', 'bids']);

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('item_type', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Advanced Filtering
            if ($request->has('min_bids')) {
                $query->has('bids', '>=', $request->min_bids);
            }
            if ($request->has('has_images')) {
                $query->has('images');
            }

            // Filter by item type
            if ($request->has('item_type')) {
                $query->whereIn('item_type', explode(',', $request->item_type));
            }

            // Filter by status
            if ($request->has('status')) {
                $query->whereIn('status', explode(',', $request->status));
            }

            // Filter by date range
            if ($request->has('created_from')) {
                $query->whereDate('created_at', '>=', $request->created_from);
            }
            if ($request->has('created_to')) {
                $query->whereDate('created_at', '<=', $request->created_to);
            }

            // Sort
            $sortField = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            if (in_array($sortField, ['item_type', 'created_at', 'status', 'images_count', 'bids_count'])) {
                $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
            }

            $collaterals = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'collaterals' => $collaterals->items(),
                    'pagination' => [
                        'current_page' => $collaterals->currentPage(),
                        'last_page' => $collaterals->lastPage(),
                        'per_page' => $collaterals->perPage(),
                        'total' => $collaterals->total()
                    ],
                    'filters' => [
                        'available_statuses' => ['pending', 'approved', 'rejected', 'sold', 'unsold'],
                        'sort_fields' => ['item_type', 'created_at', 'status', 'images_count', 'bids_count']
                    ],
                    'statistics' => [
                        'total_collaterals' => Collateral::count(),
                        'status_counts' => Collateral::select('status', DB::raw('count(*) as count'))
                            ->groupBy('status')
                            ->pluck('count', 'status'),
                        'total_images' => DB::table('collateral_images')->count(),
                        'total_bids' => DB::table('bids')->count(),
                        'average_bids_per_collateral' => number_format(DB::table('bids')->count() / Collateral::count(), 2)
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve collaterals list',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get list of branches with basic information and statistics
     */
    public function branchesList(Request $request)
    {
        $perPage = min($request->get('per_page', 20), 50);
        
        $query = Branch::query();

        // Select specific fields
        $query->select([
            'id',
            'name',
            'branch_address_id',
            'phone_number',
            'status',
            'created_at'
        ]);

        // Load relationships
        $query->with(['branchAddress:id,address_line_1,address_line_2,city,state,postcode,country']);

        // Load counts
        $query->withCount(['accounts', 'accounts as active_accounts_count' => function($query) {
            $query->where('status', 'active');
        }]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->whereIn('status', explode(',', $request->status));
        }

        // Sort
        $sortField = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        if (in_array($sortField, ['name', 'created_at', 'status', 'accounts_count'])) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $branches = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'branches' => $branches->items(),
                'pagination' => [
                    'current_page' => $branches->currentPage(),
                    'last_page' => $branches->lastPage(),
                    'per_page' => $branches->perPage(),
                    'total' => $branches->total()
                ],
                'filters' => [
                    'available_statuses' => ['active', 'inactive'],
                    'sort_fields' => ['name', 'created_at', 'status', 'accounts_count']
                ],
                'statistics' => [
                    'total_branches' => Branch::count(),
                    'active_branches' => Branch::where('status', 'active')->count(),
                    'total_accounts' => Account::count(),
                    'active_accounts' => Account::where('status', 'active')->count()
                ]
            ]
        ]);
    }
} 