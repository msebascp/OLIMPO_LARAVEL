<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trainers')->insert([
            'name' => 'Alejando',
            'surname' => 'Sanchez',
            'email' => 'alex@elcampico.org',
            'phone' => '123456789',
            'specialty' => 'calistenia'
        ]);
        DB::table('trainers')->insert([
            'name' => 'Rafa',
            'surname' => 'Caselles',
            'email' => 'rafa@elcampico.org',
            'phone' => '987654321'
        ]);
    }
}
