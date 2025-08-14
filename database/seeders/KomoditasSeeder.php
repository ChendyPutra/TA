<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomoditasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('komoditas')->insert([
            ['nama' => 'Padi'],
            ['nama' => 'Sagu'],
            ['nama' => 'Karet'],
            ['nama' => 'Pisang'],
            ['nama' => 'Rambutan'],
            ['nama' => 'Kelapa'],
            ['nama' => 'Ubi'],
            ['nama' => 'Durian'],
            ['nama' => 'Matoa'],
            ['nama' => 'Jagung'],

        ]);

    }
}
