<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('sliders')->insert([
                [
                    'title' => 'Анзибел пастили - мед и лимон',
                    'subtitle'=> 'Локално и симптоматско олеснување на благи воспалителни процеси во устата и грлото.',
                    'description' => null,
                    'price' => 100,
                    'image'=> '/assets/slider/1743698279_anzibel pastili.webp',
                ]
        ]);
    }
}
