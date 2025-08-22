<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Mockery;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $authController;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authController = new AuthController();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'name' => 'Test User',
            'role' => 'ROLE_BIDDER',
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function it_can_register_a_new_user()
    {
        $request = Request::create('/api/auth/register', 'POST', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '0123456789',
        ]);

        $response = $this->authController->register($request);
        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('user', $responseData['data']);
        $this->assertArrayHasKey('token', $responseData['data']);
        $this->assertEquals('newuser@example.com', $responseData['data']['user']['email']);
    }

    /** @test */
    public function it_fails_registration_with_invalid_data()
    {
        $request = Request::create('/api/auth/register', 'POST', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123', // Too short
            'phone' => '',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->authController->register($request);
    }

    /** @test */
    public function it_fails_registration_with_existing_email()
    {
        $request = Request::create('/api/auth/register', 'POST', [
            'name' => 'Another User',
            'email' => $this->user->email, // Existing email
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '0123456789',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->authController->register($request);
    }

    /** @test */
    public function it_can_login_with_valid_credentials()
    {
        $request = Request::create('/api/auth/login', 'POST', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $response = $this->authController->login($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('user', $responseData['data']);
        $this->assertArrayHasKey('token', $responseData['data']);
    }

    /** @test */
    public function it_fails_login_with_invalid_credentials()
    {
        $request = Request::create('/api/auth/login', 'POST', [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
        ]);

        $response = $this->authController->login($request);
        $responseData = $response->getData(true);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Invalid credentials', $responseData['message']);
    }

    /** @test */
    public function it_fails_login_with_unverified_email()
    {
        $unverifiedUser = User::factory()->create([
            'email' => 'unverified@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
        ]);

        $request = Request::create('/api/auth/login', 'POST', [
            'email' => $unverifiedUser->email,
            'password' => 'password123',
        ]);

        $response = $this->authController->login($request);
        $responseData = $response->getData(true);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertFalse($responseData['success']);
        $this->assertStringContainsString('email is not verified', $responseData['message']);
    }

    /** @test */
    public function it_can_logout_authenticated_user()
    {
        $this->actingAs($this->user, 'sanctum');
        
        $request = Request::create('/api/auth/logout', 'POST');
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->logout($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Successfully logged out', $responseData['message']);
    }

    /** @test */
    public function it_can_get_authenticated_user_profile()
    {
        $this->actingAs($this->user, 'sanctum');
        
        $request = Request::create('/api/auth/profile', 'GET');
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->profile($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('user', $responseData['data']);
        $this->assertEquals($this->user->email, $responseData['data']['user']['email']);
    }

    /** @test */
    public function it_can_update_user_profile()
    {
        $this->actingAs($this->user, 'sanctum');
        
        $request = Request::create('/api/user/profile', 'PUT', [
            'name' => 'Updated Name',
            'phone' => '0987654321',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->updateProfile($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Updated Name', $responseData['data']['user']['name']);
        $this->assertEquals('0987654321', $responseData['data']['user']['phone']);
    }

    /** @test */
    public function it_can_update_user_password()
    {
        $this->actingAs($this->user, 'sanctum');
        
        $request = Request::create('/api/user/password', 'PUT', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->updatePassword($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Password updated successfully', $responseData['message']);
    }

    /** @test */
    public function it_fails_password_update_with_wrong_current_password()
    {
        $this->actingAs($this->user, 'sanctum');
        
        $request = Request::create('/api/user/password', 'PUT', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->updatePassword($request);
        $responseData = $response->getData(true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Current password is incorrect', $responseData['message']);
    }

    /** @test */
    public function it_can_update_user_avatar()
    {
        Storage::fake('public');
        $this->actingAs($this->user, 'sanctum');
        
        $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);
        
        $request = Request::create('/api/user/avatar', 'POST');
        $request->files->set('avatar', $file);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->updateAvatar($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Avatar updated successfully', $responseData['message']);
        $this->assertNotNull($responseData['data']['avatar_url']);
    }

    /** @test */
    public function it_fails_avatar_update_with_invalid_file()
    {
        Storage::fake('public');
        $this->actingAs($this->user, 'sanctum');
        
        $file = UploadedFile::fake()->create('document.pdf', 1000); // Not an image
        
        $request = Request::create('/api/user/avatar', 'POST');
        $request->files->set('avatar', $file);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->authController->updateAvatar($request);
    }

    /** @test */
    public function it_can_update_user_preferences()
    {
        $this->actingAs($this->user, 'sanctum');
        
        $preferences = [
            'notifications' => [
                'email' => true,
                'sms' => false,
                'push' => true,
            ],
            'language' => 'en',
            'timezone' => 'Asia/Kuala_Lumpur',
        ];
        
        $request = Request::create('/api/user/preferences', 'PUT', $preferences);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->updatePreferences($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Preferences updated successfully', $responseData['message']);
    }

    /** @test */
    public function it_can_delete_user_account()
    {
        $this->actingAs($this->user, 'sanctum');
        
        $request = Request::create('/api/user/account', 'DELETE', [
            'password' => 'password123',
            'confirmation' => 'DELETE',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->deleteAccount($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Account deleted successfully', $responseData['message']);
    }

    /** @test */
    public function it_fails_account_deletion_with_wrong_password()
    {
        $this->actingAs($this->user, 'sanctum');
        
        $request = Request::create('/api/user/account', 'DELETE', [
            'password' => 'wrongpassword',
            'confirmation' => 'DELETE',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->authController->deleteAccount($request);
        $responseData = $response->getData(true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Invalid password', $responseData['message']);
    }

    /** @test */
    public function it_can_send_forgot_password_email()
    {
        $request = Request::create('/api/auth/forgot-password', 'POST', [
            'email' => $this->user->email,
        ]);

        $response = $this->authController->forgotPassword($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertStringContainsString('password reset link', $responseData['message']);
    }

    /** @test */
    public function it_fails_forgot_password_with_invalid_email()
    {
        $request = Request::create('/api/auth/forgot-password', 'POST', [
            'email' => 'nonexistent@example.com',
        ]);

        $response = $this->authController->forgotPassword($request);
        $responseData = $response->getData(true);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertFalse($responseData['success']);
        $this->assertEquals('User not found', $responseData['message']);
    }

    /** @test */
    public function it_can_reset_password_with_valid_token()
    {
        // Create a password reset token
        $token = 'valid-reset-token';
        \DB::table('password_reset_tokens')->insert([
            'email' => $this->user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $request = Request::create('/api/auth/reset-password', 'POST', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response = $this->authController->resetPassword($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Password reset successfully', $responseData['message']);
    }

    /** @test */
    public function it_fails_password_reset_with_invalid_token()
    {
        $request = Request::create('/api/auth/reset-password', 'POST', [
            'token' => 'invalid-token',
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response = $this->authController->resetPassword($request);
        $responseData = $response->getData(true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Invalid or expired token', $responseData['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 