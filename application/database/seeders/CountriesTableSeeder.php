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
        // Load countries from JSON file
        $countriesJson = file_get_contents(resource_path('views/includes/country.json'));
        $countries = json_decode($countriesJson, true);
        
        // Clear existing data
        DB::table('countries')->truncate();
        
        // Insert countries data
        foreach ($countries as $code => $data) {
            DB::table('countries')->insert([
                'code' => $code,
                'country' => $data['country'],
                'dial_code' => $data['dial_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
