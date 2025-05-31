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
        Schema::create('bids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('collateral_id');
            $table->uuid('user_id');
            $table->decimal('bid_amount_rm', 10, 2);
            $table->timestampTz('bid_time')->useCurrent();
            $table->enum('status', ['active', 'outbid', 'winning', 'cancelled', 'successful', 'unsuccessful'])->default('active');
            $table->string('ip_address', 45)->nullable();
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('collateral_id')->references('id')->on('collaterals')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Performance indexes
            $table->index('collateral_id', 'idx_bids_collateral_id');
            $table->index('user_id', 'idx_bids_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
