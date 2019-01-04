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
        'nivel_academico_id',
        'supero_limite',
        'proyectos_por_proceso',
        'foto_name',
        'no_proyectos'
    ];

    public function carrera(){

        return $this->belongsTo(Carrera::class,'carrera_id');
    }
    public function nivelAcademico(){

        return $this->belongsTo(NivelAcademico::class,'nivel_academico_id');
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

        return $this->belongsToMany(TipoProceso::class, 'procesos_estudiantes','estudiante_id','proceso_id')->withPivot(['estado','pago_arancel']);
    }
    public function preinscripciones(){

        return $this->belongsToMany(Proyecto::class, 'preinscripciones_proyectos','estudiante_id','proyecto_id')->withPivot(['estado','id','tipo_proyecto'])->withTimestamps();
    }

    public function pagoArancel(){

        return $this->hasMany(PagoArancel::class);
    }
     //QueryScopes

    public function scopeNombre($query,$name){
        if($name)
            return $query->where('nombre','LIKE',"%$name%");
    }
}
