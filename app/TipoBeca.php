<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoBeca extends Model
{
    protected $table = 'tipo_becas';
    
    protected $fillable = [
        'nombre',
        'cancela_arancel',
    ];
    public function pago_aranceles(){

        return $this->hasMany(PagoArancel::class);
    }
}
