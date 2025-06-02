<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TwoFactorGuestMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * This middleware allows access to 2FA routes when:
     * 1. User is not authenticated (guest)
     * 2. User has a pending 2FA verification session
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // If user is authenticated, redirect to intended page
            if (Auth::guard($guard)->check()) {
                return redirect('/dashboard');
            }
        }

        // Allow access if user has 2FA session (pending verification)
        if (Session::has('2fa_user_id')) {
            return $next($request);
        }

        // If no 2FA session, redirect to login
        return redirect()->route('login');
    }
}
