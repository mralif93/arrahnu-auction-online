<?php

namespace App\Services;

use App\Models\User;
use App\Models\TwoFactorCode;
use App\Notifications\TwoFactorCodeNotification;

class TwoFactorService
{
    /**
     * Generate and send 2FA code to user's email.
     */
    public function generateAndSendCode(User $user): bool
    {
        // Clear any existing codes for this user
        $this->clearVerification($user);

        // Generate 6-digit code
        $code = $this->generateCode();

        // Calculate expiry time
        $expirySeconds = (int) config('auth.two_factor.code_expiry', 300);
        $expiresAt = now()->addSeconds($expirySeconds);

        // Store code in database
        try {
            TwoFactorCode::create([
                'user_id' => $user->id,
                'code' => $code,
                'attempts' => 0,
                'expires_at' => $expiresAt,
            ]);
        } catch (\Exception $e) {
            return false;
        }

        // Send notification
        try {
            $user->notify(new TwoFactorCodeNotification($code));
            return true;
        } catch (\Exception $e) {
            // Clean up the database record if email failed
            TwoFactorCode::where('user_id', $user->id)->delete();
            return false;
        }
    }
    
    /**
     * Verify 2FA code.
     */
    public function verifyCode(User $user, string $inputCode): array
    {
        $twoFactorCode = TwoFactorCode::forUser($user->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$twoFactorCode) {
            return [
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.',
                'expired' => true
            ];
        }

        // Check if expired
        if ($twoFactorCode->isExpired()) {
            $twoFactorCode->delete();
            return [
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.',
                'expired' => true
            ];
        }

        // Check max attempts
        if ($twoFactorCode->maxAttemptsReached()) {
            $twoFactorCode->delete();
            return [
                'success' => false,
                'message' => 'Too many failed attempts. Please request a new code.',
                'max_attempts_reached' => true
            ];
        }

        // Verify code
        if ($twoFactorCode->code === $inputCode) {
            $twoFactorCode->delete();
            return [
                'success' => true,
                'message' => 'Code verified successfully.'
            ];
        }

        // Increment attempts
        $twoFactorCode->incrementAttempts();
        $remainingAttempts = $twoFactorCode->getRemainingAttempts();

        return [
            'success' => false,
            'message' => "Invalid code. You have {$remainingAttempts} attempts remaining.",
            'remaining_attempts' => $remainingAttempts
        ];
    }
    
    /**
     * Check if user has pending 2FA verification.
     */
    public function hasPendingVerification(User $user): bool
    {
        return TwoFactorCode::forUser($user->id)->active()->exists();
    }

    /**
     * Clear 2FA verification for user.
     */
    public function clearVerification(User $user): void
    {
        TwoFactorCode::where('user_id', $user->id)->delete();
    }

    /**
     * Get remaining time for 2FA code.
     */
    public function getRemainingTime(User $user): ?int
    {
        $twoFactorCode = TwoFactorCode::forUser($user->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$twoFactorCode) {
            return null;
        }

        return $twoFactorCode->getRemainingTime();
    }

    /**
     * Generate 6-digit verification code.
     */
    private function generateCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if 2FA is enabled.
     */
    public function isEnabled(): bool
    {
        return config('auth.two_factor.enabled', false);
    }
}
