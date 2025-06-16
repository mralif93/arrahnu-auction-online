<?php

namespace App\Console\Commands;

use App\Models\Auction;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateAuctionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auctions:update-statuses {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update auction statuses based on their start and end dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $now = now();
        
        $this->info('🔄 Checking auction statuses...');
        $this->info("Current time: {$now->format('Y-m-d H:i:s')}");
        $this->newLine();

        $updatedCount = 0;
        $auctions = Auction::whereIn('status', ['scheduled', 'active'])->get();

        if ($auctions->isEmpty()) {
            $this->info('✅ No scheduled or active auctions found to update.');
            return;
        }

        foreach ($auctions as $auction) {
            $shouldUpdate = false;
            $newStatus = null;
            $reason = '';

            // Check if scheduled auction should start
            if ($auction->status === 'scheduled' && $now->gte($auction->start_datetime)) {
                if ($now->lte($auction->end_datetime)) {
                    $newStatus = 'active';
                    $reason = 'Start time reached';
                    $shouldUpdate = true;
                } else {
                    $newStatus = 'completed';
                    $reason = 'Start and end time both passed';
                    $shouldUpdate = true;
                }
            }

            // Check if active auction should complete
            if ($auction->status === 'active' && $now->gt($auction->end_datetime)) {
                $newStatus = 'completed';
                $reason = 'End time reached';
                $shouldUpdate = true;
            }

            if ($shouldUpdate) {
                $this->line("📅 {$auction->auction_title}");
                $this->line("   Current: {$auction->status} → New: {$newStatus}");
                $this->line("   Reason: {$reason}");
                $this->line("   Start: {$auction->start_datetime->format('Y-m-d H:i')}");
                $this->line("   End: {$auction->end_datetime->format('Y-m-d H:i')}");

                if (!$isDryRun) {
                    if ($newStatus === 'active') {
                        $auction->start();
                        $this->info("   ✅ Started auction");
                    } elseif ($newStatus === 'completed') {
                        $auction->complete();
                        $this->info("   ✅ Completed auction");
                    }
                } else {
                    $this->comment("   🔍 DRY RUN - No changes made");
                }

                $updatedCount++;
                $this->newLine();
            }
        }

        if ($updatedCount === 0) {
            $this->info('✅ All auction statuses are up to date.');
        } else {
            if ($isDryRun) {
                $this->info("🔍 DRY RUN: {$updatedCount} auction(s) would be updated.");
                $this->comment('Run without --dry-run to apply changes.');
            } else {
                $this->info("✅ Updated {$updatedCount} auction(s) successfully.");
            }
        }

        // Show summary of all auctions
        $this->newLine();
        $this->info('📊 Current Auction Status Summary:');
        $statusCounts = Auction::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        foreach ($statusCounts as $status => $count) {
            $emoji = match($status) {
                'active' => '🟢',
                'scheduled' => '🔵',
                'completed' => '✅',
                'cancelled' => '🟡',
                'rejected' => '🔴',
                'pending_approval' => '⏳',
                'draft' => '📝',
                default => '⚪'
            };
            $this->line("   {$emoji} {$status}: {$count}");
        }
    }
}
