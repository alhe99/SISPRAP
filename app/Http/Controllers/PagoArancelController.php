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
        $pa->proceso_id = $request->proceso_id;
        $pa->fecha_registro = date('Y-m-d');
        $pa->save();
    }
    public function validateIfExiste($no_factura){
    	if($no_factura != "MINED")
    	{
    		if(PagoArancel::where('no_factura',$no_factura)->exists()){
    			return response('existe', 200);
    		}
    	}
    }
}
