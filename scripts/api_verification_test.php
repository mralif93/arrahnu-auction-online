<?php

/**
 * ArRahnu Auction Online API Verification Script
 * 
 * This script comprehensively tests all API services to verify functionality:
 * - Authentication Services
 * - User Management Services
 * - Bidding Services
 * - Address Management Services
 * - Admin Services
 * - System Health
 */

class APIVerificationTester
{
    private $baseUrl;
    private $token;
    private $adminToken;
    private $testResults = [];
    private $totalTests = 0;
    private $passedTests = 0;
    private $failedTests = 0;
    private $verbose;

    public function __construct($baseUrl = 'http://127.0.0.1:8000', $verbose = true)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->verbose = $verbose;
        $this->log("🔍 ArRahnu Auction Online - API Verification Test Suite");
        $this->log("🌐 Base URL: {$this->baseUrl}");
        $this->log(str_repeat("=", 80) . "\n");
    }

    /**
     * Run all API verification tests
     */
    public function runVerification()
    {
        $this->log("🚀 Starting Comprehensive API Verification...\n");

        try {
            // Core System Tests
            $this->testSystemHealth();
            
            // Authentication Service Tests
            $this->testAuthenticationServices();

            // Try to get tokens for further testing
            $this->acquireTestTokens();

            // Protected API Tests (if tokens available)
            if ($this->token) {
                $this->testUserServices();
                $this->testBiddingServices();
                $this->testAddressServices();
            } else {
                $this->log("⚠️  Skipping protected API tests - no user token available\n");
            }

            // Admin API Tests (if admin token available)
            if ($this->adminToken) {
                $this->testAdminServices();
            } else {
                $this->log("⚠️  Skipping admin API tests - no admin token available\n");
            }

            $this->printFinalReport();

        } catch (Exception $e) {
            $this->log("❌ API Verification failed with error: " . $e->getMessage());
            $this->printFinalReport();
        }
    }

    /**
     * Test system health endpoints
     */
    private function testSystemHealth()
    {
        $this->log("🏥 SYSTEM HEALTH VERIFICATION");
        $this->log(str_repeat("-", 50));

        // Test API Health
        $response = $this->makeRequest('GET', '/api/health');
        $this->addResult('API Health Check', 
            $response && $response['success'] === true,
            $response ? $response['message'] : 'No response'
        );

        // Test API Info
        $response = $this->makeRequest('GET', '/api/info');
        $this->addResult('API Information', 
            $response && $response['success'] === true,
            $response ? 'API documentation retrieved' : 'No response'
        );

        if ($response && $response['success']) {
            $data = $response['data'];
            $this->log("✅ API Name: " . $data['api_name']);
            $this->log("✅ Version: " . $data['version']);
            
            // Count endpoints
            $endpointCount = 0;
            foreach ($data['endpoints'] as $category => $endpoints) {
                $endpointCount += count($endpoints);
            }
            $this->log("✅ Total Endpoints: {$endpointCount}");
        }

        $this->log("");
    }

    /**
     * Test authentication services
     */
    private function testAuthenticationServices()
    {
        $this->log("🔐 AUTHENTICATION SERVICES VERIFICATION");
        $this->log(str_repeat("-", 50));

        // Test user registration
        $userData = [
            'full_name' => 'API Test User',
            'username' => 'apitest_' . time(),
            'email' => 'apitest_' . time() . '@example.com',
            'password' => 'TestPassword123!',
            'password_confirmation' => 'TestPassword123!',
            'phone_number' => '+60123456789',
            'role' => 'bidder'
        ];

        $response = $this->makeRequest('POST', '/api/auth/register', $userData);
        $this->addResult('User Registration', 
            $response && $response['success'] === true,
            $response ? $response['message'] : 'Registration failed'
        );

        // Test login with invalid credentials
        $response = $this->makeRequest('POST', '/api/auth/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword'
        ]);
        $this->addResult('Login Security (Invalid Credentials)', 
            $response && $response['success'] === false,
            'Login correctly rejected invalid credentials'
        );

        // Test 2FA endpoints
        $response = $this->makeRequest('POST', '/api/auth/2fa/verify', ['code' => '000000']);
        $this->addResult('2FA Verification Endpoint', 
            $response && $response['success'] === false,
            '2FA endpoint working (rejected invalid code)'
        );

        $response = $this->makeRequest('POST', '/api/verify/2fa', ['code' => '000000']);
        $this->addResult('2FA Alternative Endpoint', 
            $response && $response['success'] === false,
            'Alternative 2FA endpoint working'
        );

        // Test password reset request
        $response = $this->makeRequest('POST', '/api/auth/forgot-password', [
            'email' => 'test@example.com'
        ]);
        $this->addResult('Password Reset Request', 
            $response !== false,
            'Password reset endpoint accessible'
        );

        $this->log("");
    }

    /**
     * Acquire test tokens for further testing
     */
    private function acquireTestTokens()
    {
        $this->log("🔑 TOKEN ACQUISITION");
        $this->log(str_repeat("-", 50));

        // Try to login with existing test users
        $testUsers = [
            ['email' => 'bidder@example.com', 'password' => 'password'],
            ['email' => 'user@example.com', 'password' => 'password'],
            ['email' => 'bidder1@example.com', 'password' => 'password'],
        ];

        foreach ($testUsers as $user) {
            $response = $this->makeRequest('POST', '/api/auth/login', $user);
            
            if ($response && $response['success']) {
                if (isset($response['requires_2fa']) && $response['requires_2fa']) {
                    $this->log("⚠️  User {$user['email']}: 2FA required");
                } else {
                    $this->token = $response['data']['token'];
                    $this->log("✅ User token acquired for {$user['email']}");
                    break;
                }
            }
        }

        // Try to get admin token
        $adminUsers = [
            ['email' => 'admin@arrahnu.com', 'password' => 'password'],
            ['email' => 'admin@example.com', 'password' => 'password'],
        ];

        foreach ($adminUsers as $admin) {
            $response = $this->makeRequest('POST', '/api/auth/login', $admin);
            
            if ($response && $response['success']) {
                if (isset($response['requires_2fa']) && $response['requires_2fa']) {
                    $this->log("⚠️  Admin {$admin['email']}: 2FA required");
                } else {
                    $this->adminToken = $response['data']['token'];
                    $this->log("✅ Admin token acquired for {$admin['email']}");
                    break;
                }
            }
        }

        if (!$this->token) {
            $this->log("❌ No user token acquired");
        }
        if (!$this->adminToken) {
            $this->log("❌ No admin token acquired");
        }

        $this->log("");
    }

    /**
     * Test user services
     */
    private function testUserServices()
    {
        $this->log("👤 USER SERVICES VERIFICATION");
        $this->log(str_repeat("-", 50));

        $headers = ['Authorization: Bearer ' . $this->token];

        // Test user profile
        $response = $this->makeRequest('GET', '/api/user/profile', null, $headers);
        $this->addResult('Get User Profile', 
            $response && $response['success'] === true,
            $response ? 'Profile retrieved successfully' : 'Failed to get profile'
        );

        // Test profile update
        $response = $this->makeRequest('PUT', '/api/user/profile', [
            'full_name' => 'Updated API Test User',
            'nationality' => 'Malaysian'
        ], $headers);
        $this->addResult('Update User Profile', 
            $response && $response['success'] === true,
            $response ? $response['message'] : 'Profile update failed'
        );

        // Test user preferences
        $response = $this->makeRequest('PUT', '/api/user/preferences', [
            'timezone' => 'Asia/Kuala_Lumpur',
            'language' => 'en'
        ], $headers);
        $this->addResult('Update User Preferences', 
            $response && $response['success'] === true,
            $response ? 'Preferences updated' : 'Preferences update failed'
        );

        // Test bidding activity
        $response = $this->makeRequest('GET', '/api/user/bidding-activity', null, $headers);
        $this->addResult('Get Bidding Activity', 
            $response && $response['success'] === true,
            $response ? 'Bidding activity retrieved' : 'Failed to get bidding activity'
        );

        // Test watchlist
        $response = $this->makeRequest('GET', '/api/user/watchlist', null, $headers);
        $this->addResult('Get User Watchlist', 
            $response && $response['success'] === true,
            $response ? 'Watchlist retrieved' : 'Failed to get watchlist'
        );

        $this->log("");
    }

    /**
     * Test bidding services
     */
    private function testBiddingServices()
    {
        $this->log("🏷️ BIDDING SERVICES VERIFICATION");
        $this->log(str_repeat("-", 50));

        $headers = ['Authorization: Bearer ' . $this->token];

        // Test get user bids
        $response = $this->makeRequest('GET', '/api/bids', null, $headers);
        $this->addResult('Get User Bids', 
            $response && $response['success'] === true,
            $response ? 'Bids retrieved successfully' : 'Failed to get bids'
        );

        // Test active bids
        $response = $this->makeRequest('GET', '/api/bids/active', null, $headers);
        $this->addResult('Get Active Bids', 
            $response && $response['success'] === true,
            $response ? 'Active bids retrieved' : 'Failed to get active bids'
        );

        // Test bidding statistics
        $response = $this->makeRequest('GET', '/api/bids/statistics', null, $headers);
        $this->addResult('Get Bidding Statistics', 
            $response && $response['success'] === true,
            $response ? 'Statistics retrieved' : 'Failed to get statistics'
        );

        // Test active auctions
        $response = $this->makeRequest('GET', '/api/auctions/active', null, $headers);
        $this->addResult('Get Active Auctions', 
            $response && $response['success'] === true,
            $response ? 'Active auctions retrieved' : 'Failed to get auctions'
        );

        $this->log("");
    }

    /**
     * Test address services
     */
    private function testAddressServices()
    {
        $this->log("🏠 ADDRESS SERVICES VERIFICATION");
        $this->log(str_repeat("-", 50));

        $headers = ['Authorization: Bearer ' . $this->token];

        // Test get addresses
        $response = $this->makeRequest('GET', '/api/addresses', null, $headers);
        $this->addResult('Get User Addresses', 
            $response && $response['success'] === true,
            $response ? 'Addresses retrieved successfully' : 'Failed to get addresses'
        );

        // Test address statistics
        $response = $this->makeRequest('GET', '/api/addresses/statistics', null, $headers);
        $this->addResult('Get Address Statistics', 
            $response && $response['success'] === true,
            $response ? 'Statistics retrieved' : 'Failed to get statistics'
        );

        // Test Malaysian states
        $response = $this->makeRequest('GET', '/api/addresses/states/list', null, $headers);
        $this->addResult('Get Malaysian States', 
            $response && $response['success'] === true,
            $response ? 'States list retrieved' : 'Failed to get states'
        );

        // Test validation rules
        $response = $this->makeRequest('GET', '/api/addresses/validation/rules', null, $headers);
        $this->addResult('Get Validation Rules', 
            $response && $response['success'] === true,
            $response ? 'Validation rules retrieved' : 'Failed to get rules'
        );

        // Test postcode validation
        $response = $this->makeRequest('POST', '/api/addresses/validate/postcode', [
            'postcode' => '50000'
        ], $headers);
        $this->addResult('Postcode Validation', 
            $response && $response['success'] === true,
            $response ? 'Postcode validation working' : 'Postcode validation failed'
        );

        $this->log("");
    }

    /**
     * Test admin services
     */
    private function testAdminServices()
    {
        $this->log("⚙️ ADMIN SERVICES VERIFICATION");
        $this->log(str_repeat("-", 50));

        $headers = ['Authorization: Bearer ' . $this->adminToken];

        // Test dashboard overview
        $response = $this->makeRequest('GET', '/api/admin/dashboard/overview', null, $headers);
        $this->addResult('Admin Dashboard Overview', 
            $response && $response['success'] === true,
            $response ? 'Dashboard data retrieved' : 'Failed to get dashboard'
        );

        // Test user analytics
        $response = $this->makeRequest('GET', '/api/admin/dashboard/user-analytics', null, $headers);
        $this->addResult('Admin User Analytics', 
            $response && $response['success'] === true,
            $response ? 'User analytics retrieved' : 'Failed to get analytics'
        );

        // Test system status
        $response = $this->makeRequest('GET', '/api/admin/system/status', null, $headers);
        $this->addResult('Admin System Status', 
            $response && $response['success'] === true,
            $response ? 'System status retrieved' : 'Failed to get status'
        );

        // Test admin addresses
        $response = $this->makeRequest('GET', '/api/admin/addresses', null, $headers);
        $this->addResult('Admin Address Management', 
            $response && $response['success'] === true,
            $response ? 'Admin addresses retrieved' : 'Failed to get addresses'
        );

        // Test admin settings
        $response = $this->makeRequest('GET', '/api/admin/settings', null, $headers);
        $this->addResult('Admin Settings', 
            $response && $response['success'] === true,
            $response ? 'Settings retrieved' : 'Failed to get settings'
        );

        $this->log("");
    }

    /**
     * Make HTTP request
     */
    private function makeRequest($method, $endpoint, $data = null, $headers = [])
    {
        $url = $this->baseUrl . $endpoint;
        
        $defaultHeaders = [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Requested-With: XMLHttpRequest'
        ];
        
        $headers = array_merge($defaultHeaders, $headers);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            $this->log("❌ CURL Error for {$method} {$endpoint}: {$error}");
            return false;
        }
        
        $decodedResponse = json_decode($response, true);
        
        if ($this->verbose) {
            $status = $decodedResponse && isset($decodedResponse['success']) && $decodedResponse['success'] ? '✅' : '❌';
            $this->log("   {$status} {$method} {$endpoint} - HTTP {$httpCode}");
        }
        
        return $decodedResponse;
    }

    /**
     * Add test result
     */
    private function addResult($testName, $success, $message)
    {
        $this->totalTests++;
        
        if ($success) {
            $this->passedTests++;
        } else {
            $this->failedTests++;
        }
        
        $this->testResults[] = [
            'test' => $testName,
            'success' => $success,
            'message' => $message
        ];
        
        if ($this->verbose) {
            $status = $success ? '✅' : '❌';
            $this->log("   {$status} {$testName}: {$message}");
        }
    }

    /**
     * Log message
     */
    private function log($message)
    {
        if ($this->verbose) {
            echo $message . "\n";
        }
    }

    /**
     * Print final verification report
     */
    private function printFinalReport()
    {
        $this->log("\n" . str_repeat("=", 80));
        $this->log("📊 API VERIFICATION REPORT");
        $this->log(str_repeat("=", 80));
        
        $successRate = $this->totalTests > 0 ? round(($this->passedTests / $this->totalTests) * 100, 2) : 0;
        
        $this->log("📈 SUMMARY:");
        $this->log("   Total Tests: {$this->totalTests}");
        $this->log("   Passed: {$this->passedTests} ✅");
        $this->log("   Failed: {$this->failedTests} ❌");
        $this->log("   Success Rate: {$successRate}%");
        
        if ($this->failedTests > 0) {
            $this->log("\n❌ FAILED TESTS:");
            foreach ($this->testResults as $result) {
                if (!$result['success']) {
                    $this->log("   • {$result['test']}: {$result['message']}");
                }
            }
        }
        
        $this->log("\n🎯 VERIFICATION STATUS: " . ($successRate >= 90 ? "EXCELLENT ✅" : 
                                                   ($successRate >= 80 ? "GOOD ⚠️" : 
                                                   ($successRate >= 70 ? "ACCEPTABLE ⚠️" : "NEEDS ATTENTION ❌"))));
        
        $this->log("\n🔗 API Documentation: {$this->baseUrl}/api/info");
        $this->log("🏥 Health Check: {$this->baseUrl}/api/health");
        
        $this->log(str_repeat("=", 80));
    }
}

// Run the verification if script is executed directly
if (php_sapi_name() === 'cli') {
    $baseUrl = $argv[1] ?? 'http://127.0.0.1:8000';
    $verbose = !in_array('--quiet', $argv);
    
    $tester = new APIVerificationTester($baseUrl, $verbose);
    $tester->runVerification();
    
    echo "\nUsage: php api_verification_test.php [base_url] [--quiet]\n";
    echo "Examples:\n";
    echo "  php api_verification_test.php\n";
    echo "  php api_verification_test.php http://127.0.0.1:8000\n";
    echo "  php api_verification_test.php http://127.0.0.1:8000 --quiet\n";
} 