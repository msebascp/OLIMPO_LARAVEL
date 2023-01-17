<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            'name' => 'Gabriel',
            'surname' => 'Sanchez',
            'email' => 'gabriel@elcampico.org',
            'phone' => '123456789',
            'trainer_id' => 1
        ]);
        DB::table('customers')->insert([
            'name' => 'Raul',
            'surname' => 'Caselles',
            'email' => 'raul@elcampico.org',
            'phone' => '987654321',
            'trainer_id' => 1
        ]);
        DB::table('customers')->insert([
            'name' => 'Guillermo',
            'surname' => 'Perez',
            'email' => 'guillermo@elcampico.org',
            'phone' => '147258369',
            'trainer_id' => 2
        ]);
    }
}
