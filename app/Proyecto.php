<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;

    protected $table = 'proyectos';
    protected $fillable = [
        'nombre',
        'fecha',
        'actividades',
        'institucion_id',
        'proceso_id',
        'img',
        'estado',
        'horas_realizar',
        'cantidades_vacantes',
        'estado_vacantes',
        'tipo_proyecto'
    ];

    protected $cast = [
        'fecha' => 'date:Y-m-d',
    ];

    public function institucion()
    {

        return $this->belongsTo(Institucion::class, 'institucion_id');
    }
    public function tipoProceso()
    {

        return $this->belongsTo('App\TipoProceso', 'proceso_id');
    }
    public function carre_proy()
    {

        return $this->belongsToMany(Carrera::class, 'carrera_proyectos', 'proyecto_id', 'carrera_id');
    }
    public function supervision()
    {
        return $this->hasOne(SupervisionProyecto::class);
    }
    public function preRegistration()
    {
        return $this->belongsToMany(Estudiante::class, 'preinscripciones_proyectos', 'proyecto_id', 'estudiante_id')->withPivot(['estado','fecha_registro'])->withTimestamps();
    }
    public function gestionProyecto(){

        return $this->hasOne(GestionProyecto::class);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'nombre',
            ],
        ];
    }

    //QueryScopes

    public function scopeNombre($query,$name){
        if($name)
            return $query->where('nombre','LIKE',"%$name%");
    }
    public function scopeProceso($query,$proceso){
        if($proceso)
            return $query->where('proceso_id',$proceso);
    }
    public function scopeYear($query,$year){
        return $query->where('fecha_registro',$year);
    }

}
