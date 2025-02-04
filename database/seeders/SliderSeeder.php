<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Example: Storing a fake slider image
          $imagePath = 'uploads/slider_images/' . 'sdd.jpg';

          // Store a placeholder image or your custom image
          // You can replace 'https://via.placeholder.com/600x400' with a real image path

          // Seeding Slider model with image path
          Slider::create([
              'image' => $imagePath, // Path to the slider image in storage
          ]);
    }
}
