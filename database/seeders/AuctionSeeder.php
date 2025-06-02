<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Auction;
use App\Models\Collateral;
use App\Models\User;
use App\Models\Account;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates exactly 1 LIVE (active) auction with the rest being historical auctions
     * in completed, cancelled, rejected, pending_approval, or scheduled statuses.
     */
    public function run(): void
    {
        // Get admin user and active accounts
        $admin = User::where('email', 'admin@arrahnu.com')->first();
        $accounts = Account::where('status', 'active')->get();

        if (!$admin) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');
            return;
        }

        if ($accounts->isEmpty()) {
            $this->command->error('No active accounts found. Please run AccountSeeder first.');
            return;
        }

        // Create sample auctions with realistic titles
        $auctionTitles = [
            'January 2025 Live Gold & Jewelry Auction',    // The active one
            'December 2024 Premium Collection',             // Completed
            'November 2024 Diamond Showcase',               // Completed
            'October 2024 Silver Collection',               // Completed
            'September 2024 Mixed Assets Auction',          // Completed
            'August 2024 Luxury Items Sale',                // Completed
            'July 2024 Precious Metals Auction',            // Cancelled
            'June 2024 Summer Collection',                  // Cancelled
            'May 2024 Spring Jewelry Sale',                 // Rejected
            'April 2024 Easter Special',                    // Rejected
            'February 2025 Valentine Collection',           // Pending Approval
            'March 2025 Spring Preview',                    // Scheduled
            'April 2025 Easter Jewelry Sale',               // Scheduled
        ];

        $descriptions = [
            'LIVE AUCTION: Premium gold jewelry, diamond rings, and precious items currently accepting bids.',
            'Completed auction featuring high-value diamond rings, gold chains, and luxury watches.',
            'Showcase of premium diamond jewelry and precious stones - successfully completed.',
            'Collection of silver jewelry, coins, and decorative items - auction completed.',
            'Mixed collection of various precious metals and jewelry - auction completed.',
            'Luxury items including watches, jewelry, and precious stones - auction completed.',
            'Premium precious metals and rare collectibles - auction was cancelled.',
            'Summer collection of jewelry and accessories - auction was cancelled.',
            'Spring jewelry collection - auction was rejected during approval.',
            'Easter special collection - auction was rejected during approval.',
            'Valentine themed jewelry collection - awaiting approval.',
            'Spring preview collection - scheduled for upcoming auction.',
            'Easter jewelry sale - scheduled for upcoming auction.',
        ];

        // Define auction statuses - only 1 active auction, rest are older statuses
        $statuses = [
            'active',      // Only 1 live auction
            'completed',   // Most auctions should be completed
            'completed',
            'completed',
            'completed',
            'completed',
            'cancelled',   // Some cancelled auctions
            'cancelled',
            'rejected',    // Some rejected auctions
            'rejected',
            'pending_approval', // A few pending approval
            'scheduled',   // A few scheduled for future
            'scheduled',
        ];

        // Create exactly the number of auctions we have statuses for
        $auctionCount = count($statuses);

        for ($i = 0; $i < $auctionCount; $i++) {
            $status = $statuses[$i]; // Use predefined status order

            // Set appropriate dates based on status
            switch ($status) {
                case 'pending_approval':
                    $startDate = now()->addDays(rand(7, 21)); // Future dates pending approval
                    $endDate = $startDate->copy()->addDays(rand(3, 7));
                    break;
                case 'scheduled':
                    $startDate = now()->addDays(rand(1, 7)); // Near future for scheduled
                    $endDate = $startDate->copy()->addDays(rand(3, 7));
                    break;
                case 'active':
                    // THE LIVE AUCTION - currently running
                    $startDate = now()->subDays(2); // Started 2 days ago
                    $endDate = now()->addDays(3);   // Ends in 3 days
                    break;
                case 'completed':
                    // Past auctions that finished successfully
                    $daysAgo = rand(7, 90); // Completed 1 week to 3 months ago
                    $startDate = now()->subDays($daysAgo + rand(3, 7));
                    $endDate = now()->subDays($daysAgo);
                    break;
                case 'cancelled':
                    // Past auctions that were cancelled
                    $daysAgo = rand(5, 60); // Cancelled 5 days to 2 months ago
                    $startDate = now()->subDays($daysAgo + rand(3, 7));
                    $endDate = now()->subDays($daysAgo);
                    break;
                case 'rejected':
                    // Past auctions that were rejected
                    $daysAgo = rand(10, 120); // Rejected 10 days to 4 months ago
                    $startDate = now()->subDays($daysAgo + rand(3, 7));
                    $endDate = now()->subDays($daysAgo);
                    break;
                default:
                    $startDate = now()->addDays(rand(1, 14));
                    $endDate = $startDate->copy()->addDays(rand(3, 7));
                    break;
            }

            $auction = Auction::create([
                'auction_title' => $auctionTitles[$i],
                'description' => $descriptions[$i],
                'start_datetime' => $startDate,
                'end_datetime' => $endDate,
                'status' => $status,
                'created_by_user_id' => $admin->id,
                'approved_by_user_id' => in_array($status, ['scheduled', 'active', 'completed', 'cancelled']) ? $admin->id : null,
            ]);

            $this->command->info("Created auction: {$auction->auction_title} ({$status})");

            // Assign collaterals to this auction
            $this->assignCollateralsToAuction($auction, $accounts);
        }

        $totalAuctions = Auction::count();
        $this->command->info("Created {$totalAuctions} auctions total");

        // Create additional collaterals with all possible statuses
        $this->createCollateralsWithAllStatuses($accounts);
    }

    /**
     * Assign existing collaterals to the auction.
     */
    private function assignCollateralsToAuction(Auction $auction, $accounts): void
    {
        // Get collaterals that don't have an auction assigned yet
        $availableCollaterals = Collateral::whereNull('auction_id')
            ->where('status', 'active')
            ->take(rand(3, 8))
            ->get();

        // If no available collaterals, create some for this auction
        if ($availableCollaterals->isEmpty()) {
            $this->createCollateralsForAuction($auction, $accounts);
            return;
        }

        foreach ($availableCollaterals as $collateral) {
            $collateral->update([
                'auction_id' => $auction->id,
                'status' => $this->getCollateralStatusForAuction($auction->status),
            ]);

            $this->command->info("  - Assigned collateral: {$collateral->item_type} to auction");
        }
    }

    /**
     * Create new collaterals for the auction.
     */
    private function createCollateralsForAuction(Auction $auction, $accounts): void
    {
        $itemTypes = [
            'Gold Ring', 'Diamond Necklace', 'Silver Bracelet', 'Gold Chain',
            'Diamond Ring', 'Gold Earrings', 'Silver Ring', 'Gold Pendant',
            'Diamond Bracelet', 'Gold Watch', 'Silver Necklace', 'Gold Coin'
        ];

        $descriptions = [
            'Beautiful handcrafted gold ring with intricate design',
            'Stunning diamond necklace with premium quality stones',
            'Elegant silver bracelet with modern styling',
            'Classic gold chain suitable for daily wear',
            'Exquisite diamond ring with brilliant cut stones',
            'Traditional gold earrings with cultural motifs',
            'Contemporary silver ring with unique pattern',
            'Delicate gold pendant with precious stone',
            'Luxury diamond bracelet with exceptional clarity',
            'Premium gold watch with Swiss movement',
            'Sophisticated silver necklace with artistic design',
            'Rare gold coin from limited collection'
        ];

        $collateralCount = rand(3, 6);

        for ($i = 0; $i < $collateralCount; $i++) {
            $account = $accounts->random();
            $itemIndex = $i % count($itemTypes);

            $estimatedValue = rand(500, 5000);
            $startingBid = $estimatedValue * 0.6; // 60% of estimated value

            $collateral = Collateral::create([
                'account_id' => $account->id,
                'auction_id' => $auction->id,
                'item_type' => $itemTypes[$itemIndex],
                'description' => $descriptions[$itemIndex],
                'weight_grams' => rand(5, 50) + (rand(0, 99) / 100), // Random weight between 5-50 grams
                'purity' => ['18K', '22K', '24K', '925 Silver', '999 Silver'][array_rand(['18K', '22K', '24K', '925 Silver', '999 Silver'])],
                'estimated_value_rm' => $estimatedValue,
                'starting_bid_rm' => $startingBid,
                'current_highest_bid_rm' => $this->getCurrentBidForAuction($auction->status, $startingBid),
                'status' => $this->getCollateralStatusForAuction($auction->status),
                'created_by_user_id' => $auction->created_by_user_id,
                'approved_by_user_id' => $auction->approved_by_user_id,
            ]);

            $this->command->info("  - Created collateral: {$collateral->item_type} for auction");
        }
    }

    /**
     * Get appropriate collateral status based on auction status.
     */
    private function getCollateralStatusForAuction(string $auctionStatus): string
    {
        // Use valid collateral statuses from the schema: draft, pending_approval, active, inactive, rejected
        return match ($auctionStatus) {
            'scheduled' => ['active', 'pending_approval'][array_rand(['active', 'pending_approval'])], // Mix of ready items
            'active' => 'active',    // Active during auction
            'completed' => ['active', 'inactive'][array_rand(['active', 'inactive'])], // Mix after completion
            'cancelled' => ['active', 'inactive', 'draft'][array_rand(['active', 'inactive', 'draft'])], // Various states after cancellation
            default => 'active',
        };
    }

    /**
     * Get current bid amount based on auction status.
     */
    private function getCurrentBidForAuction(string $auctionStatus, float $startingBid): float
    {
        return match ($auctionStatus) {
            'active' => $startingBid + rand(50, 500), // Some bids placed
            'completed' => $startingBid + rand(100, 1000), // Final bid amount
            default => 0.00, // No bids yet
        };
    }

    /**
     * Create additional collaterals with all possible statuses to ensure comprehensive coverage.
     */
    private function createCollateralsWithAllStatuses($accounts): void
    {
        // Create a special "draft" auction for collaterals that aren't ready for regular auctions
        $draftAuction = Auction::create([
            'auction_title' => 'Draft Items Collection',
            'description' => 'Collection of items in various stages of preparation',
            'start_datetime' => now()->addMonths(6), // Far future
            'end_datetime' => now()->addMonths(6)->addDays(7),
            'status' => 'scheduled',
            'created_by_user_id' => User::where('email', 'admin@arrahnu.com')->first()->id,
            'approved_by_user_id' => User::where('email', 'admin@arrahnu.com')->first()->id,
        ]);

        $this->command->info("Created draft auction for collaterals with various statuses");

        // All possible collateral statuses from the schema
        $allStatuses = ['draft', 'pending_approval', 'active', 'inactive', 'rejected'];

        $itemTypes = [
            'Vintage Gold Watch', 'Antique Silver Ring', 'Platinum Necklace', 'Diamond Earrings',
            'Gold Bracelet', 'Silver Pendant', 'Ruby Ring', 'Emerald Necklace',
            'Sapphire Bracelet', 'Pearl Earrings', 'Gold Coin Set', 'Silver Collectible'
        ];

        $descriptions = [
            'Vintage gold watch with Swiss movement and leather strap',
            'Antique silver ring with intricate Victorian design',
            'Premium platinum necklace with modern styling',
            'Brilliant cut diamond earrings with excellent clarity',
            'Handcrafted gold bracelet with traditional motifs',
            'Elegant silver pendant with precious stone inlay',
            'Natural ruby ring with 18K gold setting',
            'Stunning emerald necklace with matching chain',
            'Blue sapphire bracelet with white gold accents',
            'Cultured pearl earrings with gold posts',
            'Limited edition gold coin collection',
            'Rare silver collectible with historical significance'
        ];

        // Create at least 2 collaterals for each status
        foreach ($allStatuses as $statusIndex => $status) {
            for ($i = 0; $i < 2; $i++) {
                $account = $accounts->random();
                $itemIndex = ($statusIndex * 2 + $i) % count($itemTypes);

                $estimatedValue = rand(800, 8000);
                $startingBid = $estimatedValue * (rand(50, 70) / 100); // 50-70% of estimated value

                // For draft and pending_approval, use the draft auction; for others, use random auction
                $auctionId = in_array($status, ['draft', 'pending_approval']) ?
                    $draftAuction->id :
                    Auction::where('id', '!=', $draftAuction->id)->inRandomOrder()->first()?->id ?? $draftAuction->id;

                $collateral = Collateral::create([
                    'account_id' => $account->id,
                    'auction_id' => $auctionId,
                    'item_type' => $itemTypes[$itemIndex],
                    'description' => $descriptions[$itemIndex],
                    'weight_grams' => rand(3, 80) + (rand(0, 99) / 100), // Random weight between 3-80 grams
                    'purity' => $this->getRandomPurity(),
                    'estimated_value_rm' => $estimatedValue,
                    'starting_bid_rm' => $startingBid,
                    'current_highest_bid_rm' => $status === 'active' ? $startingBid + rand(0, 300) : 0.00,
                    'status' => $status,
                    'created_by_user_id' => User::where('email', 'admin@arrahnu.com')->first()->id,
                    'approved_by_user_id' => in_array($status, ['active', 'inactive', 'rejected']) ?
                        User::where('email', 'admin@arrahnu.com')->first()->id : null,
                ]);

                $this->command->info("  - Created {$status} collateral: {$collateral->item_type}");
            }
        }

        $this->command->info("Created collaterals with all possible statuses");
    }

    /**
     * Get random purity value for jewelry items.
     */
    private function getRandomPurity(): string
    {
        $purities = ['18K', '22K', '24K', '14K', '10K', '925 Silver', '999 Silver', '950 Platinum', '900 Platinum'];
        return $purities[array_rand($purities)];
    }
}
