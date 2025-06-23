<?php

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\DashboardController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new DashboardController();
    }

    /** @test */
    public function overview_returns_dashboard_overview_data()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/overview', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->overview($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('overview', $data['data']);
        
        $overview = $data['data']['overview'];
        $this->assertArrayHasKey('total_users', $overview);
        $this->assertArrayHasKey('active_auctions', $overview);
        $this->assertArrayHasKey('total_bids', $overview);
        $this->assertArrayHasKey('total_revenue', $overview);
    }

    /** @test */
    public function user_analytics_returns_user_statistics()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/user-analytics', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->userAnalytics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('analytics', $data['data']);
        
        $analytics = $data['data']['analytics'];
        $this->assertArrayHasKey('user_registrations', $analytics);
        $this->assertArrayHasKey('user_activity', $analytics);
        $this->assertArrayHasKey('user_distribution', $analytics);
    }

    /** @test */
    public function auction_analytics_returns_auction_statistics()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/auction-analytics', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->auctionAnalytics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('analytics', $data['data']);
        
        $analytics = $data['data']['analytics'];
        $this->assertArrayHasKey('auction_performance', $analytics);
        $this->assertArrayHasKey('bidding_trends', $analytics);
        $this->assertArrayHasKey('revenue_analysis', $analytics);
    }

    /** @test */
    public function system_metrics_returns_system_performance_data()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/system-metrics', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->systemMetrics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('metrics', $data['data']);
        
        $metrics = $data['data']['metrics'];
        $this->assertArrayHasKey('system_performance', $metrics);
        $this->assertArrayHasKey('database_stats', $metrics);
        $this->assertArrayHasKey('cache_stats', $metrics);
    }

    /** @test */
    public function activity_feed_returns_recent_activities()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/activity-feed', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->activityFeed($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('activities', $data['data']);
        $this->assertArrayHasKey('pagination', $data['data']);
    }

    /** @test */
    public function alerts_returns_system_alerts()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/alerts', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->alerts($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('alerts', $data['data']);
        
        $alerts = $data['data']['alerts'];
        $this->assertArrayHasKey('critical', $alerts);
        $this->assertArrayHasKey('warnings', $alerts);
        $this->assertArrayHasKey('info', $alerts);
    }

    /** @test */
    public function overview_includes_date_range_filter()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/overview', 'GET', [
            'date_from' => '2024-01-01',
            'date_to' => '2024-12-31'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->overview($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('overview', $data['data']);
        $this->assertArrayHasKey('date_range', $data['data']);
    }

    /** @test */
    public function user_analytics_supports_grouping_by_period()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/user-analytics', 'GET', [
            'group_by' => 'month'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->userAnalytics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('analytics', $data['data']);
    }

    /** @test */
    public function auction_analytics_supports_category_filtering()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/auction-analytics', 'GET', [
            'category' => 'jewelry'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->auctionAnalytics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('analytics', $data['data']);
    }

    /** @test */
    public function activity_feed_supports_pagination()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/activity-feed', 'GET', [
            'page' => 1,
            'per_page' => 20
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->activityFeed($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('activities', $data['data']);
        $this->assertArrayHasKey('pagination', $data['data']);
        
        $pagination = $data['data']['pagination'];
        $this->assertArrayHasKey('current_page', $pagination);
        $this->assertArrayHasKey('per_page', $pagination);
        $this->assertArrayHasKey('total', $pagination);
    }

    /** @test */
    public function alerts_filters_by_severity()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/alerts', 'GET', [
            'severity' => 'critical'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->alerts($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('alerts', $data['data']);
    }

    /** @test */
    public function system_metrics_includes_real_time_data()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/dashboard/system-metrics', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->systemMetrics($request);
        $data = json_decode($response->getContent(), true);

        $metrics = $data['data']['metrics'];
        $this->assertArrayHasKey('timestamp', $metrics);
        $this->assertArrayHasKey('cpu_usage', $metrics['system_performance']);
        $this->assertArrayHasKey('memory_usage', $metrics['system_performance']);
        $this->assertArrayHasKey('disk_usage', $metrics['system_performance']);
    }
} 