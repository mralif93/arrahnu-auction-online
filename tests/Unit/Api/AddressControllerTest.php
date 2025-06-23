<?php

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\AddressController;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    protected AddressController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AddressController();
    }

    /** @test */
    public function index_returns_user_addresses_with_pagination()
    {
        $user = User::factory()->active()->create();
        
        // Create some addresses for the user
        Address::factory()->count(3)->create(['user_id' => $user->id]);

        $request = Request::create('/api/addresses', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->index($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('addresses', $data['data']);
        $this->assertArrayHasKey('pagination', $data['data']);
    }

    /** @test */
    public function store_creates_new_address_with_valid_data()
    {
        $user = User::factory()->active()->create();

        $addressData = [
            'type' => 'home',
            'label' => 'Home Address',
            'line_1' => '123 Main Street',
            'line_2' => 'Apartment 4B',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'postcode' => '50450',
            'country' => 'Malaysia'
        ];

        $request = Request::create('/api/addresses', 'POST', $addressData);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->store($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Address created successfully', $data['message']);
        $this->assertArrayHasKey('address', $data['data']);
    }

    /** @test */
    public function store_fails_with_invalid_data()
    {
        $user = User::factory()->active()->create();

        $invalidData = [
            'type' => '', // Required field empty
            'line_1' => '', // Required field empty
            'city' => '',
            'state' => '',
            'postcode' => 'invalid', // Invalid postcode format
        ];

        $request = Request::create('/api/addresses', 'POST', $invalidData);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->store($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
        $this->assertArrayHasKey('errors', $data);
    }

    /** @test */
    public function show_returns_specific_address()
    {
        $user = User::factory()->active()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $request = Request::create("/api/addresses/{$address->id}", 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->show($request, $address);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('address', $data['data']);
        $this->assertEquals($address->id, $data['data']['address']['id']);
    }

    /** @test */
    public function update_modifies_existing_address()
    {
        $user = User::factory()->active()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'label' => 'Updated Label',
            'line_1' => '456 Updated Street',
            'city' => 'Updated City',
        ];

        $request = Request::create("/api/addresses/{$address->id}", 'PUT', $updateData);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->update($request, $address);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Address updated successfully', $data['message']);
        $this->assertArrayHasKey('address', $data['data']);
    }

    /** @test */
    public function destroy_deletes_address()
    {
        $user = User::factory()->active()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $request = Request::create("/api/addresses/{$address->id}", 'DELETE');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->destroy($request, $address);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Address deleted successfully', $data['message']);
    }

    /** @test */
    public function set_primary_sets_address_as_primary()
    {
        $user = User::factory()->active()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $request = Request::create("/api/addresses/{$address->id}/set-primary", 'POST');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->setPrimary($request, $address);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Address set as primary successfully', $data['message']);
    }

    /** @test */
    public function get_statistics_returns_address_statistics()
    {
        $user = User::factory()->active()->create();
        
        // Create addresses with different types
        Address::factory()->create(['user_id' => $user->id, 'type' => 'home']);
        Address::factory()->create(['user_id' => $user->id, 'type' => 'work']);

        $request = Request::create('/api/addresses/statistics', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->getStatistics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('statistics', $data['data']);
        
        $stats = $data['data']['statistics'];
        $this->assertArrayHasKey('total_addresses', $stats);
        $this->assertArrayHasKey('by_type', $stats);
        $this->assertArrayHasKey('by_state', $stats);
    }

    /** @test */
    public function export_returns_user_addresses_for_export()
    {
        $user = User::factory()->active()->create();
        Address::factory()->count(2)->create(['user_id' => $user->id]);

        $request = Request::create('/api/addresses/export', 'GET', ['format' => 'csv']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->export($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('addresses', $data['data']);
    }

    /** @test */
    public function get_suggestions_returns_address_suggestions()
    {
        $user = User::factory()->active()->create();

        $request = Request::create('/api/addresses/suggestions', 'GET', ['query' => 'Kuala']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->getSuggestions($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('suggestions', $data['data']);
    }

    /** @test */
    public function get_states_returns_malaysian_states()
    {
        $user = User::factory()->active()->create();

        $request = Request::create('/api/addresses/states/list', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->getStates($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('states', $data['data']);
        
        // Check if it includes major Malaysian states
        $states = collect($data['data']['states']);
        $this->assertTrue($states->contains('value', 'Kuala Lumpur'));
        $this->assertTrue($states->contains('value', 'Selangor'));
    }

    /** @test */
    public function get_validation_rules_returns_address_validation_rules()
    {
        $user = User::factory()->active()->create();

        $request = Request::create('/api/addresses/validation/rules', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->getValidationRules($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('rules', $data['data']);
        
        $rules = $data['data']['rules'];
        $this->assertArrayHasKey('line_1', $rules);
        $this->assertArrayHasKey('city', $rules);
        $this->assertArrayHasKey('postcode', $rules);
    }

    /** @test */
    public function validate_postcode_validates_malaysian_postcode()
    {
        $user = User::factory()->active()->create();

        // Test valid postcode
        $request = Request::create('/api/addresses/validate/postcode', 'POST', [
            'postcode' => '50450',
            'state' => 'Kuala Lumpur'
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->validatePostcode($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('valid', $data['data']);
    }

    /** @test */
    public function validate_postcode_rejects_invalid_format()
    {
        $user = User::factory()->active()->create();

        // Test invalid postcode format
        $request = Request::create('/api/addresses/validate/postcode', 'POST', [
            'postcode' => 'invalid',
            'state' => 'Kuala Lumpur'
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->validatePostcode($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
    }

    /** @test */
    public function index_filters_addresses_by_type()
    {
        $user = User::factory()->active()->create();
        
        Address::factory()->create(['user_id' => $user->id, 'type' => 'home']);
        Address::factory()->create(['user_id' => $user->id, 'type' => 'work']);

        $request = Request::create('/api/addresses', 'GET', ['type' => 'home']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->index($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        
        // Check that only home addresses are returned
        $addresses = $data['data']['addresses']['data'];
        foreach ($addresses as $address) {
            $this->assertEquals('home', $address['type']);
        }
    }

    /** @test */
    public function index_searches_addresses_by_query()
    {
        $user = User::factory()->active()->create();
        
        Address::factory()->create([
            'user_id' => $user->id,
            'line_1' => '123 Main Street',
            'city' => 'Kuala Lumpur'
        ]);
        Address::factory()->create([
            'user_id' => $user->id,
            'line_1' => '456 Side Road',
            'city' => 'Petaling Jaya'
        ]);

        $request = Request::create('/api/addresses', 'GET', ['search' => 'Main']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->index($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        
        // Check that search results contain the query
        $addresses = $data['data']['addresses']['data'];
        $this->assertGreaterThan(0, count($addresses));
    }
} 