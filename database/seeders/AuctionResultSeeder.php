<?php

namespace Database\Seeders;

use App\Models\AuctionResult;
use App\Models\Collateral;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuctionResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active collaterals from completed auctions that don't have auction results yet
        $completedAuctionCollaterals = Collateral::with('auction')
                                    ->where('status', Collateral::STATUS_ACTIVE)
                                    ->whereHas('auction', function($query) {
                                        $query->where('status', 'completed');
                                    })
                                    ->whereNotNull('current_highest_bid_rm')
                                    ->where('current_highest_bid_rm', '>', 0)
                                    ->whereDoesntHave('auctionResult')
                                    ->get();

        $bidders = User::where('role', User::ROLE_BIDDER)->get();

        foreach ($completedAuctionCollaterals as $collateral) {
            $paymentStatus = rand(1, 100) <= 80 ? AuctionResult::PAYMENT_PAID : 
                           (rand(1, 100) <= 70 ? AuctionResult::PAYMENT_PENDING : AuctionResult::PAYMENT_CANCELLED);
            
            $deliveryStatus = $paymentStatus === AuctionResult::PAYMENT_PAID ? 
                            (rand(1, 100) <= 70 ? AuctionResult::DELIVERY_DELIVERED : 
                             (rand(1, 100) <= 80 ? AuctionResult::DELIVERY_SHIPPED : AuctionResult::DELIVERY_PENDING)) :
                            AuctionResult::DELIVERY_PENDING;
            
            $resultStatus = $paymentStatus === AuctionResult::PAYMENT_PAID ? 
                          AuctionResult::RESULT_COMPLETED : 
                          ($paymentStatus === AuctionResult::PAYMENT_CANCELLED ? 
                           AuctionResult::RESULT_CANCELLED : AuctionResult::RESULT_COMPLETED);

            AuctionResult::create([
                'id' => Str::uuid(),
                'collateral_id' => $collateral->id,
                'winner_user_id' => $collateral->highest_bidder_user_id,
                'winning_bid_amount' => $collateral->current_highest_bid_rm,
                'auction_end_time' => $collateral->auction_end_datetime ?? now()->subDays(rand(1, 30)),
                'payment_status' => $paymentStatus,
                'delivery_status' => $deliveryStatus,
                'result_status' => $resultStatus,
            ]);
        }

        // Create some auction results for additional collaterals from completed auctions
        $additionalCompletedCollaterals = Collateral::with('auction')
                                               ->where('status', Collateral::STATUS_ACTIVE)
                                               ->whereHas('auction', function($query) {
                                                   $query->where('status', 'completed');
                                               })
                                               ->whereNull('highest_bidder_user_id')
                                               ->whereDoesntHave('auctionResult')
                                               ->take(5)
                                               ->get();

        foreach ($additionalCompletedCollaterals as $collateral) {
            $winner = $bidders->random();
            $winningAmount = $collateral->starting_bid_rm + rand(100, 1000);
            
            // Update the collateral with winner info
            $collateral->update([
                'highest_bidder_user_id' => $winner->id,
                'current_highest_bid_rm' => $winningAmount,
            ]);

            AuctionResult::create([
                'id' => Str::uuid(),
                'collateral_id' => $collateral->id,
                'winner_user_id' => $winner->id,
                'winning_bid_amount' => $winningAmount,
                'auction_end_time' => now()->subDays(rand(1, 15)),
                'payment_status' => AuctionResult::PAYMENT_PAID,
                'delivery_status' => AuctionResult::DELIVERY_DELIVERED,
                'result_status' => AuctionResult::RESULT_COMPLETED,
            ]);
        }

        $totalResults = AuctionResult::count();
        $this->command->info("Created {$totalResults} auction results");
    }
}
