<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MosqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of sample names for mosques
        $names = ['Al-Fatih', 'Al-Haram', 'An-Nur', 'Al-Azhar', 'Al-Noor', 'Al-Taqwa', 'Ibn Taymiyyah', 'Al-Rahma', 'Al-Salam', 'Bilal'];

        for ($i = 1; $i <= 10; $i++) {
            // Insert into mosques table
            $mosqueId = DB::table('mosques')->insertGetId([
                'lat' => fake()->latitude(20.0, 30.0), // Random latitude
                'lng' => fake()->longitude(30.0, 40.0), // Random longitude
                'address' => fake()->address(), // Random address
                'city_id' => rand(26, 40), // Random city ID between 26 and 40
                'is_high_need' => fake()->boolean(30), // 30% chance to be true
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert into mosque_translations table for two locales (en, ar)
            foreach (['en', 'ar'] as $locale) {
                DB::table('mosque_translations')->insert([
                    'mosque_id' => $mosqueId,
                    'locale' => $locale,
                    'name' => $locale === 'en' ? $names[$i - 1] : 'مسجد ' . $names[$i - 1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
