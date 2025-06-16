<?php

/**
 * Enhanced Address API Test Script
 * 
 * This script tests all the enhanced address API functionality including:
 * - AddressService integration
 * - New API endpoints
 * - Enhanced filtering and statistics
 * - Admin API endpoints
 * - Bulk operations
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Support\Facades\Auth;

class EnhancedAddressApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $admin;
    protected $addressService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->user = User::factory()->create([
            'email' => 'user@test.com',
            'is_admin' => false
        ]);

        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'is_admin' => true
        ]);

        $this->addressService = app(AddressService::class);
    }

    public function testAddressServiceCreation()
    {
        echo "Testing AddressService creation...\n";
        
        $addressData = [
            'address_line_1' => '123 Test Street',
            'address_line_2' => 'Unit 4B',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'postcode' => '50000',
            'country' => 'Malaysia',
            'is_primary' => true
        ];

        $address = $this->addressService->createAddress($this->user, $addressData);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals($this->user->id, $address->user_id);
        $this->assertTrue($address->is_primary);
        $this->assertEquals('123 Test Street', $address->address_line_1);

        echo "✓ AddressService creation test passed\n";
    }

    public function testAddressServiceUpdate()
    {
        echo "Testing AddressService update...\n";
        
        $address = Address::factory()->create(['user_id' => $this->user->id]);
        
        $updateData = [
            'address_line_1' => '456 Updated Street',
            'city' => 'Petaling Jaya',
            'state' => 'Selangor',
            'postcode' => '47400',
            'is_primary' => true
        ];

        $updatedAddress = $this->addressService->updateAddress($address, $updateData);

        $this->assertEquals('456 Updated Street', $updatedAddress->address_line_1);
        $this->assertEquals('Petaling Jaya', $updatedAddress->city);
        $this->assertTrue($updatedAddress->is_primary);

        echo "✓ AddressService update test passed\n";
    }

    public function testAddressServiceDelete()
    {
        echo "Testing AddressService delete...\n";
        
        // Create multiple addresses
        $address1 = Address::factory()->create(['user_id' => $this->user->id, 'is_primary' => true]);
        $address2 = Address::factory()->create(['user_id' => $this->user->id, 'is_primary' => false]);

        $result = $this->addressService->deleteAddress($address2);

        $this->assertTrue($result['success']);
        $this->assertEquals('Address deleted successfully.', $result['message']);
        $this->assertDatabaseMissing('addresses', ['id' => $address2->id]);

        echo "✓ AddressService delete test passed\n";
    }

    public function testAddressServiceDeleteOnlyAddress()
    {
        echo "Testing AddressService delete only address protection...\n";
        
        $address = Address::factory()->create(['user_id' => $this->user->id]);

        $result = $this->addressService->deleteAddress($address);

        $this->assertFalse($result['success']);
        $this->assertEquals('ONLY_ADDRESS', $result['error_code']);
        $this->assertDatabaseHas('addresses', ['id' => $address->id]);

        echo "✓ AddressService delete protection test passed\n";
    }

    public function testUserAddressStatistics()
    {
        echo "Testing user address statistics...\n";
        
        // Create multiple addresses in different states
        Address::factory()->create(['user_id' => $this->user->id, 'state' => 'Selangor', 'is_primary' => true]);
        Address::factory()->create(['user_id' => $this->user->id, 'state' => 'Selangor', 'is_primary' => false]);
        Address::factory()->create(['user_id' => $this->user->id, 'state' => 'Johor', 'is_primary' => false]);

        $statistics = $this->addressService->getUserAddressStatistics($this->user);

        $this->assertEquals(3, $statistics['total_addresses']);
        $this->assertEquals(2, $statistics['states_covered']);
        $this->assertArrayHasKey('Selangor', $statistics['state_distribution']);
        $this->assertEquals(2, $statistics['state_distribution']['Selangor']);
        $this->assertNotNull($statistics['primary_address']);

        echo "✓ User address statistics test passed\n";
    }

    public function testGlobalStatistics()
    {
        echo "Testing global address statistics...\n";
        
        // Create addresses for multiple users
        $user2 = User::factory()->create();
        Address::factory()->create(['user_id' => $this->user->id, 'is_primary' => true]);
        Address::factory()->create(['user_id' => $user2->id, 'is_primary' => true]);

        $statistics = $this->addressService->getGlobalStatistics();

        $this->assertEquals(2, $statistics['total_addresses']);
        $this->assertEquals(2, $statistics['primary_addresses']);
        $this->assertEquals(2, $statistics['users_with_addresses']);

        echo "✓ Global statistics test passed\n";
    }

    public function testEnhancedApiIndex()
    {
        echo "Testing enhanced API index endpoint...\n";
        
        Address::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/addresses');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'addresses',
                         'statistics' => [
                             'total_addresses',
                             'primary_address',
                             'states_covered',
                             'state_distribution'
                         ]
                     ]
                 ]);

        echo "✓ Enhanced API index test passed\n";
    }

    public function testApiStatisticsEndpoint()
    {
        echo "Testing API statistics endpoint...\n";
        
        Address::factory()->count(2)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/addresses/statistics');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'total_addresses',
                         'primary_address',
                         'states_covered',
                         'state_distribution',
                         'most_recent',
                         'oldest'
                     ]
                 ]);

        echo "✓ API statistics endpoint test passed\n";
    }

    public function testApiExportEndpoint()
    {
        echo "Testing API export endpoint...\n";
        
        Address::factory()->count(2)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/addresses/export');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'addresses',
                         'total',
                         'exported_at'
                     ]
                 ]);

        $data = $response->json('data');
        $this->assertEquals(2, $data['total']);
        $this->assertCount(2, $data['addresses']);

        echo "✓ API export endpoint test passed\n";
    }

    public function testApiSuggestionsEndpoint()
    {
        echo "Testing API suggestions endpoint...\n";
        
        Address::factory()->create([
            'user_id' => $this->user->id,
            'address_line_1' => '123 Main Street',
            'city' => 'Kuala Lumpur'
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/addresses/suggestions?query=Main');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'suggestions',
                         'total'
                     ]
                 ]);

        echo "✓ API suggestions endpoint test passed\n";
    }

    public function testApiPostcodeValidation()
    {
        echo "Testing API postcode validation...\n";
        
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/addresses/validate/postcode', [
                             'postcode' => '50000',
                             'country' => 'Malaysia'
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'is_valid' => true
                     ]
                 ]);

        // Test invalid postcode
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/addresses/validate/postcode', [
                             'postcode' => '123',
                             'country' => 'Malaysia'
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'is_valid' => false
                     ]
                 ]);

        echo "✓ API postcode validation test passed\n";
    }

    public function testEnhancedShowEndpoint()
    {
        echo "Testing enhanced show endpoint...\n";
        
        $address = Address::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("/api/addresses/{$address->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'address',
                         'full_address',
                         'formatted_addresses' => [
                             'short',
                             'single_line',
                             'full'
                         ]
                     ]
                 ]);

        echo "✓ Enhanced show endpoint test passed\n";
    }

    public function testAdminApiIndex()
    {
        echo "Testing admin API index...\n";
        
        Address::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->getJson('/api/admin/addresses');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'addresses',
                         'statistics',
                         'filters_applied'
                     ]
                 ]);

        echo "✓ Admin API index test passed\n";
    }

    public function testAdminApiStatistics()
    {
        echo "Testing admin API statistics...\n";
        
        Address::factory()->count(2)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->getJson('/api/admin/addresses/statistics');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'total_addresses',
                         'primary_addresses',
                         'recent_addresses',
                         'users_with_addresses',
                         'average_addresses_per_user'
                     ]
                 ]);

        echo "✓ Admin API statistics test passed\n";
    }

    public function testAdminApiFilterOptions()
    {
        echo "Testing admin API filter options...\n";
        
        Address::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->getJson('/api/admin/addresses/filter-options');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'states',
                         'users',
                         'primary_options'
                     ]
                 ]);

        echo "✓ Admin API filter options test passed\n";
    }

    public function testBulkOperations()
    {
        echo "Testing bulk operations...\n";
        
        $addresses = Address::factory()->count(3)->create(['user_id' => $this->user->id]);
        $addressIds = $addresses->pluck('id')->toArray();

        $result = $this->addressService->bulkOperation($addressIds, 'set_primary', ['user_id' => $this->user->id]);

        $this->assertTrue($result['success']);
        $this->assertEquals('set_primary', $result['operation']);
        $this->assertEquals(3, $result['total_processed']);

        echo "✓ Bulk operations test passed\n";
    }

    public function testAddressFormatting()
    {
        echo "Testing address formatting...\n";
        
        $address = Address::factory()->create([
            'user_id' => $this->user->id,
            'address_line_1' => '123 Test Street',
            'address_line_2' => 'Unit 4B',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'postcode' => '50000',
            'country' => 'Malaysia'
        ]);

        $shortFormat = $this->addressService->formatAddress($address, 'short');
        $singleLineFormat = $this->addressService->formatAddress($address, 'single_line');
        $fullFormat = $this->addressService->formatAddress($address, 'full');

        $this->assertStringContains('Kuala Lumpur', $shortFormat);
        $this->assertStringContains('123 Test Street', $singleLineFormat);
        $this->assertStringContains('Malaysia', $fullFormat);

        echo "✓ Address formatting test passed\n";
    }

    public function testPostcodeValidation()
    {
        echo "Testing postcode validation...\n";
        
        $this->assertTrue($this->addressService->validatePostcode('50000', 'Malaysia'));
        $this->assertFalse($this->addressService->validatePostcode('123', 'Malaysia'));
        $this->assertFalse($this->addressService->validatePostcode('123456', 'Malaysia'));

        echo "✓ Postcode validation test passed\n";
    }

    public function testMalaysianStates()
    {
        echo "Testing Malaysian states...\n";
        
        $states = $this->addressService->getMalaysianStates();

        $this->assertIsArray($states);
        $this->assertContains('Selangor', $states);
        $this->assertContains('Kuala Lumpur', $states);
        $this->assertCount(16, $states);

        echo "✓ Malaysian states test passed\n";
    }

    public function runAllTests()
    {
        echo "=== Enhanced Address API Test Suite ===\n\n";

        try {
            // Service layer tests
            $this->testAddressServiceCreation();
            $this->testAddressServiceUpdate();
            $this->testAddressServiceDelete();
            $this->testAddressServiceDeleteOnlyAddress();
            
            // Statistics tests
            $this->testUserAddressStatistics();
            $this->testGlobalStatistics();
            
            // Enhanced API tests
            $this->testEnhancedApiIndex();
            $this->testApiStatisticsEndpoint();
            $this->testApiExportEndpoint();
            $this->testApiSuggestionsEndpoint();
            $this->testApiPostcodeValidation();
            $this->testEnhancedShowEndpoint();
            
            // Admin API tests
            $this->testAdminApiIndex();
            $this->testAdminApiStatistics();
            $this->testAdminApiFilterOptions();
            
            // Utility tests
            $this->testBulkOperations();
            $this->testAddressFormatting();
            $this->testPostcodeValidation();
            $this->testMalaysianStates();

            echo "\n=== All Enhanced Address API Tests Passed! ===\n";
            echo "✓ AddressService integration working\n";
            echo "✓ Enhanced API endpoints functional\n";
            echo "✓ Statistics and analytics working\n";
            echo "✓ Admin API endpoints operational\n";
            echo "✓ Bulk operations functional\n";
            echo "✓ Address formatting and validation working\n";
            echo "✓ All business logic properly encapsulated\n\n";

        } catch (Exception $e) {
            echo "\n❌ Test failed: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
    }
}

// Run the tests
if (php_sapi_name() === 'cli') {
    $test = new EnhancedAddressApiTest();
    $test->setUp();
    $test->runAllTests();
} 