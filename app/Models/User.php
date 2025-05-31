<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasAuditTrail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasAuditTrail, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';

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
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'is_email_verified',
        'email_verification_token',
        'email_verification_sent_at',
        'full_name',
        'phone_number',
        'is_phone_verified',
        'phone_verification_code',
        'phone_verification_sent_at',
        'role',
        'status',
        'is_admin',
        'is_staff',
        'primary_address_id',
        'locked_device_identifier',
        'first_login_at',
        'last_login_at',
        'reset_password_token',
        'reset_password_expires_at',
        'created_by_user_id',
        'approved_by_user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
        'email_verification_token',
        'phone_verification_code',
        'reset_password_token',
        'locked_device_identifier',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_email_verified' => 'boolean',
            'email_verification_sent_at' => 'datetime',
            'is_phone_verified' => 'boolean',
            'phone_verification_sent_at' => 'datetime',
            'is_admin' => 'boolean',
            'is_staff' => 'boolean',
            'first_login_at' => 'datetime',
            'last_login_at' => 'datetime',
            'reset_password_expires_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Role constants
     */
    const ROLE_MAKER = 'maker';
    const ROLE_CHECKER = 'checker';
    const ROLE_BIDDER = 'bidder';

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_APPROVAL = 'pending_approval';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get the password attribute (for authentication).
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the name attribute (for backward compatibility).
     */
    public function getNameAttribute(): string
    {
        return $this->full_name ?? '';
    }

    /**
     * Set the password attribute (for backward compatibility).
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password_hash'] = $value;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if user is maker.
     */
    public function isMaker(): bool
    {
        return $this->role === self::ROLE_MAKER;
    }

    /**
     * Check if user is checker.
     */
    public function isChecker(): bool
    {
        return $this->role === self::ROLE_CHECKER;
    }

    /**
     * Check if user is bidder.
     */
    public function isBidder(): bool
    {
        return $this->role === self::ROLE_BIDDER;
    }

    /**
     * Check if user can approve a model.
     */
    public function canApprove($model): bool
    {
        // Admins can approve anything
        if ($this->isAdmin() && $this->status === self::STATUS_ACTIVE) {
            return true;
        }

        // Checkers can approve, but not their own creations
        return $this->isChecker() &&
               $this->status === self::STATUS_ACTIVE &&
               (!isset($model->created_by_user_id) || $this->id !== $model->created_by_user_id);
    }

    /**
     * Check if user can create/edit models.
     */
    public function canMake(): bool
    {
        return ($this->isMaker() || $this->isAdmin()) && $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }



    /**
     * Check if the user is a regular user (not admin).
     */
    public function isUser(): bool
    {
        return !$this->is_admin;
    }

    /**
     * Get the addresses for the user.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    /**
     * Get the primary address for the user.
     */
    public function primaryAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'primary_address_id');
    }

    /**
     * Get the user who created this user.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who created this user (alias for createdBy).
     */
    public function creator(): BelongsTo
    {
        return $this->createdBy();
    }

    /**
     * Get the user who approved this user.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Get the users created by this user.
     */
    public function createdUsers(): HasMany
    {
        return $this->hasMany(User::class, 'created_by_user_id');
    }

    /**
     * Get the users approved by this user.
     */
    public function approvedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'approved_by_user_id');
    }

    /**
     * Get the bids for the user.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'user_id');
    }

    /**
     * Get the collaterals where this user is the highest bidder.
     */
    public function winningCollaterals(): HasMany
    {
        return $this->hasMany(Collateral::class, 'highest_bidder_user_id');
    }

    /**
     * Get the branches created by this user.
     */
    public function createdBranches(): HasMany
    {
        return $this->hasMany(Branch::class, 'created_by_user_id');
    }

    /**
     * Get the accounts created by this user.
     */
    public function createdAccounts(): HasMany
    {
        return $this->hasMany(Account::class, 'created_by_user_id');
    }

    /**
     * Get the collaterals created by this user.
     */
    public function createdCollaterals(): HasMany
    {
        return $this->hasMany(Collateral::class, 'created_by_user_id');
    }

    /**
     * Get the audit logs for this user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Get the auction results where this user is the winner.
     */
    public function wonAuctions(): HasMany
    {
        return $this->hasMany(AuctionResult::class, 'winner_user_id');
    }
}
