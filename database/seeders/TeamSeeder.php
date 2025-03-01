<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('teams')->insert([
            [
            'name' => 'admin',
            ],
            [
            'name' => 'moderator',
            ],
            [
            'name' => 'client',
            ]
    ]);
    }
}
