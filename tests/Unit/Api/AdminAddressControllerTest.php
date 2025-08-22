<?php

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\AdminAddressController;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminAddressControllerTest extends TestCase
{
    use RefreshDatabase;

    protected AdminAddressController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AdminAddressController();
    }

    /** @test */
    public function index_returns_all_addresses_with_advanced_filtering()
    {
        $admin = User::factory()->admin()->create();
        
        // Create test addresses for different users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Address::factory()->count(2)->create(['user_id' => $user1->id]);
        Address::factory()->count(3)->create(['user_id' => $user2->id]);

        $request = Request::create('/api/admin/addresses', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->index($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('addresses', $data['data']);
        $this->assertArrayHasKey('pagination', $data['data']);
        $this->assertArrayHasKey('filters', $data['data']);
    }

    /** @test */
    public function store_creates_address_for_specified_user()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $addressData = [
            'user_id' => $user->id,
            'type' => 'home',
            'label' => 'User Home',
            'line_1' => '123 Admin Street',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'postcode' => '50450',
            'country' => 'Malaysia'
        ];

        $request = Request::create('/api/admin/addresses', 'POST', $addressData);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->store($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Address created successfully', $data['message']);
        $this->assertArrayHasKey('address', $data['data']);
    }

    /** @test */
    public function get_statistics_returns_global_address_statistics()
    {
        $admin = User::factory()->admin()->create();
        
        // Create addresses across different users and types
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Address::factory()->create(['user_id' => $user1->id, 'type' => 'home']);
        Address::factory()->create(['user_id' => $user2->id, 'type' => 'work']);

        $request = Request::create('/api/admin/addresses/statistics', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->getStatistics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('statistics', $data['data']);
        
        $stats = $data['data']['statistics'];
        $this->assertArrayHasKey('total_addresses', $stats);
        $this->assertArrayHasKey('total_users_with_addresses', $stats);
        $this->assertArrayHasKey('by_type', $stats);
        $this->assertArrayHasKey('by_state', $stats);
        $this->assertArrayHasKey('by_user_role', $stats);
    }

    /** @test */
    public function export_returns_all_addresses_for_export()
    {
        $admin = User::factory()->admin()->create();
        
        // Create test data
        $user = User::factory()->create();
        Address::factory()->count(5)->create(['user_id' => $user->id]);

        $request = Request::create('/api/admin/addresses/export', 'GET', ['format' => 'csv']);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->export($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('addresses', $data['data']);
        $this->assertArrayHasKey('export_info', $data['data']);
    }

    /** @test */
    public function get_filter_options_returns_available_filter_options()
    {
        $admin = User::factory()->admin()->create();

        $request = Request::create('/api/admin/addresses/filter-options', 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->getFilterOptions($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('filter_options', $data['data']);
        
        $options = $data['data']['filter_options'];
        $this->assertArrayHasKey('types', $options);
        $this->assertArrayHasKey('states', $options);
        $this->assertArrayHasKey('user_roles', $options);
        $this->assertArrayHasKey('countries', $options);
    }

    /** @test */
    public function bulk_action_performs_operations_on_multiple_addresses()
    {
        $admin = User::factory()->admin()->create();
        
        $user = User::factory()->create();
        $address1 = Address::factory()->create(['user_id' => $user->id]);
        $address2 = Address::factory()->create(['user_id' => $user->id]);

        $request = Request::create('/api/admin/addresses/bulk-action', 'POST', [
            'action' => 'delete',
            'address_ids' => [$address1->id, $address2->id]
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->bulkAction($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('affected_count', $data['data']);
        $this->assertArrayHasKey('action', $data['data']);
    }

    /** @test */
    public function get_user_addresses_returns_addresses_for_specific_user()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        
        Address::factory()->count(3)->create(['user_id' => $user->id]);

        $request = Request::create("/api/admin/addresses/users/{$user->id}", 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->getUserAddresses($request, $user);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('addresses', $data['data']);
        $this->assertArrayHasKey('user', $data['data']);
        $this->assertEquals($user->id, $data['data']['user']['id']);
    }

    /** @test */
    public function show_returns_address_with_full_details()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $request = Request::create("/api/admin/addresses/{$address->id}", 'GET');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->show($request, $address);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('address', $data['data']);
        $this->assertArrayHasKey('user', $data['data']);
        $this->assertArrayHasKey('audit_trail', $data['data']);
    }

    /** @test */
    public function update_modifies_address_with_user_transfer_option()
    {
        $admin = User::factory()->admin()->create();
        $oldUser = User::factory()->create();
        $newUser = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $oldUser->id]);

        $updateData = [
            'user_id' => $newUser->id, // Transfer to new user
            'label' => 'Updated by Admin',
            'line_1' => 'Updated Address Line'
        ];

        $request = Request::create("/api/admin/addresses/{$address->id}", 'PUT', $updateData);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->update($request, $address);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Address updated successfully', $data['message']);
        $this->assertArrayHasKey('address', $data['data']);
    }

    /** @test */
    public function destroy_deletes_address_with_admin_privileges()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $request = Request::create("/api/admin/addresses/{$address->id}", 'DELETE');
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->destroy($request, $address);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Address deleted successfully', $data['message']);
    }

    /** @test */
    public function index_filters_addresses_by_user_role()
    {
        $admin = User::factory()->admin()->create();
        
        $adminUser = User::factory()->admin()->create();
        $bidderUser = User::factory()->bidder()->create();
        
        Address::factory()->create(['user_id' => $adminUser->id]);
        Address::factory()->create(['user_id' => $bidderUser->id]);

        $request = Request::create('/api/admin/addresses', 'GET', [
            'user_role' => 'bidder'
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->index($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('addresses', $data['data']);
    }

    /** @test */
    public function bulk_action_validates_action_type()
    {
        $admin = User::factory()->admin()->create();
        
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $request = Request::create('/api/admin/addresses/bulk-action', 'POST', [
            'action' => 'invalid_action',
            'address_ids' => [$address->id]
        ]);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $response = $this->controller->bulkAction($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
    }

    /** @test */
    public function export_supports_different_formats()
    {
        $admin = User::factory()->admin()->create();

        $formats = ['csv', 'excel', 'json'];
        
        foreach ($formats as $format) {
            $request = Request::create('/api/admin/addresses/export', 'GET', [
                'format' => $format
            ]);
            $request->setUserResolver(function () use ($admin) {
                return $admin;
            });

            $response = $this->controller->export($request);
            $data = json_decode($response->getContent(), true);

            $this->assertEquals(200, $response->getStatusCode(), "Failed for format: {$format}");
            $this->assertTrue($data['success']);
            $this->assertEquals($format, $data['data']['export_info']['format']);
        }
    }
} 