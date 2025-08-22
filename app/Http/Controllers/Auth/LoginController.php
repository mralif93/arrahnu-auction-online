<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

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

        // Find user for additional checks
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            // Increment login attempts
            $user->incrementLoginAttempts();

            // Check if account is locked
            if ($user->isAccountLocked()) {
                throw ValidationException::withMessages([
                    'email' => 'Account is temporarily locked due to multiple failed login attempts. Please try again later.',
                ]);
            }
        }

        // Attempt to authenticate user
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if user can login (includes all verification and approval checks)
            if (!$user->canLogin()) {
                Auth::logout();
                
                $message = 'Your account is not ready for login. ';
                if ($user->requiresEmailVerification()) {
                    $message = 'Please verify your email address before logging in. Check your email for verification instructions.';
                } elseif ($user->requiresAdminApproval()) {
                    $message = 'Your account is pending admin approval. You will be notified once approved.';
                } elseif ($user->status === User::STATUS_REJECTED) {
                    $message = 'Your account application has been rejected. Please contact support for more information.';
                } elseif ($user->status !== User::STATUS_ACTIVE) {
                    $message = 'Your account is not active. Please contact support.';
                }
                
                throw ValidationException::withMessages([
                    'email' => $message,
                ]);
            }

            $request->session()->regenerate();

            // Update login tracking
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
            $user->updateLoginTracking(User::LOGIN_SOURCE_WEB, $ipAddress, $userAgent);

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
