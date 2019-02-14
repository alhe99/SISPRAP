<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreinscripcionProyecto extends Model
{
    protected $table = 'preinscripciones_proyectos';

    protected $fillable = [
        'estudiante_id',
        'proyecto_id',
        'estado',
        'fecha_registro'
    ];

    public function gestionProy()
    {
        return $this->hasOne(GestionProyecto::class);
    }
    public function scopeYear($query,$year){
        return $query->where('fecha_registro',$year);
    }
       
}
