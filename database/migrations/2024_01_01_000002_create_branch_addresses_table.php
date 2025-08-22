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
        Schema::create('branch_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id')->nullable(); // Will be linked after branches table is created
            $table->string('address_line_1', 255);
            $table->string('address_line_2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postcode', 20);
            $table->string('country', 100)->default('Malaysia');
            $table->timestampsTz();

            // Performance indexes
            $table->index('branch_id', 'idx_branch_addresses_branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_addresses');
    }
};
