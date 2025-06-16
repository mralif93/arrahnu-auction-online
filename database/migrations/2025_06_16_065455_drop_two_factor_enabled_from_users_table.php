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
        Schema::table('users', function (Blueprint $table) {
            // Drop the two_factor_enabled column if it exists
            if (Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->dropColumn('two_factor_enabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the column back with default false
            $table->boolean('two_factor_enabled')->default(false)->after('email_verified_at');
        });
    }
};
