<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;

class ReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Reserva::create([
            'dia' => '2022-02-16',
            'idUsuario' => 1,
            'idCancha' => 1,
            'idHorario' => 1,
            'idComprobante' => 1
        ]);
    }
}
