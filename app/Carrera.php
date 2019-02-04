<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carreras';
    protected $fillable = ['nombre', 'articulado', 'estado'];
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
                $data = $this->estudiantes()->select('id','fecha_inicio_ss')->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereYear('fecha_registro',$anio)
                ->whereIn(DB::raw('MONTH(fecha_inicio_ss)'), [$data[0],$data[1],$data[2]])->get();

                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_ss,5,2) == date('m') and substr($value->fecha_inicio_ss,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
            case 2:
                $data = $this->estudiantes()->select('id','fecha_inicio_pp')->where([['tipo_beca_id',1],['estado',true]])->whereYear('fecha_registro',$anio)
                ->whereIn(DB::raw('MONTH(fecha_inicio_pp)'), [$data[0],$data[1],$data[2]])->get();
                
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_pp,5,2) == date('m') and substr($value->fecha_inicio_pp,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
        }
    }

    public function getCountStudentsByOtherBecaTrimestral(Array $data,$procesoId,$anio)
    {
          switch ($procesoId) {
            case 1:
                $data = $this->estudiantes()->select('id','fecha_inicio_ss')->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->whereYear('fecha_registro',$anio)
                ->whereIn(DB::raw('MONTH(fecha_inicio_ss)'), [$data[0],$data[1],$data[2]])->get();

                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_ss,5,2) == date('m') and substr($value->fecha_inicio_ss,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
            case 2:
                $data = $this->estudiantes()->select('id','fecha_inicio_pp')->where([['tipo_beca_id',2],['estado',true]])->whereYear('fecha_registro',$anio)
                ->whereIn(DB::raw('MONTH(fecha_inicio_pp)'), [$data[0],$data[1],$data[2]])->get();
                
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_pp,5,2) == date('m') and substr($value->fecha_inicio_pp,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
        }
    }

    //Mes Individual
    public function getCountStudentsByMinedMensual($mes,$procesoId,$anio)
    {
        switch ($procesoId) {
            case 1:
                $data = $this->estudiantes()->select('id','fecha_inicio_ss')->whereMonth('fecha_inicio_ss',$mes)->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereYear('fecha_registro',$anio)->get();
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_ss,5,2) == date('m') and substr($value->fecha_inicio_ss,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
            case 2:
                $data = $this->estudiantes()->select('id','fecha_inicio_pp')->whereMonth('fecha_inicio_pp',$mes)->where([['tipo_beca_id',1],['estado',true]])->whereYear('fecha_registro',$anio)->get();
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_pp,5,2) == date('m') and substr($value->fecha_inicio_pp,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
                break;
        }
    }

    public function getCountStudentsByOtherBecaMensual($mes,$procesoId,$anio)
    {
         switch ($procesoId) {
            case 1:
                $data = $this->estudiantes()->select('id','fecha_inicio_ss')->whereMonth('fecha_inicio_ss',$mes)->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->whereYear('fecha_registro',$anio)->get();
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_ss,5,2) == date('m') and substr($value->fecha_inicio_ss,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
            case 2:
                $data = $this->estudiantes()->select('id','fecha_inicio_pp')->whereMonth('fecha_inicio_pp',$mes)->where([['tipo_beca_id',2],['estado',true]])->whereYear('fecha_registro',$anio)->get();
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_pp,5,2) == date('m') and substr($value->fecha_inicio_pp,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
                break;
        }
    }

    //Reporte Anual
    public function getCountStudentsByMinedYear($year,$procesoId)
    {
        switch ($procesoId) {
            case 1:
                $data = $this->estudiantes()->select('id','fecha_inicio_ss')->whereYear('fecha_inicio_ss',$year)->whereYear('fecha_registro',$year)->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->get();
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_ss,5,2) == date('m') and substr($value->fecha_inicio_ss,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
            case 2:
                $data = $this->estudiantes()->select('id','fecha_inicio_pp')->whereYear('fecha_inicio_pp',$year)->whereYear('fecha_registro',$year)->where([['tipo_beca_id',1],['estado',true]])->get();
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_pp,5,2) == date('m') and substr($value->fecha_inicio_pp,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
        }
    }

    public function getCountStudentsByOtherBecaYear($year,$procesoId)
    {
      switch ($procesoId) {
            case 1:
                $data = $this->estudiantes()->select('id','fecha_inicio_ss')->whereYear('fecha_inicio_ss',$year)->whereYear('fecha_registro',$year)->where([['articulado',false],['tipo_beca_id',2],['estado',true]])->get();
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_ss,5,2) == date('m') and substr($value->fecha_inicio_ss,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
            case 2:
                $data = $this->estudiantes()->select('id','fecha_inicio_pp')->whereYear('fecha_inicio_pp',$year)->whereYear('fecha_registro',$year)->where([['tipo_beca_id',2],['estado',true]])->get();
                foreach ($data as $key => $value) {
                  if(substr($value->fecha_inicio_pp,5,2) == date('m') and substr($value->fecha_inicio_pp,8,2) > date('d')){
                        $data = $data->except($value->id);
                  }
                }
                return $data->count();
            break;
        }
    }

}
