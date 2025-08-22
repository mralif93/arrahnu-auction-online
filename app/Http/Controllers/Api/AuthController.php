<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailVerificationService;
use App\Services\AdminApprovalService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    protected $emailVerificationService;
    protected $adminApprovalService;

    public function __construct(
        EmailVerificationService $emailVerificationService,
        AdminApprovalService $adminApprovalService
    ) {
        $this->emailVerificationService = $emailVerificationService;
        $this->adminApprovalService = $adminApprovalService;
    }

    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['nullable', 'string', 'max:20', 'unique:users'],
            'role' => ['nullable', 'in:maker,checker,bidder'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role' => $request->role ?? User::ROLE_BIDDER,
                'status' => User::STATUS_PENDING_APPROVAL,
                'is_admin' => false,
                'registration_source' => User::REGISTRATION_SOURCE_API,
                'email_verification_required' => true,
                'requires_admin_approval' => true,
            ]);

            // Send email verification
            $emailSent = $this->emailVerificationService->sendVerificationEmail($user);

            $message = 'Registration successful. ';
            if ($emailSent) {
                $message .= 'Please check your email to verify your account. After email verification, your account will be reviewed by our administrators.';
            } else {
                $message .= 'Your account is pending email verification and admin approval.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                        'role' => $user->role,
                        'status' => $user->status,
                        'email_verification_required' => $user->email_verification_required,
                        'requires_admin_approval' => $user->requires_admin_approval,
                    ],
                    'verification_email_sent' => $emailSent,
                ]
            ], 201);

        } catch (\Exception $e) {
            // Check for specific database constraint errors
            if (str_contains($e->getMessage(), 'phone_number')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['phone_number' => ['The phone number has already been taken.']]
                ], 422);
            }
            
            if (str_contains($e->getMessage(), 'email')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['email' => ['The email has already been taken.']]
                ], 422);
            }
            
            if (str_contains($e->getMessage(), 'username')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['username' => ['The username has already been taken.']]
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Login user.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Find user for additional checks
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'These credentials do not match our records.',
            ], 401);
        }

        // Increment login attempts
        $user->incrementLoginAttempts();

        // Check if account is locked
        if ($user->isAccountLocked()) {
            return response()->json([
                'success' => false,
                'message' => 'Account is temporarily locked due to multiple failed login attempts. Please try again later.',
                'error_code' => 'ACCOUNT_LOCKED',
                'locked_until' => $user->account_locked_until,
            ], 423);
        }

        // Attempt to authenticate user
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user can login (includes all verification and approval checks)
            if (!$user->canLogin()) {
                Auth::logout();
                
                $message = 'Your account is not ready for login. ';
                $errorCode = 'ACCOUNT_NOT_READY';
                
                if ($user->requiresEmailVerification()) {
                    $message = 'Please verify your email address before logging in.';
                    $errorCode = 'EMAIL_NOT_VERIFIED';
                } elseif ($user->requiresAdminApproval()) {
                    $message = 'Your account is pending admin approval.';
                    $errorCode = 'PENDING_APPROVAL';
                } elseif ($user->status === User::STATUS_REJECTED) {
                    $message = 'Your account application has been rejected. Please contact support for more information.';
                    $errorCode = 'ACCOUNT_REJECTED';
                } elseif ($user->status !== User::STATUS_ACTIVE) {
                    $message = 'Your account is not active. Please contact support.';
                    $errorCode = 'ACCOUNT_INACTIVE';
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'error_code' => $errorCode,
                    'verification_status' => $this->emailVerificationService->getVerificationStatus($user),
                    'approval_status' => $this->adminApprovalService->getApprovalStatus($user),
                ], 403);
            }

            // Update login tracking
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
            $user->updateLoginTracking(User::LOGIN_SOURCE_API, $ipAddress, $userAgent);

            // Create API token
            $token = $user->createToken('api-token')->plainTextToken;

            return $this->authResponse($user, $token, 'Login successful');
        }

        return response()->json([
            'success' => false,
            'message' => 'These credentials do not match our records.',
        ], 401);
    }



    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revoke the current token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get authenticated user profile.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'role' => $user->role,
                    'is_admin' => $user->is_admin,
                    'status' => $user->status,
                    'is_email_verified' => $user->is_email_verified,
                    'is_phone_verified' => $user->is_phone_verified,
                    'avatar_url' => $user->avatar_url,
                    'initials' => $user->initials,
                    'display_name' => $user->display_name,
                    'profile_completion' => $user->profile_completion,
                    'preferences' => $user->preferences,
                    'created_at' => $user->created_at,
                    'last_login_at' => $user->last_login_at,
                ]
            ]
        ]);
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:100',
                'username' => 'required|string|max:50|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:20|unique:users,phone_number,' . $user->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ];

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                        'avatar_url' => $user->avatar_url,
                        'display_name' => $user->display_name,
                        'profile_completion' => $user->profile_completion,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify current password
            if (!Hash::check($request->current_password, $user->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['The current password is incorrect']]
                ], 422);
            }

            $user->update([
                'password_hash' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating password',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('avatar')) {
                // Store the avatar file
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                
                // Delete old avatar if exists
                if ($user->avatar_path && \Storage::disk('public')->exists($user->avatar_path)) {
                    \Storage::disk('public')->delete($user->avatar_path);
                }

                $user->update(['avatar_path' => $avatarPath]);

                return response()->json([
                    'success' => true,
                    'message' => 'Avatar updated successfully',
                    'data' => [
                        'avatar_url' => $user->avatar_url,
                        'profile_completion' => $user->profile_completion,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No avatar file provided'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating avatar',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'email_notifications' => 'boolean',
                'sms_notifications' => 'boolean',
                'marketing_emails' => 'boolean',
                'timezone' => 'nullable|string|max:50',
                'language' => 'nullable|string|max:10',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $preferences = [
                'email_notifications' => $request->boolean('email_notifications'),
                'sms_notifications' => $request->boolean('sms_notifications'),
                'marketing_emails' => $request->boolean('marketing_emails'),
                'timezone' => $request->timezone ?? 'UTC',
                'language' => $request->language ?? 'en',
            ];

            $user->update(['preferences' => $preferences]);

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully',
                'data' => [
                    'preferences' => $user->preferences
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating preferences',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Delete user account.
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify password
            if (!Hash::check($request->password, $user->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password is incorrect',
                    'errors' => ['password' => ['The password is incorrect']]
                ], 422);
            }

            // Prevent admin from deleting their own account if they're the only admin
            if ($user->is_admin) {
                $adminCount = User::where('is_admin', true)->count();
                if ($adminCount <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete account. You are the only administrator.'
                    ], 400);
                }
            }

            // Delete avatar file if exists
            if ($user->avatar_path && \Storage::disk('public')->exists($user->avatar_path)) {
                \Storage::disk('public')->delete($user->avatar_path);
            }

            // Revoke all tokens
            $user->tokens()->delete();

            // Delete user
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting account',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Verify email address.
     */
    public function verifyEmail(Request $request, string $token): JsonResponse
    {
        $result = $this->emailVerificationService->verifyEmail($token);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'user_id' => $result['user']->id,
                    'email_verified' => true,
                    'needs_admin_approval' => $result['needs_admin_approval'],
                    'can_login' => $result['user']->canLogin(),
                ]
            ]);
        }

        $statusCode = match($result['error_code']) {
            'TOKEN_EXPIRED' => 410,
            'ALREADY_VERIFIED' => 409,
            default => 400
        };

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'error_code' => $result['error_code'],
        ], $statusCode);
    }

    /**
     * Resend email verification.
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $result = $this->emailVerificationService->resendVerificationEmail($user);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
            ]);
        }

        $statusCode = match($result['error_code']) {
            'ALREADY_VERIFIED' => 409,
            'RATE_LIMITED' => 429,
            'MAX_ATTEMPTS_EXCEEDED' => 429,
            default => 400
        };

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'error_code' => $result['error_code'],
        ], $statusCode);
    }

    /**
     * Get verification status.
     */
    public function getVerificationStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        
        return response()->json([
            'success' => true,
            'data' => [
                'verification_status' => $this->emailVerificationService->getVerificationStatus($user),
                'approval_status' => $this->adminApprovalService->getApprovalStatus($user),
                'can_login' => $user->canLogin(),
            ]
        ]);
    }

    /**
     * Send password reset link to user's email.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if user exists
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                // For security reasons, don't reveal if email exists or not
                return response()->json([
                    'success' => true,
                    'message' => 'If your email address exists in our database, you will receive a password recovery link at your email address in a few minutes.',
                ]);
            }

            // Send password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link has been sent to your email address.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unable to send password reset link. Please try again later.',
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Reset user password using reset token.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Reset the password
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password_hash' => Hash::make($password)
                    ]);

                    $user->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your password has been reset successfully. You can now log in with your new password.',
                ]);
            }

            // Handle different error statuses
            $message = match($status) {
                Password::INVALID_TOKEN => 'The password reset token is invalid or has expired.',
                Password::INVALID_USER => 'We cannot find a user with that email address.',
                default => 'Unable to reset password. Please try again.'
            };

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resetting your password.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
