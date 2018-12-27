<?php

namespace App\Http\Controllers;

use App\SectorInstitucion;
use Illuminate\Http\Request;

class SectorInstitucionController extends Controller
{
    public function store(Request $request)
    {
        $sector = new SectorInstitucion();
        $sector->sector = $request->nombre;
        $sector->save();
    }
    public function __construct(){

        $this->middleware('guestVerify');
        
    }
    public function selectSectores(Request $request){

        $sectores = SectorInstitucion::orderBy('sector','asc')->get();
        $data = [];
        $data[0] = [];
        foreach ($sectores as $key => $value) {
            $data[$key+1] =[
                'value'   => $value->id,
                'label' => $value->sector,  
            ];
        }
        return  response()->json($data);
    }
}
