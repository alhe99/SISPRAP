<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['rol'];
    public $timestamps = false;

    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }
    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }
}
