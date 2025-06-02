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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username', 50)->unique();
            $table->string('password_hash', 255);
            $table->string('email', 100)->unique();
            $table->boolean('is_email_verified')->default(false);
            $table->string('email_verification_token', 255)->nullable();
            $table->timestampTz('email_verification_sent_at')->nullable();
            $table->string('full_name', 100)->nullable();
            $table->string('phone_number', 20)->unique()->nullable();
            $table->boolean('is_phone_verified')->default(false);
            $table->string('phone_verification_code', 10)->nullable();
            $table->timestampTz('phone_verification_sent_at')->nullable();
            $table->enum('role', ['maker', 'checker', 'bidder']);
            $table->enum('status', ['draft', 'pending_approval', 'active', 'inactive', 'rejected'])->default('draft');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_staff')->default(false);
            $table->uuid('primary_address_id')->nullable();
            $table->string('locked_device_identifier', 255)->unique()->nullable();
            $table->timestampTz('first_login_at')->nullable();
            $table->timestampTz('last_login_at')->nullable();
            $table->string('reset_password_token', 255)->unique()->nullable();
            $table->timestampTz('reset_password_expires_at')->nullable();
            $table->uuid('created_by_user_id')->nullable();
            $table->uuid('approved_by_user_id')->nullable();
            $table->timestampsTz();

            // Performance indexes
            $table->index('email_verification_token', 'idx_users_email_verification_token');
            $table->index('phone_verification_code', 'idx_users_phone_verification_code');
            $table->index('locked_device_identifier', 'idx_users_locked_device_identifier');
            $table->index('reset_password_token', 'idx_users_reset_password_token');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('session_token', 255)->unique();
            $table->timestampTz('expires_at');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestampsTz();

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Performance indexes
            $table->index('user_id', 'idx_sessions_user_id');
            $table->index('expires_at', 'idx_sessions_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
