<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Auction;
use App\Models\Collateral;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CollateralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activeAccounts = Account::where('status', Account::STATUS_ACTIVE)->get();
        $maker = User::where('username', 'maker01')->first();
        $checker = User::where('username', 'checker01')->first();

        $itemTypes = ['Gold Ring', 'Gold Necklace', 'Gold Bracelet', 'Gold Earrings', 'Gold Watch', 'Diamond Ring', 'Silver Chain', 'Platinum Ring'];
        $purities = ['24K', '22K', '18K', '14K', '10K', '925 Silver', '950 Platinum'];

        foreach ($activeAccounts as $account) {
            // Create 1-3 collaterals per account
            $collateralCount = rand(1, 3);

            for ($i = 0; $i < $collateralCount; $i++) {
                $itemType = $itemTypes[array_rand($itemTypes)];
                $purity = $purities[array_rand($purities)];
                $weight = rand(50, 1000) / 10; // 5.0 to 100.0 grams
                $estimatedValue = rand(500, 10000);
                $startingBid = $estimatedValue * 0.7; // 70% of estimated value

                $status = rand(1, 100) <= 60 ? Collateral::STATUS_ACTIVE :
                         (rand(1, 100) <= 70 ? Collateral::STATUS_READY_FOR_AUCTION :
                         (rand(1, 100) <= 80 ? Collateral::STATUS_AUCTIONING : Collateral::STATUS_PENDING_APPROVAL));

                // Get a random auction from the same branch as the account
                $auction = Auction::where('branch_id', $account->branch_id)->inRandomOrder()->first();

                if (!$auction) {
                    // If no auction exists for this branch, skip this collateral
                    continue;
                }

                $collateral = Collateral::create([
                    'id' => Str::uuid(),
                    'account_id' => $account->id,
                    'auction_id' => $auction->id,
                    'item_type' => $itemType,
                    'description' => "Beautiful {$itemType} with {$purity} purity, weighing {$weight}g",
                    'weight_grams' => $weight,
                    'purity' => $purity,
                    'estimated_value_rm' => $estimatedValue,
                    'starting_bid_rm' => $startingBid,
                    'current_highest_bid_rm' => $status === Collateral::STATUS_AUCTIONING ? $startingBid + rand(50, 500) : 0,
                    'status' => $status,
                    'created_by_user_id' => $maker->id,
                    'approved_by_user_id' => in_array($status, [Collateral::STATUS_ACTIVE, Collateral::STATUS_READY_FOR_AUCTION, Collateral::STATUS_AUCTIONING]) ? $checker->id : null,
                ]);
            }
        }

        $totalCollaterals = Collateral::count();
        $this->command->info("Created {$totalCollaterals} collateral items");
    }
}
