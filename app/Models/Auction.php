<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'auctions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'auction_title',
        'description',
        'start_datetime',
        'end_datetime',
        'status',
        'created_by_user_id',
        'approved_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status constants for the auction.
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_APPROVAL = 'pending_approval';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get all possible status values.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING_APPROVAL => 'Pending Approval',
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }



    /**
     * Get the user who created this auction.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who created this auction (alias).
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who approved this auction.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Get all collaterals in this auction.
     */
    public function collaterals(): HasMany
    {
        return $this->hasMany(Collateral::class, 'auction_id');
    }

    /**
     * Get all bids for this auction (through collaterals).
     */
    public function bids()
    {
        return Bid::whereIn('collateral_id', $this->collaterals()->pluck('id'));
    }

    /**
     * Get the total number of bids in this auction.
     */
    public function getTotalBidsAttribute(): int
    {
        return $this->bids()->count();
    }

    /**
     * Get the total estimated value of all collaterals in this auction.
     */
    public function getTotalEstimatedValueAttribute(): float
    {
        return $this->collaterals()->sum('estimated_value_rm') ?? 0;
    }

    /**
     * Get the total current bid value of all collaterals in this auction.
     */
    public function getTotalCurrentBidValueAttribute(): float
    {
        return $this->collaterals()->sum('current_highest_bid_rm') ?? 0;
    }

    /**
     * Get the number of sold items in this auction.
     */
    public function getSoldItemsCountAttribute(): int
    {
        return $this->collaterals()->where('status', 'sold')->count();
    }

    /**
     * Get the number of unsold items in this auction.
     */
    public function getUnsoldItemsCountAttribute(): int
    {
        return $this->collaterals()->where('status', 'unsold')->count();
    }

    /**
     * Check if the auction is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && 
               now()->between($this->start_datetime, $this->end_datetime);
    }

    /**
     * Check if the auction is scheduled to start.
     */
    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED && 
               now()->lt($this->start_datetime);
    }

    /**
     * Check if the auction has ended.
     */
    public function hasEnded(): bool
    {
        return $this->status === self::STATUS_COMPLETED || 
               now()->gt($this->end_datetime);
    }

    /**
     * Get the time remaining until auction starts (if scheduled).
     */
    public function getTimeUntilStartAttribute(): ?int
    {
        if ($this->isScheduled()) {
            return $this->start_datetime->diffInSeconds(now());
        }
        return null;
    }

    /**
     * Get the time remaining until auction ends (if active).
     */
    public function getTimeRemainingAttribute(): ?int
    {
        if ($this->isActive()) {
            return $this->end_datetime->diffInSeconds(now());
        }
        return null;
    }

    /**
     * Scope to get active auctions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('start_datetime', '<=', now())
                    ->where('end_datetime', '>', now());
    }

    /**
     * Scope to get scheduled auctions.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
                    ->where('start_datetime', '>', now());
    }

    /**
     * Scope to get completed auctions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Start the auction (change status to active).
     */
    public function start(): bool
    {
        if ($this->status === self::STATUS_SCHEDULED && now()->gte($this->start_datetime)) {
            $this->update(['status' => self::STATUS_ACTIVE]);
            
            // Update all collaterals in this auction to 'auctioning' status
            $this->collaterals()->where('status', 'ready_for_auction')
                               ->update(['status' => 'auctioning']);
            
            return true;
        }
        return false;
    }

    /**
     * Complete the auction (change status to completed).
     */
    public function complete(): bool
    {
        if ($this->status === self::STATUS_ACTIVE) {
            $this->update(['status' => self::STATUS_COMPLETED]);
            
            // Process all collaterals in this auction
            foreach ($this->collaterals()->where('status', 'auctioning')->get() as $collateral) {
                $highestBid = $collateral->bids()->orderBy('bid_amount_rm', 'desc')->first();
                
                if ($highestBid) {
                    $collateral->update([
                        'status' => 'sold',
                        'highest_bidder_user_id' => $highestBid->user_id,
                        'current_highest_bid_rm' => $highestBid->bid_amount_rm,
                    ]);
                    
                    // Create auction result
                    AuctionResult::create([
                        'collateral_id' => $collateral->id,
                        'winner_user_id' => $highestBid->user_id,
                        'winning_bid_amount' => $highestBid->bid_amount_rm,
                        'winning_bid_id' => $highestBid->id,
                        'auction_end_time' => $this->end_datetime,
                        'payment_status' => 'pending',
                        'delivery_status' => 'pending',
                        'result_status' => 'completed',
                    ]);
                } else {
                    $collateral->update(['status' => 'unsold']);
                }
            }
            
            return true;
        }
        return false;
    }

    /**
     * Cancel the auction.
     */
    public function cancel(): bool
    {
        if (in_array($this->status, [self::STATUS_SCHEDULED, self::STATUS_ACTIVE])) {
            $this->update(['status' => self::STATUS_CANCELLED]);
            
            // Update all collaterals in this auction
            $this->collaterals()->whereIn('status', ['ready_for_auction', 'auctioning'])
                               ->update(['status' => 'active']);
            
            return true;
        }
        return false;
    }
}
