<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoBeca;

class TipoBecaController extends Controller
{
    public function getAllBecas()
    {
        //if (!$request->ajax()) return redirect('/');
        $becas = TipoBeca::all();
        $data = [];
        $data[0] = [];
        foreach ($becas as $key => $value) {
            $data[$key+1] =[
                'value'   => $value->id,
                'label' => $value->nombre,  
            ];
   
        }
        return  response()->json($data);
    }
}
