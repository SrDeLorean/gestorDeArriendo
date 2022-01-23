<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->date('dia');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign('idUsuario')->references('id')->on('users');
            $table->bigInteger('idCancha')->unsigned();
            $table->foreign('idCancha')->references('id')->on('canchas');
            $table->bigInteger('idHorario')->unsigned();
            $table->foreign('idHorario')->references('id')->on('horarios');
            $table->bigInteger('idComprobante')->unsigned();
            $table->foreign('idComprobante')->references('id')->on('comprobantes');
            $table->timestamps();

            $table->unique(['idCancha','idHorario', 'dia']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
