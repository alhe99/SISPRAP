<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImgSupervision extends Model
{
    protected $table = 'img_supervisiones';
    protected $fillable = ['img','supervision_id','fecha_registro'];
    public $timestamps = false;

    public function supervisionProyecto(){

        return $this->belongsTo(SupervisionProyecto::class,'supervision_id');
    }
}
