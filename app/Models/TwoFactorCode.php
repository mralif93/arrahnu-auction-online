<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'attempts',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the 2FA code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the code has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if max attempts reached.
     */
    public function maxAttemptsReached(): bool
    {
        $maxAttempts = config('auth.two_factor.max_attempts', 3);
        return $this->attempts >= $maxAttempts;
    }

    /**
     * Increment attempts.
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Get remaining attempts.
     */
    public function getRemainingAttempts(): int
    {
        $maxAttempts = config('auth.two_factor.max_attempts', 3);
        return max(0, $maxAttempts - $this->attempts);
    }

    /**
     * Get remaining time in seconds.
     */
    public function getRemainingTime(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return max(0, now()->diffInSeconds($this->expires_at, false));
    }

    /**
     * Scope to get active (non-expired) codes.
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope to get codes for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
