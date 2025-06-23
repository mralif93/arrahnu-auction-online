<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Collateral;
use App\Models\Auction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard overview with key metrics.
     */
    public function overview(): JsonResponse
    {
        try {
            $overview = [
                'summary' => $this->getSummaryMetrics(),
                'recent_activity' => $this->getRecentActivity(),
                'system_health' => $this->getSystemHealth(),
                'quick_stats' => $this->getQuickStats()
            ];

            return response()->json([
                'success' => true,
                'message' => 'Dashboard overview retrieved successfully',
                'data' => [
                    'timestamp' => now()->toISOString(),
                    'overview' => $overview
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard overview',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get user analytics and charts data.
     */
    public function userAnalytics(Request $request): JsonResponse
    {
        try {
            $period = $request->input('period', '30'); // days
            $analytics = [
                'user_growth' => $this->getUserGrowthChart($period),
                'user_status_distribution' => $this->getUserStatusDistribution(),
                'user_role_distribution' => $this->getUserRoleDistribution(),
                'login_activity' => $this->getLoginActivityChart($period),
                'registration_trends' => $this->getRegistrationTrends($period)
            ];

            return response()->json([
                'success' => true,
                'message' => 'User analytics retrieved successfully',
                'data' => [
                    'period' => $period . ' days',
                    'timestamp' => now()->toISOString(),
                    'analytics' => $analytics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user analytics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get auction analytics and performance data.
     */
    public function auctionAnalytics(Request $request): JsonResponse
    {
        try {
            $period = $request->input('period', '30'); // days
            $analytics = [
                'auction_performance' => $this->getAuctionPerformanceChart($period),
                'auction_status_distribution' => $this->getAuctionStatusDistribution(),
                'collateral_categories' => $this->getCollateralCategoriesChart(),
                'auction_trends' => $this->getAuctionTrends($period),
                'revenue_metrics' => $this->getRevenueMetrics($period)
            ];

            return response()->json([
                'success' => true,
                'message' => 'Auction analytics retrieved successfully',
                'data' => [
                    'period' => $period . ' days',
                    'timestamp' => now()->toISOString(),
                    'analytics' => $analytics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve auction analytics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get system performance metrics for monitoring.
     */
    public function systemMetrics(): JsonResponse
    {
        try {
            $metrics = [
                'performance' => $this->getPerformanceMetrics(),
                'database' => $this->getDatabaseMetrics(),
                'storage' => $this->getStorageMetrics(),
                'security' => $this->getSecurityMetrics(),
                'uptime' => $this->getUptimeMetrics()
            ];

            return response()->json([
                'success' => true,
                'message' => 'System metrics retrieved successfully',
                'data' => [
                    'timestamp' => now()->toISOString(),
                    'metrics' => $metrics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve system metrics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get real-time activity feed.
     */
    public function activityFeed(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 20);
            $type = $request->input('type', 'all'); // all, users, auctions, system

            $activities = $this->getActivityFeed($limit, $type);

            return response()->json([
                'success' => true,
                'message' => 'Activity feed retrieved successfully',
                'data' => [
                    'timestamp' => now()->toISOString(),
                    'activities' => $activities,
                    'total' => count($activities),
                    'type_filter' => $type
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve activity feed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get alerts and notifications for admin.
     */
    public function alerts(): JsonResponse
    {
        try {
            $alerts = [
                'critical' => $this->getCriticalAlerts(),
                'warnings' => $this->getWarningAlerts(),
                'info' => $this->getInfoAlerts(),
                'system' => $this->getSystemAlerts()
            ];

            $totalAlerts = array_sum(array_map('count', $alerts));

            return response()->json([
                'success' => true,
                'message' => 'Alerts retrieved successfully',
                'data' => [
                    'timestamp' => now()->toISOString(),
                    'total_alerts' => $totalAlerts,
                    'alerts' => $alerts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve alerts',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get summary metrics for dashboard cards.
     */
    private function getSummaryMetrics(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('status', User::STATUS_ACTIVE)->count(),
            'pending_users' => User::where('status', User::STATUS_PENDING_APPROVAL)->count(),
            'total_auctions' => Auction::count(),
            'active_auctions' => Auction::where('status', 'active')->count(),
            'total_collaterals' => Collateral::count(),
            'total_branches' => Branch::count(),
            'total_accounts' => Account::count(),
            'users_growth_percentage' => $this->calculateGrowthPercentage('users'),
            'auctions_growth_percentage' => $this->calculateGrowthPercentage('auctions')
        ];
    }

    /**
     * Get recent activity summary.
     */
    private function getRecentActivity(): array
    {
        return [
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_auctions_today' => Auction::whereDate('created_at', today())->count(),
            'logins_today' => User::whereDate('last_login_at', today())->count(),
            'active_2fa_sessions' => 0, // Two-factor authentication removed
            'recent_registrations' => User::latest()->limit(5)->select('id', 'full_name', 'email', 'created_at')->get(),
            'recent_auctions' => Auction::latest()->limit(5)->select('id', 'title', 'status', 'created_at')->get()
        ];
    }

    /**
     * Get system health indicators.
     */
    private function getSystemHealth(): array
    {
        try {
            // Test database
            $dbStart = microtime(true);
            DB::select('SELECT 1');
            $dbTime = round((microtime(true) - $dbStart) * 1000, 2);

            // Test cache
            $cacheStart = microtime(true);
            Cache::put('health_test', 'ok', 1);
            Cache::get('health_test');
            Cache::forget('health_test');
            $cacheTime = round((microtime(true) - $cacheStart) * 1000, 2);

            return [
                'database' => [
                    'status' => 'healthy',
                    'response_time_ms' => $dbTime
                ],
                'cache' => [
                    'status' => 'healthy',
                    'response_time_ms' => $cacheTime
                ],
                'storage' => [
                    'status' => is_writable(storage_path()) ? 'healthy' : 'error'
                ],
                'overall_status' => 'healthy'
            ];
        } catch (\Exception $e) {
            return [
                'database' => ['status' => 'error', 'error' => $e->getMessage()],
                'cache' => ['status' => 'unknown'],
                'storage' => ['status' => 'unknown'],
                'overall_status' => 'error'
            ];
        }
    }

    /**
     * Get quick statistics for dashboard widgets.
     */
    private function getQuickStats(): array
    {
        return [
            'memory_usage' => [
                'current' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
                'formatted' => [
                    'current' => $this->formatBytes(memory_get_usage(true)),
                    'peak' => $this->formatBytes(memory_get_peak_usage(true))
                ]
            ],
            'response_time' => round((microtime(true) - (defined('LARAVEL_START') ? LARAVEL_START : microtime(true))) * 1000, 2),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ];
    }

    /**
     * Calculate growth percentage for entities.
     */
    private function calculateGrowthPercentage(string $entity): float
    {
        $model = match($entity) {
            'users' => User::class,
            'auctions' => Auction::class,
            'collaterals' => Collateral::class,
            default => User::class
        };

        $currentMonth = $model::whereMonth('created_at', now()->month)->count();
        $lastMonth = $model::whereMonth('created_at', now()->subMonth()->month)->count();

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get user growth chart data.
     */
    private function getUserGrowthChart(string $period): array
    {
        $days = (int) $period;
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = User::whereDate('created_at', $date)->count();
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $count,
                'label' => $date->format('M j')
            ];
        }

        return [
            'labels' => array_column($data, 'label'),
            'data' => array_column($data, 'count'),
            'total_period' => array_sum(array_column($data, 'count'))
        ];
    }

    /**
     * Get user status distribution.
     */
    private function getUserStatusDistribution(): array
    {
        $statuses = User::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $statuses->pluck('status')->toArray(),
            'data' => $statuses->pluck('count')->toArray(),
            'colors' => [
                'active' => '#10B981',
                'pending_approval' => '#F59E0B',
                'inactive' => '#EF4444',
                'rejected' => '#6B7280'
            ]
        ];
    }

    /**
     * Get user role distribution.
     */
    private function getUserRoleDistribution(): array
    {
        $roles = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();

        return [
            'labels' => $roles->pluck('role')->toArray(),
            'data' => $roles->pluck('count')->toArray(),
            'colors' => [
                'bidder' => '#3B82F6',
                'maker' => '#8B5CF6',
                'checker' => '#06B6D4'
            ]
        ];
    }

    /**
     * Get login activity chart.
     */
    private function getLoginActivityChart(string $period): array
    {
        $days = (int) $period;
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = User::whereDate('last_login_at', $date)->count();
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $count,
                'label' => $date->format('M j')
            ];
        }

        return [
            'labels' => array_column($data, 'label'),
            'data' => array_column($data, 'count'),
            'total_period' => array_sum(array_column($data, 'count'))
        ];
    }

    /**
     * Get registration trends.
     */
    private function getRegistrationTrends(string $period): array
    {
        $days = (int) $period;
        $weeklyData = [];
        $weeks = ceil($days / 7);

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $startDate = now()->subWeeks($i + 1)->startOfWeek();
            $endDate = now()->subWeeks($i)->endOfWeek();

            $count = User::whereBetween('created_at', [$startDate, $endDate])->count();

            $weeklyData[] = [
                'week' => $startDate->format('M j') . ' - ' . $endDate->format('M j'),
                'count' => $count
            ];
        }

        return [
            'labels' => array_column($weeklyData, 'week'),
            'data' => array_column($weeklyData, 'count'),
            'average_per_week' => count($weeklyData) > 0 ? round(array_sum(array_column($weeklyData, 'count')) / count($weeklyData), 2) : 0
        ];
    }

    /**
     * Get auction performance chart.
     */
    private function getAuctionPerformanceChart(string $period): array
    {
        $days = (int) $period;
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $created = Auction::whereDate('created_at', $date)->count();
            $completed = Auction::whereDate('updated_at', $date)
                ->where('status', 'completed')
                ->count();

            $data[] = [
                'date' => $date->format('Y-m-d'),
                'created' => $created,
                'completed' => $completed,
                'label' => $date->format('M j')
            ];
        }

        return [
            'labels' => array_column($data, 'label'),
            'datasets' => [
                [
                    'label' => 'Created',
                    'data' => array_column($data, 'created'),
                    'color' => '#3B82F6'
                ],
                [
                    'label' => 'Completed',
                    'data' => array_column($data, 'completed'),
                    'color' => '#10B981'
                ]
            ]
        ];
    }

    /**
     * Get auction status distribution.
     */
    private function getAuctionStatusDistribution(): array
    {
        $statuses = Auction::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $statuses->pluck('status')->toArray(),
            'data' => $statuses->pluck('count')->toArray(),
            'colors' => [
                'draft' => '#6B7280',
                'active' => '#10B981',
                'completed' => '#3B82F6',
                'cancelled' => '#EF4444'
            ]
        ];
    }

    /**
     * Get collateral categories chart.
     */
    private function getCollateralCategoriesChart(): array
    {
        // This would depend on your collateral categorization
        // For now, returning sample data
        return [
            'labels' => ['Gold', 'Electronics', 'Vehicles', 'Property', 'Others'],
            'data' => [45, 25, 15, 10, 5],
            'colors' => ['#F59E0B', '#3B82F6', '#10B981', '#8B5CF6', '#6B7280']
        ];
    }

    /**
     * Get auction trends.
     */
    private function getAuctionTrends(string $period): array
    {
        $days = (int) $period;
        $monthlyData = [];
        $months = ceil($days / 30);

        for ($i = $months - 1; $i >= 0; $i--) {
            $startDate = now()->subMonths($i + 1)->startOfMonth();
            $endDate = now()->subMonths($i)->endOfMonth();

            $count = Auction::whereBetween('created_at', [$startDate, $endDate])->count();

            $monthlyData[] = [
                'month' => $startDate->format('M Y'),
                'count' => $count
            ];
        }

        return [
            'labels' => array_column($monthlyData, 'month'),
            'data' => array_column($monthlyData, 'count'),
            'trend' => $this->calculateTrend(array_column($monthlyData, 'count'))
        ];
    }

    /**
     * Get revenue metrics.
     */
    private function getRevenueMetrics(string $period): array
    {
        // This would depend on your revenue tracking implementation
        // For now, returning sample data
        return [
            'total_revenue' => 125000,
            'revenue_growth' => 12.5,
            'average_auction_value' => 2500,
            'commission_earned' => 6250,
            'monthly_breakdown' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [15000, 18000, 22000, 19000, 25000, 26000]
            ]
        ];
    }

    /**
     * Calculate trend direction.
     */
    private function calculateTrend(array $data): string
    {
        if (count($data) < 2) return 'stable';

        $recent = array_slice($data, -3);
        $older = array_slice($data, 0, 3);

        $recentAvg = array_sum($recent) / count($recent);
        $olderAvg = array_sum($older) / count($older);

        if ($recentAvg > $olderAvg * 1.1) return 'up';
        if ($recentAvg < $olderAvg * 0.9) return 'down';
        return 'stable';
    }

    /**
     * Get performance metrics.
     */
    private function getPerformanceMetrics(): array
    {
        return [
            'memory' => [
                'current' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
                'limit' => ini_get('memory_limit'),
                'usage_percentage' => round((memory_get_usage(true) / $this->parseMemoryLimit()) * 100, 2)
            ],
            'cpu' => [
                'load_average' => sys_getloadavg()[0] ?? 0,
                'status' => 'normal'
            ],
            'response_time' => round((microtime(true) - (defined('LARAVEL_START') ? LARAVEL_START : microtime(true))) * 1000, 2)
        ];
    }

    /**
     * Get database metrics.
     */
    private function getDatabaseMetrics(): array
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'connection_status' => 'healthy',
                'response_time_ms' => $responseTime,
                'total_tables' => $this->getDatabaseTableCount(),
                'total_records' => $this->getTotalRecords()
            ];
        } catch (\Exception $e) {
            return [
                'connection_status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get storage metrics.
     */
    private function getStorageMetrics(): array
    {
        $storagePath = storage_path();
        $totalBytes = disk_total_space($storagePath);
        $freeBytes = disk_free_space($storagePath);
        $usedBytes = $totalBytes - $freeBytes;

        return [
            'total_space' => $this->formatBytes($totalBytes),
            'used_space' => $this->formatBytes($usedBytes),
            'free_space' => $this->formatBytes($freeBytes),
            'usage_percentage' => round(($usedBytes / $totalBytes) * 100, 2),
            'writable' => is_writable($storagePath)
        ];
    }

    /**
     * Get security metrics.
     */
    private function getSecurityMetrics(): array
    {
        return [
            'active_sessions' => 0, // Two-factor authentication removed
            'failed_logins_today' => 0, // Would need to implement login attempt tracking
            'admin_users' => User::where('is_admin', true)->count(),
            'pending_approvals' => User::where('status', User::STATUS_PENDING_APPROVAL)->count(),
            'two_factor_enabled' => config('auth.two_factor.enabled', false)
        ];
    }

    /**
     * Get uptime metrics.
     */
    private function getUptimeMetrics(): array
    {
        // This would typically come from a monitoring service
        return [
            'uptime_percentage' => 99.9,
            'last_downtime' => null,
            'average_response_time' => 150, // ms
            'status' => 'operational'
        ];
    }

    /**
     * Get activity feed.
     */
    private function getActivityFeed(int $limit, string $type): array
    {
        $activities = [];

        if ($type === 'all' || $type === 'users') {
            $recentUsers = User::latest()->limit($limit)->get();
            foreach ($recentUsers as $user) {
                $activities[] = [
                    'type' => 'user_registration',
                    'title' => 'New user registered',
                    'description' => $user->full_name . ' (' . $user->email . ') registered',
                    'timestamp' => $user->created_at,
                    'icon' => 'user-plus',
                    'color' => 'blue'
                ];
            }
        }

        if ($type === 'all' || $type === 'auctions') {
            $recentAuctions = Auction::latest()->limit($limit)->get();
            foreach ($recentAuctions as $auction) {
                $activities[] = [
                    'type' => 'auction_created',
                    'title' => 'New auction created',
                    'description' => $auction->title ?? 'Auction #' . $auction->id,
                    'timestamp' => $auction->created_at,
                    'icon' => 'gavel',
                    'color' => 'green'
                ];
            }
        }

        // Sort by timestamp and limit
        usort($activities, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return array_slice($activities, 0, $limit);
    }

    /**
     * Get critical alerts.
     */
    private function getCriticalAlerts(): array
    {
        $alerts = [];

        // Check disk space
        $storagePath = storage_path();
        $totalBytes = disk_total_space($storagePath);
        $freeBytes = disk_free_space($storagePath);
        $usagePercentage = (($totalBytes - $freeBytes) / $totalBytes) * 100;

        if ($usagePercentage > 90) {
            $alerts[] = [
                'id' => 'disk_space_critical',
                'title' => 'Critical: Low Disk Space',
                'message' => 'Disk usage is at ' . round($usagePercentage, 1) . '%',
                'timestamp' => now(),
                'severity' => 'critical'
            ];
        }

        // Check memory usage
        $memoryUsage = (memory_get_usage(true) / $this->parseMemoryLimit()) * 100;
        if ($memoryUsage > 90) {
            $alerts[] = [
                'id' => 'memory_critical',
                'title' => 'Critical: High Memory Usage',
                'message' => 'Memory usage is at ' . round($memoryUsage, 1) . '%',
                'timestamp' => now(),
                'severity' => 'critical'
            ];
        }

        return $alerts;
    }

    /**
     * Get warning alerts.
     */
    private function getWarningAlerts(): array
    {
        $alerts = [];

        // Check pending user approvals
        $pendingUsers = User::where('status', User::STATUS_PENDING_APPROVAL)->count();
        if ($pendingUsers > 10) {
            $alerts[] = [
                'id' => 'pending_users_warning',
                'title' => 'Warning: Many Pending User Approvals',
                'message' => $pendingUsers . ' users are waiting for approval',
                'timestamp' => now(),
                'severity' => 'warning'
            ];
        }

        return $alerts;
    }

    /**
     * Get info alerts.
     */
    private function getInfoAlerts(): array
    {
        $alerts = [];

        // Check new registrations today
        $newUsersToday = User::whereDate('created_at', today())->count();
        if ($newUsersToday > 0) {
            $alerts[] = [
                'id' => 'new_users_info',
                'title' => 'Info: New User Registrations',
                'message' => $newUsersToday . ' new users registered today',
                'timestamp' => now(),
                'severity' => 'info'
            ];
        }

        return $alerts;
    }

    /**
     * Get system alerts.
     */
    private function getSystemAlerts(): array
    {
        $alerts = [];

        // Check if debug mode is enabled in production
        if (config('app.env') === 'production' && config('app.debug')) {
            $alerts[] = [
                'id' => 'debug_mode_production',
                'title' => 'System: Debug Mode Enabled in Production',
                'message' => 'Debug mode should be disabled in production environment',
                'timestamp' => now(),
                'severity' => 'warning'
            ];
        }

        return $alerts;
    }

    /**
     * Parse memory limit to bytes.
     */
    private function parseMemoryLimit(): int
    {
        $limit = ini_get('memory_limit');
        if ($limit == -1) return PHP_INT_MAX;

        $unit = strtolower(substr($limit, -1));
        $value = (int) substr($limit, 0, -1);

        return match($unit) {
            'g' => $value * 1024 * 1024 * 1024,
            'm' => $value * 1024 * 1024,
            'k' => $value * 1024,
            default => $value
        };
    }

    /**
     * Get database table count.
     */
    private function getDatabaseTableCount(): int
    {
        try {
            $tables = DB::select('SHOW TABLES');
            return count($tables);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get total records across main tables.
     */
    private function getTotalRecords(): int
    {
        try {
            return User::count() +
                   Auction::count() +
                   Collateral::count() +
                   Branch::count() +
                   Account::count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
