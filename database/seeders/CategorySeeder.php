<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create the 'General' category
         $generalCategory = Category::create(['id' => 1]);
         $generalCategory->translations()->createMany([
             ['locale' => 'en', 'name' => 'General','description'=>'General Mosques'], // English
             ['locale' => 'ar', 'name' => 'عام','description' =>'مساجد عامه'],    // Arabic
         ]);

         // Create the 'High Needed' category
         $highNeededCategory = Category::create(['id' => 2]);
         $highNeededCategory->translations()->createMany([
             ['locale' => 'en', 'name' => 'High Needed','description'=>'High Needed Mosques'], // English
             ['locale' => 'ar', 'name' => 'الأكثر احتياجاً','description'=>'المساجد الأكثر احتياجاً'], // Arabic
         ]);
    }
}
