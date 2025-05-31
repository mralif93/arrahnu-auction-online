<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();
        $maker = User::where('username', 'maker01')->first();
        $checker = User::where('username', 'checker01')->first();

        $branches = [
            [
                'name' => 'Arrahnu Kuala Lumpur',
                'address' => 'No. 123, Jalan Bukit Bintang, 55100 Kuala Lumpur, Malaysia',
                'phone_number' => '+603-2141-8888',
                'status' => Branch::STATUS_ACTIVE,
            ],
            [
                'name' => 'Arrahnu Shah Alam',
                'address' => 'No. 45, Jalan Tengku Ampuan Zabedah, 40100 Shah Alam, Selangor, Malaysia',
                'phone_number' => '+603-5544-7788',
                'status' => Branch::STATUS_ACTIVE,
            ],
            [
                'name' => 'Arrahnu Johor Bahru',
                'address' => 'No. 67, Jalan Wong Ah Fook, 80000 Johor Bahru, Johor, Malaysia',
                'phone_number' => '+607-222-3333',
                'status' => Branch::STATUS_ACTIVE,
            ],
            [
                'name' => 'Arrahnu Penang',
                'address' => 'No. 89, Lebuh Campbell, 10100 George Town, Penang, Malaysia',
                'phone_number' => '+604-261-9999',
                'status' => Branch::STATUS_ACTIVE,
            ],
            [
                'name' => 'Arrahnu Ipoh',
                'address' => 'No. 12, Jalan Sultan Idris Shah, 30000 Ipoh, Perak, Malaysia',
                'phone_number' => '+605-254-1111',
                'status' => Branch::STATUS_ACTIVE,
            ],
            [
                'name' => 'Arrahnu Kota Kinabalu',
                'address' => 'No. 34, Jalan Gaya, 88000 Kota Kinabalu, Sabah, Malaysia',
                'phone_number' => '+608-232-5555',
                'status' => Branch::STATUS_PENDING_APPROVAL,
            ],
            [
                'name' => 'Arrahnu Kuching',
                'address' => 'No. 56, Jalan Padungan, 93100 Kuching, Sarawak, Malaysia',
                'phone_number' => '+608-241-6666',
                'status' => Branch::STATUS_PENDING_APPROVAL,
            ],
            [
                'name' => 'Arrahnu Melaka',
                'address' => 'No. 78, Jalan Hang Tuah, 75300 Melaka, Malaysia',
                'phone_number' => '+606-283-7777',
                'status' => Branch::STATUS_DRAFT,
            ],
        ];

        foreach ($branches as $branchData) {
            Branch::create([
                'id' => Str::uuid(),
                'name' => $branchData['name'],
                'address' => $branchData['address'],
                'phone_number' => $branchData['phone_number'],
                'status' => $branchData['status'],
                'created_by_user_id' => $maker->id,
                'approved_by_user_id' => $branchData['status'] === Branch::STATUS_ACTIVE ? $checker->id : null,
            ]);
        }

        $this->command->info('Created 8 branches: 5 active, 2 pending approval, 1 draft');
    }
}
