<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aspirante extends Model
{
    protected $connection = 'itcha';
    protected $table = 'aspirantes';
    protected $fillable = [
        'codigo',
        'nombres',
        'apellidos',
        'articulado',
        'telefono',
        'nota1',
        'nota2',
        'aprobado',
        'fecha_registro',
        'id_curso',
        'password',
        'id_carrera',
        'tipo_ingreso'

    ];
}
