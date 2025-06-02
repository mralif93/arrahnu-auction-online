<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TwoFactorController extends Controller
{
    protected TwoFactorService $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Show the 2FA verification form.
     */
    public function show()
    {
        // Check if user is in 2FA verification state
        if (!Session::has('2fa_user_id')) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $userId = Session::get('2fa_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found. Please log in again.');
        }

        if (!$this->twoFactorService->hasPendingVerification($user)) {
            return redirect()->route('login')->with('error', 'Verification session expired. Please log in again.');
        }

        $remainingTime = $this->twoFactorService->getRemainingTime($user);

        return view('auth.two-factor', [
            'user' => $user,
            'remainingTime' => $remainingTime,
        ]);
    }

    /**
     * Verify the 2FA code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'code.required' => 'Please enter the verification code.',
            'code.size' => 'Verification code must be 6 digits.',
            'code.regex' => 'Verification code must contain only numbers.',
        ]);

        // Check if user is in 2FA verification state
        if (!Session::has('2fa_user_id')) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $userId = Session::get('2fa_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found. Please log in again.');
        }

        // Verify the code
        $result = $this->twoFactorService->verifyCode($user, $request->code);

        if ($result['success']) {
            // Clear 2FA session data
            Session::forget('2fa_user_id');
            Session::forget('2fa_remember');

            // Log the user in
            Auth::login($user, Session::get('2fa_remember', false));

            // Update last login time
            $user->update(['last_login_at' => now()]);

            // Redirect to intended page or dashboard
            return redirect()->intended($user->is_admin ? '/admin/dashboard' : '/dashboard')
                ->with('success', 'Login successful! Welcome back.');
        }

        // Handle failed verification
        if (isset($result['expired']) || isset($result['max_attempts_reached'])) {
            Session::forget('2fa_user_id');
            Session::forget('2fa_remember');
            return redirect()->route('login')->with('error', $result['message']);
        }

        return back()->withErrors(['code' => $result['message']]);
    }

    /**
     * Resend the 2FA code.
     */
    public function resend(Request $request)
    {
        // Check if user is in 2FA verification state
        if (!Session::has('2fa_user_id')) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $userId = Session::get('2fa_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found. Please log in again.');
        }

        // Clear existing verification
        $this->twoFactorService->clearVerification($user);

        // Generate and send new code
        if ($this->twoFactorService->generateAndSendCode($user)) {
            return back()->with('success', 'A new verification code has been sent to your email.');
        }

        return back()->with('error', 'Failed to send verification code. Please try again.');
    }

    /**
     * Cancel 2FA verification and return to login.
     */
    public function cancel()
    {
        if (Session::has('2fa_user_id')) {
            $userId = Session::get('2fa_user_id');
            $user = User::find($userId);
            
            if ($user) {
                $this->twoFactorService->clearVerification($user);
            }
        }

        Session::forget('2fa_user_id');
        Session::forget('2fa_remember');

        return redirect()->route('login')->with('info', 'Login cancelled. Please try again.');
    }
}
