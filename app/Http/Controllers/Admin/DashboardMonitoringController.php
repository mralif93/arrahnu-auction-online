<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardMonitoringController extends Controller
{
    protected ApiDashboardController $apiDashboard;

    public function __construct(ApiDashboardController $apiDashboard)
    {
        $this->apiDashboard = $apiDashboard;
    }

    /**
     * Display the dashboard monitoring page.
     */
    public function index()
    {
        return view('admin.dashboard.monitoring');
    }

    /**
     * Get dashboard overview data via API.
     */
    public function getOverview(Request $request)
    {
        try {
            return $this->apiDashboard->overview();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching overview data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user analytics data via API.
     */
    public function getUserAnalytics(Request $request)
    {
        try {
            return $this->apiDashboard->userAnalytics($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get auction analytics data via API.
     */
    public function getAuctionAnalytics(Request $request)
    {
        try {
            return $this->apiDashboard->auctionAnalytics($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching auction analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system metrics data via API.
     */
    public function getSystemMetrics(Request $request)
    {
        try {
            return $this->apiDashboard->systemMetrics();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching system metrics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity feed data via API.
     */
    public function getActivityFeed(Request $request)
    {
        try {
            return $this->apiDashboard->activityFeed($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching activity feed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system alerts data via API.
     */
    public function getAlerts(Request $request)
    {
        try {
            return $this->apiDashboard->alerts();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching alerts: ' . $e->getMessage()
            ], 500);
        }
    }
}
