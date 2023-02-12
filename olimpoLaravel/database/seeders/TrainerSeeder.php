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
            'name' => 'Alejandro',
            'surname' => 'Sanchez',
            'email' => 'alex@elcampico.org',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'phone' => '123456789',
            'specialty' => 'calistenia'
        ]);
        DB::table('trainers')->insert([
            'name' => 'Rafa',
            'surname' => 'Caselles',
            'email' => 'rafa@elcampico.org',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'phone' => '987654321'
        ]);
    }
}
