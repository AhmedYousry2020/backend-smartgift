<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $query = <<<SQL
        INSERT INTO `countries` (`id`, `flag`, `created_at`, `updated_at`) VALUES
        (198, 'https://restcountries.eu/data/sau.svg', '2021-07-14 10:03:42', '2021-07-14 10:03:42'),
        SQL;

        DB::statement($query);
        $query1 = <<<SQL
        INSERT INTO `country_translations` (`id`, `country_id`,`locale`,`name` ,`created_at`, `updated_at`) VALUES
        (2, 198, 'en', 'Saudi Arabia', NULL, NULL),
        (4, 198, 'ar', 'المملكة العربية السعودية', NULL, NULL),
        SQL;

        DB::statement($query1);

    }
}
