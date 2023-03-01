<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('information')->insert([
            'instagram' => 'https://www.instagram.com/olimpogymorihuela/',
            'facebook' => 'https://es-es.facebook.com/olimpogymfc/',
            'horario1' => '09:00 - 12:00 / 15:00 - 21:00.',
            'horario2' => '17:00 - 21:00.',
        ]);
    }
}
