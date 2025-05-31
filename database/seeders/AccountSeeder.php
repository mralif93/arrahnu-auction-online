<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activeBranches = Branch::where('status', Branch::STATUS_ACTIVE)->get();
        $maker = User::where('username', 'maker01')->first();
        $checker = User::where('username', 'checker01')->first();

        $accountTitles = [
            'Gold Batch January 2025',
            'Silver Collection Q1 2025',
            'Mixed Precious Metals Lot',
            'High Value Gold Items',
            'Platinum & Gold Mix',
            'Silver Jewelry Collection',
            'Gold Coins & Bars',
            'Antique Jewelry Lot',
            'Modern Gold Jewelry',
            'Traditional Silver Items',
            'Investment Grade Gold',
            'Mixed Metal Accessories',
            'Premium Gold Collection',
            'Silver Ornaments Batch',
            'Luxury Jewelry Lot'
        ];

        $descriptions = [
            'Collection of defaulted gold items ready for auction',
            'Assorted silver jewelry and ornaments from various customers',
            'Mixed lot of precious metal items including gold and silver',
            'High-value gold items including jewelry and investment pieces',
            'Premium collection of platinum and gold items',
            'Traditional and modern silver jewelry collection',
            'Investment grade gold coins and bars',
            'Antique and vintage jewelry pieces',
            'Contemporary gold jewelry designs',
            'Traditional Malaysian silver ornaments',
            'Investment grade gold items for serious collectors',
            'Mixed collection of precious metal accessories',
            'Premium gold jewelry and ornaments',
            'Silver items including jewelry and decorative pieces',
            'Luxury jewelry collection from various sources'
        ];

        foreach ($activeBranches as $branch) {
            // Create 3-6 asset groupings per active branch
            $accountCount = rand(3, 6);

            for ($i = 0; $i < $accountCount; $i++) {
                $title = $accountTitles[array_rand($accountTitles)];
                $description = $descriptions[array_rand($descriptions)];

                $status = rand(1, 100) <= 85 ? Account::STATUS_ACTIVE :
                         (rand(1, 100) <= 70 ? Account::STATUS_PENDING_APPROVAL : Account::STATUS_DRAFT);

                Account::create([
                    'id' => Str::uuid(),
                    'branch_id' => $branch->id,
                    'account_title' => $title . ' - ' . $branch->name,
                    'description' => $description,
                    'status' => $status,
                    'created_by_user_id' => $maker->id,
                    'approved_by_user_id' => $status === Account::STATUS_ACTIVE ? $checker->id : null,
                ]);
            }
        }

        // Create some specific test accounts
        $klBranch = Branch::where('name', 'Arrahnu Kuala Lumpur')->first();
        if ($klBranch) {
            // High-value account
            Account::create([
                'id' => Str::uuid(),
                'branch_id' => $klBranch->id,
                'account_title' => 'Premium Gold Collection - KL Central',
                'description' => 'High-value gold items including investment grade pieces and luxury jewelry',
                'status' => Account::STATUS_ACTIVE,
                'created_by_user_id' => $maker->id,
                'approved_by_user_id' => $checker->id,
            ]);

            // Mixed metals account
            Account::create([
                'id' => Str::uuid(),
                'branch_id' => $klBranch->id,
                'account_title' => 'Mixed Precious Metals - KL Special',
                'description' => 'Diverse collection of gold, silver, and platinum items from various sources',
                'status' => Account::STATUS_ACTIVE,
                'created_by_user_id' => $maker->id,
                'approved_by_user_id' => $checker->id,
            ]);
        }

        $totalAccounts = Account::count();
        $this->command->info("Created {$totalAccounts} asset grouping accounts across all active branches");
    }
}
