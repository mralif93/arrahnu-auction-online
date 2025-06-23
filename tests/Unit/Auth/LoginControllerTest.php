<?php

namespace Tests\Unit\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected LoginController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new LoginController();
    }

    /** @test */
    public function show_login_form_returns_correct_view()
    {
        $response = $this->controller->showLoginForm();
        
        $this->assertEquals('public.auth.login', $response->getName());
    }

    /** @test */
    public function login_with_valid_credentials_authenticates_user()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $response = $this->controller->login($request);

        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/dashboard', $response->getTargetUrl());
    }

    /** @test */
    public function login_with_admin_user_redirects_to_admin_dashboard()
    {
        $admin = User::factory()->admin()->active()->create([
            'email' => 'admin@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $response = $this->controller->login($request);

        $this->assertTrue(Auth::check());
        $this->assertEquals($admin->id, Auth::id());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/dashboard', $response->getTargetUrl());
    }

    /** @test */
    public function login_updates_last_login_timestamp()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
            'last_login_at' => null,
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $this->controller->login($request);

        $user->refresh();
        $this->assertNotNull($user->last_login_at);
    }

    /** @test */
    public function login_with_invalid_credentials_throws_validation_exception()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('These credentials do not match our records.');

        $this->controller->login($request);

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function login_with_inactive_user_throws_validation_exception()
    {
        $user = User::factory()->inactive()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Your account is not active. Please contact support.');

        $this->controller->login($request);

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function login_validation_fails_without_email()
    {
        $request = Request::create('/login', 'POST', [
            'password' => 'password123',
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $this->expectException(ValidationException::class);

        $this->controller->login($request);
    }

    /** @test */
    public function login_validation_fails_with_invalid_email_format()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $this->expectException(ValidationException::class);

        $this->controller->login($request);
    }

    /** @test */
    public function login_validation_fails_without_password()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $this->expectException(ValidationException::class);

        $this->controller->login($request);
    }

    /** @test */
    public function remember_me_functionality_works()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $this->controller->login($request);

        $this->assertTrue(Auth::check());
        
        // Check that remember token was set
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }

    /** @test */
    public function logout_logs_out_user_and_invalidates_session()
    {
        $user = User::factory()->active()->create();
        Auth::login($user);
        
        $this->assertTrue(Auth::check());

        $request = Request::create('/logout', 'POST');
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->controller->logout($request);

        $this->assertFalse(Auth::check());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/', $response->getTargetUrl());
    }

    /** @test */
    public function login_regenerates_session()
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $session = $this->app['session.store'];
        $request->setLaravelSession($session);
        
        $originalSessionId = $session->getId();

        $this->controller->login($request);

        // Session should be regenerated
        $this->assertNotEquals($originalSessionId, $session->getId());
    }
} 