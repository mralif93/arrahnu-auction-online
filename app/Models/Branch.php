<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'email',
        'manager_id',
        'is_active',
        'description',
        'latitude',
        'longitude',
        'operating_hours',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'operating_hours' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the manager for this branch
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the full address as a string
     */
    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->postal_code}, {$this->country}";
    }

    /**
     * Check if branch is currently open
     */
    public function isOpen(): bool
    {
        if (!$this->operating_hours) {
            return false;
        }

        $currentDay = strtolower(now()->format('l'));
        $currentTime = now()->format('H:i');

        if (!isset($this->operating_hours[$currentDay])) {
            return false;
        }

        $hours = $this->operating_hours[$currentDay];

        if ($hours['closed'] ?? false) {
            return false;
        }

        return $currentTime >= $hours['open'] && $currentTime <= $hours['close'];
    }

    /**
     * Scope for active branches
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for branches in a specific city
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }
}
