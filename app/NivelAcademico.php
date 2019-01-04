<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NivelAcademico extends Model
{
    protected $table = 'niveles_academicos';
    
    protected $fillable = [
        'nivel',
    ];

    public function estudiantes(){

        return $this->hasMany(Estudiante::class);
    }
}
