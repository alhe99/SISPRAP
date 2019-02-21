<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
	protected $table = 'mensajes';
	protected $fillable = ['mensaje'];

	public function usuario()
	{
		return $this->belongsTo(User::class,'usuario_id');
	}
}
