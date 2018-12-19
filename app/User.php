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
}
