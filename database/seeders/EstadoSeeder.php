<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado::create([
            'descripcion' => 'Reservada'
        ]);
        Estado::create([
            'descripcion' => 'Pagada'
        ]);
        Estado::create([
            'descripcion' => 'Cancelada'
        ]);
    }
}
