<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_categories')->insert(
            [
            [
            'name' => 'symptomps_conditions',
            ],
            [
            'name' => 'vitamins_dietry_supplements',
            ],
            [
            'name' => 'gels_creams'
            ],
            [
            'name' => 'children_remedies',
            ],
            [
            'name' => 'homeopathic_remedies',
            ],
            [
            'name' => 'baby_food',
            ],
            [
            'name' => 'baby_equipment',
            ],
            [
            'name' => 'cosmetics',
            ],
            [
            'name' => 'oral_health',
            ],
            [
            'name' => 'medical_devices',
            ],
        ]);
    }
}
