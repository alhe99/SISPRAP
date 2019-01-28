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

    //******* REPORTE DE ESTUDIANTES QUE HAN INCIADO SU PROCESO *******//

    //Trimestral
    public function getCountStudentsByMinedTrimestral(Array $data,$procesoId,$anio)
    {
        switch ($procesoId) {
            case 1:
                return $this->estudiantes()->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereDay('fecha_inicio_ss','<=',date('d'))->whereYear('fecha_registro',$anio)->whereMonth('fecha_inicio_ss',$data[0])->orWhereMonth('fecha_inicio_ss',$data[1])->orWhereMonth('fecha_inicio_ss',$data[2])->count();
            break;
            case 2:
                return $this->estudiantes()->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereDay('fecha_inicio_pp','<=',date('d'))->whereYear('fecha_registro',$anio)->whereMonth('fecha_inicio_pp',$data[0])->orWhereMonth('fecha_inicio_pp',$data[1])->orWhereMonth('fecha_inicio_pp',$data[2])->count();
            break;
        }
    }

    public function getCountStudentsByOtherBecaTrimestral(Array $data,$procesoId,$anio)
    {
         switch ($procesoId) {
            case 1:
                return $this->estudiantes()->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->whereDay('fecha_inicio_ss','<=',date('d'))->whereYear('fecha_registro',$anio)->whereMonth('fecha_inicio_ss',$data[0])->orWhereMonth('fecha_inicio_ss',$data[1])->orWhereMonth('fecha_inicio_ss',$data[2])->count();
            break;
            case 2:
                return $this->estudiantes()->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->whereDay('fecha_inicio_pp','<=',date('d'))->whereYear('fecha_registro',$anio)->whereMonth('fecha_inicio_pp',$data[0])->orWhereMonth('fecha_inicio_pp',$data[1])->orWhereMonth('fecha_inicio_pp',$data[2])->count();
            break;
        }
    }

    //Mes Individual
    public function getCountStudentsByMinedMensual($mes,$procesoId,$anio)
    {
        switch ($procesoId) {
            case 1:
                return $this->estudiantes()->whereMonth('fecha_inicio_ss',$mes)->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereYear('fecha_registro',$anio)->whereDay('fecha_inicio_ss','<=',date('d'))->count();
            break;
            case 2:
                return $this->estudiantes()->whereMonth('fecha_inicio_pp',$mes)->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereYear('fecha_registro',$anio)->whereDay('fecha_inicio_pp','<=',date('d'))->count();
                break;
        }
    }

    public function getCountStudentsByOtherBecaMensual($mes,$procesoId,$anio)
    {
        switch ($procesoId) {
            case 1:
                return $this->estudiantes()->whereMonth('fecha_inicio_ss',$mes)->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->whereYear('fecha_registro',$anio)->whereDay('fecha_inicio_ss','<=',date('d'))->count();
            break;
            case 2:
                return $this->estudiantes()->whereMonth('fecha_inicio_pp',$mes)->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->whereYear('fecha_registro',$anio)->whereDay('fecha_inicio_pp','<=',date('d'))->count();
            break;
        }
    }

    //Reporte Anual
    public function getCountStudentsByMinedYear($year,$procesoId)
    {
        switch ($procesoId) {
            case 1:
                return $this->estudiantes()->whereYear('fecha_inicio_ss',$year)->whereYear('fecha_registro',$year)->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereDay('fecha_inicio_ss','<=',date('d'))->count();
            break;
            case 2:
                return $this->estudiantes()->whereYear('fecha_inicio_pp',$year)->whereYear('fecha_registro',$year)->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereDay('fecha_inicio_pp','<=',date('d'))->count();
            break;
        }
    }

    public function getCountStudentsByOtherBecaYear($year,$procesoId)
    {
       switch ($procesoId) {
            case 1:
                return $this->estudiantes()->whereYear('fecha_inicio_ss',$year)->whereYear('fecha_registro',$year)->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->whereDay('fecha_inicio_ss','<=',date('d'))->count();
            break;
            case 2:
                return $this->estudiantes()->whereYear('fecha_inicio_pp',$year)->whereYear('fecha_registro',$year)->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->whereDay('fecha_inicio_pp','<=',date('d'))->count();
            break;
        }
    }

}
