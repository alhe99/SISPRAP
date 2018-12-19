<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function store(Request $request)
    {
        $rol = new Rol();
        $rol->rol = $request->nombre;
        $rol->save();
    }

    public function GetRol()
    {
        $rol= Rol::select('id', 'rol')->get();
        $data = [];
            foreach ($rol as $key => $value) {
                $data[$key] =[
                    'value'   => $value->id,
                    'label' => $value->rol,  
                ];
       
            }
        return  response()->json($data);
    }
}
