<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Company;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'company_id' => 1, // Example company
                'translations' => [
                    ['locale' => 'en', 'name' => 'Tesla Water Bottle - Small', 'description' => '500ml Tesla branded water bottle.'],
                    ['locale' => 'ar', 'name' => 'زجاجة ماء تيسلا - صغيرة', 'description' => 'زجاجة ماء سعة 500 مل تحمل علامة تيسلا.'],
                ],
                'price' => 10.99,
                'bottle_count' => 1, // Assuming this ID exists for 500ml bottle type
            ],
            [
                'company_id' =>1,
                'translations' => [
                    ['locale' => 'en', 'name' => 'Tesla Water Bottle - Medium', 'description' => '1L Tesla branded water bottle.'],
                    ['locale' => 'ar', 'name' => 'زجاجة ماء تيسلا - متوسطة', 'description' => 'زجاجة ماء سعة 1 لتر تحمل علامة تيسلا.'],
                ],
                'price' => 15.99,
                'bottle_count' => 2, // Assuming this ID exists for 1L bottle type
            ],
            [
                'company_id' => 4, // Example company
                'translations' => [
                    ['locale' => 'en', 'name' => 'Nestle Pure Life - Small', 'description' => '500ml Nestle Pure Life water bottle.'],
                    ['locale' => 'ar', 'name' => 'نستله بيور لايف - صغيرة', 'description' => 'زجاجة ماء نستله بيور لايف سعة 500 مل.'],
                ],
                'price' => 5.49,
                'bottle_count' => 1, // 500ml bottle type
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'company_id' => $productData['company_id'],
                'price' => $productData['price'],
                'bottle_count' => $productData['bottle_count'],
            ]);

            foreach ($productData['translations'] as $translation) {
                $product->translations()->create($translation);
            }
        }
    }
}
