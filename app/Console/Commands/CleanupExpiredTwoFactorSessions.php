<?php

namespace App\Console\Commands;

use App\Models\TwoFactorSession;
use Illuminate\Console\Command;

class CleanupExpiredTwoFactorSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:cleanup-2fa-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired two-factor authentication sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up expired 2FA sessions...');
        
        $deletedCount = TwoFactorSession::cleanupExpired();
        
        if ($deletedCount > 0) {
            $this->info("Cleaned up {$deletedCount} expired 2FA sessions.");
        } else {
            $this->info('No expired 2FA sessions found.');
        }
        
        return Command::SUCCESS;
    }
}
