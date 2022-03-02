<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idUsuario', 'dia', 'bloques', 'inicio', 'termino', 'total', 'idEstado'
    ];

    public function getUsuario(){
        return $this->belongsTo('App\Models\User','idUsuario');
    }

    public function getEstado(){
        return $this->belongsTo('App\Models\Estado','idEstado');
    }
}
