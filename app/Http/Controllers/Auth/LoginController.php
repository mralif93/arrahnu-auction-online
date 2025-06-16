<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected TwoFactorService $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('public.auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Attempt to authenticate user without logging them in
        if (Auth::validate($credentials)) {
            // Make sure user is not logged in yet
            Auth::logout();

            $user = User::where('email', $request->email)->first();

            // Check if user is active
            if ($user->status !== 'active') {
                throw ValidationException::withMessages([
                    'email' => 'Your account is not active. Please contact support.',
                ]);
            }

            // Check if 2FA is enabled for this user
            if ($this->twoFactorService->isEnabled()) {
                // Store user ID and remember preference in session for 2FA
                Session::put('2fa_user_id', $user->id);
                Session::put('2fa_remember', $remember);

                // Generate and send 2FA code
                if ($this->twoFactorService->generateAndSendCode($user)) {
                    return redirect()->route('2fa.show')
                        ->with('success', 'A verification code has been sent to your email.');
                } else {
                    return back()->with('error', 'Failed to send verification code. Please try again.');
                }
            }

            // If 2FA is disabled, log in normally
            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Update last login time
            $user->update(['last_login_at' => now()]);

            return redirect()->intended($user->is_admin ? '/admin/dashboard' : '/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records.'),
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
