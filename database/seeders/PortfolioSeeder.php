<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portfolio;
use App\Models\PortfolioTranslation;
use App\Models\PortfolioMedia;
use Illuminate\Support\Facades\Storage;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define some sample translations
        $translations = [
            'en' => [
                'title' => 'Sample Portfolio Title',
                'description' => 'This is a sample portfolio description in English.',
            ],
            'ar' => [
                'title' => 'عنوان محفظة عينة',
                'description' => 'هذا وصف محفظة عينة باللغة العربية.',
            ]
        ];
        // Seed 10 random portfolios
        for ($i = 1; $i <= 10; $i++) {
            // Random type
            $type = ['images', 'video'][array_rand(['images', 'video'])];

            // Create portfolio
            $portfolio = Portfolio::create([
                'type' => $type,
            ]);
            // Add translations
            foreach ($translations as $locale => $data) {
                PortfolioTranslation::create([
                    'portfolio_id' => $portfolio->id,
                    'locale' => $locale,
                    'title' => $data['title'] . " $i",
                    'description' => $data['description'] . " $i",
                ]);
            }

            // Add media
            if ($type == 'images') {
                // Add multiple images
                for ($j = 1; $j <= rand(2, 5); $j++) {
                    PortfolioMedia::create([
                        'portfolio_id' => $portfolio->id,
                        'media_path' => "portfolios/images/sample_image_$j.jpg",
                    ]);
                }
            } else {
                // Add a single video
                PortfolioMedia::create([
                    'portfolio_id' => $portfolio->id,
                    'media_path' => "portfolios/videos/sample_video.mp4",
                ]);
            }
        }
    }
}
