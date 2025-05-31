<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Collateral;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $branches = Branch::all();
        $accounts = Account::all();
        $collaterals = Collateral::all();

        $actions = [
            AuditLog::ACTION_CREATE,
            AuditLog::ACTION_UPDATE,
            AuditLog::ACTION_APPROVE,
            AuditLog::ACTION_VIEW,
            AuditLog::ACTION_LOGIN,
            AuditLog::ACTION_BID,
        ];

        $modules = ['users', 'branches', 'accounts', 'collaterals', 'bids', 'system'];

        // Create 50 sample audit log entries
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $action = $actions[array_rand($actions)];
            $module = $modules[array_rand($modules)];
            
            $recordId = null;
            $oldData = null;
            $newData = null;
            $description = null;

            // Generate realistic data based on action and module
            switch ($module) {
                case 'users':
                    $recordId = $users->random()->id;
                    if ($action === AuditLog::ACTION_UPDATE) {
                        $oldData = ['status' => 'pending_approval'];
                        $newData = ['status' => 'active'];
                        $description = 'User status updated from pending to active';
                    } elseif ($action === AuditLog::ACTION_CREATE) {
                        $newData = ['username' => 'newuser', 'role' => 'bidder'];
                        $description = 'New user account created';
                    }
                    break;

                case 'branches':
                    if (!$branches->isEmpty()) {
                        $recordId = $branches->random()->id;
                        if ($action === AuditLog::ACTION_APPROVE) {
                            $oldData = ['status' => 'pending_approval'];
                            $newData = ['status' => 'active'];
                            $description = 'Branch approved and activated';
                        }
                    }
                    break;

                case 'accounts':
                    if (!$accounts->isEmpty()) {
                        $recordId = $accounts->random()->id;
                        if ($action === AuditLog::ACTION_CREATE) {
                            $newData = ['customer_name' => 'Ahmad bin Ali', 'loan_amount' => 5000];
                            $description = 'New customer account created';
                        }
                    }
                    break;

                case 'collaterals':
                    if (!$collaterals->isEmpty()) {
                        $recordId = $collaterals->random()->id;
                        if ($action === AuditLog::ACTION_UPDATE) {
                            $oldData = ['status' => 'active'];
                            $newData = ['status' => 'ready_for_auction'];
                            $description = 'Collateral moved to auction ready status';
                        }
                    }
                    break;

                case 'bids':
                    if ($action === AuditLog::ACTION_BID) {
                        $recordId = $collaterals->isNotEmpty() ? $collaterals->random()->id : null;
                        $newData = ['bid_amount' => rand(1000, 5000), 'bidder' => $user->username];
                        $description = 'New bid placed on collateral item';
                    }
                    break;

                case 'system':
                    if ($action === AuditLog::ACTION_LOGIN) {
                        $description = 'User logged into the system';
                    }
                    break;
            }

            AuditLog::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'action_type' => $action,
                'module_affected' => $module,
                'record_id_affected' => $recordId,
                'old_data' => $oldData,
                'new_data' => $newData,
                'description' => $description ?: "{$action} performed on {$module}",
            ]);
        }

        $totalLogs = AuditLog::count();
        $this->command->info("Created {$totalLogs} audit log entries");
    }
}
