<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AddressSeeder::class,
            BranchSeeder::class,
            AccountSeeder::class,
            AuctionSeeder::class,
            CollateralSeeder::class,
            CollateralImageSeeder::class,
            BidSeeder::class,
            AuctionResultSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
