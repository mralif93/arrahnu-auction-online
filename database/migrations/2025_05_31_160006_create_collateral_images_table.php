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
        Schema::create('collateral_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('collateral_id');
            $table->string('image_url', 255);
            $table->boolean('is_thumbnail')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('collateral_id')->references('id')->on('collaterals')->onDelete('cascade');

            // Performance indexes
            $table->index('collateral_id', 'idx_collateral_images_collateral_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collateral_images');
    }
};
