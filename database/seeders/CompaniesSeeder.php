<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'name' => 'Tesla',
                'translations' => [
                    ['locale' => 'en', 'name' => 'Tesla'],
                    ['locale' => 'ar', 'name' => 'تسلا']
                ],
            ],
            [
                'name' => 'Ford',
                'translations' => [
                    ['locale' => 'en', 'name' => 'Barka'],
                    ['locale' => 'ar', 'name' => 'بركة']
                ],
            ],
            [
                'name' => 'BMW',
                'translations' => [
                    ['locale' => 'en', 'name' => 'Dasani'],
                    ['locale' => 'ar', 'name' => 'دسانى']
                ],
            ]
        ];

        foreach ($companies as $companyData) {
            $company = Company::create([]);
            foreach ($companyData['translations'] as $translation) {
                $company->translations()->create($translation);
            }
        }
    }
}
