<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dia', 'idUsuario', 'idCancha', 'idHorario', 'idComprobante'
    ];

    public function getUsuario(){
        return $this->belongsTo('App\Models\User','idUsuario');
    }

    public function getCancha(){
        return $this->belongsTo('App\Models\Cancha','idCancha');
    }

    public function getHorario(){
        return $this->belongsTo('App\Models\Horario','idHorario');
    }

    public function getComprobante(){
        return $this->belongsTo('App\Models\Comprobante','idComprobante');
    }
}