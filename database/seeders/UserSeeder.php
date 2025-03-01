<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Dimitar Vitanov',
            'email' => 'vitanov1@yahoo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('_admin123'),
        ]);
    }
}
