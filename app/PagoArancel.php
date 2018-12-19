<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoArancel extends Model
{
    protected $table = 'pago_aranceles';
    
    protected $fillable = [
        'no_factura',
        'estudiante_id',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }
}
