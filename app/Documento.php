<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';
    protected $fillable = ['nombre','proceso_id'];
    public $timestamps = false;

    public function tipoProceso()
    {
        return $this->belongsTo(TipoProceso::class, 'proceso_id');
    }
    public function documentos_entreg(){

        return $this->hasMany(Proyecto::class);
    }
}
