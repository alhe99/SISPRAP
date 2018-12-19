<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function municipios()
    {
        return $this->hasMany('App\Municipio');
    }
}
