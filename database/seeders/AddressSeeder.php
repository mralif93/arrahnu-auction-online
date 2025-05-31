<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        $malaysianStates = [
            'Kuala Lumpur',
            'Selangor',
            'Johor',
            'Penang',
            'Perak',
            'Kedah',
            'Kelantan',
            'Terengganu',
            'Pahang',
            'Negeri Sembilan',
            'Melaka',
            'Sabah',
            'Sarawak',
            'Perlis',
            'Putrajaya',
            'Labuan'
        ];

        $cities = [
            'Kuala Lumpur' => ['Kuala Lumpur', 'Cheras', 'Ampang', 'Petaling Jaya'],
            'Selangor' => ['Shah Alam', 'Subang Jaya', 'Klang', 'Kajang'],
            'Johor' => ['Johor Bahru', 'Skudai', 'Batu Pahat', 'Muar'],
            'Penang' => ['George Town', 'Butterworth', 'Bukit Mertajam', 'Nibong Tebal'],
            'Perak' => ['Ipoh', 'Taiping', 'Teluk Intan', 'Kampar'],
        ];

        $streetNames = [
            'Jalan Bukit Bintang',
            'Jalan Raja Chulan',
            'Jalan Ampang',
            'Jalan Tun Razak',
            'Jalan Damansara',
            'Jalan Genting Klang',
            'Jalan Cheras',
            'Jalan Kepong',
            'Jalan Setapak',
            'Jalan Segambut'
        ];

        foreach ($users as $user) {
            $state = $malaysianStates[array_rand($malaysianStates)];
            $cityOptions = $cities[$state] ?? [$state];
            $city = $cityOptions[array_rand($cityOptions)];
            $street = $streetNames[array_rand($streetNames)];
            
            // Create primary address
            $primaryAddress = Address::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'address_line_1' => rand(1, 999) . ', ' . $street,
                'address_line_2' => rand(1, 20) . '-' . rand(1, 50),
                'city' => $city,
                'state' => $state,
                'postcode' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                'country' => 'Malaysia',
                'is_primary' => true,
            ]);

            // Update user's primary address
            $user->update(['primary_address_id' => $primaryAddress->id]);

            // 30% chance to create a secondary address
            if (rand(1, 100) <= 30) {
                $secondaryState = $malaysianStates[array_rand($malaysianStates)];
                $secondaryCityOptions = $cities[$secondaryState] ?? [$secondaryState];
                $secondaryCity = $secondaryCityOptions[array_rand($secondaryCityOptions)];
                $secondaryStreet = $streetNames[array_rand($streetNames)];
                
                Address::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'address_line_1' => rand(1, 999) . ', ' . $secondaryStreet,
                    'address_line_2' => 'Unit ' . rand(1, 20) . '-' . rand(1, 50),
                    'city' => $secondaryCity,
                    'state' => $secondaryState,
                    'postcode' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                    'country' => 'Malaysia',
                    'is_primary' => false,
                ]);
            }
        }

        $this->command->info('Created addresses for all users with primary addresses set');
    }
}
