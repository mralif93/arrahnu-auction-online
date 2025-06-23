<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Collateral;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BidSeeder extends Seeder
{

    public function run(): void
    {

        $activeCollaterals = Collateral::with('auction')
            ->where('status', Collateral::STATUS_ACTIVE)
            ->whereHas('auction', function($query) {
                $query->where('status', 'active');
            })
            ->get();

        $bidders = User::where('role', User::ROLE_BIDDER)->where('status', User::STATUS_ACTIVE)->get();

        if ($bidders->isEmpty()) {
            $this->command->info("No bidders found, skipping bid creation");
            return;
        }

        foreach ($activeCollaterals as $collateral) {

            $bidCount = rand(3, 8);
            $currentBid = $collateral->starting_bid_rm;

            for ($i = 0; $i < $bidCount; $i++) {
                $bidder = $bidders->random();
                $bidIncrement = rand(10, 100);
                $currentBid += $bidIncrement;

                $auctionStartTime = $collateral->auction->start_datetime;
                $bidTime = $auctionStartTime->copy()->addMinutes(rand(10, 1440));

                $status = ($i === $bidCount - 1) ? Bid::STATUS_WINNING :
                         (rand(1, 100) <= 90 ? Bid::STATUS_OUTBID : Bid::STATUS_ACTIVE);

                Bid::create([
                    'id' => Str::uuid(),
                    'collateral_id' => $collateral->id,
                    'user_id' => $bidder->id,
                    'bid_amount_rm' => $currentBid,
                    'bid_time' => $bidTime,
                    'status' => $status,
                    'ip_address' => '192.168.1.' . rand(1, 254),
                ]);
            }

            $collateral->update([
                'current_highest_bid_rm' => $currentBid,
                'highest_bidder_user_id' => $bidders->random()->id,
            ]);
        }

        $scheduledCollaterals = Collateral::with('auction')
            ->where('status', Collateral::STATUS_ACTIVE)
            ->whereHas('auction', function($query) {
                $query->where('status', 'scheduled');
            })
            ->take(3)
            ->get();

        foreach ($scheduledCollaterals as $collateral) {
            $bidCount = rand(1, 3);

            for ($i = 0; $i < $bidCount; $i++) {
                $bidder = $bidders->random();
                $bidAmount = $collateral->starting_bid_rm + rand(50, 200);

                $bidTime = now()->subHours(rand(1, 48));

                Bid::create([
                    'id' => Str::uuid(),
                    'collateral_id' => $collateral->id,
                    'user_id' => $bidder->id,
                    'bid_amount_rm' => $bidAmount,
                    'bid_time' => $bidTime,
                    'status' => Bid::STATUS_ACTIVE,
                    'ip_address' => '192.168.1.' . rand(1, 254),
                ]);
            }
        }

        $totalBids = Bid::count();
        $this->command->info("Created {$totalBids} bids");
    }
}
