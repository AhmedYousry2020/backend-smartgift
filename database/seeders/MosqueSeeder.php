<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MosqueSeeder extends Seeder
{
    public function run(): void
    {
        // Sample data extracted from Excel (Modify as needed)
        $mosques = [
            ['name' => '(زين) شركة الاتصالات المتنقلة', 'lat' => 29.344935, 'lng' => 47.944224, 'address' => 'الشويخ'],
            ['name' => 'ابداح عبدالرحمن المطيري', 'lat' => 29.293126, 'lng' => 47.883615, 'address' => 'العارضية'],
            ['name' => 'ابن الجوزي', 'lat' => 29.219836, 'lng' => 48.077358, 'address' => 'القصور'],
            ['name' => 'ابن سلامه', 'lat' => 29.321147, 'lng' => 48.079911, 'address' => 'الرميثية'],
            ['name' => 'ابن شرار', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'ابن شرف', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'العاصمة'],
            ['name' => 'ابن شيتان', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'الفروانية'],
            ['name' => 'ابن قطامي', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'العاصمة'],
            ['name' => 'اتحاد الصيادين', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'العاصمة'],
            ['name' => 'الأحمدي الكبير', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'الأحمدي'],
            ['name' => 'الأحنف بن قيس', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الأذينة', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الأرملي', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الأسود بن يزيد', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الأشج المنذر', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'جابر الأحمد'],
            ['name' => 'الأقرع بن حابس', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الأنصار', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الإحسان (مقبرة صبحان)', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'جابر الأحمد'],
            ['name' => 'الإرشاد الديني', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الإسماعيل', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الإمام ابن الصلاح', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'الأحمدي'],
            ['name' => 'الإمام ابن المنذر', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'علي صباح السالم'],
            ['name' => 'الإمام الرضا (جعفري)', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الإمام الربيع بن سليمان', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الإمام الدارمي', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الإمام الدارقطني', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],
            ['name' => 'الإمام الخطابي', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'الأحمدي'],
            ['name' => 'الإمام الحسين (جعفري)', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'علي صباح السالم'],
            ['name' => 'الإمام الحاكم', 'lat' => 29.114075, 'lng' => 47.093368, 'address' => 'السالمي'],

        ];

        // City IDs from 26 to 38 (Assign randomly or sequentially)

        foreach ($mosques as $index => $mosque) {

            // Insert into mosques table
            $mosqueId = DB::table('mosques')->insertGetId([
                'lat' => $mosque['lat'],
                'lng' => $mosque['lng'],
                'address' => $mosque['address'],
                'city_id' => rand(26,35),
                'is_high_need' => rand(0, 1),
                'category_id' => rand(1, 2),
                'image' => 'mosque_images/default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert translations (English & Arabic)
            foreach (['en' => 'Mosque', 'ar' => $mosque['name']] as $locale => $name) {
                DB::table('mosque_translations')->insert([
                    'mosque_id' => $mosqueId,
                    'locale' => $locale,
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
