<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function login_page_can_be_rendered()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('public.auth.login');
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        
        // Check that last_login_at was updated
        $user->refresh();
        $this->assertNotNull($user->last_login_at);
    }

    /** @test */
    public function admin_user_redirected_to_admin_dashboard()
    {
        $admin = User::factory()->admin()->active()->create([
            'email' => 'admin@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertTrue(Auth::check());
        $this->assertEquals($admin->id, Auth::id());
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function inactive_user_cannot_login()
    {
        $user = User::factory()->inactive()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function pending_approval_user_cannot_login()
    {
        $user = User::factory()->pendingApproval()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function login_validation_requires_email()
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function login_validation_requires_valid_email_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function login_validation_requires_password()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->active()->create();
        
        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function remember_me_functionality_works()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        
        // Check that remember token was set
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }

    /** @test */
    public function api_login_returns_token_for_valid_credentials()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id', 'full_name', 'username', 'email', 'role', 'is_admin'
                ],
                'token'
            ]
        ]);
        
        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('data.token'));
    }

    /** @test */
    public function api_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => 'These credentials do not match our records.',
        ]);
    }

    /** @test */
    public function api_login_fails_for_inactive_user()
    {
        $user = User::factory()->inactive()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Your account is not active. Please contact support.',
        ]);
    }

    /** @test */
    public function api_login_validation_errors()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'invalid-email',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => [
                'email',
                'password'
            ]
        ]);
    }

    /** @test */
    public function api_logout_returns_success()
    {
        $user = User::factory()->active()->create();
        $tokenResult = $user->createToken('test-token');
        $token = $tokenResult->plainTextToken;

        // Verify token works before logout
        $profileResponse1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/profile');
        $profileResponse1->assertStatus(200);

        // Logout
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logout successful'
        ]);

        // Verify the token record is deleted from database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenResult->accessToken->id
        ]);
    }

    /** @test */
    public function api_profile_returns_user_data()
    {
        $user = User::factory()->active()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/profile');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'user' => [
                    'id', 'full_name', 'username', 'email', 'phone_number',
                    'role', 'is_admin', 'status', 'is_email_verified',
                    'is_phone_verified', 'avatar_url', 'initials',
                    'display_name', 'profile_completion', 'preferences',
                    'created_at', 'last_login_at'
                ]
            ]
        ]);
        
        $this->assertEquals($user->id, $response->json('data.user.id'));
        $this->assertEquals($user->email, $response->json('data.user.email'));
    }
} 