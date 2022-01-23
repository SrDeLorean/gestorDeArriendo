<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'fullName' => 'Sebastian Ibarra',
            'email' => "xebaelvemgador@gmail.com",
            'role' => 'desarrollador', 
            'password' => bcrypt('Sterek64')
        ]);
        User::create([
            'fullName' => 'Benja',
            'email' => "benjmain.jofre@gmail.com",
            'role' => 'desarrollador', 
            'password' => bcrypt('Sterek64')
        ]);
        User::create([
            'fullName' => 'admin',
            'email' => "admin@gmail.com",
            'role' => 'admin', 
            'password' => bcrypt('Sterek64')
        ]);
        User::create([
            'fullName' => 'client',
            'email' => "client@gmail.com",
            'role' => 'client', 
            'password' => bcrypt('Sterek64')
        ]);
    }
}
