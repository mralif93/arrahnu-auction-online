<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiTestingService;
use Illuminate\Support\Facades\Route;

class ApiController extends Controller
{
    protected $apiTesting;

    public function __construct(ApiTestingService $apiTesting)
    {
        $this->apiTesting = $apiTesting;
    }

    public function index()
    {
        $routes = Route::getRoutes();
        
        $apiEndpoints = [
            'Public APIs' => [
                'Authentication' => [],
                'Monitoring' => [],
                'Lists' => []
            ],
            'Protected APIs' => [
                'User Management' => [],
                'Bidding' => [],
                'Address Management' => []
            ],
            'Admin APIs' => [
                'Dashboard' => [],
                'User Management' => [],
                'Auction Management' => [],
                'Branch Management' => [],
                'Account Management' => [],
                'Collateral Management' => [],
                'System Management' => [],
                'Address Management' => []
            ]
        ];

        foreach ($routes as $route) {
            // Skip non-API routes and internal Laravel routes
            if (!str_starts_with($route->uri(), 'api') || str_starts_with($route->uri(), 'api/documentation')) {
                continue;
            }

            $endpoint = '/' . $route->uri();
            $methods = $route->methods();
            
            // We only want to test the first HTTP method if there are multiple
            $method = $methods[0];
            
            // Skip HEAD and OPTIONS methods
            if (in_array($method, ['HEAD', 'OPTIONS'])) {
                continue;
            }

            $status = $this->apiTesting->testEndpoint($method, $endpoint);
            
            $apiInfo = [
                'method' => $method,
                'endpoint' => $endpoint,
                'description' => $this->getEndpointDescription($route),
                'status' => $status,
                'method_color' => $this->getMethodColor($method)
            ];

            // Categorize the API based on its path and middleware
            $category = $this->categorizeEndpoint($route);
            $section = $this->determineSection($route);

            if (isset($apiEndpoints[$section][$category])) {
                $apiEndpoints[$section][$category][] = $apiInfo;
            }
        }

        return view('admin.api.index', compact('apiEndpoints'));
    }

    protected function getMethodColor($method)
    {
        return match ($method) {
            'GET' => 'primary',
            'POST' => 'success',
            'PUT', 'PATCH' => 'warning',
            'DELETE' => 'danger',
            default => 'secondary'
        };
    }

    protected function getEndpointDescription($route)
    {
        $action = $route->getAction();
        $controller = $action['controller'] ?? null;
        
        if ($controller) {
            // Extract the method name from the controller string
            $parts = explode('@', $controller);
            $methodName = $parts[1] ?? '';
            
            // Convert camelCase to words and clean up common suffixes
            $description = ucfirst(preg_replace('/(?<!^)[A-Z]/', ' $0', $methodName));
            $description = str_replace(['Action', 'Request'], '', $description);
            
            return trim($description);
        }
        
        return 'API Endpoint';
    }

    protected function categorizeEndpoint($route)
    {
        $uri = $route->uri();
        $segments = explode('/', $uri);
        $controller = $route->getAction()['controller'] ?? '';

        // Check if it's an admin route
        if (str_contains($uri, 'admin/') || str_contains($controller, '\Admin\\')) {
            // Admin-specific categorization
            $adminCategories = [
                'users' => 'User Management',
                'auctions' => 'Auction Management',
                'branches' => 'Branch Management',
                'accounts' => 'Account Management',
                'collaterals' => 'Collateral Management',
                'system' => 'System Management',
                'addresses' => 'Address Management',
                'dashboard' => 'Dashboard'
            ];

            foreach ($segments as $segment) {
                if (isset($adminCategories[$segment])) {
                    return $adminCategories[$segment];
                }
            }

            // Try to determine from controller name for admin routes
            foreach ($adminCategories as $key => $category) {
                if (stripos($controller, $key) !== false) {
                    return $category;
                }
            }

            // Default admin category based on controller pattern
            if (preg_match('/Admin\\\\(\w+)Controller/', $controller, $matches)) {
                $controllerName = $matches[1];
                if ($controllerName !== 'Base') {
                    return str_replace('Controller', ' Management', $controllerName);
                }
            }

            return 'System Management'; // Default admin category
        }

        // Non-admin categories
        $categoryMappings = [
            'auth' => 'Authentication',
            'monitoring' => 'Monitoring',
            'lists' => 'Lists',
            'user' => 'User Management',
            'bids' => 'Bidding',
            'addresses' => 'Address Management'
        ];

        foreach ($segments as $segment) {
            if (isset($categoryMappings[$segment])) {
                return $categoryMappings[$segment];
            }
        }

        // Try to determine category from controller name for non-admin routes
        foreach ($categoryMappings as $key => $category) {
            if (stripos($controller, $key) !== false) {
                return $category;
            }
        }

        return 'Other';
    }

    protected function determineSection($route)
    {
        if ($this->hasMiddleware($route, 'auth:sanctum') && $this->hasMiddleware($route, 'admin')) {
            return 'Admin APIs';
        } elseif ($this->hasMiddleware($route, 'auth:sanctum')) {
            return 'Protected APIs';
        }
        return 'Public APIs';
    }

    protected function hasMiddleware($route, $middleware)
    {
        return in_array($middleware, $route->middleware()) || 
               in_array($middleware, $route->gatherMiddleware());
    }
} 