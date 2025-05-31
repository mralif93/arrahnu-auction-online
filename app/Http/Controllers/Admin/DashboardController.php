<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Auction;
use App\Models\Collateral;
use App\Models\Bid;
use App\Models\AuctionResult;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get active auctions
        $activeAuctions = $this->getActiveAuctions();

        // Get pending approvals
        $pendingApprovals = $this->getPendingApprovals();

        return view('admin.dashboard', compact(
            'stats',
            'recentActivities',
            'activeAuctions',
            'pendingApprovals'
        ));
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats()
    {
        // Calculate total revenue from completed auctions
        $totalRevenue = AuctionResult::where('result_status', 'completed')
                                   ->where('payment_status', 'paid')
                                   ->sum('winning_bid_amount');

        // Get active auctions count
        $activeAuctions = Auction::where('status', 'active')->count();

        // Get total users count
        $totalUsers = User::count();

        // Calculate commission (assuming 10% commission rate)
        $commissionEarned = $totalRevenue * 0.10;

        // Get user statistics
        $activeBidders = User::where('role', 'bidder')
                           ->where('status', 'active')
                           ->count();

        $newRegistrations = User::where('created_at', '>=', now()->subDays(30))
                              ->count();

        $pendingVerifications = User::where('status', 'pending_approval')
                                  ->count();

        return [
            'total_revenue' => $totalRevenue,
            'active_auctions' => $activeAuctions,
            'total_users' => $totalUsers,
            'commission_earned' => $commissionEarned,
            'active_bidders' => $activeBidders,
            'new_registrations' => $newRegistrations,
            'pending_verifications' => $pendingVerifications,
            'auctions_ending_today' => Auction::where('status', 'active')
                                              ->whereDate('end_datetime', today())
                                              ->count(),
        ];
    }

    /**
     * Get recent activities from audit logs.
     */
    private function getRecentActivities()
    {
        return AuditLog::with('user')
                      ->orderBy('timestamp', 'desc')
                      ->take(10)
                      ->get()
                      ->map(function ($log) {
                          return [
                              'type' => strtolower($log->action_type),
                              'title' => $this->formatActivityTitle($log),
                              'description' => $log->description ?? $this->formatActivityDescription($log),
                              'time' => $log->timestamp->diffForHumans(),
                              'status' => $this->getActivityStatus($log),
                              'color' => $this->getActivityColor($log),
                              'user' => $log->user->full_name ?? 'System',
                          ];
                      });
    }

    /**
     * Get active auctions.
     */
    private function getActiveAuctions()
    {
        return Auction::with(['branch', 'collaterals.images', 'collaterals.bids'])
                     ->where('status', 'active')
                     ->orderBy('end_datetime', 'asc')
                     ->take(5)
                     ->get()
                     ->map(function ($auction) {
                         $totalBids = $auction->collaterals->sum(function ($collateral) {
                             return $collateral->bids->count();
                         });
                         $totalCurrentBid = $auction->collaterals->sum('current_highest_bid_rm');

                         return [
                             'id' => $auction->id,
                             'title' => $auction->auction_title,
                             'current_bid' => $totalCurrentBid,
                             'bid_count' => $totalBids,
                             'ends_at' => $auction->end_datetime,
                             'status' => $auction->end_datetime > now() ? 'Live' : 'Ending Soon',
                             'image' => $auction->collaterals->first()?->images->first()?->image_url ?? null,
                             'items_count' => $auction->collaterals->count(),
                         ];
                     });
    }

    /**
     * Get pending approvals.
     */
    private function getPendingApprovals()
    {
        return [
            'users' => User::where('status', 'pending_approval')->count(),
            'branches' => Branch::where('status', 'pending_approval')->count(),
            'accounts' => Account::where('status', 'pending_approval')->count(),
            'auctions' => Auction::where('status', 'scheduled')->count(),
            'collaterals' => Collateral::where('status', 'pending_approval')->count(),
        ];
    }

    /**
     * Format activity title based on audit log.
     */
    private function formatActivityTitle($log)
    {
        $action = strtolower($log->action_type);
        $module = $log->module_affected;

        switch ($action) {
            case 'create':
                return "New {$module} created";
            case 'update':
                return "{$module} updated";
            case 'delete':
                return "{$module} deleted";
            case 'approve':
                return "{$module} approved";
            case 'reject':
                return "{$module} rejected";
            default:
                return "{$module} {$action}";
        }
    }

    /**
     * Format activity description based on audit log.
     */
    private function formatActivityDescription($log)
    {
        $module = $log->module_affected;
        $recordId = $log->record_id_affected;

        return "{$module} ID: {$recordId}";
    }

    /**
     * Get activity status based on audit log.
     */
    private function getActivityStatus($log)
    {
        $action = strtolower($log->action_type);

        switch ($action) {
            case 'create':
                return 'New';
            case 'update':
                return 'Updated';
            case 'approve':
                return 'Approved';
            case 'reject':
                return 'Rejected';
            default:
                return ucfirst($action);
        }
    }

    /**
     * Get activity color based on audit log.
     */
    private function getActivityColor($log)
    {
        $action = strtolower($log->action_type);

        switch ($action) {
            case 'create':
                return 'green';
            case 'update':
                return 'blue';
            case 'delete':
                return 'red';
            case 'approve':
                return 'green';
            case 'reject':
                return 'red';
            default:
                return 'gray';
        }
    }

    /**
     * Show admin profile settings.
     */
    public function profile()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.profile', compact('totalUsers', 'totalAdmins', 'recentUsers'));
    }
}
