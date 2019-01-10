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
use PDF;


class InstitucionController extends Controller
{

    //Devuelve el listado de instituciones por proceso
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

//registrar instituciones
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

//actualizar instituciones
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

    if($request->proceso_id == 3){
        $institucion->procesos()->detach();
        $institucion->procesos()->attach(array( 1, 2));

    }else{

        $institucion->procesos()->sync($request->proceso_id);
    }

    $institucion->save();

}
//cambiar de estado a una institucion para desactivarla
public function desactivar(Request $request)
{
    if(!$request->ajax()) return redirect('/');
    $institucion = Institucion::findOrFail($request->id);
    $institucion->estado = '0';
    $institucion->save();
}
//cambiar de estado a una institucion para activarla
public function activar(Request $request)
{
    if(!$request->ajax()) return redirect('/');
    $institucion = Institucion::findOrFail($request->id);
    $institucion->estado = '1';
    $institucion->save();
}

//listado de instituciones por su proceso y id
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

//listado de instituciones relacionadas a los proyectos
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

//listado de instituciones desactivadas
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

//obtener todas las instituciones
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

//obtener instituciones por municipio    
function getReportByMunicipio(Request $request){

    $ins = Institucion::with(['municipio.departamento'])->where('municipio_id',$request->muni_id)->where('estado',1)->get();
    $no_insti = Institucion::where('municipio_id',$request->muni_id)->where('estado',1)->count();
    $date = date('Y-m-d');
    $municipio = Municipio::with('departamento')->findOrFail($request->muni_id);
    $direccionSupervision = $municipio->nombre."/".$municipio->departamento->nombre;

    $pdf = PDF::loadView('reportes.hojasupervigen',['instituciones'=>$ins,'total'=>$no_insti,'direccion'=>$direccionSupervision])->setOption('footer-center', 'Página [page] de [topage]');

    $pdf->setOption('margin-top',20);
    $pdf->setOption('margin-bottom',20);
    $pdf->setOption('margin-left',20);
    $pdf->setOption('margin-right',20);
    return $pdf->stream('Hoja de Supervisión General '.$date.'.pdf');
    // return $direccionSupervision;
}

//obtener listado de instituciones por proceso
function getReportInstituciones(Request $request){
    $proceso = $request->proceso_id;

    $institucion = Institucion::select('id', 'nombre')->whereHas('procesos', function ($query) use ($proceso) {
        $query->where('proceso_id', $proceso);
    })->where('instituciones.estado','1')->orderBy('instituciones.id','desc')->get();

    $inst = Institucion::select('id', 'nombre')->whereHas('procesos', function ($query) use ($proceso) {
     $query->where('proceso_id', $proceso);
 })->where('instituciones.estado', '1')->orderBy('instituciones.id', 'desc')->count();

    $process = "";
    if($proceso == 1)
        $process = "Servicio Social";
    else
        $process = "Práctica Profesional";

    $date = date('Y-m-d');
    $pdf = PDF::loadView('reportes.reginst',['instituciones'=>$institucion,'total' =>$inst, 'proceso' => $process])->setOption('footer-center', 'Página [page] de [topage]');
    $pdf->setOption('margin-top',20);
    $pdf->setOption('margin-bottom',20);
    $pdf->setOption('margin-left',20);
    $pdf->setOption('margin-right',20);
    return $pdf->stream('Reporte General de Instuciones '.$date.'.pdf');

}

//obtener el listado de instituciones ya supervisadas
function getSupervisiones(Request $request){
    $proceso = $request->proceso_id;
    $arrayInstitucion = [];
    $supervisiones = array();
    $arrayProyectos = [];

    $instituciones = Institucion::has('proyectosInsti.supervision')->whereHas('procesos',function($query) use ($proceso){
        $query->where('proceso_id',$proceso);
    })->get();


    for ($i=0; $i < $instituciones->count() ; $i++) {
        $test = 0;

        $arrayInstitucion[0] = $instituciones[$i]->nombre;
        foreach ($instituciones[$i]->proyectosInsti as $key => $value) {

         if(!is_null($value->supervision)){
           if($proceso == 2){
               $carreraProy = Proyecto::findOrFail($value->id)->carre_proy[0]->nombre;
               $arrayInstitucion[1] = $test += count($value);
               $arrayInstitucion[$key+2] = array(
                 "nombreProyecto" => $value->nombre,
                 "fechaSupervision" => $value->supervision["fecha"],
                 "carreraProyecto" => $carreraProy
             );
           }else{
             $arrayInstitucion[$key+2] = array(
               "nombreProyecto" => $value->nombre,
               "fechaSupervision" => $value->supervision["fecha"],
           );
         }

     }
 }
 array_push($supervisiones,$arrayInstitucion);
}
if($proceso == 1)
   $process = "Servicio Social";
else
   $process = "Práctica Profesional";

$date = date('Y-m-d');
$pdf = PDF::loadView('reportes.supervisiones',['supervisiones'=>$supervisiones, 'proceso' =>$process])->setOption('footer-center', 'Página [page] de [topage]');
$pdf->setOption('margin-top',20);
$pdf->setOption('margin-bottom',20);
$pdf->setOption('margin-left',20);
$pdf->setOption('margin-right',20);

return $pdf->stream('Reporte General de Supervisiones ' .$date.'.pdf');
// return $supervisiones;
}
function regSupervision(){

    $date = Carbon::now();
    $date = $date->format('Y-m-d');
    $pdf = PDF::loadView('reportes.registro');
    return base64_encode($pdf->stream('regsupervisiones' .Carbon::parse($date).'.pdf'))->setOption('footer-center', 'Página [page] de [topage]');
}

//validar el nombre de la instituciones
public function validateInstitucion(Request $request){

    if(Institucion::where('nombre',$request->nombre)->whereHas('procesos',function($query) use($request){
        $query->where('proceso_id',$request->proceso_id);
    })->exists()){
        return response('existe', 200);
    }
}
}

