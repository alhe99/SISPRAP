<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
	protected $table = 'supervisores';
	protected $fillable = ['nombre', 'no_telefono','institucion_id','fecha_registro'];
	public $timestamps = false;

	public function institucion()
	{
		return $this->belongsTo(Institucion::class, 'institucion_id');
	}
	
}
