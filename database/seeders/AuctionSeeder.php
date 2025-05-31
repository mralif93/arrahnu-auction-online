<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Auction;
use App\Models\Collateral;
use App\Models\User;
use App\Models\Branch;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user and branches
        $admin = User::where('email', 'admin@arrahnu.com')->first();
        $branches = Branch::where('status', 'active')->get();

        if (!$admin || $branches->isEmpty()) {
            $this->command->error('Admin user or active branches not found. Please run UserSeeder and BranchSeeder first.');
            return;
        }

        // Create sample auctions for each branch
        $auctionTitles = [
            'May 2025 Gold & Jewelry Auction',
            'June 2025 Premium Collection',
            'July 2025 Diamond Showcase',
            'August 2025 Silver Collection',
            'September 2025 Mixed Assets',
        ];

        $descriptions = [
            'Monthly auction featuring premium gold jewelry, rings, and precious items.',
            'Exclusive auction featuring high-value diamond rings, gold chains, and luxury watches.',
            'Showcase of premium diamond jewelry and precious stones.',
            'Collection of silver jewelry, coins, and decorative items.',
            'Mixed collection of various precious metals and jewelry.',
        ];

        $statuses = ['scheduled', 'active', 'completed'];

        foreach ($branches as $index => $branch) {
            // Create 1-2 auctions per branch
            $auctionCount = rand(1, 2);

            for ($i = 0; $i < $auctionCount; $i++) {
                $titleIndex = ($index * 2 + $i) % count($auctionTitles);
                $status = $statuses[array_rand($statuses)];

                // Set appropriate dates based on status
                switch ($status) {
                    case 'scheduled':
                        $startDate = now()->addDays(rand(1, 7));
                        $endDate = $startDate->copy()->addDays(rand(3, 7));
                        break;
                    case 'active':
                        $startDate = now()->subDays(rand(1, 3));
                        $endDate = now()->addDays(rand(1, 5));
                        break;
                    case 'completed':
                        $startDate = now()->subDays(rand(10, 30));
                        $endDate = $startDate->copy()->addDays(rand(3, 7));
                        break;
                }

                $auction = Auction::create([
                    'id' => Str::uuid(),
                    'branch_id' => $branch->id,
                    'auction_title' => $auctionTitles[$titleIndex] . " - {$branch->name}",
                    'description' => $descriptions[$titleIndex % count($descriptions)],
                    'start_datetime' => $startDate,
                    'end_datetime' => $endDate,
                    'status' => $status,
                    'created_by_user_id' => $admin->id,
                    'approved_by_user_id' => $admin->id,
                ]);

                $this->command->info("Created auction: {$auction->auction_title} ({$status})");
            }
        }

        $totalAuctions = Auction::count();
        $this->command->info("Created {$totalAuctions} auctions total");
    }

    /**
     * Assign existing collaterals to the auction.
     */
    private function assignCollateralsToAuction(Auction $auction): void
    {
        // Get collaterals from the same branch that don't have an auction assigned
        $collaterals = Collateral::whereHas('account.branch', function ($query) use ($auction) {
            $query->where('id', $auction->branch_id);
        })
        ->whereNull('auction_id')
        ->take(rand(3, 8))
        ->get();

        foreach ($collaterals as $collateral) {
            $collateral->update([
                'auction_id' => $auction->id,
                'status' => $this->getCollateralStatusForAuction($auction->status),
            ]);

            $this->command->info("  - Assigned collateral: {$collateral->item_type} to auction");
        }
    }

    /**
     * Get appropriate collateral status based on auction status.
     */
    private function getCollateralStatusForAuction(string $auctionStatus): string
    {
        return match ($auctionStatus) {
            'scheduled' => 'ready_for_auction',
            'active' => 'auctioning',
            'completed' => rand(0, 1) ? 'sold' : 'unsold',
            'cancelled' => 'active',
            default => 'active',
        };
    }
}
