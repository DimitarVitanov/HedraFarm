<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductSubcategorySeeder extends Seeder
{

    public function run(): void
    {
        DB::table('product_subcategories')->insert([
            [
            'name' => 'cold',
            'product_category_id' => 1,
            ],
            [
                'name' => 'flue',
                'product_category_id' => 1,
            ],
            [
                'name' => 'cough',
                'product_category_id' => 1,
            ],
            [
                'name' => 'sore_throat',
                'product_category_id' => 1,
            ],
            [
                'name' => 'Stuffy nose',
                'product_category_id' => 1,
            ],
            [
                'name' => 'probiotics_electorlytes',
                'product_category_id' => 1,
            ],
            [
                'name' => 'stress',
                'product_category_id' => 1,
            ],
            [
                'name' => 'urinary_infections',
                'product_category_id' => 1,
            ],
            [
                'name' => 'memory_concentration',
                'product_category_id' => 1,
            ],
            [
                'name' => 'mens_health',
                'product_category_id' => 1,
            ],
            [
                'name' => 'womens_health',
                'product_category_id' => 1,
            ],
            [
                'name' => 'weight_loss',
                'product_category_id' => 1,
            ],
            [
                'name' => 'acidity',
                'product_category_id' => 1,
            ],
            [
                'name' => 'varicose_veins_hemorrhoids',
                'product_category_id' => 1,
            ],
            [
                'name' => 'cholesterol',
                'product_category_id' => 2,
            ],
            [
                'name' => 'iron',
                'product_category_id' => 2,
            ],
            [
                'name' => 'bones_joints',
                'product_category_id' => 2,
            ],
            [
                'name' => 'liver',
                'product_category_id' => 2,
            ],
            [
                'name' => 'skin_hair_nails',
                'product_category_id' => 2,
            ],
            [
                'name' => 'immunity',
                'product_category_id' => 2,
            ],
            [
                'name' => 'pain',
                'product_category_id' => 3,
            ],
            [
                'name' => 'hemooroids',
                'product_category_id' => 3,
            ],
            [
                'name' => 'veins',
                'product_category_id' => 3,
            ],
            [
                'name' => 'immunity_syrups',
                'product_category_id' => 4,
            ],
            [
                'name' => 'cough',
                'product_category_id' => 4,
            ],
            [
                'name' => 'throat_sparys',
                'product_category_id' => 4,
            ],
            [
                'name' => 'nasal_sprays',
                'product_category_id' => 4,
            ],
            [
                'name' => 'conentration_vitamins',
                'product_category_id' => 4,
            ],
            [
                'name' => 'probiotics',
                'product_category_id' => 4,
            ],
            [
                'name'=> 'milk',
                'product_category_id' => 5,
            ],
            [
                'name'=> 'food',
                'product_category_id' => 5,
            ],
            [
                'name' => 'dry_skin',
                'product_category_id' => 6,
            ],
            [
                'name' => 'oily_skin',
                'product_category_id' => 6,
            ],
            [
                'name' => 'combination_skin',
                'product_category_id' => 6,
            ],
            [
                'name' => 'antiperspirants_roll_on',
                'product_category_id' => 6,
            ],
            [
                'name' => 'spf_factors',
                'product_category_id' => 6,
            ],
            [
                'name' => 'soaps',
                'product_category_id' => 6,
            ],
            [
                'name' => 'shampoos',
                'product_category_id' => 6,
            ],
            [
                'name' => 'hair_dyes',
                'product_category_id' => 6,
            ],
            [
                'name' => 'hair_sprays',
                'product_category_id' => 6,
            ],
            [
                'name' => 'perfumes',
                'product_category_id' => 6,
            ],
            [
                'name' => 'toothbrushes',
                'product_category_id' => 7,
            ],
            [
                'name' => 'toothpastes',
                'product_category_id' => 7,
            ],
            [
                'name' => 'mouthwashes',
                'product_category_id' => 7,
            ],
            [
                'name' => 'dental_floss',
                'product_category_id' => 7,
            ],
            [
                'name' => 'interdental_brushes',
                'product_category_id' => 7,
            ],
            [
                'name' => 'blood pressure',
                'product_category_id' => 8,
            ],
            [
                'name' => 'blood_sugar',
                'product_category_id' => 8,
            ],
            [
                'name' => 'inhalers',
                'product_category_id' => 8,
            ],
            [
                'name' => 'sterilizers',
                'product_category_id' => 8,
            ]
    ]);
    }
}
