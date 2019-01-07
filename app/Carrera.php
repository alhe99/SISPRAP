<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carreras';
    protected $fillable = ['nombre', 'articulado'];
    public $timestamps = false;

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
    public function proyecto(){
        return $this->hasMany(Proyecto::class);
    }

    //Trimestral
    public function getCountStudentsByMinedTrimestral(Array $data,$procesoId)
    {
        return $this->estudiantes()->whereHas('gestionProyecto',function($query) use($data){
            $query->whereMonth('created_at',$data[0])->orWhereMonth('created_at',$data[1])->orWhereMonth('created_at',$data[2])->where('estado','=','I')->orWhere('estado','=','P');
        })->where('tipo_beca_id',1)->whereHas('proceso', function ($query) use($procesoId) {
            $query->where('procesos_estudiantes.proceso_id',$procesoId);
        })->count();
    }

    public function getCountStudentsByOtherBecaTrimestral(Array $data,$procesoId)
    {
        return $this->estudiantes()->whereHas('gestionProyecto',function($query) use ($data){
            $query->whereMonth('created_at',$data[0])->orWhereMonth('created_at',$data[1])->orWhereMonth('created_at',$data[2])->where('estado','=','I')->orWhere('estado','=','P');
        })->where('tipo_beca_id',2)->where('estado',true)->whereHas('proceso', function ($query) use($procesoId) {
            $query->where('procesos_estudiantes.proceso_id',$procesoId);
        })->count();
    }

    //Mes Individual
    public function getCountStudentsByMinedMensual($mes,$procesoId)
    {
        return $this->estudiantes()->whereHas('gestionProyecto',function($query) use($mes){
            $query->whereMonth('created_at',$mes)->where('estado','=','I')->orWhere('estado','=','P');
        })->where('tipo_beca_id',1)->where('estado',true)->whereHas('proceso', function ($query) use($procesoId) {
            $query->where('procesos_estudiantes.proceso_id',$procesoId);
        })->count();
    }

    public function getCountStudentsByOtherBecaMensual($mes,$procesoId)
    {
        return $this->estudiantes()->whereHas('gestionProyecto',function($query) use($mes){
            $query->whereMonth('created_at',$mes)->where('estado','=','I')->orWhere('estado','=','P');
        })->where('tipo_beca_id',2)->where('estado',true)->whereHas('proceso', function ($query) use($procesoId) {
            $query->where('procesos_estudiantes.proceso_id',$procesoId);
        })->count();
    }

    //Reporte Anual
    public function getCountStudentsByMinedYear($year,$procesoId)
    {
        return $this->estudiantes()->whereHas('gestionProyecto',function($query) use($year){
            $query->whereYear('created_at',$year)->where('estado','=','I')->orWhere('estado','=','P');
        })->where('tipo_beca_id',1)->where('estado',true)->whereHas('proceso', function ($query) use($procesoId) {
            $query->where('procesos_estudiantes.proceso_id',$procesoId);
        })->count();
    }

    public function getCountStudentsByOtherBecaYear($year,$procesoId)
    {
        return $this->estudiantes()->whereHas('gestionProyecto',function($query) use($year){
            $query->whereYear('created_at',$year)->where('estado','=','I')->orWhere('estado','=','P');
        })->where('tipo_beca_id',2)->where('estado',true)->whereHas('proceso', function ($query) use($procesoId) {
            $query->where('procesos_estudiantes.proceso_id',$procesoId);
        })->count();
    }

}
