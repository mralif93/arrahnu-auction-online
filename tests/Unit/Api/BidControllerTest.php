<?php

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\BidController;
use App\Models\User;
use App\Models\Bid;
use App\Models\Collateral;
use App\Models\Auction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class BidControllerTest extends TestCase
{
    use RefreshDatabase;

    protected BidController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BidController();
    }

    /** @test */
    public function index_returns_user_bids_with_pagination()
    {
        $user = User::factory()->active()->create();
        
        // Create test data
        $collateral = Collateral::factory()->create();
        Bid::factory()->count(3)->create([
            'user_id' => $user->id,
            'collateral_id' => $collateral->id,
        ]);

        $request = Request::create('/api/bids', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->index($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('bids', $data['data']);
        $this->assertArrayHasKey('pagination', $data['data']);
    }

    /** @test */
    public function store_creates_new_bid_with_valid_data()
    {
        $user = User::factory()->active()->create();
        $collateral = Collateral::factory()->create([
            'status' => 'active',
            'reserve_price' => 1000.00,
        ]);

        $bidData = [
            'collateral_id' => $collateral->id,
            'amount' => 1500.00,
            'type' => 'standard'
        ];

        $request = Request::create('/api/bids', 'POST', $bidData);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->store($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Bid placed successfully', $data['message']);
        $this->assertArrayHasKey('bid', $data['data']);
    }

    /** @test */
    public function active_bids_returns_user_active_bids()
    {
        $user = User::factory()->active()->create();
        
        $collateral = Collateral::factory()->create();
        Bid::factory()->create([
            'user_id' => $user->id,
            'collateral_id' => $collateral->id,
            'status' => 'active'
        ]);

        $request = Request::create('/api/bids/active', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->activeBids($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('bids', $data['data']);
    }

    /** @test */
    public function statistics_returns_bidding_statistics()
    {
        $user = User::factory()->active()->create();
        
        $collateral = Collateral::factory()->create();
        Bid::factory()->create([
            'user_id' => $user->id,
            'collateral_id' => $collateral->id,
            'status' => 'successful'
        ]);

        $request = Request::create('/api/bids/statistics', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->statistics($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('statistics', $data['data']);
    }

    /** @test */
    public function cancel_cancels_active_bid()
    {
        $user = User::factory()->active()->create();
        
        $collateral = Collateral::factory()->create();
        $bid = Bid::factory()->create([
            'user_id' => $user->id,
            'collateral_id' => $collateral->id,
            'status' => 'active'
        ]);

        $request = Request::create("/api/bids/{$bid->id}/cancel", 'POST');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->cancel($request, $bid);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Bid cancelled successfully', $data['message']);
    }

    /** @test */
    public function active_auctions_returns_available_auctions()
    {
        $user = User::factory()->active()->create();
        
        // Create active auction with collaterals
        $auction = Auction::factory()->create(['status' => 'active']);
        Collateral::factory()->count(2)->create([
            'auction_id' => $auction->id,
            'status' => 'active'
        ]);

        $request = Request::create('/api/auctions/active', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->activeAuctions($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('auctions', $data['data']);
    }

    /** @test */
    public function collateral_details_returns_specific_collateral()
    {
        $user = User::factory()->active()->create();
        $collateral = Collateral::factory()->create(['status' => 'active']);

        $request = Request::create("/api/auctions/collaterals/{$collateral->id}", 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->collateralDetails($request, $collateral);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('collateral', $data['data']);
    }

    /** @test */
    public function live_updates_returns_real_time_bidding_data()
    {
        $user = User::factory()->active()->create();
        $collateral = Collateral::factory()->create(['status' => 'active']);

        $request = Request::create("/api/auctions/collaterals/{$collateral->id}/live-updates", 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->liveUpdates($request, $collateral);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('current_highest_bid', $data['data']);
    }
} 