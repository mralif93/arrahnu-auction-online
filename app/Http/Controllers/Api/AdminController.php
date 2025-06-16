<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Collateral;
use App\Models\Auction;
use App\Models\TwoFactorCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    /**
     * Get system status and health information.
     */
    public function systemStatus(): JsonResponse
    {
        try {
            // Database connection test
            $dbStatus = $this->checkDatabaseConnection();
            
            // Cache status
            $cacheStatus = $this->checkCacheConnection();
            
            // Storage status
            $storageStatus = $this->checkStorageStatus();
            
            // Application metrics
            $metrics = $this->getApplicationMetrics();
            
            // System information
            $systemInfo = $this->getSystemInformation();
            
            return response()->json([
                'success' => true,
                'message' => 'System status retrieved successfully',
                'data' => [
                    'overall_status' => $this->getOverallStatus($dbStatus, $cacheStatus, $storageStatus),
                    'timestamp' => now()->toISOString(),
                    'database' => $dbStatus,
                    'cache' => $cacheStatus,
                    'storage' => $storageStatus,
                    'metrics' => $metrics,
                    'system' => $systemInfo
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve system status',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get error logs with filtering and pagination.
     */
    public function errorLogs(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'level' => 'nullable|in:emergency,alert,critical,error,warning,notice,info,debug',
                'date' => 'nullable|date',
                'limit' => 'nullable|integer|min:1|max:1000',
                'search' => 'nullable|string|max:255'
            ]);

            $logFile = storage_path('logs/laravel.log');
            
            if (!File::exists($logFile)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No log file found',
                    'data' => [
                        'logs' => [],
                        'total' => 0,
                        'filtered' => 0
                    ]
                ]);
            }

            $logs = $this->parseLogFile($logFile, $request);

            return response()->json([
                'success' => true,
                'message' => 'Error logs retrieved successfully',
                'data' => $logs
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve error logs',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get application performance metrics.
     */
    public function performanceMetrics(): JsonResponse
    {
        try {
            $metrics = [
                'memory' => $this->getMemoryMetrics(),
                'database' => $this->getDatabaseMetrics(),
                'cache' => $this->getCacheMetrics(),
                'queue' => $this->getQueueMetrics(),
                'response_times' => $this->getResponseTimeMetrics()
            ];

            return response()->json([
                'success' => true,
                'message' => 'Performance metrics retrieved successfully',
                'data' => [
                    'timestamp' => now()->toISOString(),
                    'metrics' => $metrics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve performance metrics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Clear application caches.
     */
    public function clearCaches(): JsonResponse
    {
        try {
            $results = [];

            // Clear application cache
            Artisan::call('cache:clear');
            $results['cache'] = 'cleared';

            // Clear route cache
            Artisan::call('route:clear');
            $results['routes'] = 'cleared';

            // Clear config cache
            Artisan::call('config:clear');
            $results['config'] = 'cleared';

            // Clear view cache
            Artisan::call('view:clear');
            $results['views'] = 'cleared';

            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully',
                'data' => [
                    'cleared' => $results,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear caches',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get recent system activities.
     */
    public function recentActivities(): JsonResponse
    {
        try {
            $activities = [
                'recent_users' => $this->getRecentUsers(),
                'recent_logins' => $this->getRecentLogins(),
                'recent_2fa_codes' => $this->getRecent2FACodes(),
                'recent_errors' => $this->getRecentErrors()
            ];

            return response()->json([
                'success' => true,
                'message' => 'Recent activities retrieved successfully',
                'data' => [
                    'timestamp' => now()->toISOString(),
                    'activities' => $activities
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recent activities',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Check database connection.
     */
    private function checkDatabaseConnection(): array
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => 'healthy',
                'connection' => 'active',
                'response_time_ms' => $responseTime,
                'driver' => config('database.default'),
                'database' => config('database.connections.' . config('database.default') . '.database')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'connection' => 'failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check cache connection.
     */
    private function checkCacheConnection(): array
    {
        try {
            $start = microtime(true);
            Cache::put('health_check', 'test', 1);
            $value = Cache::get('health_check');
            Cache::forget('health_check');
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => $value === 'test' ? 'healthy' : 'error',
                'driver' => config('cache.default'),
                'response_time_ms' => $responseTime
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check storage status.
     */
    private function checkStorageStatus(): array
    {
        $storagePath = storage_path();
        $publicPath = public_path();

        return [
            'storage_writable' => is_writable($storagePath),
            'public_writable' => is_writable($publicPath),
            'logs_writable' => is_writable(storage_path('logs')),
            'cache_writable' => is_writable(storage_path('framework/cache')),
            'sessions_writable' => is_writable(storage_path('framework/sessions')),
            'disk_space' => $this->getDiskSpace()
        ];
    }

    /**
     * Get application metrics.
     */
    private function getApplicationMetrics(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('status', User::STATUS_ACTIVE)->count(),
            'pending_users' => User::where('status', User::STATUS_PENDING_APPROVAL)->count(),
            'total_branches' => Branch::count(),
            'total_accounts' => Account::count(),
            'total_collaterals' => Collateral::count(),
            'total_auctions' => Auction::count(),
            'active_2fa_codes' => TwoFactorCode::where('expires_at', '>', now())->count()
        ];
    }

    /**
     * Get system information.
     */
    private function getSystemInformation(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'timezone' => config('app.timezone'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize')
        ];
    }

    /**
     * Get overall system status.
     */
    private function getOverallStatus(array $db, array $cache, array $storage): string
    {
        if ($db['status'] === 'error' || $cache['status'] === 'error' || !$storage['storage_writable']) {
            return 'critical';
        }

        if (!$storage['logs_writable'] || !$storage['cache_writable']) {
            return 'warning';
        }

        return 'healthy';
    }

    /**
     * Parse log file and filter entries.
     */
    private function parseLogFile(string $logFile, Request $request): array
    {
        $content = File::get($logFile);
        $lines = explode("\n", $content);
        $logs = [];
        $currentLog = null;

        foreach ($lines as $line) {
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)/', $line, $matches)) {
                // Save previous log if exists
                if ($currentLog) {
                    $logs[] = $currentLog;
                }

                // Start new log entry
                $currentLog = [
                    'timestamp' => $matches[1],
                    'environment' => $matches[2],
                    'level' => $matches[3],
                    'message' => $matches[4],
                    'context' => ''
                ];
            } elseif ($currentLog && !empty(trim($line))) {
                // Add to context of current log
                $currentLog['context'] .= $line . "\n";
            }
        }

        // Add last log
        if ($currentLog) {
            $logs[] = $currentLog;
        }

        // Filter logs
        $filteredLogs = $this->filterLogs($logs, $request);

        // Limit results
        $limit = $request->input('limit', 100);
        $paginatedLogs = array_slice($filteredLogs, 0, $limit);

        return [
            'logs' => $paginatedLogs,
            'total' => count($logs),
            'filtered' => count($filteredLogs),
            'limit' => $limit
        ];
    }

    /**
     * Filter logs based on request parameters.
     */
    private function filterLogs(array $logs, Request $request): array
    {
        $level = $request->input('level');
        $date = $request->input('date');
        $search = $request->input('search');

        return array_filter($logs, function ($log) use ($level, $date, $search) {
            // Filter by level
            if ($level && $log['level'] !== $level) {
                return false;
            }

            // Filter by date
            if ($date && !str_starts_with($log['timestamp'], $date)) {
                return false;
            }

            // Filter by search term
            if ($search && !str_contains(strtolower($log['message']), strtolower($search))) {
                return false;
            }

            return true;
        });
    }

    /**
     * Get memory metrics.
     */
    private function getMemoryMetrics(): array
    {
        return [
            'current_usage' => memory_get_usage(true),
            'current_usage_formatted' => $this->formatBytes(memory_get_usage(true)),
            'peak_usage' => memory_get_peak_usage(true),
            'peak_usage_formatted' => $this->formatBytes(memory_get_peak_usage(true)),
            'limit' => ini_get('memory_limit')
        ];
    }

    /**
     * Get database metrics.
     */
    private function getDatabaseMetrics(): array
    {
        try {
            $start = microtime(true);
            $result = DB::select('SELECT 1');
            $queryTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'connection_time_ms' => $queryTime,
                'active_connections' => 1, // Basic metric
                'status' => 'healthy'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get cache metrics.
     */
    private function getCacheMetrics(): array
    {
        try {
            $start = microtime(true);
            Cache::put('metric_test', 'test', 1);
            Cache::get('metric_test');
            Cache::forget('metric_test');
            $cacheTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'response_time_ms' => $cacheTime,
                'driver' => config('cache.default'),
                'status' => 'healthy'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get queue metrics.
     */
    private function getQueueMetrics(): array
    {
        return [
            'driver' => config('queue.default'),
            'status' => 'not_implemented' // Can be extended based on queue driver
        ];
    }

    /**
     * Get response time metrics.
     */
    private function getResponseTimeMetrics(): array
    {
        return [
            'current_request_time' => round((microtime(true) - LARAVEL_START) * 1000, 2),
            'note' => 'Response time for this API request'
        ];
    }

    /**
     * Get recent users.
     */
    private function getRecentUsers(): array
    {
        return User::latest()
            ->limit(5)
            ->select('id', 'full_name', 'username', 'email', 'status', 'created_at')
            ->get()
            ->toArray();
    }

    /**
     * Get recent logins.
     */
    private function getRecentLogins(): array
    {
        return User::whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->limit(5)
            ->select('id', 'full_name', 'username', 'email', 'last_login_at')
            ->get()
            ->toArray();
    }

    /**
     * Get recent 2FA codes.
     */
    private function getRecent2FACodes(): array
    {
        return TwoFactorCode::with('user:id,full_name,email')
            ->latest()
            ->limit(5)
            ->select('id', 'user_id', 'created_at', 'expires_at', 'attempts')
            ->get()
            ->toArray();
    }

    /**
     * Get recent errors from log.
     */
    private function getRecentErrors(): array
    {
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            return [];
        }

        $content = File::get($logFile);
        $lines = explode("\n", $content);
        $errors = [];

        foreach (array_reverse($lines) as $line) {
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(ERROR|CRITICAL|EMERGENCY): (.+)/', $line, $matches)) {
                $errors[] = [
                    'timestamp' => $matches[1],
                    'level' => $matches[2],
                    'message' => $matches[3]
                ];

                if (count($errors) >= 5) {
                    break;
                }
            }
        }

        return $errors;
    }

    /**
     * Get disk space information.
     */
    private function getDiskSpace(): array
    {
        $path = storage_path();
        $totalBytes = disk_total_space($path);
        $freeBytes = disk_free_space($path);
        $usedBytes = $totalBytes - $freeBytes;

        return [
            'total' => $this->formatBytes($totalBytes),
            'used' => $this->formatBytes($usedBytes),
            'free' => $this->formatBytes($freeBytes),
            'usage_percentage' => round(($usedBytes / $totalBytes) * 100, 2)
        ];
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
}
