<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SectorInstitucion extends Model
{
    protected $table = 'sectores_instituciones';
    protected $fillable = ['sector'];
    public $timestamps = false;

    //Un sector institucion puede tener muchas instituciones

    public function instituciones()
    {
        return $this->hasMany('App\Institucion');
    }
    
}
