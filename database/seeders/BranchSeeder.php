<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all admin users for assignment
        $adminUsers = User::where('is_admin', true)->get();

        // If no admin users exist, create a default one
        if ($adminUsers->isEmpty()) {
            $adminUser = User::create([
                'name' => 'Default Admin',
                'email' => 'admin@arrahnu.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);
            $adminUsers = collect([$adminUser]);
        }

        $branches = [
            [
                'name' => 'Downtown Auction House',
                'code' => 'DAH001',
                'address' => '123 Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA',
                'phone' => '+1 (555) 123-4567',
                'email' => 'downtown@arrahnu.com',
                'manager_id' => $adminUsers->get(0)?->id, // Admin User
                'is_active' => true,
                'description' => 'Our flagship location in the heart of downtown, featuring premium auction items and luxury collectibles.',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'operating_hours' => [
                    'monday' => ['open' => '09:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                    'wednesday' => ['open' => '09:00', 'close' => '18:00'],
                    'thursday' => ['open' => '09:00', 'close' => '18:00'],
                    'friday' => ['open' => '09:00', 'close' => '20:00'],
                    'saturday' => ['open' => '10:00', 'close' => '17:00'],
                    'sunday' => ['closed' => true],
                ],
            ],
            [
                'name' => 'Westside Gallery',
                'code' => 'WSG002',
                'address' => '456 Art Avenue',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'postal_code' => '90210',
                'country' => 'USA',
                'phone' => '+1 (555) 987-6543',
                'email' => 'westside@arrahnu.com',
                'manager_id' => $adminUsers->get(1)?->id, // John Mitchell
                'is_active' => true,
                'description' => 'Specializing in fine art, antiques, and contemporary pieces from renowned artists.',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'operating_hours' => [
                    'monday' => ['open' => '10:00', 'close' => '19:00'],
                    'tuesday' => ['open' => '10:00', 'close' => '19:00'],
                    'wednesday' => ['open' => '10:00', 'close' => '19:00'],
                    'thursday' => ['open' => '10:00', 'close' => '19:00'],
                    'friday' => ['open' => '10:00', 'close' => '21:00'],
                    'saturday' => ['open' => '09:00', 'close' => '18:00'],
                    'sunday' => ['open' => '12:00', 'close' => '17:00'],
                ],
            ],
            [
                'name' => 'Heritage Auction Center',
                'code' => 'HAC003',
                'address' => '789 Historic Boulevard',
                'city' => 'Chicago',
                'state' => 'IL',
                'postal_code' => '60601',
                'country' => 'USA',
                'phone' => '+1 (555) 456-7890',
                'email' => 'heritage@arrahnu.com',
                'manager_id' => $adminUsers->get(2)?->id, // Sarah Johnson
                'is_active' => true,
                'description' => 'Historic venue featuring vintage items, collectibles, and estate sales.',
                'latitude' => 41.8781,
                'longitude' => -87.6298,
                'operating_hours' => [
                    'monday' => ['open' => '08:30', 'close' => '17:30'],
                    'tuesday' => ['open' => '08:30', 'close' => '17:30'],
                    'wednesday' => ['open' => '08:30', 'close' => '17:30'],
                    'thursday' => ['open' => '08:30', 'close' => '17:30'],
                    'friday' => ['open' => '08:30', 'close' => '19:00'],
                    'saturday' => ['open' => '09:00', 'close' => '16:00'],
                    'sunday' => ['closed' => true],
                ],
            ],
            [
                'name' => 'Luxury Collectibles Hub',
                'code' => 'LCH004',
                'address' => '321 Prestige Plaza',
                'city' => 'Miami',
                'state' => 'FL',
                'postal_code' => '33101',
                'country' => 'USA',
                'phone' => '+1 (555) 321-0987',
                'email' => 'luxury@arrahnu.com',
                'manager_id' => $adminUsers->get(3)?->id, // Michael Chen
                'is_active' => false,
                'description' => 'Premium location for high-end collectibles, jewelry, and luxury items. Currently under renovation.',
                'latitude' => 25.7617,
                'longitude' => -80.1918,
                'operating_hours' => [
                    'monday' => ['closed' => true],
                    'tuesday' => ['closed' => true],
                    'wednesday' => ['closed' => true],
                    'thursday' => ['closed' => true],
                    'friday' => ['closed' => true],
                    'saturday' => ['closed' => true],
                    'sunday' => ['closed' => true],
                ],
            ],
            [
                'name' => 'Pacific Coast Auctions',
                'code' => 'PCA005',
                'address' => '789 Ocean Boulevard',
                'city' => 'San Francisco',
                'state' => 'CA',
                'postal_code' => '94102',
                'country' => 'USA',
                'phone' => '+1 (555) 789-0123',
                'email' => 'pacific@arrahnu.com',
                'manager_id' => $adminUsers->get(4)?->id, // Emily Rodriguez
                'is_active' => true,
                'description' => 'Coastal location specializing in maritime antiques, vintage surfboards, and California art.',
                'latitude' => 37.7749,
                'longitude' => -122.4194,
                'operating_hours' => [
                    'monday' => ['open' => '10:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '10:00', 'close' => '18:00'],
                    'wednesday' => ['open' => '10:00', 'close' => '18:00'],
                    'thursday' => ['open' => '10:00', 'close' => '18:00'],
                    'friday' => ['open' => '10:00', 'close' => '19:00'],
                    'saturday' => ['open' => '09:00', 'close' => '17:00'],
                    'sunday' => ['open' => '11:00', 'close' => '16:00'],
                ],
            ],
            [
                'name' => 'Texas Treasures Auction',
                'code' => 'TTA006',
                'address' => '456 Lone Star Drive',
                'city' => 'Austin',
                'state' => 'TX',
                'postal_code' => '73301',
                'country' => 'USA',
                'phone' => '+1 (555) 456-7891',
                'email' => 'texas@arrahnu.com',
                'manager_id' => $adminUsers->get(5)?->id, // David Thompson
                'is_active' => true,
                'description' => 'Everything is bigger in Texas! Specializing in western memorabilia, oil paintings, and ranch collectibles.',
                'latitude' => 30.2672,
                'longitude' => -97.7431,
                'operating_hours' => [
                    'monday' => ['open' => '09:00', 'close' => '17:00'],
                    'tuesday' => ['open' => '09:00', 'close' => '17:00'],
                    'wednesday' => ['open' => '09:00', 'close' => '17:00'],
                    'thursday' => ['open' => '09:00', 'close' => '17:00'],
                    'friday' => ['open' => '09:00', 'close' => '18:00'],
                    'saturday' => ['open' => '08:00', 'close' => '16:00'],
                    'sunday' => ['closed' => true],
                ],
            ],
            [
                'name' => 'Boston Antique Exchange',
                'code' => 'BAE007',
                'address' => '234 Historic Harbor Way',
                'city' => 'Boston',
                'state' => 'MA',
                'postal_code' => '02101',
                'country' => 'USA',
                'phone' => '+1 (555) 234-5678',
                'email' => 'boston@arrahnu.com',
                'manager_id' => $adminUsers->get(6)?->id, // Lisa Anderson
                'is_active' => true,
                'description' => 'Historic New England location featuring colonial antiques, maritime artifacts, and Revolutionary War memorabilia.',
                'latitude' => 42.3601,
                'longitude' => -71.0589,
                'operating_hours' => [
                    'monday' => ['open' => '09:30', 'close' => '17:30'],
                    'tuesday' => ['open' => '09:30', 'close' => '17:30'],
                    'wednesday' => ['open' => '09:30', 'close' => '17:30'],
                    'thursday' => ['open' => '09:30', 'close' => '17:30'],
                    'friday' => ['open' => '09:30', 'close' => '18:30'],
                    'saturday' => ['open' => '10:00', 'close' => '17:00'],
                    'sunday' => ['open' => '12:00', 'close' => '16:00'],
                ],
            ],
            [
                'name' => 'Rocky Mountain Collectibles',
                'code' => 'RMC008',
                'address' => '567 Mountain View Road',
                'city' => 'Denver',
                'state' => 'CO',
                'postal_code' => '80201',
                'country' => 'USA',
                'phone' => '+1 (555) 567-8901',
                'email' => 'denver@arrahnu.com',
                'manager_id' => $adminUsers->get(7)?->id, // Robert Wilson
                'is_active' => false,
                'description' => 'Mountain region specialty house focusing on Native American artifacts, mining memorabilia, and outdoor gear. Temporarily closed for expansion.',
                'latitude' => 39.7392,
                'longitude' => -104.9903,
                'operating_hours' => [
                    'monday' => ['closed' => true],
                    'tuesday' => ['closed' => true],
                    'wednesday' => ['closed' => true],
                    'thursday' => ['closed' => true],
                    'friday' => ['closed' => true],
                    'saturday' => ['closed' => true],
                    'sunday' => ['closed' => true],
                ],
            ],
        ];

        foreach ($branches as $branchData) {
            Branch::create($branchData);
        }
    }
}
