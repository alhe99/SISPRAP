<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function instituciones()
    {
        return $this->hasMany('App\Institucion');
    }

    public function departamento()
    {
        return $this->belongsTo('App\Departamento');
    }
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }

}
