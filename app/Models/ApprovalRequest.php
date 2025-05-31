<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApprovalRequest extends Model
{
    use HasFactory;

    /**
     * Disable updated_at timestamp
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'requestable_type',
        'requestable_id',
        'maker_id',
        'checker_id',
        'action_type',
        'status',
        'request_data',
        'rejection_reason',
        'maker_notes',
        'checker_notes',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'request_data' => 'array',
        'created_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Action type constants
     */
    const ACTION_CREATE = 'CREATE';
    const ACTION_UPDATE = 'UPDATE';
    const ACTION_DELETE = 'DELETE';

    /**
     * Get the requestable model (polymorphic relationship).
     */
    public function requestable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the maker user.
     */
    public function maker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'maker_id');
    }

    /**
     * Get the checker user.
     */
    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    /**
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for specific checker.
     */
    public function scopeForChecker($query, int $checkerId)
    {
        return $query->where('checker_id', $checkerId);
    }

    /**
     * Scope for specific maker.
     */
    public function scopeByMaker($query, int $makerId)
    {
        return $query->where('maker_id', $makerId);
    }

    /**
     * Check if request is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if request is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approve the request.
     */
    public function approve(User $checker, ?string $notes = null): bool
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'checker_id' => $checker->id,
            'checker_notes' => $notes,
            'processed_at' => now(),
        ]);

        // Log the approval
        AuditLog::logAction(
            AuditLog::ACTION_APPROVE,
            'ApprovalRequest',
            $this->id,
            ['status' => self::STATUS_PENDING],
            ['status' => self::STATUS_APPROVED, 'checker_id' => $checker->id],
            "Approval request #{$this->id} approved by {$checker->name}"
        );

        return true;
    }

    /**
     * Reject the request.
     */
    public function reject(User $checker, string $reason, ?string $notes = null): bool
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'checker_id' => $checker->id,
            'rejection_reason' => $reason,
            'checker_notes' => $notes,
            'processed_at' => now(),
        ]);

        // Log the rejection
        AuditLog::logAction(
            AuditLog::ACTION_REJECT,
            'ApprovalRequest',
            $this->id,
            ['status' => self::STATUS_PENDING],
            ['status' => self::STATUS_REJECTED, 'checker_id' => $checker->id],
            "Approval request #{$this->id} rejected by {$checker->name}: {$reason}"
        );

        return true;
    }

    /**
     * Get formatted action type.
     */
    public function getFormattedActionAttribute(): string
    {
        $actions = [
            self::ACTION_CREATE => 'Create',
            self::ACTION_UPDATE => 'Update',
            self::ACTION_DELETE => 'Delete',
        ];

        return $actions[$this->action_type] ?? $this->action_type;
    }

    /**
     * Get formatted status.
     */
    public function getFormattedStatusAttribute(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Create approval request.
     */
    public static function createRequest(
        Model $model,
        string $actionType,
        array $requestData,
        User $maker,
        ?string $notes = null
    ): self {
        return self::create([
            'requestable_type' => get_class($model),
            'requestable_id' => $model->id,
            'maker_id' => $maker->id,
            'action_type' => $actionType,
            'request_data' => $requestData,
            'maker_notes' => $notes,
        ]);
    }
}
