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
        'pago_arancel'
    ];

    public function gestionProy()
    {
        return $this->hasOne(GestionProyecto::class);
    }
       
}
