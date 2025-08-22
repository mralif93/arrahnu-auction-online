<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasAuditTrail;

class AuctionResult extends Model
{
    use HasFactory, HasUuids, HasAuditTrail;

    /**
     * The table associated with the model.
     */
    protected $table = 'auction_results';

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
        'winner_user_id',
        'winning_bid_amount',
        'winning_bid_id',
        'auction_end_time',
        'payment_status',
        'delivery_status',
        'result_status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'winning_bid_amount' => 'decimal:2',
        'auction_end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Payment status constants
     */
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_CANCELLED = 'cancelled';
    const PAYMENT_REFUNDED = 'refunded';

    /**
     * Delivery status constants
     */
    const DELIVERY_PENDING = 'pending';
    const DELIVERY_SHIPPED = 'shipped';
    const DELIVERY_DELIVERED = 'delivered';
    const DELIVERY_COLLECTED = 'collected';
    const DELIVERY_RETURNED = 'returned';

    /**
     * Result status constants
     */
    const RESULT_COMPLETED = 'completed';
    const RESULT_FAILED_PAYMENT = 'failed_payment';
    const RESULT_CANCELLED = 'cancelled';

    /**
     * Get the collateral that was auctioned.
     */
    public function collateral(): BelongsTo
    {
        return $this->belongsTo(Collateral::class, 'collateral_id');
    }

    /**
     * Get the winner user.
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    /**
     * Get the winning bid for this auction result.
     */
    public function winningBid(): BelongsTo
    {
        return $this->belongsTo(Bid::class, 'winning_bid_id');
    }

    /**
     * Check if payment is completed.
     */
    public function isPaymentCompleted(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Check if delivery is completed.
     */
    public function isDeliveryCompleted(): bool
    {
        return in_array($this->delivery_status, [
            self::DELIVERY_DELIVERED,
            self::DELIVERY_COLLECTED
        ]);
    }

    /**
     * Check if the auction result is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->result_status === self::RESULT_COMPLETED &&
               $this->isPaymentCompleted();
    }

    /**
     * Scope for completed results.
     */
    public function scopeCompleted($query)
    {
        return $query->where('result_status', self::RESULT_COMPLETED);
    }

    /**
     * Scope for paid results.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    /**
     * Scope for delivered results.
     */
    public function scopeDelivered($query)
    {
        return $query->whereIn('delivery_status', [
            self::DELIVERY_DELIVERED,
            self::DELIVERY_COLLECTED
        ]);
    }
}
