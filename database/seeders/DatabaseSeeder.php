<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       $this->call(UserSeeder::class);
       $this->call(TeamSeeder::class);
       $this->call(CompanyInfoSeeder::class);
       $this->call(ProductCategorySeeder::class);
       $this->call(ProductSubcategorySeeder::class);
       $this->call(BlogSeeder::class);
       $this->call(ProductSeeder::class);
       $this->call(SliderSeeder::class);

    }
}
