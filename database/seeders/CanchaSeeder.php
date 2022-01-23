<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cancha;

class CanchaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cancha::create([
            'descripcion' => 'Pasto sintetico azul'
        ]);
        Cancha::create([
            'descripcion' => 'Pasto sintetico verde'
        ]);
        Cancha::create([
            'descripcion' => 'Pasto natural azul'
        ]);
        Cancha::create([
            'descripcion' => 'Pasto natural azul'
        ]);
    }
}
