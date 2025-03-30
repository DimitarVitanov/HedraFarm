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
            'translated' => 'Симптоми и состојби'
            ],
            [
            'name' => 'vitamins_dietry_supplements',
            'translated' => 'додатоци во исхрана'
            ],
            [
            'name' => 'gels_creams',
            'translated' => 'Гелови и кремови'
            ],
            [
            'name' => 'children_remedies',
            'translated' => 'Препарати за деца ',
            ],
            [
            'name' => 'homeopathic_remedies',
            'translated' => 'Хомеопатски препарати',
            ],
            [
            'name' => 'baby_food',
            'translated' => 'Храна за бебиња',
            ],
            [
            'name' => 'baby_equipment',
            'translated' => 'Опрема за бебиња',
            ],
            [
            'name' => 'cosmetics',
            'translated' => 'Козметика',
            ],
            [
            'name' => 'oral_health',
            'translated' => 'Орално здравје',
            ],
            [
            'name' => 'medical_devices',
            'translated' => 'Апарати',
            ],
        ]);
    }
}
