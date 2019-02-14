<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GestionProyecto extends Model
{
    protected $table = 'gestion_proyectos';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'horas_realizadas',
        'horas_a_realizar',
        'estudiante_id',
        'proyecto_id',
        'estado',
        'nombre_supervisor',
        'tel_supervisor',
        'observacion_final',
        'tipo_gp '
    ];

    public function preinscripcion()
    {
        return $this->belongsTo(PreinscripcionProyecto::class, 'preinscripcion_id');
    }
    public function constancia_entreg()
    {
        return $this->hasMany(ConstanciaEntregada::class);
    }
    public function documentos_entrega()
    {
        return $this->belongsToMany(Documento::class, 'documentos_entregados','gestion_proyecto_id','documento_id')->withPivot(['observacion','estado'])->withTimestamps();;
    }
    public function estudiante(){

        return $this->belongsTo(Estudiante::class,'estudiante_id');
    }
    public function proyecto(){

        return $this->belongsTo(Proyecto::class,'proyecto_id');
    }
    public function scopeYear($query,$year){
        return $query->where('fecha_registro',$year);
    }

}
