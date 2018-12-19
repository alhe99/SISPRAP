<?php

namespace App\Http\Controllers;
use App\Municipio;
use App\Departamento;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function __construct(){

        $this->middleware('guestVerify');
        
    }
   public function GetDepartamentos()
    {
        //if (!$request->ajax()) return redirect('/');
        $Departamentos = Departamento::all();
        $data = [];
        $data[0] = [];
        foreach ($Departamentos as $key => $value) {
            $data[$key+1] =[
                'value'   => $value->id,
                'label' => $value->nombre,  
            ];
   
        }
        return  response()->json($data);
    }
    public function GetMunicipios($id)
    {
        //if (!$request->ajax()) return redirect('/');
        $Municipios = Municipio::where('departamento_id',$id)->get();
        $data = [];
        $data[0] = [];
        foreach ($Municipios as $key => $value) {
           $data[$key+1] =[
               'value'   => $value->id,
               'label' => $value->nombre,
            ];
        }
        return response()->json($data);
    }
}
