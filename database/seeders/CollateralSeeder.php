<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Auction;
use App\Models\Collateral;
use App\Models\CollateralImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CollateralSeeder extends Seeder
{

    public function run(): void
    {
        $activeAccounts = Account::where('status', Account::STATUS_ACTIVE)->get();
        $maker = User::where('username', 'maker01')->first();
        $checker = User::where('username', 'checker01')->first();

        if (!$maker || !$checker) {
            $this->command->error('Required users (maker01, checker01) not found. Please run UserSeeder first.');
            return;
        }

        $itemTypes = [
            'Gold Ring', 'Gold Necklace', 'Gold Bracelet', 'Gold Earrings',
            'Gold Watch', 'Diamond Ring', 'Silver Chain', 'Platinum Ring',
            'Gold Pendant', 'Diamond Earrings', 'Gold Bangle', 'Pearl Necklace'
        ];

        $purities = ['24K', '22K', '18K', '14K', '10K', '925 Silver', '950 Platinum', '999 Gold'];

        $descriptions = [
            'Exquisite handcrafted jewelry piece with intricate design details',
            'Premium quality jewelry with excellent craftsmanship and finish',
            'Elegant and timeless piece perfect for special occasions',
            'Beautiful jewelry item with traditional Malaysian design elements',
            'High-quality piece with modern styling and superior materials',
            'Classic design with contemporary appeal and lasting value'
        ];

        $sampleImageUrls = [
            'https://via.placeholder.com/800x600/FFD700/000000?text=Gold+Jewelry',
            'https://via.placeholder.com/800x600/C0C0C0/000000?text=Silver+Item',
            'https://via.placeholder.com/800x600/E5E4E2/000000?text=Platinum+Ring',
            'https://via.placeholder.com/800x600/B87333/000000?text=Diamond+Ring',
            'https://via.placeholder.com/800x600/DAA520/000000?text=Precious+Item',
        ];

        $collateralCount = 0;

        foreach ($activeAccounts as $account) {

            $itemsPerAccount = rand(2, 4);

            for ($i = 0; $i < $itemsPerAccount; $i++) {
                $itemType = $itemTypes[array_rand($itemTypes)];
                $purity = $purities[array_rand($purities)];
                $weight = rand(50, 1500) / 10;
                $estimatedValue = rand(800, 15000);
                $startingBid = round($estimatedValue * (rand(60, 80) / 100), 2);

                $statusRand = rand(1, 100);
                if ($statusRand <= 50) {
                    $status = Collateral::STATUS_ACTIVE;
                    $approvedBy = $checker->id;
                } elseif ($statusRand <= 70) {
                    $status = Collateral::STATUS_PENDING_APPROVAL;
                    $approvedBy = null;
                } elseif ($statusRand <= 85) {
                    $status = Collateral::STATUS_DRAFT;
                    $approvedBy = null;
                } elseif ($statusRand <= 95) {
                    $status = Collateral::STATUS_INACTIVE;
                    $approvedBy = $checker->id;
                } else {
                    $status = Collateral::STATUS_REJECTED;
                    $approvedBy = $checker->id;
                }

                $auction = Auction::whereIn('status', ['scheduled', 'active'])
                    ->inRandomOrder()
                    ->first();

                if (!$auction) {

                    continue;
                }

                $currentHighestBid = 0;
                $highestBidderId = null;

                if ($status === Collateral::STATUS_ACTIVE && rand(1, 100) <= 40) {

                    $bidIncrease = rand(50, 500);
                    $currentHighestBid = $startingBid + $bidIncrease;

                    $potentialBidders = User::whereNotIn('username', ['maker01', 'checker01'])
                        ->where('status', 'active')
                        ->inRandomOrder()
                        ->first();

                    if ($potentialBidders) {
                        $highestBidderId = $potentialBidders->id;
                    }
                }

                $collateral = Collateral::create([
                    'account_id' => $account->id,
                    'auction_id' => $auction->id,
                    'item_type' => $itemType,
                    'description' => $descriptions[array_rand($descriptions)] . " This {$itemType} features {$purity} purity and weighs {$weight}g. Perfect condition with authentic certification.",
                    'weight_grams' => $weight,
                    'purity' => $purity,
                    'estimated_value_rm' => $estimatedValue,
                    'starting_bid_rm' => $startingBid,
                    'current_highest_bid_rm' => $currentHighestBid,
                    'highest_bidder_user_id' => $highestBidderId,
                    'status' => $status,
                    'created_by_user_id' => $maker->id,
                    'approved_by_user_id' => $approvedBy,
                ]);

                $imageCount = rand(2, 4);
                for ($j = 0; $j < $imageCount; $j++) {
                    CollateralImage::create([
                        'collateral_id' => $collateral->id,
                        'image_url' => $sampleImageUrls[array_rand($sampleImageUrls)],
                        'is_thumbnail' => $j === 0,
                        'order_index' => $j,
                    ]);
                }

                $collateralCount++;
            }
        }

        $this->command->info("Created {$collateralCount} collateral items with images");

        $statusCounts = [
            'Active' => Collateral::where('status', Collateral::STATUS_ACTIVE)->count(),
            'Pending Approval' => Collateral::where('status', Collateral::STATUS_PENDING_APPROVAL)->count(),
            'Draft' => Collateral::where('status', Collateral::STATUS_DRAFT)->count(),
            'Inactive' => Collateral::where('status', Collateral::STATUS_INACTIVE)->count(),
            'Rejected' => Collateral::where('status', Collateral::STATUS_REJECTED)->count(),
        ];

        $this->command->info("Status breakdown:");
        foreach ($statusCounts as $status => $count) {
            $this->command->info("  {$status}: {$count}");
        }

        $imageCount = CollateralImage::count();
        $this->command->info("Created {$imageCount} collateral images");
    }
}
