<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasAuditTrail;

class Bid extends Model
{
    use HasFactory, HasUuids, HasAuditTrail;

    /**
     * The table associated with the model.
     */
    protected $table = 'bids';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * The data type of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'collateral_id',
        'user_id',
        'bid_amount_rm',
        'bid_time',
        'status',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'bid_amount_rm' => 'decimal:2',
        'bid_time' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'ip_address',
    ];

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_OUTBID = 'outbid';
    const STATUS_WINNING = 'winning';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_SUCCESSFUL = 'successful';
    const STATUS_UNSUCCESSFUL = 'unsuccessful';

    /**
     * Get the collateral that owns the bid.
     */
    public function collateral(): BelongsTo
    {
        return $this->belongsTo(Collateral::class, 'collateral_id');
    }

    /**
     * Get the user that owns the bid.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the bidder (alias for user).
     */
    public function bidder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include active bids.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include winning bids.
     */
    public function scopeWinning($query)
    {
        return $query->where('status', self::STATUS_WINNING);
    }

    /**
     * Scope a query to order by bid amount descending.
     */
    public function scopeHighestFirst($query)
    {
        return $query->orderBy('bid_amount_rm', 'desc');
    }

    /**
     * Scope a query to order by bid time.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('bid_time', 'desc');
    }

    /**
     * Check if the bid is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if the bid is winning.
     */
    public function isWinning(): bool
    {
        return $this->status === self::STATUS_WINNING;
    }

    /**
     * Mark the bid as outbid.
     */
    public function markAsOutbid(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $this->update(['status' => self::STATUS_OUTBID]);
        return true;
    }

    /**
     * Mark the bid as winning.
     */
    public function markAsWinning(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $this->update(['status' => self::STATUS_WINNING]);
        return true;
    }
}
