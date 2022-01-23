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
        Reserva::create([
            'dia' => '2022-02-16',
            'idUsuario' => 1,
            'idCancha' => 1,
            'idHorario' => 2,
            'idComprobante' => 1
        ]);
        Reserva::create([
            'dia' => '2022-02-16',
            'idUsuario' => 1,
            'idCancha' => 1,
            'idHorario' => 3,
            'idComprobante' => 1
        ]);
        Reserva::create([
            'dia' => '2022-02-16',
            'idUsuario' => 2,
            'idCancha' => 3,
            'idHorario' => 3,
            'idComprobante' => 2
        ]);
        Reserva::create([
            'dia' => '2022-02-16',
            'idUsuario' => 2,
            'idCancha' => 3,
            'idHorario' => 4,
            'idComprobante' => 2
        ]);

        Reserva::create([
            'dia' => '2022-02-16',
            'idUsuario' => 2,
            'idCancha' => 3,
            'idHorario' => 8,
            'idComprobante' => 3
        ]);
        Reserva::create([
            'dia' => '2022-02-16',
            'idUsuario' => 3,
            'idCancha' => 3,
            'idHorario' => 9,
            'idComprobante' => 3
        ]);
    }
}
