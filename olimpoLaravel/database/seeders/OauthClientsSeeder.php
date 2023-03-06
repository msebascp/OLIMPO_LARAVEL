<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            'id' => 13,
            'name' => 'Customers',
            'secret' => 'SgK9gniC50uIwvebBP7D0qNaeqDM9EnKcInpw8Dn',
            'redirect' => 'http://localhost',
            'provider' => 'customers',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
        ]);
        DB::table('oauth_clients')->insert([
            'id' => 15,
            'name' => 'Trainers',
            'secret' => 'GRp1YCgt07NCJdLGCNbojmKWwSAd4uiXHT2K7wOq',
            'redirect' => 'http://localhost',
            'provider' => 'trainers',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
        ]);
    }
}
