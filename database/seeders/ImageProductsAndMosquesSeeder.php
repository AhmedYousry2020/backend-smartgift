<?php

namespace Database\Seeders;

use App\Models\Mosque;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageProductsAndMosquesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(Product::all() as $product)
        {
            $product->image ="product_images/image.jfif";
            $product->save();
        }

        foreach(Mosque::all() as $mosque)
        {
            $mosque->image ="mosque_images/image.jfif";
            $mosque->save();
        }
    }
}
