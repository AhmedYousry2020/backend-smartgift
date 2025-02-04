<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Company;

class ProductSeeder extends Seeder
{
    public function run()
    {

        // Create companies first if they don't already exist
    Company::firstOrCreate(['id' => 1], ['name' => 'Tesla']);
    Company::firstOrCreate(['id' => 4], ['name' => 'Nestle']);

    $products = [
        [
            'company_id' => 1, // Tesla
            'translations' => [
                ['locale' => 'en', 'name' => 'Tesla Water Bottle - Small', 'description' => '500ml Tesla branded water bottle.'],
                ['locale' => 'ar', 'name' => 'زجاجة ماء تيسلا - صغيرة', 'description' => 'زجاجة ماء سعة 500 مل تحمل علامة تيسلا.'],
            ],
            'price' => 10.99,
            'bottle_count' => 1, // 500ml bottle type
        ],
        [
            'company_id' => 4, // Nestle
            'translations' => [
                ['locale' => 'en', 'name' => 'Nestle Pure Life - Small', 'description' => '500ml Nestle Pure Life water bottle.'],
                ['locale' => 'ar', 'name' => 'نستله بيور لايف - صغيرة', 'description' => 'زجاجة ماء نستله بيور لايف سعة 500 مل.'],
            ],
            'price' => 5.49,
            'bottle_count' => 1, // 500ml bottle type
        ],
    ];

    // Insert products and their translations
    foreach ($products as $productData) {
        $product = Product::create([
            'company_id' => $productData['company_id'],
            'price' => $productData['price'],
            'bottle_count' => $productData['bottle_count'],
            'image'=>null
        ]);

        foreach ($productData['translations'] as $translation) {
            $product->translations()->create($translation);
        }
    }
    }
}
