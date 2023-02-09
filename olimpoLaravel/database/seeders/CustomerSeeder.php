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
                'phone' => '123456789',
                'registration_date' => '01/01/2021',
                'typeTraining' => 'Calistenia',
                'dateInscription' => '01/01/2022',
                'trainer_id' => 1
            ]);
            DB::table('customers')->insert([
                'name' => 'Raul',
                'surname' => 'Caselles',
                'email' => 'raul@elcampico.org',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'phone' => '987654321',
                'registration_date' => '01/01/2020',
                'dateInscription' => '01/01/2022',
                'trainer_id' => 1
            ]);
            DB::table('customers')->insert([
                'name' => 'Guillermo',
                'surname' => 'Perez',
                'email' => 'guillermo@elcampico.org',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'phone' => '147258369',
                'registration_date' => '01/01/2022',
                'typeTraining' => 'Fuerza',
                'dateInscription' => '01/01/2022',
                'trainer_id' => 2
            ]);
            DB::table('customers')->insert([
                'name' => 'Luis',
                'surname' => 'Cacho',
                'email' => 'luis@elcampico.org',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'phone' => '999999999',
                'registration_date' => '01/01/2022',
                'dateInscription' => '01/01/2022',
            ]);
        }
    }
