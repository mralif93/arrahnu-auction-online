<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\HasAuditTrail;

class Collateral extends Model
{
    use HasFactory, HasUuids, HasAuditTrail;

    /**
     * The table associated with the model.
     */
    protected $table = 'collaterals';

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
        'account_id',
        'auction_id',
        'item_type',
        'description',
        'weight_grams',
        'purity',
        'estimated_value_rm',
        'starting_bid_rm',
        'current_highest_bid_rm',
        'highest_bidder_user_id',
        'status',
        'created_by_user_id',
        'approved_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'weight_grams' => 'decimal:2',
        'estimated_value_rm' => 'decimal:2',
        'starting_bid_rm' => 'decimal:2',
        'current_highest_bid_rm' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_APPROVAL = 'pending_approval';
    const STATUS_ACTIVE = 'active';
    const STATUS_READY_FOR_AUCTION = 'ready_for_auction';
    const STATUS_AUCTIONING = 'auctioning';
    const STATUS_SOLD = 'sold';
    const STATUS_UNSOLD = 'unsold';
    const STATUS_RETURNED = 'returned';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get the account that owns the collateral.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the auction this collateral belongs to.
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    /**
     * Get the highest bidder for this collateral.
     */
    public function highestBidder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'highest_bidder_user_id');
    }

    /**
     * Check if collateral is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if collateral is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status === 'active';
    }

    /**
     * Get the loan-to-value ratio.
     */
    public function getLoanToValueRatioAttribute(): float
    {
        if ($this->estimated_value > 0) {
            return ($this->loan_amount / $this->estimated_value) * 100;
        }
        return 0;
    }

    /**
     * Generate collateral number.
     */
    public static function generateCollateralNumber(int $accountId): string
    {
        $account = Account::find($accountId);
        $accountNumber = $account ? $account->account_number : 'UNKNOWN';
        $sequence = str_pad(static::where('account_id', $accountId)->count() + 1, 3, '0', STR_PAD_LEFT);

        return "{$accountNumber}-C{$sequence}";
    }

    /**
     * Get the primary image.
     */
    public function getPrimaryImageAttribute(): ?string
    {
        $images = $this->images ?? [];
        return !empty($images) ? $images[0] : null;
    }

    /**
     * Get condition badge color.
     */
    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'excellent' => 'green',
            'good' => 'blue',
            'fair' => 'yellow',
            'poor' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'redeemed' => 'blue',
            'sold' => 'purple',
            'lost' => 'red',
            'damaged' => 'orange',
            default => 'gray'
        };
    }

    /**
     * Get the bids for this collateral.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'collateral_id');
    }

    /**
     * Get the auction result for this collateral.
     */
    public function auctionResult(): HasOne
    {
        return $this->hasOne(AuctionResult::class, 'collateral_id');
    }

    /**
     * Get the images for this collateral.
     */
    public function images(): HasMany
    {
        return $this->hasMany(CollateralImage::class, 'collateral_id');
    }

    /**
     * Get the user who created this collateral.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who approved this collateral.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

}
