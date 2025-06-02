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
        Schema::create('branches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->uuid('branch_address_id');
            $table->string('phone_number', 20)->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'active', 'inactive', 'rejected'])->default('draft');
            $table->uuid('created_by_user_id')->nullable();
            $table->uuid('approved_by_user_id')->nullable();
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('branch_address_id')->references('id')->on('branch_addresses')->onDelete('cascade');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');

            // Performance indexes
            $table->index('status', 'idx_branches_status');
            $table->index('created_by_user_id', 'idx_branches_created_by');
            $table->index('branch_address_id', 'idx_branches_branch_address_id');
        });

        // Add foreign key to branch_addresses.branch_id after branches table is created
        Schema::table('branch_addresses', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key from branch_addresses table first
        Schema::table('branch_addresses', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });

        Schema::dropIfExists('branches');
    }
};
