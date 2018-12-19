<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estudiante;
use App\TipoBeca;
use App\PagoArancel;

class PagoArancelController extends Controller
{
    public function payArancel(Request $request){

        $pa = new PagoArancel();
        $pa->no_factura = $request->noFac;
        $pa->estudiante_id = $request->estudiante_id;
        //$pa->tipo_beca_id = $request->tipobeca_id;
        $pa->save();
    }
}
