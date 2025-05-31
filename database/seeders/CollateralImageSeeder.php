<?php

namespace Database\Seeders;

use App\Models\Collateral;
use App\Models\CollateralImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CollateralImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collaterals = Collateral::all();

        $sampleImages = [
            'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400',
            'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=400',
            'https://images.unsplash.com/photo-1506630448388-4e683c67ddb0?w=400',
            'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=400',
            'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=400',
            'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400',
            'https://images.unsplash.com/photo-1611652022419-a9419f74343d?w=400',
            'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?w=400',
        ];

        foreach ($collaterals as $collateral) {
            // Create 2-4 images per collateral
            $imageCount = rand(2, 4);

            for ($i = 0; $i < $imageCount; $i++) {
                CollateralImage::create([
                    'id' => Str::uuid(),
                    'collateral_id' => $collateral->id,
                    'image_url' => $sampleImages[array_rand($sampleImages)],
                    'is_thumbnail' => $i === 0, // First image is thumbnail
                    'order_index' => $i,
                ]);
            }
        }

        $totalImages = CollateralImage::count();
        $this->command->info("Created {$totalImages} collateral images");
    }
}
