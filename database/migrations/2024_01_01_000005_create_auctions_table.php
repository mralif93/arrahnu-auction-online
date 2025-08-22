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
        Schema::create('auctions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('auction_title', 255);
            $table->text('description')->nullable();
            $table->timestampTz('start_datetime');
            $table->timestampTz('end_datetime');
            $table->enum('status', ['draft', 'pending_approval', 'scheduled', 'active', 'completed', 'cancelled', 'rejected'])->default('scheduled');
            $table->uuid('created_by_user_id')->nullable();
            $table->uuid('approved_by_user_id')->nullable();
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');

            // Performance indexes
            $table->index('status', 'idx_auctions_status');
            $table->index(['start_datetime', 'end_datetime'], 'idx_auctions_datetime');
            $table->index('created_by_user_id', 'idx_auctions_created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
