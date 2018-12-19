<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    protected $fillable = [
        'nombre',
        'apellido',
        'fechaNac',
        'genero',
        'telefono',
        'codCarnet',
        'password',
        'email',
        'direccion',
        'tipo_beca_id',
        'estado',
        'carrera_id',
        'municipio_id',
        'supero_limite',
        'proyectos_por_proceso',
        'foto_name',
        'no_proyectos'
    ];

    public $timestamps = false;
    public function carrera(){

        return $this->belongsTo(Carrera::class,'carrera_id');
    }
    public function municipio(){

        return $this->belongsTo(Municipio::class);
    }
    public function usuario(){
        
        return $this->hasOne(User::class);
    }
    public function gestionProyecto(){
        
        return $this->hasOne(GestionProyecto::class);
    }
    public function proceso(){

        return $this->belongsToMany(TipoProceso::class, 'procesos_estudiantes','estudiante_id','proceso_id')->withPivot('estado');
    }
    public function preinscripciones(){

        return $this->belongsToMany(Proyecto::class, 'preinscripciones_proyectos','estudiante_id','proyecto_id')->withPivot(['estado','id'])->withTimestamps();
    }

    public function pagoArancel(){
        
        return $this->hasMany(PagoArancel::class);
    }
}
