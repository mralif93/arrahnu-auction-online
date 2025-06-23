<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiMonitoringService;

class TestApiMonitoring extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:test-monitoring {--endpoint= : Test specific endpoint}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test API monitoring endpoints and services';

    protected $monitoringService;

    public function __construct(ApiMonitoringService $monitoringService)
    {
        parent::__construct();
        $this->monitoringService = $monitoringService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing API Monitoring System...');
        $this->newLine();

        if ($endpoint = $this->option('endpoint')) {
            $this->testSpecificEndpoint($endpoint);
            return;
        }

        $this->testAllServices();
    }

    private function testAllServices()
    {
        // Test overall status
        $this->info('📊 Testing Overall Status...');
        try {
            $status = $this->monitoringService->getOverallStatus();
            $this->displayStatus($status);
        } catch (\Exception $e) {
            $this->error('❌ Failed to get overall status: ' . $e->getMessage());
        }
        $this->newLine();

        // Test health check
        $this->info('🏥 Testing Health Check...');
        try {
            $health = $this->monitoringService->getHealthCheck();
            $this->displayHealth($health);
        } catch (\Exception $e) {
            $this->error('❌ Failed to get health check: ' . $e->getMessage());
        }
        $this->newLine();

        // Test endpoints status
        $this->info('🔗 Testing Endpoints Status...');
        try {
            $endpoints = $this->monitoringService->getEndpointsStatus();
            $this->displayEndpoints($endpoints);
        } catch (\Exception $e) {
            $this->error('❌ Failed to get endpoints status: ' . $e->getMessage());
        }
        $this->newLine();

        // Test performance metrics
        $this->info('⚡ Testing Performance Metrics...');
        try {
            $performance = $this->monitoringService->getPerformanceMetrics();
            $this->displayPerformance($performance);
        } catch (\Exception $e) {
            $this->error('❌ Failed to get performance metrics: ' . $e->getMessage());
        }
        $this->newLine();

        // Test usage statistics
        $this->info('📈 Testing Usage Statistics...');
        try {
            $usage = $this->monitoringService->getUsageStatistics();
            $this->displayUsage($usage);
        } catch (\Exception $e) {
            $this->error('❌ Failed to get usage statistics: ' . $e->getMessage());
        }
        $this->newLine();

        // Test system resources
        $this->info('💻 Testing System Resources...');
        try {
            $resources = $this->monitoringService->getSystemResources();
            $this->displayResources($resources);
        } catch (\Exception $e) {
            $this->error('❌ Failed to get system resources: ' . $e->getMessage());
        }
        $this->newLine();

        // Test database status
        $this->info('🗄️ Testing Database Status...');
        try {
            $database = $this->monitoringService->getDatabaseStatus();
            $this->displayDatabase($database);
        } catch (\Exception $e) {
            $this->error('❌ Failed to get database status: ' . $e->getMessage());
        }
        $this->newLine();

        // Test cache status
        $this->info('💾 Testing Cache Status...');
        try {
            $cache = $this->monitoringService->getCacheStatus();
            $this->displayCache($cache);
        } catch (\Exception $e) {
            $this->error('❌ Failed to get cache status: ' . $e->getMessage());
        }
        $this->newLine();

        $this->info('✅ API Monitoring Test Complete!');
    }

    private function testSpecificEndpoint($endpoint)
    {
        $this->info("🔗 Testing endpoint: {$endpoint}");
        
        try {
            $result = $this->monitoringService->testEndpoint($endpoint, 'GET');
            $this->displayEndpointResult($result);
        } catch (\Exception $e) {
            $this->error('❌ Failed to test endpoint: ' . $e->getMessage());
        }
    }

    private function displayStatus($status)
    {
        $overallStatus = $status['overall_status'];
        $icon = $overallStatus === 'healthy' ? '✅' : ($overallStatus === 'degraded' ? '⚠️' : '❌');
        
        $this->line("{$icon} Overall Status: {$overallStatus}");
        $this->line("📊 Response Time: {$status['summary']['response_time']}ms");
        $this->line("🔗 Endpoints: {$status['summary']['healthy_endpoints']}/{$status['summary']['total_endpoints']} Healthy");
        
        foreach ($status['services'] as $service => $serviceStatus) {
            $icon = $serviceStatus['status'] === 'healthy' ? '✅' : '❌';
            $this->line("  {$icon} {$service}: {$serviceStatus['status']}");
        }
    }

    private function displayHealth($health)
    {
        $this->line("📱 Application: {$health['application']['name']} v{$health['application']['version']}");
        $this->line("🌍 Environment: {$health['application']['environment']}");
        $this->line("🕐 Timezone: {$health['application']['timezone']}");
        
        foreach ($health as $component => $status) {
            if ($component !== 'timestamp' && $component !== 'application') {
                $icon = $status['status'] === 'healthy' ? '✅' : '❌';
                $this->line("  {$icon} {$component}: {$status['status']}");
            }
        }
    }

    private function displayEndpoints($endpoints)
    {
        foreach ($endpoints as $category => $categoryEndpoints) {
            $this->line("📁 {$category}:");
            foreach ($categoryEndpoints as $name => $endpoint) {
                $icon = $endpoint['status'] === 'healthy' ? '✅' : '❌';
                $this->line("  {$icon} {$name}: {$endpoint['method']} {$endpoint['endpoint']}");
                if (isset($endpoint['response_time'])) {
                    $this->line("     ⏱️ Response Time: {$endpoint['response_time']}ms");
                }
            }
        }
    }

    private function displayPerformance($performance)
    {
        $memory = $performance['memory_usage'];
        $this->line("🧠 Memory Usage: " . $this->formatBytes($memory['current']) . " / {$memory['limit']}");
        $this->line("📈 Peak Memory: " . $this->formatBytes($memory['peak']));
        $this->line("⏱️ Execution Time: " . round($performance['execution_time']['current'], 2) . "s");
        $this->line("🗄️ Database Queries: {$performance['database']['query_count']}");
    }

    private function displayUsage($usage)
    {
        $this->line("👥 Users: {$usage['users']['total']} total, {$usage['users']['active']} active");
        $this->line("🏷️ Auctions: {$usage['auctions']['total']} total, {$usage['auctions']['active']} active");
        $this->line("💎 Collaterals: {$usage['collaterals']['total']} total, {$usage['collaterals']['active']} active");
        $this->line("💰 Bids: {$usage['bids']['total']} total, {$usage['bids']['today']} today");
        $this->line("📍 Addresses: {$usage['addresses']['total']} total, {$usage['addresses']['primary']} primary");
    }

    private function displayResources($resources)
    {
        $memory = $resources['memory'];
        $disk = $resources['disk'];
        
        $this->line("🧠 Memory: {$memory['percentage']}% used (" . $this->formatBytes($memory['usage']) . ")");
        $this->line("💾 Disk: {$disk['usage_percentage']}% used (" . $this->formatBytes($disk['total_space'] - $disk['free_space']) . ")");
        $this->line("🐘 PHP Version: {$resources['php']['version']}");
        $this->line("🖥️ Server: {$resources['server']['software']}");
    }

    private function displayDatabase($database)
    {
        $icon = $database['status'] === 'connected' ? '✅' : '❌';
        $this->line("{$icon} Status: {$database['status']}");
        $this->line("🔧 Driver: {$database['driver']}");
        $this->line("🗄️ Database: {$database['database']}");
        $this->line("🌐 Host: {$database['host']}:{$database['port']}");
        if (isset($database['version'])) {
            $this->line("📋 Version: {$database['version']}");
        }
    }

    private function displayCache($cache)
    {
        $icon = $cache['status'] === 'connected' ? '✅' : '❌';
        $this->line("{$icon} Status: {$cache['status']}");
        $this->line("🔧 Driver: {$cache['driver']}");
        $this->line("🧪 Test Passed: " . ($cache['test_passed'] ? 'Yes' : 'No'));
        $this->line("🏷️ Prefix: {$cache['prefix']}");
    }

    private function displayEndpointResult($result)
    {
        $icon = $result['status'] === 'healthy' ? '✅' : '❌';
        $this->line("{$icon} Status: {$result['status']}");
        $this->line("🔗 Endpoint: {$result['endpoint']}");
        $this->line("📝 Method: {$result['method']}");
        if (isset($result['response_time'])) {
            $this->line("⏱️ Response Time: {$result['response_time']}ms");
        }
        if (isset($result['status_code'])) {
            $this->line("📊 Status Code: {$result['status_code']}");
        }
        if (isset($result['error'])) {
            $this->line("❌ Error: {$result['error']}");
        }
    }

    private function formatBytes($bytes)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
} 