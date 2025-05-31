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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('action_type', 50);
            $table->string('module_affected', 50);
            $table->uuid('record_id_affected')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestampTz('timestamp')->useCurrent();
            $table->text('description')->nullable();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Performance indexes
            $table->index('user_id', 'idx_audit_logs_user_id');
            $table->index('module_affected', 'idx_audit_logs_module_affected');
            $table->index('record_id_affected', 'idx_audit_logs_record_id_affected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
