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
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'typeTraining' => 'Calistenia',
            'dateInscription' => today(),
            'lastPayment' => today(),
            'nextPayment' => today()->addMonth(),
            'trainer_id' => 1
        ]);
        DB::table('customers')->insert([
            'name' => 'Raul',
            'surname' => 'Caselles',
            'email' => 'raul@elcampico.org',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'dateInscription' => today(),
            'lastPayment' => today(),
            'nextPayment' => today()->addMonth(),
            'trainer_id' => 1
        ]);
        DB::table('customers')->insert([
            'name' => 'Guillermo',
            'surname' => 'Perez',
            'email' => 'guillermo@elcampico.org',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'typeTraining' => 'Fuerza',
            'dateInscription' => today(),
            'lastPayment' => today(),
            'nextPayment' => today()->addMonth(),
            'trainer_id' => 2
        ]);
        DB::table('customers')->insert([
            'name' => 'Luis',
            'surname' => 'Cacho',
            'email' => 'luis@elcampico.org',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'dateInscription' => today(),
            'lastPayment' => today(),
            'nextPayment' => today()->addMonth(),
        ]);
}
}
