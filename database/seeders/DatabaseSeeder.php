<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(CanchaSeeder::class);
        $this->call(HorarioSeeder::class);
        $this->call(ComprobanteSeeder::class);
        $this->call(ReservaSeeder::class);
    }
}
