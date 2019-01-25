<?php

namespace App\Http\Controllers;

use App\Estudiante;
use App\Institucion;
use App\Municipio;
use App\Proyecto;
use App\SectorInstitucion;
use App\SupervisionProyecto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDF;


class InstitucionController extends Controller
{

    public $anio = 2019;
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
        $institucion->fecha_registro = date('Y-m-d');
        $institucion->save();

        if($request->proceso_id == 3){
            $institucion->procesos()->attach(array(1,2));
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
            $institucion->procesos()->attach(array(1,2));
        }else{
            $institucion->procesos()->sync($request->proceso_id);
        }
        $institucion->update();
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
        $institucion = Institucion::select('id', 'nombre')->whereHas('procesos', function ($query) use ($proceso){
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

    //listado de proyectos por instituciones
    public function getProyectosByInstitucion(Request $request)
    {

        $id = $request->id;
        $buscar = $request->buscar;
        $proceso = $request->proceso;
        $tipoProyecto = $request->tipoProyecto;

        $proyectos = Institucion::findOrFail($id)->proyectosInsti()->has('gestionProyecto')->nombre($buscar)->where([['proceso_id',$proceso],['tipo_proyecto',$tipoProyecto]])->whereYear('fecha_registro',$this->anio)->get();

        $proyectos_ids = $proyectos->pluck('id');

        $arrayProyectos = Proyecto::with(['supervision','carre_proy'])->whereIn('id',$proyectos_ids)->paginate(5);

        return [
            'pagination' => [
                'total'        => $arrayProyectos->total(),
                'current_page' => $arrayProyectos->currentPage(),
                'per_page'     => $arrayProyectos->perPage(),
                'last_page'    => $arrayProyectos->lastPage(),
                'from'         => $arrayProyectos->firstItem(),
                'to'           => $arrayProyectos->lastItem(),
            ],
            'proyectos' => $arrayProyectos
        ];
        // return $arrayProyectos;
    }

    //listado de instituciones desactivadas
    public function getInstiDes(Request $request)
    {

            $buscar = $request->buscar;
            $proceso = $request->proceso;
            $institucion = Institucion::whereHas('procesos', function ($query) use ($proceso) {
                    $query->where('proceso_id', $proceso);
            })->where('instituciones.estado','=','0')->orderBy('instituciones.id','desc')->nombre($buscar)->paginate(5);

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

    // *********** METODOS UTLIZADOS PARA REPORTES *********** //
    //Obtener instituciones por municipio
    function getHojaSupervision(Request $request)
    {
        $arrayInstituciones = explode(",", $request->instituciones_id);
        $arrayInstitucionGeneral = [];
        $arrayInstitucionDetalle = [];
        $procesoTitulo = "";

        for ($i=0; $i < count($arrayInstituciones) ; $i++) {

            $arrayIteracion = [];
            $institucion = Institucion::with(['supervisores','municipio.departamento'])->where('estado',1)->whereYear('fecha_registro',$this->anio)->find($arrayInstituciones[$i]);

            $alumno = DB::table('instituciones')
            ->join('procesos_instituciones', 'instituciones.id', '=', 'procesos_instituciones.institucion_id')
            ->join('proyectos', 'instituciones.id', '=', 'proyectos.institucion_id')
            ->join('gestion_proyectos', 'proyectos.id', '=', 'gestion_proyectos.proyecto_id')
            ->join('estudiantes', 'estudiantes.id', '=', 'gestion_proyectos.estudiante_id')
            ->select('estudiantes.nombre', 'estudiantes.apellido')
            ->where([
                ['instituciones.id',$arrayInstituciones[$i]],
                ['instituciones.estado',1],
                ['proyectos.estado',1],
                ['gestion_proyectos.tipo_gp',$request->proceso_id],
                ['gestion_proyectos.estado','I'],
                ['procesos_instituciones.proceso_id',$request->proceso_id]
            ])->get();

            if($alumno->count() > 0){

                $arrayIteracion[0] = $institucion->nombre;
                $arrayIteracion[1] = $alumno;

                array_push($arrayInstitucionDetalle,$arrayIteracion);
            }
            array_push($arrayInstitucionGeneral,$institucion);
        }

        if($request->proceso_id == 1)
            $procesoTitulo = "Servicio Social";
        else
            $procesoTitulo = "Práctica Profesional";

        $pdf = PDF::loadView('reportes.hojaSupervisionGeneralByMunicipios',['institucionesGeneral'=>$arrayInstitucionGeneral,'institucionDetalle'=> $arrayInstitucionDetalle,'anio'=>$this->anio,'proceso' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
        $pdf->setOption('margin-top',15);
        $pdf->setOption('margin-bottom',15);
        $pdf->setOption('margin-left',15);
        $pdf->setOption('margin-right',15);
        $pdf->setOption('orientation','landscape');
        return $pdf->stream('Hoja de Supervisión General '.date('Y-m-d').'.pdf');

        // return $arrayInstitucionDetalle;
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

    //Obtener el listado de instituciones ya supervisadas
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
    }

    function regSupervision(){

        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        $pdf = PDF::loadView('reportes.registro');
        return base64_encode($pdf->stream('regsupervisiones' .Carbon::parse($date).'.pdf'))->setOption('footer-center', 'Página [page] de [topage]');
    }

    //Validar el nombre de la instituciones ya existen en la BD
    public function validateInstitucion(Request $request)
    {

        if(Institucion::where('nombre',$request->nombre)->whereHas('procesos',function($query) use($request){
            $query->where('proceso_id',$request->proceso_id);
        })->exists()){
            return response('existe', 200);
        }
    }

    //listado de instituciones por su proceso y municipio
    public function getInstitucionesByProcess(Request $request)
    {
        $proceso = $request->proceso_id;
        $municipio_id = explode(',',$request->municipio_id);
        $institucion = Institucion::select('id','nombre')->whereHas('procesos', function ($query) use ($proceso){
        $query->where('proceso_id', $proceso);
        })->whereHas('proyectosInsti',function($query) use($proceso){
            $query->whereHas('gestionProyecto',function($query2) use($proceso){
                $query2->where([
                    ['tipo_gp',$proceso],
                    ['estado','I']
                ]);
            });
        })->whereIn('municipio_id',$municipio_id)->where('estado',1)->orderBy('instituciones.id','desc')->get();

        $data = [];
        foreach ($institucion as $key => $value) {
            $data[$key] =[
                'value'   => $value->id,
                'label' => $value->nombre,
            ];
        }

        return response()->json($data);
    }

}

