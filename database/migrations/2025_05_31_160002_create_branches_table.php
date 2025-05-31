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
            $table->text('address');
            $table->string('phone_number', 20)->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'active', 'inactive', 'rejected'])->default('draft');
            $table->uuid('created_by_user_id')->nullable();
            $table->uuid('approved_by_user_id')->nullable();
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
