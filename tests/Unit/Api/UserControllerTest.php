<?php

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\UserController;
use App\Models\User;
use App\Models\Bid;
use App\Models\Collateral;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected UserController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new UserController();
    }

    /** @test */
    public function profile_returns_user_data_with_statistics()
    {
        $user = User::factory()->active()->create([
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'username' => 'johndoe',
        ]);

        $request = Request::create('/api/user/profile', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->profile($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('user', $data['data']);
        $this->assertArrayHasKey('statistics', $data['data']);
        
        $userData = $data['data']['user'];
        $this->assertEquals('John Doe', $userData['full_name']);
        $this->assertEquals('john@example.com', $userData['email']);
        $this->assertEquals('johndoe', $userData['username']);
    }

    /** @test */
    public function update_profile_with_valid_data_succeeds()
    {
        $user = User::factory()->active()->create([
            'full_name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $request = Request::create('/api/user/profile', 'PUT', [
            'full_name' => 'New Name',
            'username' => 'newusername',
            'email' => 'new@example.com',
            'phone_number' => '+60123456789',
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->updateProfile($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Profile updated successfully', $data['message']);
        $this->assertArrayHasKey('user', $data['data']);
    }

    /** @test */
    public function update_profile_with_duplicate_email_fails()
    {
        $existingUser = User::factory()->active()->create([
            'email' => 'existing@example.com',
        ]);
        
        $user = User::factory()->active()->create([
            'email' => 'user@example.com',
        ]);

        $request = Request::create('/api/user/profile', 'PUT', [
            'full_name' => 'Test User',
            'username' => 'testuser',
            'email' => 'existing@example.com', // Duplicate email
            'phone_number' => '+60123456789',
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->updateProfile($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
        $this->assertArrayHasKey('errors', $data);
    }

    /** @test */
    public function update_password_with_valid_data_succeeds()
    {
        $user = User::factory()->active()->create([
            'password_hash' => Hash::make('oldpassword'),
        ]);

        $request = Request::create('/api/user/password', 'PUT', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->updatePassword($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Password updated successfully', $data['message']);
    }

    /** @test */
    public function update_password_with_wrong_current_password_fails()
    {
        $user = User::factory()->active()->create([
            'password_hash' => Hash::make('correctpassword'),
        ]);

        $request = Request::create('/api/user/password', 'PUT', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->updatePassword($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Current password is incorrect', $data['message']);
    }

    /** @test */
    public function update_password_with_mismatched_confirmation_fails()
    {
        $user = User::factory()->active()->create([
            'password_hash' => Hash::make('currentpassword'),
        ]);

        $request = Request::create('/api/user/password', 'PUT', [
            'current_password' => 'currentpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->updatePassword($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
    }

    /** @test */
    public function update_avatar_with_valid_image_succeeds()
    {
        Storage::fake('public');
        
        $user = User::factory()->active()->create();
        
        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $request = Request::create('/api/user/avatar', 'POST');
        $request->files->set('avatar', $file);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->updateAvatar($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Avatar updated successfully', $data['message']);
        $this->assertArrayHasKey('avatar_url', $data['data']);
    }

    /** @test */
    public function update_avatar_with_invalid_file_fails()
    {
        $user = User::factory()->active()->create();
        
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $request = Request::create('/api/user/avatar', 'POST');
        $request->files->set('avatar', $file);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->updateAvatar($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
    }

    /** @test */
    public function remove_avatar_succeeds()
    {
        Storage::fake('public');
        
        $user = User::factory()->active()->create([
            'avatar_path' => 'avatars/test.jpg'
        ]);

        $request = Request::create('/api/user/avatar', 'DELETE');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->removeAvatar($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Avatar removed successfully', $data['message']);
    }

    /** @test */
    public function update_preferences_with_valid_data_succeeds()
    {
        $user = User::factory()->active()->create();

        $preferences = [
            'language' => 'en',
            'currency' => 'MYR',
            'notifications' => [
                'email' => true,
                'sms' => false,
                'push' => true
            ]
        ];

        $request = Request::create('/api/user/preferences', 'PUT', [
            'preferences' => $preferences
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->updatePreferences($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Preferences updated successfully', $data['message']);
    }

    /** @test */
    public function bidding_activity_returns_user_bids()
    {
        $user = User::factory()->active()->create();
        
        // Create some test data
        $collateral = Collateral::factory()->create();
        $bid = Bid::factory()->create([
            'user_id' => $user->id,
            'collateral_id' => $collateral->id,
        ]);

        $request = Request::create('/api/user/bidding-activity', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->biddingActivity($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('bids', $data['data']);
        $this->assertArrayHasKey('statistics', $data['data']);
    }

    /** @test */
    public function watchlist_returns_user_watchlist()
    {
        $user = User::factory()->active()->create();

        $request = Request::create('/api/user/watchlist', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->watchlist($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('watchlist', $data['data']);
    }

    /** @test */
    public function delete_account_with_valid_password_succeeds()
    {
        $user = User::factory()->active()->create([
            'password_hash' => Hash::make('password123'),
        ]);

        $request = Request::create('/api/user/account', 'DELETE', [
            'password' => 'password123',
            'confirmation' => 'DELETE',
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->deleteAccount($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals('Account deleted successfully', $data['message']);
    }

    /** @test */
    public function delete_account_with_wrong_password_fails()
    {
        $user = User::factory()->active()->create([
            'password_hash' => Hash::make('correctpassword'),
        ]);

        $request = Request::create('/api/user/account', 'DELETE', [
            'password' => 'wrongpassword',
            'confirmation' => 'DELETE',
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->deleteAccount($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Current password is incorrect', $data['message']);
    }

    /** @test */
    public function delete_account_without_confirmation_fails()
    {
        $user = User::factory()->active()->create([
            'password_hash' => Hash::make('password123'),
        ]);

        $request = Request::create('/api/user/account', 'DELETE', [
            'password' => 'password123',
            'confirmation' => 'WRONG',
        ]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->deleteAccount($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
    }

    /** @test */
    public function profile_includes_required_user_fields()
    {
        $user = User::factory()->active()->create();

        $request = Request::create('/api/user/profile', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->profile($request);
        $data = json_decode($response->getContent(), true);

        $userData = $data['data']['user'];
        $requiredFields = [
            'id', 'full_name', 'username', 'email', 'phone_number',
            'role', 'status', 'is_email_verified', 'is_phone_verified',
            'avatar_url', 'display_name', 'profile_completion'
        ];

        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey($field, $userData, "Missing required field: {$field}");
        }
    }

    /** @test */
    public function profile_includes_statistics()
    {
        $user = User::factory()->active()->create();

        $request = Request::create('/api/user/profile', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->profile($request);
        $data = json_decode($response->getContent(), true);

        $statistics = $data['data']['statistics'];
        $requiredStats = [
            'total_bids', 'active_bids', 'successful_bids',
            'total_addresses', 'profile_completion_percentage'
        ];

        foreach ($requiredStats as $stat) {
            $this->assertArrayHasKey($stat, $statistics, "Missing required statistic: {$stat}");
        }
    }
} 