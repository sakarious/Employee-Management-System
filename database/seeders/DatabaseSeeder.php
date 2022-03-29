<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $insert = DB::table('users')->insert([
            'id' => 1,
            'firstname' => "admin",
            'email' => "admin@ems.com",
            'password' => Hash::make('password'),
            'is_admin' => 1
        ]);

        //Create Wallet for Admin
        $wallet = DB::table('wallets')->insert([
            'user_id' => 1,
            'balance' => 999999999
        ]);
    }
}
