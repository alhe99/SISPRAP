<?php

namespace App\Http\Controllers;
use App\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    public function __construct(){

        $this->middleware('guestVerify');
        
    }
    
    public function GetCarreras()
    {
        //if (!$request->ajax()) return redirect('/');
        $Carreras = Carrera::all();
        $data = [];
        foreach ($Carreras as $key => $value) {
            $data[$key] =[
                'value'   => $value->id,
                'label' => $value->nombre,  
            ];
   
        }
        return  response()->json($data);
    }
}
