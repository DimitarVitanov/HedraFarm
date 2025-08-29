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
                'name' => 'pain',
                'translated' => 'Болка',
                'product_category_id' => 1,
            ],
            [
            'name' => 'cold',
            'translated' => 'Настинка',
            'product_category_id' => 1,
            ],
            [
                'name' => 'flue',
                'translated' => 'Грип',
                'product_category_id' => 1,
            ],
            [
                'name' => 'cough',
                'translated' => 'Кашлица',
                'product_category_id' => 1,
            ],

            [
                'name' => 'sore_throat',
                'translated' => 'Болно грло',
                'product_category_id' => 1,
            ],
            [
                'name' => 'Stuffy nose',
                'translated' => 'Затнат нос',
                'product_category_id' => 1,
            ],
            [
                'name' => 'probiotics_electorlytes',
                'translated' => 'Пробиотици и електролити',
                'product_category_id' => 1,
            ],
            [
                'name' => 'stress',
                'translated' => 'Стрес',
                'product_category_id' => 1,
            ],
            [
                'name' => 'urinary_infections',
                'translated' => 'Уринарни инфекции',
                'product_category_id' => 1,
            ],
            [
                'name' => 'memory_concentration',
                'translated' => 'Меморија и концентрација',
                'product_category_id' => 1,
            ],
            [
                'name' => 'mens_health',
                'translated' => 'Машко здравје',
                'product_category_id' => 1,
            ],
            [
                'name' => 'womens_health',
                'translated' => 'Женско здравје',
                'product_category_id' => 1,
            ],
            [
                'name' => 'weight_loss',
                'translated' => 'Слабеење',
                'product_category_id' => 1,
            ],
            [
                'name' => 'acidity',
                'translated' => 'Киселини',
                'product_category_id' => 1,
            ],
            [
                'name' => 'varicose_veins_hemorrhoids',
                'translated' => 'Проширени вени и хемороиди',
                'product_category_id' => 1,
            ],
            [
                'name' => 'cholesterol',
                'translated' => 'Холестерол',
                'product_category_id' => 2,
            ],
            [
                'name' => 'iron',
                'translated' => 'Железо',
                'product_category_id' => 2,
            ],
            [
                'name' => 'bones_joints',
                'translated' => 'Коските и зглобовите',
                'product_category_id' => 2,
            ],
            [
                'name' => 'liver',
                'translated' => 'Црн дроб',
                'product_category_id' => 2,
            ],
            [
                'name' => 'skin_hair_nails',
                'translated' => 'Коса, кожа и нокти',
                'product_category_id' => 2,
            ],
            [
                'name' => 'immunity',
                'translated' => 'Имунитет',
                'product_category_id' => 2,
            ],
            [
                'name' => 'pain',
                'translated' => 'Болка ( за загревање/ладење)',
                'product_category_id' => 3,
            ],
            [
                'name' => 'hemooroids',
                'translated' => 'Хемороиди',
                'product_category_id' => 3,
            ],
            [
                'name' => 'veins',
                'translated' => 'Вени',
                'product_category_id' => 3,
            ],
            [
                'name' => 'immunity_syrups',
                'translated' => 'Имунитет',
                'product_category_id' => 4,
            ],
            [
                'name' => 'kids_cough',
                'translated' => 'Кашлица',
                'product_category_id' => 4,
            ],
            [
                'name' => 'throat_sparys',
                'translated' => 'Спреј за грло',
                'product_category_id' => 4,
            ],
            [
                'name' => 'nasal_sprays',
                'translated' => 'Спреј за нос',
                'product_category_id' => 4,
            ],
            [
                'name' => 'conentration_vitamins',
                'translated' => 'Витамини за концентрација',
                'product_category_id' => 4,
            ],
            [
                'name' => 'probiotics',
                'translated' => 'Пробиотици',
                'product_category_id' => 4,
            ],
            [
               'name' => 'pacifier,',
               'translated' => 'Цуцли',
               'product_category_id' => 6
            ],
            [
                'name' => 'bottles',
                'translated' => 'Шишиња',
                'product_category_id' => 6,
            ],
            [
                'name' => 'spoons',
                'translated' => 'Лажици',
                'product_category_id' => 6,
            ],
            [
                'name' => 'plates',
                'translated' => 'Чинии ',
                'product_category_id' => 6,
            ],
            [
                'name' => 'teethers',
                'translated' => 'Глодалки',
                'product_category_id' => 6,
            ],
            [
                'name' => 'thermometers',
                'translated' => 'Термометри',
                'product_category_id' => 6,
            ],
            [
                'name' => 'cosmetics',
                'translated' => 'Козметика',
                'product_category_id' => 6,
            ],
            [
                'name'=> 'milk',
                'translated'=> 'Млеко',
                'product_category_id' => 7,
            ],
            [
                'name'=> 'food',
                'translated'=> 'Храна',
                'product_category_id' => 7,
            ],
            [
                'name' => 'dry_skin',
                'translated' => 'Сува кожа',
                'product_category_id' => 8,
            ],
            [
                'name' => 'oily_skin',
                'translated' => 'Масна кожа',
                'product_category_id' => 8,
            ],
            [
                'name' => 'combination_skin',
                'translated' => 'Комбинирана кожа',
                'product_category_id' => 8,
            ],
            [
                'name' => 'antiperspirants_roll_on',
                'translated' => 'Антиперспиранти',
                'product_category_id' => 8,
            ],
            [
                'name' => 'spf_factors',
                'translated' => 'SPF фактори',
                'product_category_id' => 8,
            ],
            [
                'name' => 'soaps',
                'translated' => 'Сапуни',
                'product_category_id' => 8,
            ],
            [
                'name' => 'shampoos',
                'translated' => 'Шампони',
                'product_category_id' => 8,
            ],
            [
                'name' => 'hair_dyes',
                'translated' => 'Фарби за коса',
                'product_category_id' => 8,
            ],
            [
                'name' => 'hair_sprays',
                'translated' => 'Лак за коса',
                'product_category_id' => 8,
            ],
            [
                'name' => 'perfumes',
                'translated' => 'Парфеми',
                'product_category_id' => 8,
            ],
            [
                'name' => 'toothbrushes',
                'translated' => 'Четки за заби',
                'product_category_id' => 9,
            ],
            [
                'name' => 'toothpastes',
                'translated' => 'Пасти за заби',
                'product_category_id' => 9,
            ],
            [
                'name' => 'mouthwashes',
                'translated' => 'Течности за уста',
                'product_category_id' => 9,
            ],
            [
                'name' => 'dental_floss',
                'translated' => 'Забен конец',
                'product_category_id' => 9,
            ],
            [
                'name' => 'interdental_brushes',
                'translated' => 'Интердентални четки',
                'product_category_id' => 9,
            ],
            [
                'name' => 'blood pressure',
                'translated' => 'Крвен притисок',
                'product_category_id' => 10,
            ],
            [
                'name' => 'blood_sugar',
                'translated' => 'Шеќер',
                'product_category_id' => 10,
            ],
            [
                'name' => 'inhalers',
                'translated' => 'Инхалатори',
                'product_category_id' => 10,
            ],
            [
                'name' => 'sterilizers',
                'translated' => 'Стерилизатори',
                'product_category_id' => 10,
            ]


    ]);
    }
}
