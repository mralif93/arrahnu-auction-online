<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AuditLog extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'audit_logs';

    /**
     * Indicates if the model should be timestamped.
     * We use 'timestamp' field instead of created_at/updated_at.
     */
    public $timestamps = false;

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
        'user_id',
        'action_type',
        'module_affected',
        'record_id_affected',
        'old_data',
        'new_data',
        'description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'timestamp' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Action type constants
     */
    const ACTION_CREATE = 'CREATE';
    const ACTION_UPDATE = 'UPDATE';
    const ACTION_DELETE = 'DELETE';
    const ACTION_APPROVE = 'APPROVE';
    const ACTION_REJECT = 'REJECT';
    const ACTION_VIEW = 'VIEW';
    const ACTION_LOGIN = 'LOGIN';
    const ACTION_LOGOUT = 'LOGOUT';
    const ACTION_BID = 'BID';
    const ACTION_AUCTION_START = 'AUCTION_START';
    const ACTION_AUCTION_END = 'AUCTION_END';

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the affected model instance.
     */
    public function getAffectedModel()
    {
        $modelClass = 'App\\Models\\' . $this->module_affected;

        if (class_exists($modelClass)) {
            return $modelClass::find($this->record_id_affected);
        }

        return null;
    }

    /**
     * Scope to filter by action type.
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action_type', $action);
    }

    /**
     * Scope to filter by module.
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module_affected', $module);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted action description.
     */
    public function getFormattedActionAttribute(): string
    {
        $actions = [
            self::ACTION_CREATE => 'Created',
            self::ACTION_UPDATE => 'Updated',
            self::ACTION_DELETE => 'Deleted',
            self::ACTION_APPROVE => 'Approved',
            self::ACTION_REJECT => 'Rejected',
            self::ACTION_VIEW => 'Viewed',
        ];

        return $actions[$this->action_type] ?? $this->action_type;
    }

    /**
     * Get changes summary for display.
     */
    public function getChangesSummaryAttribute(): array
    {
        if (!$this->old_data || !$this->new_data) {
            return [];
        }

        $changes = [];
        foreach ($this->new_data as $field => $newValue) {
            $oldValue = $this->old_data[$field] ?? null;
            if ($oldValue !== $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    /**
     * Create audit log entry.
     */
    public static function logAction(
        string $actionType,
        string $module,
        ?string $recordId = null,
        ?array $oldData = null,
        ?array $newData = null,
        ?string $description = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action_type' => $actionType,
            'module_affected' => $module,
            'record_id_affected' => $recordId,
            'old_data' => $oldData,
            'new_data' => $newData,
            'description' => $description,
            'timestamp' => now(),
        ]);
    }
}
