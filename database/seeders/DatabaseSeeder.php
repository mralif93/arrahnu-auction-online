<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AddressSeeder::class,
            BranchSeeder::class,
            AccountSeeder::class,
            AuctionSeeder::class,  // Create auctions first
            CollateralSeeder::class,  // Then create collaterals and assign to auctions
            CollateralImageSeeder::class,
            BidSeeder::class,
            AuctionResultSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
