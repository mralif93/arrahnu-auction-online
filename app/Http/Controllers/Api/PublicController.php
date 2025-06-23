<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Auction;
use App\Models\Branch;
use App\Models\Collateral;
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
        
        $query = Auction::select([
            'id',
            'auction_title',
            'description',
            'start_datetime',
            'end_datetime',
            'status'
        ])->withCount(['collaterals', 'bids']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('auction_title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Advanced Filtering
        if ($request->has('min_bids')) {
            $query->has('bids', '>=', $request->min_bids);
        }
        if ($request->has('min_collaterals')) {
            $query->has('collaterals', '>=', $request->min_collaterals);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->whereIn('status', explode(',', $request->status));
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('start_datetime', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('end_datetime', '<=', $request->end_date);
        }

        // Grouping
        if ($request->has('group_by') && in_array($request->group_by, ['status', 'date'])) {
            if ($request->group_by === 'date') {
                $auctions = $query->get()->groupBy(function($auction) {
                    return $auction->start_datetime->format('Y-m');
                });
            } else {
                $auctions = $query->get()->groupBy($request->group_by);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'grouped_auctions' => $auctions,
                    'summary' => [
                        'total_auctions' => $auctions->sum(function($group) {
                            return count($group);
                        }),
                        'groups_count' => $auctions->count()
                    ]
                ]
            ]);
        }

        // Sort
        $sortField = $request->get('sort_by', 'start_datetime');
        $sortOrder = $request->get('sort_order', 'desc');
        if (in_array($sortField, ['auction_title', 'start_datetime', 'end_datetime', 'status', 'collaterals_count', 'bids_count'])) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        // Statistics
        $statistics = [
            'total_auctions' => $query->count(),
            'status_counts' => $query->get()->groupBy('status')->map->count(),
            'total_collaterals' => $query->sum('collaterals_count'),
            'total_bids' => $query->sum('bids_count'),
            'average_collaterals_per_auction' => $query->avg('collaterals_count')
        ];

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
                    'sort_fields' => ['auction_title', 'start_datetime', 'end_datetime', 'status', 'collaterals_count', 'bids_count'],
                    'group_by_options' => ['status', 'date']
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
        try {
            $perPage = min($request->get('per_page', 20), 50);
            
            $query = Account::select([
                'id',
                'branch_id',
                'account_title',
                'description',
                'status',
                'created_at',
                'created_by_user_id',
                'approved_by_user_id'
            ])
            ->with([
                'branch:id,name,branch_address_id',
                'branch.address:id,branch_id,address_line_1,city,state,postcode',
                'collaterals' => function($q) {
                    $q->select('id', 'account_id', 'item_type', 'estimated_value_rm');
                }
            ]);

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('account_title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('branch', function($branchQuery) use ($search) {
                          $branchQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Advanced Filtering
            if ($request->has('min_collaterals')) {
                $query->has('collaterals', '>=', $request->min_collaterals);
            }

            // Filter by branch
            if ($request->has('branch_id')) {
                $query->whereIn('branch_id', explode(',', $request->branch_id));
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

            // Grouping
            if ($request->has('group_by') && in_array($request->group_by, ['status', 'branch_id', 'created_month'])) {
                if ($request->group_by === 'created_month') {
                    $accounts = $query->get()->groupBy(function($account) {
                        return $account->created_at->format('Y-m');
                    });
                } else {
                    $accounts = $query->get()->groupBy($request->group_by);
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'grouped_accounts' => $accounts->map(function($group) {
                            return $group->map(function($account) {
                                return [
                                    'id' => $account->id,
                                    'account_title' => $account->account_title,
                                    'description' => $account->description,
                                    'status' => $account->status,
                                    'branch' => [
                                        'id' => $account->branch->id,
                                        'name' => $account->branch->name,
                                        'address' => $account->branch->address
                                    ],
                                    'collaterals_count' => $account->collaterals->count(),
                                    'total_value' => $account->collaterals->sum('estimated_value_rm'),
                                    'created_at' => $account->created_at
                                ];
                            });
                        }),
                        'summary' => [
                            'total_accounts' => $accounts->sum(function($group) {
                                return count($group);
                            }),
                            'groups_count' => $accounts->count()
                        ]
                    ]
                ]);
            }

            // Sort
            $sortField = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            if (in_array($sortField, ['account_title', 'status', 'created_at'])) {
                $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
            }

            // Get statistics
            $statistics = [
                'total_accounts' => Account::count(),
                'status_counts' => Account::groupBy('status')->selectRaw('status, count(*) as count')->pluck('count', 'status'),
                'total_collaterals' => Account::withCount('collaterals')->get()->sum('collaterals_count'),
                'total_value' => Account::with('collaterals')->get()->sum(function($account) {
                    return $account->collaterals->sum('estimated_value_rm');
                })
            ];

            if ($request->has('export')) {
                $accounts = $query->get();
                return response()->json([
                    'success' => true,
                    'data' => [
                        'accounts' => $accounts->map(function($account) {
                            return [
                                'id' => $account->id,
                                'account_title' => $account->account_title,
                                'description' => $account->description,
                                'status' => $account->status,
                                'branch' => [
                                    'id' => $account->branch->id,
                                    'name' => $account->branch->name,
                                    'address' => $account->branch->address
                                ],
                                'collaterals_count' => $account->collaterals->count(),
                                'total_value' => $account->collaterals->sum('estimated_value_rm'),
                                'created_at' => $account->created_at
                            ];
                        }),
                        'statistics' => $statistics,
                        'generated_at' => now()->toISOString()
                    ]
                ]);
            }

            $accounts = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'accounts' => $accounts->map(function($account) {
                        return [
                            'id' => $account->id,
                            'account_title' => $account->account_title,
                            'description' => $account->description,
                            'status' => $account->status,
                            'branch' => [
                                'id' => $account->branch->id,
                                'name' => $account->branch->name,
                                'address' => $account->branch->address
                            ],
                            'collaterals_count' => $account->collaterals->count(),
                            'total_value' => $account->collaterals->sum('estimated_value_rm'),
                            'created_at' => $account->created_at
                        ];
                    }),
                    'pagination' => [
                        'current_page' => $accounts->currentPage(),
                        'last_page' => $accounts->lastPage(),
                        'per_page' => $accounts->perPage(),
                        'total' => $accounts->total()
                    ],
                    'filters' => [
                        'available_statuses' => ['draft', 'pending_approval', 'active', 'inactive', 'rejected'],
                        'sort_fields' => ['account_title', 'status', 'created_at'],
                        'group_by_options' => ['status', 'branch_id', 'created_month']
                    ],
                    'statistics' => $statistics
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve accounts list',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get list of collaterals with basic information and statistics
     */
    public function collateralsList(Request $request)
    {
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

        // Grouping
        if ($request->has('group_by') && in_array($request->group_by, ['status', 'item_type', 'created_month'])) {
            if ($request->group_by === 'created_month') {
                $collaterals = $query->get()->groupBy(function($collateral) {
                    return $collateral->created_at->format('Y-m');
                });
            } else {
                $collaterals = $query->get()->groupBy($request->group_by);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'grouped_collaterals' => $collaterals,
                    'summary' => [
                        'total_collaterals' => $collaterals->sum(function($group) {
                            return count($group);
                        }),
                        'groups_count' => $collaterals->count()
                    ]
                ]
            ]);
        }

        // Sort
        $sortField = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        if (in_array($sortField, ['item_type', 'status', 'created_at', 'images_count', 'bids_count'])) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        // Statistics
        $statistics = [
            'total_collaterals' => $query->count(),
            'status_counts' => $query->get()->groupBy('status')->map->count(),
            'item_type_counts' => $query->get()->groupBy('item_type')->map->count(),
            'total_images' => $query->sum('images_count'),
            'total_bids' => $query->sum('bids_count'),
            'average_bids_per_collateral' => $query->avg('bids_count')
        ];

        if ($request->has('export')) {
            $collaterals = $query->get();
            return response()->json([
                'success' => true,
                'data' => [
                    'collaterals' => $collaterals,
                    'statistics' => $statistics,
                    'generated_at' => now()->toISOString()
                ]
            ]);
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
                    'available_item_types' => ['gold', 'jewelry', 'watch', 'other'],
                    'available_statuses' => ['available', 'in_auction', 'sold', 'returned'],
                    'sort_fields' => ['item_type', 'status', 'created_at', 'images_count', 'bids_count'],
                    'group_by_options' => ['status', 'item_type', 'created_month']
                ],
                'statistics' => $statistics
            ]
        ]);
    }

    /**
     * Get list of branches with basic information and statistics
     */
    public function branchesList(Request $request)
    {
        try {
            $perPage = min($request->get('per_page', 20), 50);
            
            $query = Branch::select([
                'id',
                'name',
                'branch_address_id',
                'phone_number',
                'status',
                'created_at',
                'created_by_user_id',
                'approved_by_user_id'
            ])
            ->with([
                'address:id,branch_id,address_line_1,address_line_2,city,state,postcode,country',
                'accounts' => function($q) {
                    $q->select('id', 'branch_id', 'account_title', 'status');
                },
                'accounts.collaterals' => function($q) {
                    $q->select('id', 'account_id', 'estimated_value_rm');
                }
            ]);

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%")
                      ->orWhereHas('address', function($addressQuery) use ($search) {
                          $addressQuery->where('city', 'like', "%{$search}%")
                                     ->orWhere('state', 'like', "%{$search}%");
                      });
                });
            }

            // Advanced Filtering
            if ($request->has('min_accounts')) {
                $query->has('accounts', '>=', $request->min_accounts);
            }

            // Filter by state
            if ($request->has('state')) {
                $query->whereHas('address', function($q) use ($request) {
                    $q->whereIn('state', explode(',', $request->state));
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $query->whereIn('status', explode(',', $request->status));
            }

            // Grouping
            if ($request->has('group_by') && in_array($request->group_by, ['status', 'state'])) {
                if ($request->group_by === 'state') {
                    $branches = $query->get()->groupBy(function($branch) {
                        return $branch->address->state ?? 'Unknown';
                    });
                } else {
                    $branches = $query->get()->groupBy($request->group_by);
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'grouped_branches' => $branches->map(function($group) {
                            return $group->map(function($branch) {
                                return [
                                    'id' => $branch->id,
                                    'name' => $branch->name,
                                    'phone_number' => $branch->phone_number,
                                    'status' => $branch->status,
                                    'address' => $branch->address,
                                    'accounts_count' => $branch->accounts->count(),
                                    'total_value' => $branch->accounts->sum(function($account) {
                                        return $account->collaterals->sum('estimated_value_rm');
                                    }),
                                    'created_at' => $branch->created_at
                                ];
                            });
                        }),
                        'summary' => [
                            'total_branches' => $branches->sum(function($group) {
                                return count($group);
                            }),
                            'groups_count' => $branches->count()
                        ]
                    ]
                ]);
            }

            // Sort
            $sortField = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            if (in_array($sortField, ['name', 'status', 'created_at'])) {
                $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
            }

            // Get statistics
            $statistics = [
                'total_branches' => Branch::count(),
                'status_counts' => Branch::groupBy('status')->selectRaw('status, count(*) as count')->pluck('count', 'status'),
                'state_counts' => Branch::join('branch_addresses', 'branches.id', '=', 'branch_addresses.branch_id')
                    ->groupBy('branch_addresses.state')
                    ->selectRaw('branch_addresses.state, count(*) as count')
                    ->pluck('count', 'state'),
                'total_accounts' => Branch::withCount('accounts')->get()->sum('accounts_count'),
                'total_value' => Branch::with(['accounts.collaterals'])->get()->sum(function($branch) {
                    return $branch->accounts->sum(function($account) {
                        return $account->collaterals->sum('estimated_value_rm');
                    });
                })
            ];

            // Get unique states for filter options
            $states = Branch::join('branch_addresses', 'branches.id', '=', 'branch_addresses.branch_id')
                ->distinct()
                ->pluck('branch_addresses.state')
                ->sort()
                ->values();

            if ($request->has('export')) {
                $branches = $query->get();
                return response()->json([
                    'success' => true,
                    'data' => [
                        'branches' => $branches->map(function($branch) {
                            return [
                                'id' => $branch->id,
                                'name' => $branch->name,
                                'phone_number' => $branch->phone_number,
                                'status' => $branch->status,
                                'address' => $branch->address,
                                'accounts_count' => $branch->accounts->count(),
                                'total_value' => $branch->accounts->sum(function($account) {
                                    return $account->collaterals->sum('estimated_value_rm');
                                }),
                                'created_at' => $branch->created_at
                            ];
                        }),
                        'statistics' => $statistics,
                        'generated_at' => now()->toISOString()
                    ]
                ]);
            }

            $branches = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'branches' => $branches->map(function($branch) {
                        return [
                            'id' => $branch->id,
                            'name' => $branch->name,
                            'phone_number' => $branch->phone_number,
                            'status' => $branch->status,
                            'address' => $branch->address,
                            'accounts_count' => $branch->accounts->count(),
                            'total_value' => $branch->accounts->sum(function($account) {
                                return $account->collaterals->sum('estimated_value_rm');
                            }),
                            'created_at' => $branch->created_at
                        ];
                    }),
                    'pagination' => [
                        'current_page' => $branches->currentPage(),
                        'last_page' => $branches->lastPage(),
                        'per_page' => $branches->perPage(),
                        'total' => $branches->total()
                    ],
                    'filters' => [
                        'available_states' => $states,
                        'available_statuses' => ['draft', 'pending_approval', 'active', 'inactive', 'rejected'],
                        'sort_fields' => ['name', 'status', 'created_at'],
                        'group_by_options' => ['status', 'state']
                    ],
                    'statistics' => $statistics
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branches list',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
} 