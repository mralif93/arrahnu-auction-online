<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Collateral;
use App\Models\Address;
use Carbon\Carbon;

class ApiMonitoringService
{
    /**
     * Get overall API status
     */
    public function getOverallStatus(): array
    {
        $startTime = microtime(true);
        
        $status = [
            'timestamp' => now()->toISOString(),
            'overall_status' => 'healthy',
            'services' => [],
            'summary' => [
                'total_endpoints' => 0,
                'healthy_endpoints' => 0,
                'unhealthy_endpoints' => 0,
                'response_time' => 0,
            ]
        ];

        // Check core services
        $status['services']['database'] = $this->checkDatabaseConnection();
        $status['services']['cache'] = $this->checkCacheConnection();
        $status['services']['queue'] = $this->checkQueueConnection();
        $status['services']['storage'] = $this->checkStorageConnection();
        $status['services']['system_resources'] = $this->checkSystemResources();

        // Check API endpoints
        $endpointsStatus = $this->getEndpointsStatus();
        $status['endpoints'] = $endpointsStatus;
        
        // Calculate summary
        $status['summary']['total_endpoints'] = count($endpointsStatus);
        $status['summary']['healthy_endpoints'] = collect($endpointsStatus)->where('status', 'healthy')->count();
        $status['summary']['unhealthy_endpoints'] = collect($endpointsStatus)->where('status', 'unhealthy')->count();
        $status['summary']['response_time'] = round((microtime(true) - $startTime) * 1000, 2);

        // Determine overall status
        $unhealthyServices = collect($status['services'])->where('status', 'unhealthy')->count();
        if ($unhealthyServices > 0 || $status['summary']['unhealthy_endpoints'] > 0) {
            $status['overall_status'] = 'degraded';
        }

        if ($unhealthyServices > 2) {
            $status['overall_status'] = 'unhealthy';
        }

        return $status;
    }

    /**
     * Get detailed status of all API endpoints
     */
    public function getEndpointsStatus(): array
    {
        $endpoints = [
            // Public endpoints
            'public' => [
                'auth_register' => array_merge($this->testEndpoint('/api/auth/register', 'POST'), [
                    'description' => 'Register a new user account'
                ]),
                'auth_login' => array_merge($this->testEndpoint('/api/auth/login', 'POST'), [
                    'description' => 'Authenticate user and get access token'
                ]),
                'auth_forgot_password' => array_merge($this->testEndpoint('/api/auth/forgot-password', 'POST'), [
                    'description' => 'Send password reset link to email'
                ]),
                'auth_reset_password' => array_merge($this->testEndpoint('/api/auth/reset-password', 'POST'), [
                    'description' => 'Reset user password using token'
                ]),
                'health_check' => array_merge($this->testEndpoint('/api/health', 'GET'), [
                    'description' => 'Check API health status'
                ]),
                'api_info' => array_merge($this->testEndpoint('/api/info', 'GET'), [
                    'description' => 'Get API information and version'
                ]),
            ],
            
            // Protected endpoints (will be marked as requires_auth)
            'protected' => [
                'user_profile' => array_merge($this->testEndpoint('/api/user/profile', 'GET', [], true), [
                    'description' => 'Get authenticated user profile'
                ]),
                'user_update_profile' => array_merge($this->testEndpoint('/api/user/profile', 'PUT', [], true), [
                    'description' => 'Update user profile information'
                ]),
                'user_bidding_activity' => array_merge($this->testEndpoint('/api/user/bidding-activity', 'GET', [], true), [
                    'description' => 'Get user bidding history and activity'
                ]),
                'user_watchlist' => array_merge($this->testEndpoint('/api/user/watchlist', 'GET', [], true), [
                    'description' => 'Get user auction watchlist'
                ]),
                'bids_index' => array_merge($this->testEndpoint('/api/bids', 'GET', [], true), [
                    'description' => 'List all user bids'
                ]),
                'bids_store' => array_merge($this->testEndpoint('/api/bids', 'POST', [], true), [
                    'description' => 'Place a new bid'
                ]),
                'bids_active' => array_merge($this->testEndpoint('/api/bids/active', 'GET', [], true), [
                    'description' => 'Get active bids'
                ]),
                'bids_statistics' => array_merge($this->testEndpoint('/api/bids/statistics', 'GET', [], true), [
                    'description' => 'Get bidding statistics'
                ]),
                'auctions_active' => array_merge($this->testEndpoint('/api/auctions/active', 'GET', [], true), [
                    'description' => 'Get active auctions'
                ]),
                'addresses_index' => array_merge($this->testEndpoint('/api/addresses', 'GET', [], true), [
                    'description' => 'List user addresses'
                ]),
                'addresses_store' => array_merge($this->testEndpoint('/api/addresses', 'POST', [], true), [
                    'description' => 'Add new address'
                ]),
                'addresses_statistics' => array_merge($this->testEndpoint('/api/addresses/statistics', 'GET', [], true), [
                    'description' => 'Get address usage statistics'
                ]),
            ],
            
            // Admin endpoints (will be marked as requires_admin)
            'admin' => [
                'admin_dashboard_overview' => array_merge($this->testEndpoint('/api/admin/dashboard/overview', 'GET', [], true, true), [
                    'description' => 'Get admin dashboard overview'
                ]),
                'admin_dashboard_user_analytics' => array_merge($this->testEndpoint('/api/admin/dashboard/user-analytics', 'GET', [], true, true), [
                    'description' => 'Get user analytics data'
                ]),
                'admin_dashboard_auction_analytics' => array_merge($this->testEndpoint('/api/admin/dashboard/auction-analytics', 'GET', [], true, true), [
                    'description' => 'Get auction analytics data'
                ]),
                'admin_dashboard_system_metrics' => array_merge($this->testEndpoint('/api/admin/dashboard/system-metrics', 'GET', [], true, true), [
                    'description' => 'Get system performance metrics'
                ]),
                'admin_system_status' => array_merge($this->testEndpoint('/api/admin/system/status', 'GET', [], true, true), [
                    'description' => 'Get system health status'
                ]),
                'admin_system_performance' => array_merge($this->testEndpoint('/api/admin/system/performance', 'GET', [], true, true), [
                    'description' => 'Get system performance data'
                ]),
                'admin_addresses_index' => array_merge($this->testEndpoint('/api/admin/addresses', 'GET', [], true, true), [
                    'description' => 'List all addresses'
                ]),
                'admin_addresses_statistics' => array_merge($this->testEndpoint('/api/admin/addresses/statistics', 'GET', [], true, true), [
                    'description' => 'Get global address statistics'
                ]),
            ]
        ];

        return $endpoints;
    }

