<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasAuditTrail
{
    /**
     * Boot the trait.
     */
    protected static function bootHasAuditTrail()
    {
        static::created(function ($model) {
            $model->logAuditAction(AuditLog::ACTION_CREATE, null, $model->toArray());
        });

        static::updated(function ($model) {
            $model->logAuditAction(
                AuditLog::ACTION_UPDATE,
                $model->getOriginal(),
                $model->getChanges()
            );
        });

        static::deleted(function ($model) {
            $model->logAuditAction(AuditLog::ACTION_DELETE, $model->toArray(), null);
        });
    }

    /**
     * Get all audit logs for this model.
     */
    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable', 'module_affected', 'record_id_affected')
                    ->where('module_affected', class_basename($this))
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Log an audit action.
     */
    public function logAuditAction(
        string $actionType,
        ?array $oldData = null,
        ?array $newData = null,
        ?string $description = null
    ): AuditLog {
        return AuditLog::logAction(
            $actionType,
            class_basename($this),
            (string) $this->id,
            $oldData,
            $newData,
            $description ?? $this->getAuditDescription($actionType)
        );
    }

    /**
     * Get audit description for the action.
     */
    protected function getAuditDescription(string $actionType): string
    {
        $modelName = class_basename($this);
        $identifier = $this->getAuditIdentifier();

        switch ($actionType) {
            case AuditLog::ACTION_CREATE:
                return "{$modelName} '{$identifier}' was created";
            case AuditLog::ACTION_UPDATE:
                return "{$modelName} '{$identifier}' was updated";
            case AuditLog::ACTION_DELETE:
                return "{$modelName} '{$identifier}' was deleted";
            case AuditLog::ACTION_APPROVE:
                return "{$modelName} '{$identifier}' was approved";
            case AuditLog::ACTION_REJECT:
                return "{$modelName} '{$identifier}' was rejected";
            case AuditLog::ACTION_VIEW:
                return "{$modelName} '{$identifier}' was viewed";
            default:
                return "{$modelName} '{$identifier}' - {$actionType}";
        }
    }

    /**
     * Get identifier for audit logs.
     * Override this method in models to provide meaningful identifiers.
     */
    protected function getAuditIdentifier(): string
    {
        // Try common identifier fields
        if (isset($this->name)) {
            return $this->name;
        }

        if (isset($this->title)) {
            return $this->title;
        }

        if (isset($this->account_number)) {
            return $this->account_number;
        }

        if (isset($this->item_name)) {
            return $this->item_name;
        }

        if (isset($this->email)) {
            return $this->email;
        }

        return "ID: {$this->id}";
    }

    /**
     * Log a view action (for sensitive data access).
     */
    public function logViewAction(?string $description = null): AuditLog
    {
        return $this->logAuditAction(
            AuditLog::ACTION_VIEW,
            null,
            null,
            $description ?? "Viewed {$this->getAuditIdentifier()}"
        );
    }

    /**
     * Log approval action.
     */
    public function logApprovalAction(string $approverName, ?string $notes = null): AuditLog
    {
        $description = "Approved by {$approverName}";
        if ($notes) {
            $description .= " - Notes: {$notes}";
        }

        return $this->logAuditAction(
            AuditLog::ACTION_APPROVE,
            ['status' => $this->getOriginal('status')],
            ['status' => $this->status],
            $description
        );
    }

    /**
     * Log rejection action.
     */
    public function logRejectionAction(string $rejectorName, string $reason, ?string $notes = null): AuditLog
    {
        $description = "Rejected by {$rejectorName} - Reason: {$reason}";
        if ($notes) {
            $description .= " - Notes: {$notes}";
        }

        return $this->logAuditAction(
            AuditLog::ACTION_REJECT,
            ['status' => $this->getOriginal('status')],
            ['status' => $this->status],
            $description
        );
    }

    /**
     * Get recent audit activity.
     */
    public function getRecentAuditActivity(int $limit = 10)
    {
        return AuditLog::where('module_affected', class_basename($this))
                      ->where('record_id_affected', (string) $this->id)
                      ->with('user')
                      ->orderBy('created_at', 'desc')
                      ->limit($limit)
                      ->get();
    }

    /**
     * Check if model has been approved.
     */
    public function isApproved(): bool
    {
        return isset($this->status) && $this->status === 'active';
    }

    /**
     * Check if model is pending approval.
     */
    public function isPendingApproval(): bool
    {
        return isset($this->status) && $this->status === 'pending_approval';
    }

    /**
     * Check if model is rejected.
     */
    public function isRejected(): bool
    {
        return isset($this->status) && $this->status === 'rejected';
    }
}
