<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'id' => Str::uuid(),
            'username' => 'admin',
            'password_hash' => Hash::make('password'),
            'email' => 'admin@arrahnu.com',
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'full_name' => 'System Administrator',
            'phone_number' => '+60123456789',
            'is_phone_verified' => true,
            'role' => User::ROLE_CHECKER,
            'status' => User::STATUS_ACTIVE,
            'is_admin' => true,
            'is_staff' => true,
            'first_login_at' => now(),
            'last_login_at' => now(),
            'requires_admin_approval' => false,
            'email_verification_required' => false,
            'approved_at' => now(),
        ]);

        $maker = User::create([
            'id' => Str::uuid(),
            'username' => 'maker01',
            'password_hash' => Hash::make('password'),
            'email' => 'maker@arrahnu.com',
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'full_name' => 'Ahmad Maker',
            'phone_number' => '+60123456788',
            'is_phone_verified' => true,
            'role' => User::ROLE_MAKER,
            'status' => User::STATUS_ACTIVE,
            'is_admin' => false,
            'is_staff' => true,
            'created_by_user_id' => $admin->id,
            'approved_by_user_id' => $admin->id,
            'first_login_at' => now(),
            'last_login_at' => now(),
            'requires_admin_approval' => false,
            'email_verification_required' => false,
            'approved_at' => now(),
        ]);

        $checker = User::create([
            'id' => Str::uuid(),
            'username' => 'checker01',
            'password_hash' => Hash::make('password'),
            'email' => 'checker@arrahnu.com',
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'full_name' => 'Siti Checker',
            'phone_number' => '+60123456787',
            'is_phone_verified' => true,
            'role' => User::ROLE_CHECKER,
            'status' => User::STATUS_ACTIVE,
            'is_admin' => false,
            'is_staff' => true,
            'created_by_user_id' => $admin->id,
            'approved_by_user_id' => $admin->id,
            'first_login_at' => now(),
            'last_login_at' => now(),
            'requires_admin_approval' => false,
            'email_verification_required' => false,
            'approved_at' => now(),
        ]);

        $bidders = [];
        for ($i = 1; $i <= 5; $i++) {
            $bidders[] = User::create([
                'id' => Str::uuid(),
                'username' => "bidder" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'password_hash' => Hash::make('password'),
                'email' => "bidder{$i}@example.com",
                'email_verified_at' => now(),
                'is_email_verified' => true,
                'full_name' => "Bidder User {$i}",
                'phone_number' => "+6012345678{$i}",
                'is_phone_verified' => true,
                'role' => User::ROLE_BIDDER,
                'status' => User::STATUS_ACTIVE,
                'is_admin' => false,
                'is_staff' => false,
                'created_by_user_id' => $maker->id,
                'approved_by_user_id' => $checker->id,
                'first_login_at' => now()->subDays(rand(1, 30)),
                'last_login_at' => now()->subHours(rand(1, 24)),
                'requires_admin_approval' => false,
                'email_verification_required' => false,
                'approved_at' => now(),
            ]);
        }

        User::create([
            'id' => Str::uuid(),
            'username' => 'demo',
            'password_hash' => Hash::make('password'),
            'email' => 'demo@arrahnu.com',
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'full_name' => 'Demo User',
            'phone_number' => '+60123456780',
            'is_phone_verified' => true,
            'role' => User::ROLE_BIDDER,
            'status' => User::STATUS_ACTIVE,
            'is_admin' => false,
            'is_staff' => false,
            'created_by_user_id' => $maker->id,
            'approved_by_user_id' => $checker->id,
            'first_login_at' => now(),
            'last_login_at' => now(),
            'requires_admin_approval' => false,
            'email_verification_required' => false,
            'approved_at' => now(),
        ]);

        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'id' => Str::uuid(),
                'username' => "pending" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'password_hash' => Hash::make('password'),
                'email' => "pending{$i}@example.com",
                'email_verified_at' => null,
                'is_email_verified' => false,
                'full_name' => "Pending User {$i}",
                'phone_number' => "+6012345679{$i}",
                'is_phone_verified' => false,
                'role' => User::ROLE_BIDDER,
                'status' => User::STATUS_PENDING_APPROVAL,
                'is_admin' => false,
                'is_staff' => false,
                'created_by_user_id' => $maker->id,
                'requires_admin_approval' => true,
                'email_verification_required' => true,
                'verification_token_expires_at' => now()->addDays(1),
            ]);
        }

        $this->command->info('Created users: 1 admin, 1 maker, 1 checker, 5 bidders, 1 demo, 3 pending approval');
    }
}
