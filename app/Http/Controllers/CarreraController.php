<?php

namespace App\Http\Controllers;
use App\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{

    public function index(Request $request)
    {

            if (!$request->ajax()) return redirect('/');
            $buscar = $request->buscar;
            if($buscar==''){

                $carrera = Carrera::orderBy('id','desc')->paginate(5);

            }else{

                $carrera = Carrera::where('nombre','like','%'.$buscar.'%')->orderBy('id','desc')->paginate(5);
            }

            return [
                'pagination' => [
                    'total'        => $carrera->total(),
                    'current_page' => $carrera->currentPage(),
                    'per_page'     => $carrera->perPage(),
                    'last_page'    => $carrera->lastPage(),
                    'from'         => $carrera->firstItem(),
                    'to'           => $carrera->lastItem(),
                ],
                'carrera' => $carrera
            ];
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
    public function update(Request $request)
    {

        $carrera = Carrera::findOrFail($request->id);
        $carrera->nombre = $request->nombre;
        $carrera->update();
    }
     //Validar nombre de la carrera ya existente
     public function validateCarrera(Request $request){

        if(Carrera::where('nombre',$request->nombre)->exists()){
            return response('existe', 200);
        }
    }
}
