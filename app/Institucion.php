<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'instituciones';
    protected $fillable = ['nombre','direccion','telefono','email','sectorInstitucion_id','municipio_id','estado'];


    public function sectorInstitucion(){

        return $this->belongsTo(SectorInstitucion::class,'sector_institucion_id');
    }

    public function municipio(){

        return $this->belongsTo(Municipio::class,'municipio_id');
    }
    public function procesos(){

        return $this->belongsToMany(TipoProceso::class, 'procesos_instituciones','institucion_id','proceso_id') ;
    }
    public function proyectosInsti(){

        return $this->hasMany(Proyecto::class);
    }
    public function supervisores(){

        return $this->hasMany(Supervisor::class);
    }

    //QueryScopes
    public function scopeNombre($query,$name){
        if($name)
            return $query->where('nombre','LIKE',"%$name%");
    }
}
