<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = [
            [
                'name' => 'Admin User',
                'email' => 'admin@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@arrahnu.com',
                'password' => Hash::make('superadmin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'John Mitchell',
                'email' => 'john.mitchell@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david.thompson@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Robert Wilson',
                'email' => 'robert.wilson@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jennifer Davis',
                'email' => 'jennifer.davis@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'James Brown',
                'email' => 'james.brown@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        ];

        // Create admin users
        foreach ($adminUsers as $adminData) {
            User::firstOrCreate(
                ['email' => $adminData['email']],
                $adminData
            );
        }

        // Create regular user for testing
        User::firstOrCreate(
            ['email' => 'user@arrahnu.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@arrahnu.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // Create additional regular users
        $regularUsers = [
            [
                'name' => 'Alice Cooper',
                'email' => 'alice.cooper@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob.smith@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carol White',
                'email' => 'carol.white@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Daniel Green',
                'email' => 'daniel.green@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Eva Martinez',
                'email' => 'eva.martinez@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Frank Williams',
                'email' => 'frank.williams@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Grace Taylor',
                'email' => 'grace.taylor@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Henry Jackson',
                'email' => 'henry.jackson@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Isabella Moore',
                'email' => 'isabella.moore@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jack Thompson',
                'email' => 'jack.thompson@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Katherine Lee',
                'email' => 'katherine.lee@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lucas Garcia',
                'email' => 'lucas.garcia@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maya Patel',
                'email' => 'maya.patel@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Nathan Clark',
                'email' => 'nathan.clark@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Olivia Brown',
                'email' => 'olivia.brown@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Peter Wilson',
                'email' => 'peter.wilson@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Quinn Davis',
                'email' => 'quinn.davis@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Rachel Miller',
                'email' => 'rachel.miller@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Samuel Johnson',
                'email' => 'samuel.johnson@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Tiffany Anderson',
                'email' => 'tiffany.anderson@example.com',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
        ];

        // Create regular users
        foreach ($regularUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
