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
        Schema::create('auction_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('collateral_id')->unique();
            $table->uuid('winner_user_id');
            $table->decimal('winning_bid_amount', 10, 2);
            $table->uuid('winning_bid_id')->unique();
            $table->timestampTz('auction_end_time');
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'refunded'])->default('pending');
            $table->enum('delivery_status', ['pending', 'shipped', 'delivered', 'collected', 'returned'])->default('pending');
            $table->enum('result_status', ['completed', 'failed_payment', 'cancelled'])->default('completed');
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('collateral_id')->references('id')->on('collaterals')->onDelete('cascade');
            $table->foreign('winner_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('winning_bid_id')->references('id')->on('bids')->onDelete('cascade');

            // Performance indexes
            $table->index('collateral_id', 'idx_auction_results_collateral_id');
            $table->index('winner_user_id', 'idx_auction_results_winner_user_id');
            $table->index('winning_bid_id', 'idx_auction_results_winning_bid_id');
            $table->index('result_status', 'idx_auction_results_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_results');
    }
};
