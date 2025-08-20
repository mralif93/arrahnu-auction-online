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
        // Create admin users
        $admin1 = User::create([
            'id' => Str::uuid(),
            'username' => 'm.alif.tajudin',
            'password_hash' => Hash::make('P@ssw0rd123'),
            'email' => 'm.alif.tajudin@muamalat.com.my',
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'full_name' => 'Muhammad Alif Tajudin',
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

        $admin2 = User::create([
            'id' => Str::uuid(),
            'username' => 'naziha.izzati',
            'password_hash' => Hash::make('P@ssw0rd123'),
            'email' => 'naziha.izzati@muamalat.com.my',
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'full_name' => 'Naziha Izzati Md Puzi',
            'phone_number' => '+60123456788',
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

        $admin3 = User::create([
            'id' => Str::uuid(),
            'username' => 'zaid.azman',
            'password_hash' => Hash::make('P@ssw0rd123'),
            'email' => 'zaid.azman@muamalat.com.my',
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'full_name' => 'Zaid Azman',
            'phone_number' => '+60123456787',
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

        // Display success messages
        $this->command->info('✅ Admin users created successfully!');
        $this->command->info('🔑 Admin credentials:');
        $this->command->info('   Email: m.alif.tajudin@muamalat.com.my');
        $this->command->info('   Email: naziha.izzati@muamalat.com.my');
        $this->command->info('   Email: zaid.azman@muamalat.com.my');
        $this->command->info('   Password: P@ssw0rd123');
    }
}
