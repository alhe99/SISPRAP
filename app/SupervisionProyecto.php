<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisionProyecto extends Model
{
    protected $table = 'supervisiones_proyectos';
    protected $fillable = ['fecha','observacion','estado','proyecto_id','fecha_registro'];
    public $timestamps = false;

    public function imgSupervisiones()
    {
        return $this->hasMany(ImgSupervision::class,'supervision_id');
    }
    public function proyecto(){

        return $this->belongsTo(Proyecto::class,'proyecto_id');
    }
    public function scopeYear($query,$year){
        return $query->where('fecha_registro',$year);
    }
}
