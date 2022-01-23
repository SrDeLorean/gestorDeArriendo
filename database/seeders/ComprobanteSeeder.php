<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comprobante;

class ComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comprobante::create([
            'pago' => 10000,
            'idUsuario' => 1,
        ]);
        Comprobante::create([
            'pago' => 20000,
            'idUsuario' => 2,
        ]);
        Comprobante::create([
            'pago' => 30000,
            'idUsuario' => 3,
        ]);
    }
}
