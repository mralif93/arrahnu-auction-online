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
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('address_line_1', 255);
            $table->string('address_line_2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postcode', 20);
            $table->string('country', 100)->default('Malaysia');
            $table->boolean('is_primary')->default(false);
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Performance indexes
            $table->index('user_id', 'idx_addresses_user_id');
        });

        // Add foreign key constraint for primary_address_id in users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('primary_address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints from users table first
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['primary_address_id']);
            $table->dropForeign(['created_by_user_id']);
            $table->dropForeign(['approved_by_user_id']);
        });

        Schema::dropIfExists('addresses');
    }
};
