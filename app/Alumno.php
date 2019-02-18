<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $connection = 'itcha';
    protected $table = 'alumnos';
    protected $fillable = [
        'fecha_nac',
        'foto',
        'correo',
        'password',
        'genero',
        'direccion',
        'celular',
        'telefono',
        'dui',
        'red_social',
        'usuario_rs',
        'id_municipio',
        'vivienda_propia',
        'id_vivienda',
        'id_instituto',
        'anio_bach',
        'id_bachillerato',
        'id_carrera',
        'id_carrera2',
        'nombre_padre',
        'telefono_padre',
        'nombre_madre',
        'telefono_madre',
        'nombre_enc ',
        'parentesco_enc',
        'telefono_enc',
        'integrantes_familia',
        'integrantes_trabajan',
        'voluntario',
        'grupo_voluntario',
        'id_deporte',
        'enfermedad',
        'medicamento',
        'medio',
        'aceptacion',
        'observaciones',
    ];

    public function aspirante(){
        return $this->belongsTo(Aspirante::class,'id_aspirante');
    }
}
