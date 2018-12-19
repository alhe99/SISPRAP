<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    protected $fillable = ['nombre','rol_id'];
    public $timestamps = false;

    public function rol()
    {
        return $this->belongsTo(Rol::class,'rol_id');
    }
}
