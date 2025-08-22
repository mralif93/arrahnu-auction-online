<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    protected $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * Show the application's registration form.
     */
    public function showRegistrationForm()
    {
        return view('public.auth.register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20', 'unique:users,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'id' => Str::uuid(),
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password_hash' => Hash::make($request->password),
            'is_email_verified' => false,
            'is_phone_verified' => false,
            'role' => User::ROLE_BIDDER,
            'status' => User::STATUS_PENDING_APPROVAL,
            'is_admin' => false,
            'is_staff' => false,
            'registration_source' => User::REGISTRATION_SOURCE_WEB,
            'email_verification_required' => true,
            'requires_admin_approval' => true,
        ]);

        // Send email verification
        $emailSent = $this->emailVerificationService->sendVerificationEmail($user);

        $message = 'Registration successful! ';
        if ($emailSent) {
            $message .= 'Please check your email to verify your account. After email verification, your account will be reviewed by our administrators.';
        } else {
            $message .= 'Your account is pending email verification and admin approval. Please check your email for verification instructions.';
        }

        return redirect()->route('login')->with('success', $message);
    }
}
