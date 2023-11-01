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
        $this->call(InformationDeviceCategoryTableSeeder::class);
        $this->call(InformationBrandTableSeeder::class);
        $this->call(AssetNumberRulesTableSeeder::class);
    }
}
