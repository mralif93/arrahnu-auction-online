<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_token',
        'remember',
        'expires_at',
    ];

    protected $casts = [
        'remember' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the 2FA session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the session has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Generate a secure session token.
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Scope to get active (non-expired) sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope to get sessions for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Clean up expired sessions.
     */
    public static function cleanupExpired(): int
    {
        return static::where('expires_at', '<=', now())->delete();
    }
} 