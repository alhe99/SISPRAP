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

    //obtener todos los municipios relacionados al departamento seleccionado por id
    public function GetMunicipios($id)
    {
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
