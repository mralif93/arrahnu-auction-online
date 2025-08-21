<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Login source tracking
            $table->timestampTz('last_api_login_at')->nullable()->after('last_login_at');
            $table->timestampTz('last_web_login_at')->nullable()->after('last_api_login_at');
            $table->string('last_login_source', 20)->nullable()->after('last_web_login_at'); // 'api', 'web'
            $table->string('last_login_ip', 45)->nullable()->after('last_login_source');
            $table->text('last_login_user_agent')->nullable()->after('last_login_ip');
            
            // Enhanced verification workflow
            // Check if email_verification_sent_at exists, if not add it after email
            if (!Schema::hasColumn('users', 'email_verification_sent_at')) {
                $table->timestampTz('email_verification_sent_at')->nullable()->after('email');
            }
            
            // Add email_verified_at after email_verification_sent_at (or email if it doesn't exist)
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestampTz('email_verified_at')->nullable()->after('email_verification_sent_at');
            }
            
            $table->boolean('requires_admin_approval')->default(true)->after('email_verified_at');
            $table->timestampTz('approved_at')->nullable()->after('approved_by_user_id');
            $table->timestampTz('rejected_at')->nullable()->after('approved_at');
            $table->text('approval_notes')->nullable()->after('rejected_at');
            
            // Registration workflow tracking
            $table->enum('registration_source', ['web', 'api', 'admin'])->default('web')->after('approval_notes');
            $table->boolean('email_verification_required')->default(true)->after('registration_source');
            $table->integer('login_attempts')->default(0)->after('email_verification_required');
            $table->timestampTz('last_login_attempt_at')->nullable()->after('login_attempts');
            $table->timestampTz('account_locked_until')->nullable()->after('last_login_attempt_at');
            
            // Additional security fields
            $table->integer('failed_verification_attempts')->default(0)->after('account_locked_until');
            $table->timestampTz('verification_token_expires_at')->nullable()->after('failed_verification_attempts');
            
            // Indexes for performance
            $table->index('last_login_source', 'idx_users_last_login_source');
            $table->index('email_verified_at', 'idx_users_email_verified_at');
            $table->index('requires_admin_approval', 'idx_users_requires_admin_approval');
            $table->index('registration_source', 'idx_users_registration_source');
            $table->index('account_locked_until', 'idx_users_account_locked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove indexes first
            $table->dropIndex('idx_users_last_login_source');
            $table->dropIndex('idx_users_email_verified_at');
            $table->dropIndex('idx_users_requires_admin_approval');
            $table->dropIndex('idx_users_registration_source');
            $table->dropIndex('idx_users_account_locked_until');
            
            // Remove columns
            $table->dropColumn([
                'last_api_login_at',
                'last_web_login_at',
                'last_login_source',
                'last_login_ip',
                'last_login_user_agent',
                'email_verified_at',
                'requires_admin_approval',
                'approved_at',
                'rejected_at',
                'approval_notes',
                'registration_source',
                'email_verification_required',
                'login_attempts',
                'last_login_attempt_at',
                'account_locked_until',
                'failed_verification_attempts',
                'verification_token_expires_at',
            ]);
        });
    }
}; 