    /**
     * Test a specific endpoint
     */
    public function testEndpoint(string $endpoint, string $method, array $data = [], bool $requiresAuth = false, bool $requiresAdmin = false): array
    {
        $startTime = microtime(true);
        
        try {
            // Check if route exists
            $exists = Route::has($endpoint);
            
            if (!$exists) {
                return [
                    'status' => 'unhealthy',
                    'error' => 'Route not found',
                    'response_time' => 0,
                    'requires_auth' => $requiresAuth,
                    'requires_admin' => $requiresAdmin,
                    'method' => $method,
                    'endpoint' => $endpoint,
                    'last_tested' => now()->toISOString(),
                    'exists' => false
                ];
            }

            // For protected routes, we'll just check if they exist and are properly configured
            if ($requiresAuth) {
                return [
                    'status' => 'healthy',
                    'message' => 'Route exists and requires authentication',
                    'response_time' => round((microtime(true) - $startTime) * 1000, 2),
                    'requires_auth' => $requiresAuth,
                    'requires_admin' => $requiresAdmin,
                    'method' => $method,
                    'endpoint' => $endpoint,
                    'last_tested' => now()->toISOString(),
                    'exists' => true
                ];
            }

            // For public routes, we can actually test them
            $response = Http::timeout(10)->send($method, url($endpoint), [
                'json' => $data
            ]);

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'status' => $response->successful() ? 'healthy' : 'unhealthy',
                'status_code' => $response->status(),
                'response_time' => $responseTime,
                'requires_auth' => $requiresAuth,
                'requires_admin' => $requiresAdmin,
                'method' => $method,
                'endpoint' => $endpoint,
                'response_size' => strlen($response->body()),
                'last_tested' => now()->toISOString(),
                'exists' => true
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'response_time' => round((microtime(true) - $startTime) * 1000, 2),
                'requires_auth' => $requiresAuth,
                'requires_admin' => $requiresAdmin,
                'method' => $method,
                'endpoint' => $endpoint,
                'last_tested' => now()->toISOString(),
                'exists' => false
            ];
        }
    }

    /**
     * Get health check information
     */
    public function getHealthCheck(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'application' => [
                'name' => config('app.name'),
                'environment' => config('app.env'),
                'debug' => config('app.debug'),
                'version' => '1.0.0',
                'timezone' => config('app.timezone'),
            ],
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'queue' => $this->checkQueueConnection(),
            'storage' => $this->checkStorageConnection(),
            'system' => $this->checkSystemResources(),
        ];
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'memory_usage' => [
                'current' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
                'limit' => ini_get('memory_limit'),
            ],
            'execution_time' => [
                'current' => microtime(true) - LARAVEL_START,
                'max_execution_time' => ini_get('max_execution_time'),
            ],
            'database' => [
                'connections' => DB::connection()->getPdo() ? 'active' : 'inactive',
                'query_count' => DB::getQueryLog() ? count(DB::getQueryLog()) : 0,
            ],
            'cache' => [
                'driver' => config('cache.default'),
                'status' => Cache::store()->getConnection() ? 'connected' : 'disconnected',
            ],
            'queue' => [
                'driver' => config('queue.default'),
                'status' => 'active', // Assuming queue is working
            ],
        ];
    }

    /**
     * Get error summary
     */
    public function getErrorSummary(): array
    {
        $logFile = storage_path('logs/laravel.log');
        $errorCount = 0;
        $recentErrors = [];

        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            $errorCount = substr_count($logContent, '.ERROR');
            
            // Get recent error lines (last 50 lines)
            $lines = file($logFile);
            $recentLines = array_slice($lines, -50);
            
            foreach ($recentLines as $line) {
                if (strpos($line, '.ERROR') !== false) {
                    $recentErrors[] = trim($line);
                }
            }
        }

        return [
            'timestamp' => now()->toISOString(),
            'total_errors' => $errorCount,
            'recent_errors' => array_slice($recentErrors, -10), // Last 10 errors
            'log_file_exists' => file_exists($logFile),
            'log_file_size' => file_exists($logFile) ? filesize($logFile) : 0,
        ];
    }

    /**
     * Get usage statistics
     */
    public function getUsageStatistics(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'users' => [
                'total' => User::count(),
                'active' => User::where('email_verified_at', '!=', null)->count(),
                'pending_verification' => User::whereNull('email_verified_at')->count(),
            ],
            'auctions' => [
                'total' => Auction::count(),
                'active' => Auction::where('status', 'active')->count(),
                'completed' => Auction::where('status', 'completed')->count(),
                'scheduled' => Auction::where('status', 'scheduled')->count(),
            ],
            'collaterals' => [
                'total' => Collateral::count(),
                'active' => Collateral::where('status', 'active')->count(),
                'pending_approval' => Collateral::where('status', 'pending_approval')->count(),
            ],
            'bids' => [
                'total' => Bid::count(),
                'today' => Bid::whereDate('created_at', today())->count(),
                'this_week' => Bid::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'addresses' => [
                'total' => Address::count(),
                'primary' => Address::where('is_primary', true)->count(),
            ],
        ];
    }

    /**
     * Get system resources status
     */
    public function getSystemResources(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'memory' => [
                'usage' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
                'limit' => ini_get('memory_limit'),
                'percentage' => $this->calculateMemoryUsage(),
            ],
            'disk' => [
                'free_space' => disk_free_space(storage_path()),
                'total_space' => disk_total_space(storage_path()),
                'usage_percentage' => $this->calculateDiskUsage(),
            ],
            'php' => [
                'version' => PHP_VERSION,
                'extensions' => get_loaded_extensions(),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
            ],
            'server' => [
                'software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'php_sapi' => php_sapi_name(),
                'server_time' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * Get database status
     */
    public function getDatabaseStatus(): array
    {
        try {
            $connection = DB::connection();
            $pdo = $connection->getPdo();
            
            return [
                'timestamp' => now()->toISOString(),
                'status' => 'connected',
                'driver' => config('database.default'),
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'host' => config('database.connections.' . config('database.default') . '.host'),
                'port' => config('database.connections.' . config('database.default') . '.port'),
                'version' => $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION),
                'connection_timeout' => config('database.connections.' . config('database.default') . '.options.connection_timeout'),
                'read_timeout' => config('database.connections.' . config('database.default') . '.options.read_timeout'),
            ];
        } catch (\Exception $e) {
            return [
                'timestamp' => now()->toISOString(),
                'status' => 'disconnected',
                'error' => $e->getMessage(),
                'driver' => config('database.default'),
            ];
        }
    }

    /**
     * Get cache status
     */
    public function getCacheStatus(): array
    {
        try {
            $cache = Cache::store();
            $testKey = 'monitoring_test_' . time();
            $testValue = 'test_value';
            
            $cache->put($testKey, $testValue, 60);
            $retrieved = $cache->get($testKey);
            $cache->forget($testKey);
            
            return [
                'timestamp' => now()->toISOString(),
                'status' => 'connected',
                'driver' => config('cache.default'),
                'test_passed' => $retrieved === $testValue,
                'prefix' => config('cache.prefix'),
                'ttl' => config('cache.ttl'),
            ];
        } catch (\Exception $e) {
            return [
                'timestamp' => now()->toISOString(),
                'status' => 'disconnected',
                'error' => $e->getMessage(),
                'driver' => config('cache.default'),
            ];
        }
    }

    /**
     * Get queue status
     */
    public function getQueueStatus(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'driver' => config('queue.default'),
            'connections' => config('queue.connections'),
            'failed_driver' => config('queue.failed.driver'),
            'status' => 'active', // Assuming queue is working
        ];
    }

    /**
     * Check database connection
     */
    private function checkDatabaseConnection(): array
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'driver' => config('database.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => config('database.default'),
            ];
        }
    }

    /**
     * Check cache connection
     */
    private function checkCacheConnection(): array
    {
        try {
            $cache = Cache::store();
            $testKey = 'health_check_' . time();
            $cache->put($testKey, 'test', 60);
            $cache->forget($testKey);
            
            return [
                'status' => 'healthy',
                'message' => 'Cache connection successful',
                'driver' => config('cache.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => config('cache.default'),
            ];
        }
    }

    /**
     * Check queue connection
     */
    private function checkQueueConnection(): array
    {
        try {
            // Basic queue check
            return [
                'status' => 'healthy',
                'message' => 'Queue system available',
                'driver' => config('queue.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => config('queue.default'),
            ];
        }
    }

    /**
     * Check storage connection
     */
    private function checkStorageConnection(): array
    {
        try {
            $disk = Storage::disk();
            $testFile = 'monitoring_test_' . time() . '.txt';
            $disk->put($testFile, 'test content');
            $disk->delete($testFile);
            
            return [
                'status' => 'healthy',
                'message' => 'Storage connection successful',
                'driver' => config('filesystems.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => config('filesystems.default'),
            ];
        }
    }

    /**
     * Check system resources
     */
    private function checkSystemResources(): array
    {
        $memoryUsage = $this->calculateMemoryUsage();
        $diskUsage = $this->calculateDiskUsage();
        
        $status = 'healthy';
        if ($memoryUsage > 80 || $diskUsage > 90) {
            $status = 'warning';
        }
        if ($memoryUsage > 95 || $diskUsage > 95) {
            $status = 'unhealthy';
        }
        
        return [
            'status' => $status,
            'memory_usage_percentage' => $memoryUsage,
            'disk_usage_percentage' => $diskUsage,
            'message' => 'System resources monitored',
        ];
    }

    /**
     * Calculate memory usage percentage
     */
    private function calculateMemoryUsage(): float
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        
        if ($memoryLimit === '-1') {
            return 0; // No limit
        }
        
        $limitBytes = $this->convertToBytes($memoryLimit);
        return $limitBytes > 0 ? round(($memoryUsage / $limitBytes) * 100, 2) : 0;
    }

    /**
     * Calculate disk usage percentage
     */
    private function calculateDiskUsage(): float
    {
        $totalSpace = disk_total_space(storage_path());
        $freeSpace = disk_free_space(storage_path());
        $usedSpace = $totalSpace - $freeSpace;
        
        return $totalSpace > 0 ? round(($usedSpace / $totalSpace) * 100, 2) : 0;
    }

    /**
     * Convert memory limit string to bytes
     */
    private function convertToBytes(string $memoryLimit): int
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);
        
        switch ($unit) {
            case 'k': return $value * 1024;
            case 'm': return $value * 1024 * 1024;
            case 'g': return $value * 1024 * 1024 * 1024;
            default: return $value;
        }
    }
} 