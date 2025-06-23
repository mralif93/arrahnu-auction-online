<?php

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\AdminController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected AdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AdminController();
    }

    /** @test */
    public function system_status_returns_system_health_information()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/status', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->systemStatus($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('status', $data['data']);
        
        $status = $data['data']['status'];
        $this->assertArrayHasKey('application', $status);
        $this->assertArrayHasKey('database', $status);
        $this->assertArrayHasKey('cache', $status);
        $this->assertArrayHasKey('queue', $status);
    }

    /** @test */
    public function performance_metrics_returns_system_performance_data()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/performance', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->performanceMetrics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('metrics', $data['data']);
        
        $metrics = $data['data']['metrics'];
        $this->assertArrayHasKey('response_time', $metrics);
        $this->assertArrayHasKey('memory_usage', $metrics);
        $this->assertArrayHasKey('cpu_usage', $metrics);
        $this->assertArrayHasKey('disk_usage', $metrics);
    }

    /** @test */
    public function recent_activities_returns_system_activities()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/activities', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->recentActivities($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('activities', $data['data']);
        $this->assertArrayHasKey('pagination', $data['data']);
    }

    /** @test */
    public function error_logs_returns_system_error_logs()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/logs/errors', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->errorLogs($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('logs', $data['data']);
        $this->assertArrayHasKey('pagination', $data['data']);
    }

    /** @test */
    public function clear_caches_clears_system_caches()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/clear-caches', 'POST');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->clearCaches($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Caches cleared successfully', $data['message']);
        $this->assertArrayHasKey('cleared_caches', $data['data']);
    }

    /** @test */
    public function system_status_includes_uptime_information()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/status', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->systemStatus($request);
        $data = json_decode($response->getContent(), true);

        $status = $data['data']['status'];
        $this->assertArrayHasKey('uptime', $status);
        $this->assertArrayHasKey('last_restart', $status);
        $this->assertArrayHasKey('version', $status);
    }

    /** @test */
    public function performance_metrics_supports_time_range_filtering()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/performance', 'GET', [
            'period' => '24h'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->performanceMetrics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('metrics', $data['data']);
        $this->assertArrayHasKey('period', $data['data']);
    }

    /** @test */
    public function recent_activities_supports_activity_type_filtering()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/activities', 'GET', [
            'type' => 'user_login'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->recentActivities($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('activities', $data['data']);
    }

    /** @test */
    public function error_logs_supports_severity_filtering()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/logs/errors', 'GET', [
            'severity' => 'error'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->errorLogs($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('logs', $data['data']);
    }

    /** @test */
    public function error_logs_supports_date_range_filtering()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/logs/errors', 'GET', [
            'date_from' => '2024-01-01',
            'date_to' => '2024-12-31'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->errorLogs($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('logs', $data['data']);
    }

    /** @test */
    public function clear_caches_specifies_which_caches_to_clear()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/clear-caches', 'POST', [
            'caches' => ['config', 'route', 'view']
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->clearCaches($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('cleared_caches', $data['data']);
        
        $clearedCaches = $data['data']['cleared_caches'];
        $this->assertContains('config', $clearedCaches);
        $this->assertContains('route', $clearedCaches);
        $this->assertContains('view', $clearedCaches);
    }

    /** @test */
    public function system_status_includes_service_health_checks()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/status', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->systemStatus($request);
        $data = json_decode($response->getContent(), true);

        $status = $data['data']['status'];
        $this->assertArrayHasKey('services', $status);
        
        $services = $status['services'];
        $this->assertArrayHasKey('mail', $services);
        $this->assertArrayHasKey('storage', $services);
        $this->assertArrayHasKey('session', $services);
    }

    /** @test */
    public function performance_metrics_includes_historical_data()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/system/performance', 'GET', [
            'include_history' => true
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->performanceMetrics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('metrics', $data['data']);
        $this->assertArrayHasKey('history', $data['data']);
    }
} 