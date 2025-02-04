<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portfolio;
use App\Models\PortfolioTranslation;
use App\Models\PortfolioMedia;
use Illuminate\Support\Facades\DB;
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disable foreign key checks
        DB::table('portfolios')->truncate(); // Truncate the table
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Enable foreign key checks
    }
}
