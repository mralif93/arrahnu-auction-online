<?php

namespace Database\Seeders;

use App\Models\AuctionResult;
use App\Models\Bid;
use App\Models\Collateral;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuctionResultSeeder extends Seeder
{
    public function run(): void
    {
        $completedAuctionCollaterals = Collateral::with(['auction', 'bids'])
                                    ->where('status', Collateral::STATUS_ACTIVE)
                                    ->whereHas('auction', function($query) {
                                        $query->where('status', 'completed');
                                    })
                                                                         ->whereHas('bids')
                                    ->whereDoesntHave('auctionResult')
                                    ->get();

        foreach ($completedAuctionCollaterals as $collateral) {
            $winningBid = $collateral->bids()
                                   ->orderBy('bid_amount_rm', 'desc')
                                   ->orderBy('bid_time', 'asc')
                                   ->first();

            if (!$winningBid) {
                continue;
            }
            if (!$collateral->highest_bidder_user_id) {
                $collateral->update([
                    'highest_bidder_user_id' => $winningBid->user_id,
                    'current_highest_bid_rm' => $winningBid->bid_amount_rm,
                ]);
            }

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
                'winner_user_id' => $winningBid->user_id,
                'winning_bid_amount' => $winningBid->bid_amount_rm,
                'winning_bid_id' => $winningBid->id,
                'auction_end_time' => $collateral->auction->end_datetime ?? now()->subDays(rand(1, 30)),
                'payment_status' => $paymentStatus,
                'delivery_status' => $deliveryStatus,
                'result_status' => $resultStatus,
            ]);

            $winningBid->update(['status' => 'successful']);
            $collateral->bids()->where('id', '!=', $winningBid->id)->update(['status' => 'unsuccessful']);
        }

        $additionalCompletedCollaterals = Collateral::with('auction')
                                               ->where('status', Collateral::STATUS_ACTIVE)
                                               ->whereHas('auction', function($query) {
                                                   $query->where('status', 'completed');
                                               })
                                               ->whereDoesntHave('bids')
                                               ->whereDoesntHave('auctionResult')
                                               ->take(3)
                                               ->get();

        $bidders = User::where('role', User::ROLE_BIDDER)->get();

        foreach ($additionalCompletedCollaterals as $collateral) {
            if ($bidders->isEmpty()) {
                continue;
            }

            $winner = $bidders->random();
            $winningAmount = $collateral->starting_bid_rm + rand(100, 1000);
            
            $winningBid = Bid::create([
                'id' => Str::uuid(),
                'collateral_id' => $collateral->id,
                'user_id' => $winner->id,
                'bid_amount_rm' => $winningAmount,
                'bid_time' => now()->subDays(rand(1, 15)),
                'status' => 'successful',
                'ip_address' => '127.0.0.1',
            ]);

            $collateral->update([
                'highest_bidder_user_id' => $winner->id,
                'current_highest_bid_rm' => $winningAmount,
            ]);

            AuctionResult::create([
                'id' => Str::uuid(),
                'collateral_id' => $collateral->id,
                'winner_user_id' => $winner->id,
                'winning_bid_amount' => $winningAmount,
                'winning_bid_id' => $winningBid->id,
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
