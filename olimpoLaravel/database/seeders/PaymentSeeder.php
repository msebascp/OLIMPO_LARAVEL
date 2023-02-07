<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payments')->insert([
            'payment_type' => 'efectivo',
            'payment_date' => '01/01/2022',
            'paid' => true,
            'customer_id' => 1
        ]);
        DB::table('payments')->insert([
            'payment_type' => 'efectivo',
            'payment_date' => '01/02/2022',
            'paid' => true,
            'customer_id' => 1
        ]);
    }
}
