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
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasAuditTrail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasAuditTrail, HasUuids;

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
        'email_verified_at',
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
        'last_api_login_at',
        'last_web_login_at',
        'last_login_source',
        'last_login_ip',
        'last_login_user_agent',
        'reset_password_token',
        'reset_password_expires_at',
        'created_by_user_id',
        'approved_by_user_id',
        'approved_at',
        'rejected_at',
        'approval_notes',
        'requires_admin_approval',
        'registration_source',
        'email_verification_required',
        'login_attempts',
        'last_login_attempt_at',
        'account_locked_until',
        'failed_verification_attempts',
        'verification_token_expires_at',
        'avatar_path',
        'preferences',
    ];

    /**
     * Get all available roles.
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_MAKER => 'Maker',
            self::ROLE_CHECKER => 'Checker',
            self::ROLE_BIDDER => 'Bidder',
        ];
    }

    /**
     * Get all available statuses.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING_APPROVAL => 'Pending Approval',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

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
            'email_verified_at' => 'datetime',
            'is_phone_verified' => 'boolean',
            'phone_verification_sent_at' => 'datetime',
            'is_admin' => 'boolean',
            'is_staff' => 'boolean',
            'first_login_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_api_login_at' => 'datetime',
            'last_web_login_at' => 'datetime',
            'last_login_attempt_at' => 'datetime',
            'account_locked_until' => 'datetime',
            'reset_password_expires_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'verification_token_expires_at' => 'datetime',
            'requires_admin_approval' => 'boolean',
            'email_verification_required' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'preferences' => 'array',
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
     * Login source constants
     */
    const LOGIN_SOURCE_API = 'api';
    const LOGIN_SOURCE_WEB = 'web';
    
    /**
     * Registration source constants
     */
    const REGISTRATION_SOURCE_API = 'api';
    const REGISTRATION_SOURCE_WEB = 'web';
    const REGISTRATION_SOURCE_ADMIN = 'admin';

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
     * Check if user is email verified.
     */
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Check if user requires email verification.
     */
    public function requiresEmailVerification(): bool
    {
        return $this->email_verification_required && !$this->isEmailVerified();
    }

    /**
     * Check if user requires admin approval.
     */
    public function requiresAdminApproval(): bool
    {
        return $this->requires_admin_approval && $this->approved_at === null;
    }

    /**
     * Check if user is approved by admin.
     */
    public function isApprovedByAdmin(): bool
    {
        return $this->approved_at !== null;
    }

    /**
     * Check if user is rejected by admin.
     */
    public function isRejectedByAdmin(): bool
    {
        return $this->rejected_at !== null;
    }

    /**
     * Check if user account is locked.
     */
    public function isAccountLocked(): bool
    {
        return $this->account_locked_until !== null && $this->account_locked_until->isFuture();
    }

    /**
     * Check if user can login.
     */
    public function canLogin(): bool
    {
        // Check if account is locked
        if ($this->isAccountLocked()) {
            return false;
        }

        // Check if email verification is required and not completed
        if ($this->requiresEmailVerification()) {
            return false;
        }

        // Check if admin approval is required and not completed
        if ($this->requiresAdminApproval()) {
            return false;
        }

        // Check if account is active
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        return true;
    }

    /**
     * Update login tracking information.
     */
    public function updateLoginTracking(string $source, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $now = now();
        
        $this->update([
            'last_login_at' => $now,
            'last_login_source' => $source,
            'last_login_ip' => $ipAddress,
            'last_login_user_agent' => $userAgent,
            'login_attempts' => 0, // Reset failed attempts on successful login
            'last_login_attempt_at' => $now,
        ]);

        // Update specific login source timestamp
        if ($source === self::LOGIN_SOURCE_API) {
            $this->update(['last_api_login_at' => $now]);
        } elseif ($source === self::LOGIN_SOURCE_WEB) {
            $this->update(['last_web_login_at' => $now]);
        }
    }

    /**
     * Increment failed login attempts.
     */
    public function incrementLoginAttempts(): void
    {
        $attempts = $this->login_attempts + 1;
        $updateData = [
            'login_attempts' => $attempts,
            'last_login_attempt_at' => now(),
        ];

        // Lock account after 5 failed attempts for 30 minutes
        if ($attempts >= 5) {
            $updateData['account_locked_until'] = now()->addMinutes(30);
        }

        $this->update($updateData);
    }

    /**
     * Mark email as verified.
     */
    public function markEmailAsVerified(): void
    {
        $this->update([
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'email_verification_token' => null,
            'verification_token_expires_at' => null,
            'failed_verification_attempts' => 0,
        ]);
    }

    /**
     * Approve user account.
     */
    public function approveAccount(User $approvedBy, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'approved_at' => now(),
            'requires_admin_approval' => false,
            'approved_by_user_id' => $approvedBy->id,
            'approval_notes' => $notes,
            'rejected_at' => null, // Clear any previous rejection
        ]);
    }

    /**
     * Reject user account.
     */
    public function rejectAccount(User $rejectedBy, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
            'approved_by_user_id' => $rejectedBy->id, // Track who rejected
            'approval_notes' => $notes,
            'approved_at' => null, // Clear any previous approval
        ]);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }
        
        // Return default avatar or gravatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name ?? $this->username) . '&color=ffffff&background=f53003&size=200';
    }

    /**
     * Get user's initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->full_name ?? $this->username;
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Get user preferences with defaults.
     */
    public function getPreferencesAttribute($value): array
    {
        $defaults = [
            'email_notifications' => true,
            'sms_notifications' => false,
            'marketing_emails' => false,
            'timezone' => 'UTC',
            'language' => 'en',
        ];

        if (!$value) {
            return $defaults;
        }

        $preferences = is_string($value) ? json_decode($value, true) : $value;
        return array_merge($defaults, $preferences ?? []);
    }

    /**
     * Get user's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?? $this->username;
    }

    /**
     * Check if user has completed their profile.
     */
    public function hasCompleteProfile(): bool
    {
        return !empty($this->full_name) && 
               !empty($this->email) && 
               $this->is_email_verified;
    }

    /**
     * Get profile completion percentage.
     */
    public function getProfileCompletionAttribute(): int
    {
        $fields = [
            'full_name' => !empty($this->full_name),
            'email' => !empty($this->email),
            'phone_number' => !empty($this->phone_number),
            'is_email_verified' => $this->is_email_verified,
            'is_phone_verified' => $this->is_phone_verified,
            'avatar_path' => !empty($this->avatar_path),
        ];

        $completed = array_sum($fields);
        $total = count($fields);

        return round(($completed / $total) * 100);
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
