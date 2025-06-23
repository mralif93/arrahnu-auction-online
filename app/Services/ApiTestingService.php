<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ApiTestingService
{
    const CACHE_TTL = 300; // 5 minutes cache
    const TIMEOUT = 5; // 5 seconds timeout
    const LOCAL_API_URL = 'http://127.0.0.1:8000'; // Local development server URL

    public function testEndpoint($method, $endpoint)
    {
        $baseUrl = app()->environment('local') ? self::LOCAL_API_URL : config('app.url');
        $baseUrl = rtrim($baseUrl, '/');
        $endpoint = ltrim($endpoint, '/');
        $url = "{$baseUrl}/{$endpoint}";

        try {
            $start = microtime(true);
            $response = Http::timeout(self::TIMEOUT)->$method($url);
            $duration = (microtime(true) - $start) * 1000;

            return [
                'url' => $url,
                'status' => $response->status(),
                'success' => $response->successful(),
                'duration_ms' => round($duration, 2)
            ];
        } catch (\Exception $e) {
            return [
                'url' => $url,
                'status' => 503,
                'success' => false,
                'duration_ms' => 0
            ];
        }
    }

    private function getTestData($endpoint, $method)
    {
        // Basic test data based on endpoint pattern
        $testData = [];

        if (str_contains($endpoint, 'auth')) {
            $testData = [
                'email' => 'test@example.com',
                'password' => 'password123'
            ];
        } elseif (str_contains($endpoint, 'user')) {
            $testData = [
                'name' => 'Test User',
                'email' => 'test@example.com'
            ];
        } elseif (str_contains($endpoint, 'auction')) {
            $testData = [
                'title' => 'Test Auction',
                'description' => 'Test Description',
                'start_date' => now()->toDateTimeString(),
                'end_date' => now()->addDays(7)->toDateTimeString()
            ];
        } elseif (str_contains($endpoint, 'bid')) {
            $testData = [
                'amount' => 1000,
                'auction_id' => 1
            ];
        } elseif (str_contains($endpoint, 'address')) {
            $testData = [
                'street' => 'Test Street',
                'city' => 'Test City',
                'state' => 'Test State',
                'postal_code' => '12345'
            ];
        }

        return $testData;
    }

    private function getStatusLabel($statusCode)
    {
        return match (true) {
            $statusCode >= 200 && $statusCode < 300 => 'Active',
            $statusCode >= 300 && $statusCode < 400 => 'Redirect',
            $statusCode >= 400 && $statusCode < 500 => 'Client Error',
            $statusCode >= 500 => 'Server Error',
            default => 'Unknown'
        };
    }

    private function getHealthStatus($statusCode, $responseTime)
    {
        if ($statusCode >= 200 && $statusCode < 300) {
            if ($responseTime < 0.3) {
                return 'healthy';
            } elseif ($responseTime < 1.0) {
                return 'degraded';
            } else {
                return 'slow';
            }
        } elseif ($statusCode >= 300 && $statusCode < 400) {
            return 'redirect';
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            return 'client-error';
        } else {
            return 'error';
        }
    }

    private function getStatusMessage($statusCode, $responseTime)
    {
        $baseMessage = match (true) {
            $statusCode >= 200 && $statusCode < 300 => $responseTime < 0.3 
                ? 'Endpoint is healthy and responding quickly'
                : ($responseTime < 1.0 
                    ? 'Endpoint is active but response time is degraded'
                    : 'Endpoint is active but responding slowly'),
            $statusCode >= 300 && $statusCode < 400 => 'Endpoint is redirecting to another location',
            $statusCode >= 400 && $statusCode < 500 => 'Client-side error - check authentication or parameters',
            $statusCode >= 500 => 'Server-side error - endpoint is not functioning correctly',
            default => 'Unknown status'
        };

        return $baseMessage . " (Status: {$statusCode}, Response Time: " . round($responseTime * 1000) . "ms)";
    }

    private function getErrorMessage($exception)
    {
        if ($exception instanceof \Illuminate\Http\Client\ConnectionException) {
            return 'Connection failed - Please check if the server is running (php artisan serve) and accessible at ' . self::LOCAL_API_URL;
        } elseif ($exception instanceof \Illuminate\Http\Client\RequestException) {
            return 'Request failed - invalid response from server. Check logs for details.';
        } else {
            return 'Error: ' . $exception->getMessage();
        }
    }
} 