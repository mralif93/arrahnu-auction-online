<?php

namespace Database\Seeders;

use App\Models\Collateral;
use App\Models\CollateralImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CollateralImageSeeder extends Seeder
{

    public function run(): void
    {
        $collaterals = Collateral::all();

        $sampleImages = [
            'https://via.placeholder.com/800x600/FFD700/000000?text=Gold+Ring',
            'https://via.placeholder.com/800x600/C0C0C0/000000?text=Silver+Chain',
            'https://via.placeholder.com/800x600/E5E4E2/000000?text=Platinum+Item',
            'https://via.placeholder.com/800x600/B87333/000000?text=Diamond+Jewelry',
            'https://via.placeholder.com/800x600/DAA520/000000?text=Gold+Necklace',
            'https://via.placeholder.com/800x600/CD7F32/000000?text=Bronze+Item',
            'https://via.placeholder.com/800x600/FFF8DC/000000?text=Pearl+Jewelry',
            'https://via.placeholder.com/800x600/708090/000000?text=Precious+Stone',
        ];

        foreach ($collaterals as $collateral) {

            $imageCount = rand(2, 4);

            for ($i = 0; $i < $imageCount; $i++) {
                CollateralImage::create([
                    'id' => Str::uuid(),
                    'collateral_id' => $collateral->id,
                    'image_url' => $sampleImages[array_rand($sampleImages)],
                    'is_thumbnail' => $i === 0,
                    'order_index' => $i,
                ]);
            }
        }

        $totalImages = CollateralImage::count();
        $this->command->info("Created {$totalImages} collateral images");
    }
}
