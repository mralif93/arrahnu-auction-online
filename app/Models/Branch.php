<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasAuditTrail;

class Branch extends Model
{
    use HasFactory, HasUuids, HasAuditTrail;

    /**
     * The table associated with the model.
     */
    protected $table = 'branches';

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
        'name',
        'branch_address_id',
        'phone_number',
        'status',
        'created_by_user_id',
        'approved_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
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
    const STATUS_INACTIVE = 'inactive';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get the user who created this branch.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who created this branch (alias).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who approved this branch.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Get the address for this branch.
     */
    public function branchAddress(): BelongsTo
    {
        return $this->belongsTo(BranchAddress::class, 'branch_address_id');
    }

    /**
     * Get the address for this branch (alias).
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(BranchAddress::class, 'branch_address_id');
    }

    /**
     * Get the accounts for this branch.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'branch_id');
    }

    /**
     * Scope a query to only include active branches.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include pending approval branches.
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', self::STATUS_PENDING_APPROVAL);
    }

    /**
     * Check if the branch is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if the branch is pending approval.
     */
    public function isPendingApproval(): bool
    {
        return $this->status === self::STATUS_PENDING_APPROVAL;
    }

    /**
     * Approve the branch.
     */
    public function approve(User $approver): bool
    {
        if ($this->status !== self::STATUS_PENDING_APPROVAL) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'approved_by_user_id' => $approver->id,
        ]);

        return true;
    }

    /**
     * Reject the branch.
     */
    public function reject(User $approver): bool
    {
        if ($this->status !== self::STATUS_PENDING_APPROVAL) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by_user_id' => $approver->id,
        ]);

        return true;
    }
}
