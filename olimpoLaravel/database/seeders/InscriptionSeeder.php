<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inscriptions')->insert([
            'registration_date' => '11/12/2022',
            'payment_type' => 'efectivo',
            'customer_id' => 2
        ]);
        DB::table('inscriptions')->insert([
            'registration_date' => '01/01/2023',
            'payment_type' => 'efectivo',
            'customer_id' => 3
        ]);
    }
}
