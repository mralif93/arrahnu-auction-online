<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list {--role=all : Filter by role (admin, user, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users in the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $role = $this->option('role');

        $this->info('=== ARRAHNU AUCTION USERS ===');
        $this->newLine();

        // Get users based on role filter
        $query = User::query();

        if ($role === 'admin') {
            $query->where('is_admin', true);
        } elseif ($role === 'user') {
            $query->where('is_admin', false);
        }

        $users = $query->orderBy('is_admin', 'desc')->orderBy('name')->get();

        // Display statistics
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();
        $regularUsers = User::where('is_admin', false)->count();

        $this->info("Total Users: {$totalUsers}");
        $this->info("Admin Users: {$adminUsers}");
        $this->info("Regular Users: {$regularUsers}");
        $this->newLine();

        // Create table headers
        $headers = ['Name', 'Email', 'Role', 'Status'];
        $rows = [];

        foreach ($users as $user) {
            $rows[] = [
                $user->name,
                $user->email,
                $user->is_admin ? '👑 Admin' : '👤 User',
                $user->email_verified_at ? '✅ Verified' : '❌ Unverified'
            ];
        }

        $this->table($headers, $rows);

        $this->newLine();
        $this->info('Login URL: http://127.0.0.1:8002/login');
        $this->info('Admin Password: admin123 | User Password: user123');

        return 0;
    }
}
