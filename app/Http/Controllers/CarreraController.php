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
                $carrera = Carrera::orderBy('id','asc')->where('estado','1')->paginate(10);
            }else{

                $carrera = Carrera::where('nombre','like','%'.$buscar.'%')->where('estado','=','1')->orderBy('id','asc')->paginate(10);
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
    public function GetCarreraDes(Request $request)
    {

            if (!$request->ajax()) return redirect('/');
            $buscar = $request->buscar;
            if($buscar==''){
                $carrera = Carrera::orderBy('id','asc')->where('estado','0')->paginate(5);
            }else{
                $carrera = Carrera::where('nombre','like','%'.$buscar.'%')->where('estado','0')->orderBy('id','asc')->paginate(5);
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

    //cambiar de estado a una carrera para desactivarla
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $institucion = Carrera::findOrFail($request->id);
        $institucion->estado = '0';
        $institucion->save();
    }

    //cambiar de estado a una carrera para activarla
    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $institucion = Carrera::findOrFail($request->id);
        $institucion->estado = '1';
        $institucion->save();
    }
}
