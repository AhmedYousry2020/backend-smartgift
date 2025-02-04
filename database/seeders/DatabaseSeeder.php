<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(CountriesTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MosqueSeeder::class);
        $this->call(PortfolioSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SliderSeeder::class);
         $this->call(AdminSeeder::class);



    }
}
