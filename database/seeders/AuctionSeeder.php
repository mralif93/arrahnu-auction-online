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
    public function run(): void
    {
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

        $auctionTitles = [
            'January 2025 Live Gold & Jewelry Auction',
            'December 2024 Premium Collection',
            'November 2024 Diamond Showcase',
            'October 2024 Silver Collection',
            'September 2024 Mixed Assets Auction',
            'August 2024 Luxury Items Sale',
            'July 2024 Precious Metals Auction',
            'June 2024 Summer Collection',
            'May 2024 Spring Jewelry Sale',
            'April 2024 Easter Special',
            'February 2025 Valentine Collection',
            'March 2025 Spring Preview',
            'April 2025 Easter Jewelry Sale',
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

        $statuses = [
            'active',
            'completed',
            'completed',
            'completed',
            'completed',
            'completed',
            'cancelled',
            'cancelled',
            'rejected',
            'rejected',
            'pending_approval',
            'scheduled',
            'scheduled',
        ];

        $auctionCount = count($statuses);

        for ($i = 0; $i < $auctionCount; $i++) {
            $status = $statuses[$i];
            switch ($status) {
                case 'pending_approval':
                    $startDate = now()->addDays(rand(7, 21));
                    $endDate = $startDate->copy()->addDays(rand(3, 7));
                    break;
                case 'scheduled':
                    $startDate = now()->addDays(rand(1, 7));
                    $endDate = $startDate->copy()->addDays(rand(3, 7));
                    break;
                case 'active':
                    $startDate = now()->subDays(2);
                    $endDate = now()->addDays(3);
                    break;
                case 'completed':
                    $daysAgo = rand(7, 90);
                    $startDate = now()->subDays($daysAgo + rand(3, 7));
                    $endDate = now()->subDays($daysAgo);
                    break;
                case 'cancelled':
                    $daysAgo = rand(5, 60);
                    $startDate = now()->subDays($daysAgo + rand(3, 7));
                    $endDate = now()->subDays($daysAgo);
                    break;
                case 'rejected':
                    $daysAgo = rand(10, 120);
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

            $this->assignCollateralsToAuction($auction, $accounts);
        }

        $totalAuctions = Auction::count();
        $this->command->info("Created {$totalAuctions} auctions total");

        $this->createCollateralsWithAllStatuses($accounts);
    }

    private function assignCollateralsToAuction(Auction $auction, $accounts): void
    {
        $availableCollaterals = Collateral::whereNull('auction_id')
            ->where('status', 'active')
            ->take(rand(3, 8))
            ->get();
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
            $startingBid = $estimatedValue * 0.6;

            $collateral = Collateral::create([
                'account_id' => $account->id,
                'auction_id' => $auction->id,
                'item_type' => $itemTypes[$itemIndex],
                'description' => $descriptions[$itemIndex],
                'weight_grams' => rand(5, 50) + (rand(0, 99) / 100),
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

    private function getCollateralStatusForAuction(string $auctionStatus): string
    {

        return match ($auctionStatus) {
            'scheduled' => ['active', 'pending_approval'][array_rand(['active', 'pending_approval'])],
            'active' => 'active',
            'completed' => ['active', 'inactive'][array_rand(['active', 'inactive'])],
            'cancelled' => ['active', 'inactive', 'draft'][array_rand(['active', 'inactive', 'draft'])],
            default => 'active',
        };
    }

    private function getCurrentBidForAuction(string $auctionStatus, float $startingBid): float
    {
        return match ($auctionStatus) {
            'active' => $startingBid + rand(50, 500),
            'completed' => $startingBid + rand(100, 1000),
            default => 0.00,
        };
    }

    private function createCollateralsWithAllStatuses($accounts): void
    {

        $draftAuction = Auction::create([
            'auction_title' => 'Draft Items Collection',
            'description' => 'Collection of items in various stages of preparation',
            'start_datetime' => now()->addMonths(6),
            'end_datetime' => now()->addMonths(6)->addDays(7),
            'status' => 'scheduled',
            'created_by_user_id' => User::where('email', 'admin@arrahnu.com')->first()->id,
            'approved_by_user_id' => User::where('email', 'admin@arrahnu.com')->first()->id,
        ]);

        $this->command->info("Created draft auction for collaterals with various statuses");

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

        foreach ($allStatuses as $statusIndex => $status) {
            for ($i = 0; $i < 2; $i++) {
                $account = $accounts->random();
                $itemIndex = ($statusIndex * 2 + $i) % count($itemTypes);

                $estimatedValue = rand(800, 8000);
                $startingBid = $estimatedValue * (rand(50, 70) / 100);

                $auctionId = in_array($status, ['draft', 'pending_approval']) ?
                    $draftAuction->id :
                    Auction::where('id', '!=', $draftAuction->id)->inRandomOrder()->first()?->id ?? $draftAuction->id;

                $collateral = Collateral::create([
                    'account_id' => $account->id,
                    'auction_id' => $auctionId,
                    'item_type' => $itemTypes[$itemIndex],
                    'description' => $descriptions[$itemIndex],
                    'weight_grams' => rand(3, 80) + (rand(0, 99) / 100),
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

    private function getRandomPurity(): string
    {
        $purities = ['18K', '22K', '24K', '14K', '10K', '925 Silver', '999 Silver', '950 Platinum', '900 Platinum'];
        return $purities[array_rand($purities)];
    }
}
