<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiMonitoringService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class ApiMonitoringController extends Controller
{
    use ApiResponse;

    protected $monitoringService;

    public function __construct(ApiMonitoringService $monitoringService)
    {
        $this->monitoringService = $monitoringService;
    }

    /**
     * Get comprehensive API status overview
     */
    public function status(): JsonResponse
    {
        try {
            $status = $this->monitoringService->getOverallStatus();
            return $this->successResponse($status, 'API Status Overview');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get API status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get detailed status of all API endpoints
     */
    public function endpoints(): JsonResponse
    {
        try {
            $endpoints = $this->monitoringService->getEndpointsStatus();
            return $this->successResponse($endpoints, 'API Endpoints Status');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get endpoints status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get service health check
     */
    public function health(): JsonResponse
    {
        try {
            $health = $this->monitoringService->getHealthCheck();
            return $this->successResponse($health, 'API Health Check');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get health check: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get performance metrics
     */
    public function performance(): JsonResponse
    {
        try {
            $performance = $this->monitoringService->getPerformanceMetrics();
            return $this->successResponse($performance, 'API Performance Metrics');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get performance metrics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get error summary
     */
    public function errors(): JsonResponse
    {
        try {
            $errors = $this->monitoringService->getErrorSummary();
            return $this->successResponse($errors, 'API Error Summary');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get error summary: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Test specific endpoint
     */
    public function testEndpoint(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'endpoint' => 'required|string',
                'method' => 'required|in:GET,POST,PUT,DELETE,PATCH',
                'data' => 'sometimes|array'
            ]);

            $result = $this->monitoringService->testEndpoint(
                $request->endpoint,
                $request->method,
                $request->data ?? []
            );

            return $this->successResponse($result, 'Endpoint Test Result');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to test endpoint: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get API usage statistics
     */
    public function usage(): JsonResponse
    {
        try {
            $usage = $this->monitoringService->getUsageStatistics();
            return $this->successResponse($usage, 'API Usage Statistics');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get usage statistics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get system resources status
     */
    public function resources(): JsonResponse
    {
        try {
            $resources = $this->monitoringService->getSystemResources();
            return $this->successResponse($resources, 'System Resources Status');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get system resources: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get database connectivity status
     */
    public function database(): JsonResponse
    {
        try {
            $database = $this->monitoringService->getDatabaseStatus();
            return $this->successResponse($database, 'Database Status');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get database status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get cache status
     */
    public function cache(): JsonResponse
    {
        try {
            $cache = $this->monitoringService->getCacheStatus();
            return $this->successResponse($cache, 'Cache Status');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get cache status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get queue status
     */
    public function queue(): JsonResponse
    {
        try {
            $queue = $this->monitoringService->getQueueStatus();
            return $this->successResponse($queue, 'Queue Status');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get queue status: ' . $e->getMessage(), 500);
        }
    }
} 