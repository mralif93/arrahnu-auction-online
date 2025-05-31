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
        Schema::create('collaterals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_id');
            $table->uuid('auction_id');
            $table->string('item_type', 50);
            $table->text('description');
            $table->decimal('weight_grams', 8, 2)->nullable();
            $table->string('purity', 20)->nullable();
            $table->decimal('estimated_value_rm', 10, 2)->nullable();
            $table->decimal('starting_bid_rm', 10, 2)->default(0.00);
            $table->decimal('current_highest_bid_rm', 10, 2)->default(0.00);
            $table->uuid('highest_bidder_user_id')->nullable();
            $table->enum('status', [
                'draft', 
                'pending_approval', 
                'active', 
                'ready_for_auction', 
                'auctioning', 
                'sold', 
                'unsold', 
                'returned', 
                'rejected'
            ])->default('draft');
            $table->uuid('created_by_user_id')->nullable();
            $table->uuid('approved_by_user_id')->nullable();
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
            $table->foreign('highest_bidder_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');

            // Performance indexes
            $table->index('account_id', 'idx_collaterals_account_id');
            $table->index('auction_id', 'idx_collaterals_auction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaterals');
    }
};
