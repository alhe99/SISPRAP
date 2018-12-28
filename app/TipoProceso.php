<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoProceso extends Model
{
    protected $table = 'tipos_procesos';
    protected $fillable = ['nombre,horas'];
    public $timestamps = false;

    public function instituciones(){

        return $this->belongsToMany(Institucion::class,'procesos_instituciones','proceso_id','institucion_id');
    }
    public function estudiantes(){

        return $this->belongsToMany(Estudiante::class,'procesos_estudiantes','proceso_id','estudiante_id')->withPivot('estado');
    }
    public function proyectos(){

        return $this->hasMany(Proyectos::class);
    }
    public function documentos(){

        return $this->hasMany(Documento::class);
    }
     public function aranceles(){

        return $this->hasMany(PagoArancel::class);
    }
}
