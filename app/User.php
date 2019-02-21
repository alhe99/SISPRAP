<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $fillable = ['nombre','usuario','password','estado'];
    public $timestamps = false;

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
    public function estudiante(){
        return $this->belongsTo(Estudiante::class,'id');
    }
    public function mensajes(){
        return $this->hasMany(Mensaje::class,'usuario_id');
    }

    public function scopeUsuario($query,$name){
        if($name)
            return $query->where('nombre','LIKE',"%$name%")->orWhere('usuario','LIKE',"%$name%");
    }
}
