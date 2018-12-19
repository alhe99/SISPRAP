<?php

namespace App\Http\Controllers;

use App\Institucion;
use App\Municipio;
use App\Proyecto;
use App\SupervisionProyecto;
use App\SectorInstitucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class InstitucionController extends Controller
{
    public function __construct(){

        //$this->middleware('auguestVerifyth');
        
    }
    public function index(Request $request)
    {
       if (!$request->ajax()) return redirect('/');
            $buscar = $request->buscar;
            $proceso = $request->proceso;
            if($buscar==''){

                $institucion = Institucion::with(['sectorInstitucion','municipio.departamento','procesos'])
                ->whereHas('procesos', function ($query) use ($proceso) {
                    $query->where('proceso_id', $proceso);
                })->where('instituciones.estado','=','1')->orderBy('instituciones.id','desc')->paginate(5);

            }else{

                $institucion = Institucion::with(['sectorInstitucion','municipio.departamento','procesos'])
                ->whereHas('procesos', function ($query) use ($proceso) {
                    $query->where('proceso_id', $proceso);
                })->where('instituciones.nombre','like','%'.$buscar.'%')->where('instituciones.estado','=','1')->orderBy('instituciones.id','desc')->paginate(5);
            }
  
        return [
            'pagination' => [
                'total'        => $institucion->total(),
                'current_page' => $institucion->currentPage(),
                'per_page'     => $institucion->perPage(),
                'last_page'    => $institucion->lastPage(),
                'from'         => $institucion->firstItem(),
                'to'           => $institucion->lastItem(),
            ],
            'institucion' => $institucion
        ];
    }
    public function store(Request $request)
    {
 
        $institucion = new Institucion();
        $institucion->nombre = $request->nombre;
        $institucion->direccion = $request->direccion;
        $institucion->telefono = $request->telefono;
        $institucion->email = $request->email;
        $institucion->sector_institucion_id = $request->sector_institucion_id;
        $institucion->municipio_id = $request->municipio_id;
        $institucion->estado = '1';
        $institucion->save();
        
        if($request->proceso_id == 3){
            
            $institucion->procesos()->attach(array( 1, 2));

        }else{

            $institucion->procesos()->attach($request->proceso_id);
        }
    }

    public function update(Request $request)
    {
        
        $institucion = Institucion::findOrFail($request->id);
        $institucion->nombre = $request->nombre;
        $institucion->direccion = $request->direccion;
        $institucion->telefono = $request->telefono;
        $institucion->email = $request->email;
        $institucion->sector_institucion_id = $request->sector_institucion_id;
        $institucion->municipio_id = $request->municipio_id;
        $institucion->estado = $request->estado;
        $institucion->procesos()->sync($request->proceso_id);
        $institucion->save();
    
    }
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $institucion = Institucion::findOrFail($request->id);
        $institucion->estado = '0';
        $institucion->save();
    }
    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $institucion = Institucion::findOrFail($request->id);
        $institucion->estado = '1';
        $institucion->save();
    }
    public function GetInstituciones($id)
    {
           $proceso = $id;
            $institucion = Institucion::select('id', 'nombre')->whereHas('procesos', function ($query) use ($proceso) {
                $query->where('proceso_id', $proceso);
            })->where('instituciones.estado','1')->orderBy('instituciones.id','desc')->get();
 
        $data = [];
        foreach ($institucion as $key => $value) {
            $data[$key] =[
                'value'   => $value->id,
                'label' => $value->nombre,  
            ];
   
        }
        return  response()->json($data);

    }
    public function GetInstitucion(Request $request)
    {

        $id = $request->id;
        $buscar = $request->buscar;
        $proceso = $request->proceso;
        if($buscar != ""){

           $institucion = Institucion::findOrFail($id)->proyectosInsti()->where('nombre','like','%'.$buscar.'%')->where('proceso_id','=',$proceso)->paginate(5);

        }else{

           $institucion = Institucion::findOrFail($id)->proyectosInsti()->where('proceso_id','=',$proceso)->paginate(5);

        }

            for ($i=0; $i <count($institucion); $i++) { 
                $idEstado = Proyecto::findOrFail($institucion[$i]->id)->supervision()->select('estado')->get();  
                $carrera = Proyecto::findOrFail($institucion[$i]->id)->carre_proy()->select('nombre')->get(); 

                if(sizeof($idEstado) > 0){
                  for ($j=0; $j <count($idEstado) ; $j++) { 
                     if($idEstado[$j]->estado == 0){
                       $institucion[$i]->setAttribute('supervision',0);
                     }elseif(count($idEstado[$j])==0 && $idEstado[$j]->estado == 1 ){
                         $institucion[$i]->setAttribute('supervision',1);
                     }
                  }
                }else{
                 $institucion[$i]->setAttribute('supervision',1);
                }

                if($proceso == 2){
                    for ($x=0; $x <count($carrera) ; $x++) { 
                        
                        $institucion[$i]->setAttribute('carrera',$carrera[$x]);                
                    }
                }
        }
            
        return [
            'pagination' => [
                'total'        => $institucion->total(),
                'current_page' => $institucion->currentPage(),
                'per_page'     => $institucion->perPage(),
                'last_page'    => $institucion->lastPage(),
                'from'         => $institucion->firstItem(),
                'to'           => $institucion->lastItem(),
            ],
            'institucion' => $institucion
        ];
    }
    public function getInstiDes(Request $request)
    {
      // if (!$request->ajax()) return redirect('/');
            $buscar = $request->buscar;
            $proceso = $request->proceso;
            if($buscar==''){

                $institucion = Institucion::whereHas('procesos', function ($query) use ($proceso) {
                    $query->where('proceso_id', $proceso);
                })->where('instituciones.estado','=','0')->orderBy('instituciones.id','desc')->paginate(5);

            }else{

                $institucion = Institucion::whereHas('procesos', function ($query) use ($proceso) {
                    $query->where('proceso_id', $proceso);
                })->where('instituciones.nombre','like','%'.$buscar.'%')->where('instituciones.estado','=','0')->orderBy('instituciones.id','desc')->paginate(5);
            }
  
        return [
            'pagination' => [
                'total'        => $institucion->total(),
                'current_page' => $institucion->currentPage(),
                'per_page'     => $institucion->perPage(),
                'last_page'    => $institucion->lastPage(),
                'from'         => $institucion->firstItem(),
                'to'           => $institucion->lastItem(),
            ],
            'institucion' => $institucion
        ];


    }
    public function GetInst()
    {
        //if (!$request->ajax()) return redirect('/');
        $instituciones = Institucion::all();
        $data = [];
        $data[0] = [];
        foreach ($instituciones as $key => $value) {
            $data[$key+1] =[
                'value'   => $value->id,
                'label' => $value->nombre,  
            ];
   
        }
        return  response()->json($data);
    }

    //FUNCIONES PARA REPORTES//
    function getReportByMunicipio(Request $request){

        $ins = Institucion::with(['municipio.departamento'])->where('municipio_id',$request->muni_id)->where('estado',1)->get();
        $no_insti = Institucion::where('municipio_id',$request->muni_id)->count();
        $date = Carbon::now();
        $date = $date->format('Y-m-d');

        $pdf = \PDF::loadView('reportes.hojasupervigen',['instituciones'=>$ins,'total'=>$no_insti]);
        return base64_encode($pdf->stream('supervision-general '.Carbon::parse($date).'.pdf'));
    }

    function getReportInstituciones($id){
        $proceso = $id;
        $institucion = Institucion::select('id', 'nombre')->whereHas('procesos', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);
        })->where('instituciones.estado','1')->orderBy('instituciones.id','desc')->get();
        $inst = Institucion::where('instituciones.estado','1')->count();
        $proces = Institucion::findOrFail($id)->procesos()->select('nombre')->get();
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        $pdf = \PDF::loadView('reportes.reginst',['instituciones'=>$institucion,'total' =>$inst, 'proceso' => $proces]);
    
        return base64_encode($pdf->stream('instituciones ' .Carbon::parse($date).'.pdf'));
    }
    function getSupervisiones($id){
        $proceso = $id;
        $supervision = SupervisionProyecto::whereHas('proyecto', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);
        })->select('fecha','proyecto_id')->get();
        $supv = SupervisionProyecto::where('estado','0')->count();
        $proces = Institucion::findOrFail($id)->procesos()->select('nombre')->get();
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        $pdf = \PDF::loadView('reportes.supervisiones',['supervisiones'=>$supervision,'total' =>$supv, 'proceso' =>$proces]);
    
        return base64_encode($pdf->stream('supervisiones ' .Carbon::parse($date).'.pdf'));
    }
    function regSupervision(){

        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        $pdf = \PDF::loadView('reportes.registro');
        return base64_encode($pdf->stream('regsupervisiones' .Carbon::parse($date).'.pdf'));
    }

    public function ifInstitucionExist($nombre,$proceso_id){
        
        if(Institucion::where('nombre',$nombre)->whereHas('procesos',function($query) use($proceso_id){
            $query->where('proceso_id',$proceso_id);
        })->first()){
            return "true";
        }            
        else{
            return "false";
        }
            
    }
}

