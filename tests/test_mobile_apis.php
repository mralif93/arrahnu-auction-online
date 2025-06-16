<?php

/**
 * Comprehensive Mobile API Test Script
 * Tests all user management and bidding APIs for mobile functionality
 */

require_once 'vendor/autoload.php';

class MobileAPITester
{
    private $baseUrl;
    private $token;
    private $testResults = [];
    private $userId;
    private $addressId;
    private $collateralId;
    private $bidId;

    public function __construct($baseUrl = 'http://localhost:8000')
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        echo "🚀 Starting Mobile API Tests for ArRahnu Auction Online\n";
        echo "Base URL: {$this->baseUrl}\n";
        echo str_repeat("=", 80) . "\n\n";
    }

    /**
     * Run all tests
     */
    public function runAllTests()
    {
        try {
            // Authentication Tests
            $this->testHealthCheck();
            $this->testUserRegistration();
            $this->testUserLogin();
            
            // User Management Tests
            $this->testUserProfile();
            $this->testUpdateUserProfile();
            $this->testUpdateUserPassword();
            $this->testUserPreferences();
            $this->testUserBiddingActivity();
            
            // Address Management Tests
            $this->testCreateAddress();
            $this->testGetAddresses();
            $this->testUpdateAddress();
            $this->testSetPrimaryAddress();
            
            // Bidding Tests
            $this->testGetActiveAuctions();
            $this->testGetCollateralDetails();
            $this->testPlaceBid();
            $this->testGetUserBids();
            $this->testGetActiveBids();
            $this->testBiddingStatistics();
            $this->testLiveUpdates();
            
            // Cleanup
            $this->testDeleteAddress();
            
            $this->printSummary();
            
        } catch (Exception $e) {
            echo "❌ Test suite failed with error: " . $e->getMessage() . "\n";
            $this->printSummary();
        }
    }

    /**
     * Test API health check
     */
    private function testHealthCheck()
    {
        echo "🔍 Testing API Health Check...\n";
        
        $response = $this->makeRequest('GET', '/api/health');
        
        if ($response && $response['success'] === true) {
            $this->addResult('Health Check', true, 'API is running');
        } else {
            $this->addResult('Health Check', false, 'API health check failed');
        }
    }

    /**
     * Test user registration
     */
    private function testUserRegistration()
    {
        echo "🔍 Testing User Registration...\n";
        
        $userData = [
            'full_name' => 'Mobile Test User',
            'username' => 'mobiletest_' . time(),
            'email' => 'mobiletest_' . time() . '@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => '+60123456789',
            'role' => 'bidder'
        ];
        
        $response = $this->makeRequest('POST', '/api/auth/register', $userData);
        
        if ($response && $response['success'] === true) {
            $this->userId = $response['data']['user']['id'];
            $this->addResult('User Registration', true, 'User registered successfully');
        } else {
            $this->addResult('User Registration', false, $response['message'] ?? 'Registration failed');
        }
    }

    /**
     * Test user login
     */
    private function testUserLogin()
    {
        echo "🔍 Testing User Login...\n";
        
        $loginData = [
            'email' => 'mobiletest_' . (time() - 1) . '@example.com',
            'password' => 'password123'
        ];
        
        // Try to login with existing user or create a test user
        $response = $this->makeRequest('POST', '/api/auth/login', [
            'email' => 'bidder@example.com',
            'password' => 'password'
        ]);
        
        if ($response && $response['success'] === true) {
            $this->token = $response['data']['token'];
            $this->userId = $response['data']['user']['id'];
            $this->addResult('User Login', true, 'Login successful');
        } else {
            $this->addResult('User Login', false, $response['message'] ?? 'Login failed');
        }
    }

    /**
     * Test get user profile
     */
    private function testUserProfile()
    {
        echo "🔍 Testing Get User Profile...\n";
        
        $response = $this->makeRequest('GET', '/api/user/profile');
        
        if ($response && $response['success'] === true && isset($response['data']['user'])) {
            $this->addResult('Get User Profile', true, 'Profile retrieved with statistics');
        } else {
            $this->addResult('Get User Profile', false, $response['message'] ?? 'Failed to get profile');
        }
    }

    /**
     * Test update user profile
     */
    private function testUpdateUserProfile()
    {
        echo "🔍 Testing Update User Profile...\n";
        
        $updateData = [
            'full_name' => 'Updated Mobile User',
            'nationality' => 'Malaysian',
            'occupation' => 'Software Developer'
        ];
        
        $response = $this->makeRequest('PUT', '/api/user/profile', $updateData);
        
        if ($response && $response['success'] === true) {
            $this->addResult('Update User Profile', true, 'Profile updated successfully');
        } else {
            $this->addResult('Update User Profile', false, $response['message'] ?? 'Profile update failed');
        }
    }

    /**
     * Test update user password
     */
    private function testUpdateUserPassword()
    {
        echo "🔍 Testing Update User Password...\n";
        
        $passwordData = [
            'current_password' => 'password',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123'
        ];
        
        $response = $this->makeRequest('PUT', '/api/user/password', $passwordData);
        
        if ($response && $response['success'] === true) {
            $this->addResult('Update Password', true, 'Password updated successfully');
        } else {
            $this->addResult('Update Password', false, $response['message'] ?? 'Password update failed');
        }
    }

    /**
     * Test user preferences
     */
    private function testUserPreferences()
    {
        echo "🔍 Testing User Preferences...\n";
        
        $preferences = [
            'email_notifications' => true,
            'push_notifications' => true,
            'bid_notifications' => true,
            'language' => 'en',
            'currency_preference' => 'MYR'
        ];
        
        $response = $this->makeRequest('PUT', '/api/user/preferences', $preferences);
        
        if ($response && $response['success'] === true) {
            $this->addResult('User Preferences', true, 'Preferences updated successfully');
        } else {
            $this->addResult('User Preferences', false, $response['message'] ?? 'Preferences update failed');
        }
    }

    /**
     * Test user bidding activity
     */
    private function testUserBiddingActivity()
    {
        echo "🔍 Testing User Bidding Activity...\n";
        
        $response = $this->makeRequest('GET', '/api/user/bidding-activity?period=30');
        
        if ($response && $response['success'] === true) {
            $this->addResult('User Bidding Activity', true, 'Bidding activity retrieved');
        } else {
            $this->addResult('User Bidding Activity', false, $response['message'] ?? 'Failed to get bidding activity');
        }
    }

    /**
     * Test create address
     */
    private function testCreateAddress()
    {
        echo "🔍 Testing Create Address...\n";
        
        $addressData = [
            'address_line_1' => '123 Test Street',
            'address_line_2' => 'Unit 4B',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'postcode' => '50000',
            'country' => 'Malaysia',
            'is_primary' => true
        ];
        
        $response = $this->makeRequest('POST', '/api/addresses', $addressData);
        
        if ($response && $response['success'] === true) {
            $this->addressId = $response['data']['address']['id'];
            $this->addResult('Create Address', true, 'Address created successfully');
        } else {
            $this->addResult('Create Address', false, $response['message'] ?? 'Address creation failed');
        }
    }

    /**
     * Test get addresses
     */
    private function testGetAddresses()
    {
        echo "🔍 Testing Get Addresses...\n";
        
        $response = $this->makeRequest('GET', '/api/addresses');
        
        if ($response && $response['success'] === true) {
            $this->addResult('Get Addresses', true, 'Addresses retrieved successfully');
        } else {
            $this->addResult('Get Addresses', false, $response['message'] ?? 'Failed to get addresses');
        }
    }

    /**
     * Test update address
     */
    private function testUpdateAddress()
    {
        if (!$this->addressId) {
            $this->addResult('Update Address', false, 'No address ID available');
            return;
        }
        
        echo "🔍 Testing Update Address...\n";
        
        $updateData = [
            'address_line_1' => '456 Updated Street',
            'city' => 'Petaling Jaya'
        ];
        
        $response = $this->makeRequest('PUT', "/api/addresses/{$this->addressId}", $updateData);
        
        if ($response && $response['success'] === true) {
            $this->addResult('Update Address', true, 'Address updated successfully');
        } else {
            $this->addResult('Update Address', false, $response['message'] ?? 'Address update failed');
        }
    }

    /**
     * Test set primary address
     */
    private function testSetPrimaryAddress()
    {
        if (!$this->addressId) {
            $this->addResult('Set Primary Address', false, 'No address ID available');
            return;
        }
        
        echo "🔍 Testing Set Primary Address...\n";
        
        $response = $this->makeRequest('POST', "/api/addresses/{$this->addressId}/set-primary");
        
        if ($response && $response['success'] === true) {
            $this->addResult('Set Primary Address', true, 'Primary address set successfully');
        } else {
            $this->addResult('Set Primary Address', false, $response['message'] ?? 'Failed to set primary address');
        }
    }

    /**
     * Test get active auctions
     */
    private function testGetActiveAuctions()
    {
        echo "🔍 Testing Get Active Auctions...\n";
        
        $response = $this->makeRequest('GET', '/api/auctions/active?per_page=10');
        
        if ($response && $response['success'] === true) {
            // Try to get a collateral ID for bidding tests
            if (isset($response['data']['auctions'][0]['collaterals'][0]['id'])) {
                $this->collateralId = $response['data']['auctions'][0]['collaterals'][0]['id'];
            }
            $this->addResult('Get Active Auctions', true, 'Active auctions retrieved');
        } else {
            $this->addResult('Get Active Auctions', false, $response['message'] ?? 'Failed to get active auctions');
        }
    }

    /**
     * Test get collateral details
     */
    private function testGetCollateralDetails()
    {
        if (!$this->collateralId) {
            $this->addResult('Get Collateral Details', false, 'No collateral ID available');
            return;
        }
        
        echo "🔍 Testing Get Collateral Details...\n";
        
        $response = $this->makeRequest('GET', "/api/auctions/collaterals/{$this->collateralId}");
        
        if ($response && $response['success'] === true) {
            $this->addResult('Get Collateral Details', true, 'Collateral details retrieved');
        } else {
            $this->addResult('Get Collateral Details', false, $response['message'] ?? 'Failed to get collateral details');
        }
    }

    /**
     * Test place bid
     */
    private function testPlaceBid()
    {
        if (!$this->collateralId) {
            $this->addResult('Place Bid', false, 'No collateral ID available');
            return;
        }
        
        echo "🔍 Testing Place Bid...\n";
        
        $bidData = [
            'collateral_id' => $this->collateralId,
            'bid_amount' => 1000.00
        ];
        
        $response = $this->makeRequest('POST', '/api/bids', $bidData);
        
        if ($response && $response['success'] === true) {
            $this->bidId = $response['data']['bid']['id'];
            $this->addResult('Place Bid', true, 'Bid placed successfully');
        } else {
            $this->addResult('Place Bid', false, $response['message'] ?? 'Failed to place bid');
        }
    }

    /**
     * Test get user bids
     */
    private function testGetUserBids()
    {
        echo "🔍 Testing Get User Bids...\n";
        
        $response = $this->makeRequest('GET', '/api/bids?per_page=10');
        
        if ($response && $response['success'] === true) {
            $this->addResult('Get User Bids', true, 'User bids retrieved');
        } else {
            $this->addResult('Get User Bids', false, $response['message'] ?? 'Failed to get user bids');
        }
    }

    /**
     * Test get active bids
     */
    private function testGetActiveBids()
    {
        echo "🔍 Testing Get Active Bids...\n";
        
        $response = $this->makeRequest('GET', '/api/bids/active');
        
        if ($response && $response['success'] === true) {
            $this->addResult('Get Active Bids', true, 'Active bids retrieved');
        } else {
            $this->addResult('Get Active Bids', false, $response['message'] ?? 'Failed to get active bids');
        }
    }

    /**
     * Test bidding statistics
     */
    private function testBiddingStatistics()
    {
        echo "🔍 Testing Bidding Statistics...\n";
        
        $response = $this->makeRequest('GET', '/api/bids/statistics');
        
        if ($response && $response['success'] === true) {
            $this->addResult('Bidding Statistics', true, 'Bidding statistics retrieved');
        } else {
            $this->addResult('Bidding Statistics', false, $response['message'] ?? 'Failed to get bidding statistics');
        }
    }

    /**
     * Test live updates
     */
    private function testLiveUpdates()
    {
        if (!$this->collateralId) {
            $this->addResult('Live Updates', false, 'No collateral ID available');
            return;
        }
        
        echo "🔍 Testing Live Updates...\n";
        
        $response = $this->makeRequest('GET', "/api/auctions/collaterals/{$this->collateralId}/live-updates");
        
        if ($response && $response['success'] === true) {
            $this->addResult('Live Updates', true, 'Live updates retrieved');
        } else {
            $this->addResult('Live Updates', false, $response['message'] ?? 'Failed to get live updates');
        }
    }

    /**
     * Test delete address
     */
    private function testDeleteAddress()
    {
        if (!$this->addressId) {
            $this->addResult('Delete Address', false, 'No address ID available');
            return;
        }
        
        echo "🔍 Testing Delete Address...\n";
        
        $response = $this->makeRequest('DELETE', "/api/addresses/{$this->addressId}");
        
        if ($response && $response['success'] === true) {
            $this->addResult('Delete Address', true, 'Address deleted successfully');
        } else {
            $this->addResult('Delete Address', false, $response['message'] ?? 'Failed to delete address');
        }
    }

    /**
     * Make HTTP request
     */
    private function makeRequest($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . $endpoint;
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        if ($this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            echo "❌ CURL Error: $error\n";
            return null;
        }
        
        $decodedResponse = json_decode($response, true);
        
        if ($httpCode >= 400) {
            echo "❌ HTTP Error $httpCode: " . ($decodedResponse['message'] ?? 'Unknown error') . "\n";
        }
        
        return $decodedResponse;
    }

    /**
     * Add test result
     */
    private function addResult($test, $success, $message)
    {
        $this->testResults[] = [
            'test' => $test,
            'success' => $success,
            'message' => $message
        ];
        
        $status = $success ? '✅' : '❌';
        echo "$status $test: $message\n\n";
    }

    /**
     * Print test summary
     */
    private function printSummary()
    {
        echo str_repeat("=", 80) . "\n";
        echo "📊 TEST SUMMARY\n";
        echo str_repeat("=", 80) . "\n";
        
        $total = count($this->testResults);
        $passed = count(array_filter($this->testResults, function($result) {
            return $result['success'];
        }));
        $failed = $total - $passed;
        
        echo "Total Tests: $total\n";
        echo "✅ Passed: $passed\n";
        echo "❌ Failed: $failed\n";
        echo "Success Rate: " . round(($passed / $total) * 100, 2) . "%\n\n";
        
        if ($failed > 0) {
            echo "Failed Tests:\n";
            foreach ($this->testResults as $result) {
                if (!$result['success']) {
                    echo "❌ {$result['test']}: {$result['message']}\n";
                }
            }
        }
        
        echo "\n🎉 Mobile API testing completed!\n";
    }
}

// Run the tests
$tester = new MobileAPITester();
$tester->runAllTests(); 