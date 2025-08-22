<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailVerificationService
{
    /**
     * Send email verification link to user.
     */
    public function sendVerificationEmail(User $user): bool
    {
        try {
            // Generate verification token
            $token = Str::random(60);
            $expiresAt = now()->addHours(24); // Token expires in 24 hours

            // Update user with verification token
            $user->update([
                'email_verification_token' => $token,
                'email_verification_sent_at' => now(),
                'verification_token_expires_at' => $expiresAt,
            ]);

            // Send verification email
            Mail::send('emails.email-verification', [
                'user' => $user,
                'verificationUrl' => $this->generateVerificationUrl($token),
                'expiresAt' => $expiresAt,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->full_name)
                        ->subject('Verify Your Email Address - ArRahnu Auction');
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verify email using token.
     */
    public function verifyEmail(string $token): array
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid verification token.',
                'error_code' => 'INVALID_TOKEN'
            ];
        }

        // Check if token is expired
        if ($user->verification_token_expires_at && $user->verification_token_expires_at->isPast()) {
            return [
                'success' => false,
                'message' => 'Verification token has expired. Please request a new one.',
                'error_code' => 'TOKEN_EXPIRED'
            ];
        }

        // Check if email is already verified
        if ($user->isEmailVerified()) {
            return [
                'success' => false,
                'message' => 'Email is already verified.',
                'error_code' => 'ALREADY_VERIFIED'
            ];
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        // Check if user still needs admin approval
        $needsApproval = $user->requiresAdminApproval();

        return [
            'success' => true,
            'message' => $needsApproval 
                ? 'Email verified successfully. Your account is pending admin approval.'
                : 'Email verified successfully. You can now login.',
            'user' => $user,
            'needs_admin_approval' => $needsApproval
        ];
    }

    /**
     * Resend verification email.
     */
    public function resendVerificationEmail(User $user): array
    {
        // Check if email is already verified
        if ($user->isEmailVerified()) {
            return [
                'success' => false,
                'message' => 'Email is already verified.',
                'error_code' => 'ALREADY_VERIFIED'
            ];
        }

        // Check rate limiting (max 3 attempts per hour)
        if ($user->email_verification_sent_at && 
            $user->email_verification_sent_at->diffInMinutes(now()) < 20) {
            return [
                'success' => false,
                'message' => 'Please wait before requesting another verification email.',
                'error_code' => 'RATE_LIMITED'
            ];
        }

        // Check failed attempts (max 5 per day)
        if ($user->failed_verification_attempts >= 5) {
            return [
                'success' => false,
                'message' => 'Too many verification attempts. Please contact support.',
                'error_code' => 'MAX_ATTEMPTS_EXCEEDED'
            ];
        }

        $sent = $this->sendVerificationEmail($user);

        if ($sent) {
            return [
                'success' => true,
                'message' => 'Verification email sent successfully.',
            ];
        } else {
            // Increment failed attempts
            $user->increment('failed_verification_attempts');
            
            return [
                'success' => false,
                'message' => 'Failed to send verification email. Please try again later.',
                'error_code' => 'SEND_FAILED'
            ];
        }
    }

    /**
     * Generate verification URL.
     */
    private function generateVerificationUrl(string $token): string
    {
        return url("/verify-email/{$token}");
    }

    /**
     * Generate API verification URL.
     */
    public function generateApiVerificationUrl(string $token): string
    {
        return url("/api/auth/verify-email/{$token}");
    }

    /**
     * Check if user needs email verification before login.
     */
    public function requiresVerificationBeforeLogin(User $user): bool
    {
        return $user->email_verification_required && !$user->isEmailVerified();
    }

    /**
     * Get verification status for user.
     */
    public function getVerificationStatus(User $user): array
    {
        return [
            'email_verified' => $user->isEmailVerified(),
            'email_verified_at' => $user->email_verified_at,
            'verification_required' => $user->email_verification_required,
            'verification_sent_at' => $user->email_verification_sent_at,
            'verification_expires_at' => $user->verification_token_expires_at,
            'failed_attempts' => $user->failed_verification_attempts,
            'can_resend' => $this->canResendVerification($user),
        ];
    }

    /**
     * Check if user can resend verification email.
     */
    private function canResendVerification(User $user): bool
    {
        if ($user->isEmailVerified()) {
            return false;
        }

        if ($user->failed_verification_attempts >= 5) {
            return false;
        }

        if ($user->email_verification_sent_at && 
            $user->email_verification_sent_at->diffInMinutes(now()) < 20) {
            return false;
        }

        return true;
    }
} 