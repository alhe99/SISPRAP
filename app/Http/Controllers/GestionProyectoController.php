<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PreinscripcionProyecto;
use App\GestionProyecto;
use App\Carrera;
use Carbon\Carbon;
use App\Estudiante;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\DB;
use PDF;

class GestionProyectoController extends Controller
{
    public $meses = array(
       "1" => "ENERO", 
       "2" => "FEBRERO",
       "3" => "MARZO", 
       "4" => "ABRIL", 
       "5" => "MAYO", 
       "6" => "JUNIO", 
       "7" => "JULIO", 
       "8" => "AGOSTO", 
       "9" => "SEPTIEMBRE", 
       "10" => "OCTUBRE", 
       "11" => "NOVIEMBRE", 
       "12" => "DICIEMBRE");
    public $trimestres = array(
        "123" => 'PRIMER TRIMESTRE',
        "456" => 'SEGUNDO TRIMESTRE',
        "789" => 'TERCER TRIMESTRE',
        "101112" => 'CUARTO TRIMESTRE'
    );
    public function initGestionProyecto(Request $request){

        try {
            DB::beginTransaction();
            $gp = new GestionProyecto();
            $gp->fecha_inicio = $request->fechaini; //Fecha Inicio
            $gp->fecha_fin = $request->fechafin; //Fecha Fin
            $gp->horas_a_realizar = $request->hrsreal; //Total de Horas
            $gp->proyecto_id = $request->proyecto_id;
            $gp->estudiante_id = $request->student_id;
            $gp->nombre_supervisor = $request->super_name; //Nombre del supervisor
            $gp->tel_supervisor = $request->super_cell; //Telefono del supervisor


            if(Auth::user()->rol_id >2){
            //Informacion del estudiante
                $proceso = Auth::user()->estudiante->proceso[0]->id;
                $nombre = Auth::user()->estudiante->nombre;
                $apellido = Auth::user()->estudiante->apellido;
                $carnet = Auth::user()->estudiante->codCarnet;
                $telefono = Auth::user()->estudiante->telefono;
                $carrera = Auth::user()->estudiante->carrera->nombre;
                $email = Auth::user()->estudiante->email;

            //Informacion de la institucion
                $nombreI = Auth::user()->estudiante->preinscripciones[0]->institucion->nombre;
                $direccionI = Auth::user()->estudiante->preinscripciones[0]->institucion->direccion;
                $departamentoI = Auth::user()->estudiante->preinscripciones[0]->institucion->municipio->departamento->nombre;
                $municipioI = Auth::user()->estudiante->preinscripciones[0]->institucion->municipio->nombre;
                $sectorI = Auth::user()->estudiante->preinscripciones[0]->institucion->sectorInstitucion->sector;
                $telefonoI = Auth::user()->estudiante->preinscripciones[0]->institucion->telefono;
                $emailI = Auth::user()->estudiante->preinscripciones[0]->institucion->email;

            //Informacion del proyecto
                $nombreP = Auth::user()->estudiante->preinscripciones[0]->nombre;
                $actividadesP = Auth::user()->estudiante->preinscripciones[0]->actividades;

            }

            $collection = new Collection([
                "proceso" => $proceso,
                "nombreE" => $nombre,
                "apellidoE" => $apellido,
                "carnetE" => $carnet,
                "telefonoE" => $telefono,
                "carreraE" => $carrera,
                "emailE" => $email,
                "nombreI" => $nombreI,
                "direccionI" => $direccionI,
                "departamentoI" => $departamentoI,
                "municipioI" => $municipioI,
                "sectorI" => $sectorI,
                "telefonoI" => $telefonoI,
                "emailI" => $emailI,
                "nombreP" => $nombreP,
                "actividadesP" => $actividadesP,
                "hrasRealizar" => $request->hrsreal,
                "fechaInicio" => $request->fechaini,
                "fechaFin" => $request->fechafin,
                "nombreS" => $request->super_name,
                "telefonoS" => $request->super_cell,
            ]);

            if($gp->save())
            {

                $pdf = PDF::loadView('public.reportes.rellenarperfil',['data'=>$collection]);
                 //DB::table('preinscripciones_proyectos')->where('estudiante_id', $student_id)->where('estado','F')->delete();
                DB::commit();
                return base64_encode($pdf->download('Perfil de proyecto.pdf'));
            }
        } catch (Exception $e) {
          DB::rollBack();
      }

  }
  public function index(Request $request)
  {
        //if (!$request->ajax()) {
           // return redirect('/');
        //
    $buscar = $request->buscar;
    $proceso = $request->proceso_id;
    $carrera_id = $request->carre_id;

    if ($buscar == '') {

        $gp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion'])
        ->whereHas('estudiante.proceso', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);

        })->whereHas('estudiante', function ($query) use ($carrera_id) {
            $query->where('carrera_id', $carrera_id);

        })->paginate(8);

    } else {

        $gp = GestionProyecto::with(['estudiante.carrera', 'proyecto'])
        ->whereHas('estudiante.proceso', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);

        })->whereHas('estudiante', function ($query) use ($buscar) {
            $query->where('nombre', 'like', '%' . $buscar . '%');

        })->whereHas('estudiante', function ($query) use ($carrera_id) {
            $query->where('carrera_id', $carrera_id);
        })->paginate(8);
    }

    return [
        'pagination' => [
            'total' => $gp->total(),
            'current_page' => $gp->currentPage(),
            'per_page' => $gp->perPage(),
            'last_page' => $gp->lastPage(),
            'from' => $gp->firstItem(),
            'to' => $gp->lastItem(),
        ],
        'gp' => $gp,
    ];
}
public function constancias(Request $request)
{
    $buscar = $request->buscar;
    $proceso = $request->proceso_id;
    $carrera_id = $request->carre_id;

    if ($buscar == '') {

        $gp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion','constancia_entreg'])
        ->whereHas('estudiante.proceso', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);

        })->whereHas('estudiante', function ($query) use ($carrera_id) {
            $query->where('carrera_id', $carrera_id);

        })->where('estado','F')->paginate(8);

    } else {

        $gp = GestionProyecto::with(['estudiante.carrera', 'proyecto','constancia_entreg'])
        ->whereHas('estudiante.proceso', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);

        })->whereHas('estudiante', function ($query) use ($buscar) {
            $query->where('nombre', 'like', '%' . $buscar . '%');

        })->whereHas('estudiante', function ($query) use ($carrera_id) {
            $query->where('carrera_id', $carrera_id);

        })->where('estado','F')->paginate(8);
    }

    return [
        'pagination' => [
            'total' => $gp->total(),
            'current_page' => $gp->currentPage(),
            'per_page' => $gp->perPage(),
            'last_page' => $gp->lastPage(),
            'from' => $gp->firstItem(),
            'to' => $gp->lastItem(),
        ],
        'gp' => $gp,
    ];
}

public function getInfoGpById($id){

    $gestionp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion','documentos_entrega'])->findOrFail($id);

    return $gestionp;

}
public function getGestionProyectoByStudent($student_id){

    $gestionp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion'])->whereHas('estudiante', function ($query) use ($student_id) {
        $query->where('estudiantes.id',$student_id);
    })->get();

    return view('public.gestionPro',compact("gestionp"));
        //return $gestionp;
}
public function closeProy(Request $request){

    $gp = GestionProyecto::findOrFail($request->gestionId);
    $gp->fecha_fin = $request->fechaFin;
    $gp->horas_realizadas = $request->horasRea;
    $gp->observacion_final = $request->obsFinal;
    $gp->estado = 'F';
    $gp->update();

    if($request->horasFina == $gp->horas_a_realizar){
        $a = $gp->estudiante_id;
        $e = Estudiante::findOrFail($a);
        $e->no_proyectos = 0;
        $e->update();
        $e->proceso()->detach(1);
        $e->proceso()->attach(2);
    }
}
    //Funciones para reportes
    //REPORTE 1 RUTA = /gestionProy/reportes/initialprocess/{pId}
public function getInitialProcessReporte(Request $request){
    $carrera = Carrera::get();
    $procesoId = $request->proceso_id;
    $procesoTitulo = "";
    $mesesTitulo = "";

    if($procesoId == 1)
            $procesoTitulo = "SERVICIO SOCIAL";
        else if($procesoId == 2)
            $procesoTitulo = "PRACTICA PROFESIONAL";

    if($request->tipoRepo == 'T'){
        $collection;
        $arrayTrimestre = explode(",",$request->meses);
        $totalMined = 0;$totalOtros = 0;$totalMinedMes1 = 0;$totalOtrosMes1 = 0;$totalMinedMes2 = 0;$totalOtrosMes2 = 0;$totalMinedMes3 = 0;
        $totalOtrosMes3 = 0;
        
        //Sacando datos mensuales
        $dataMensual = [];
        $mes1 = [];$mes2 = [];$mes3 = [];

        $mes1[0] = $this->meses[$arrayTrimestre[0]];
        $mes2[0] = $this->meses[$arrayTrimestre[1]];
        $mes3[0] = $this->meses[$arrayTrimestre[2]];
        $collection1;$collection2;$collection3;
        $mesesTitulo = $mes1[0].", ".$mes2[0].", ".$mes3[0];
        foreach($carrera as $key => $carre){

            $totalMinedMes1 += $carre->getCountStudentsByMinedMensual($arrayTrimestre[0], $procesoId);
            $totalOtrosMes1 += $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[0], $procesoId);

            $mes1[1] = array(
                "totalMined" => $totalMinedMes1,
                "totalOtros"=> $totalOtrosMes1
            ); 

            $mes1[$carre->id+1] = $collection1 = new Collection([
                "Carrera" => $carre->nombre,
                "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayTrimestre[0], $procesoId),
                "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[0], $procesoId)
            ]);
            
            $totalMinedMes2 += $carre->getCountStudentsByMinedMensual($arrayTrimestre[1], $procesoId);
            $totalOtrosMes2 += $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[1], $procesoId);

            $mes2[1] = array(
                "totalMined" => $totalMinedMes2,
                "totalOtros" => $totalOtrosMes2,
            );

            $mes2[$carre->id+1] = $collection2 = new Collection([
                "Carrera" => $carre->nombre,
                "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayTrimestre[1], $procesoId),
                "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[1], $procesoId),
            ]);

            $totalMinedMes3 += $carre->getCountStudentsByMinedMensual($arrayTrimestre[2], $procesoId);
            $totalOtrosMes3 += $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[2], $procesoId);

            $mes3[1] = array(
                "totalMined" => $totalMinedMes3,
                "totalOtros" => $totalOtrosMes3,
            );

            $mes3[$carre->id+1] = $collection2 = new Collection([
                "Carrera" => $carre->nombre,
                "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayTrimestre[2], $procesoId),
                "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[2], $procesoId),
            ]);
        }
        //Sacando Consolidado por los 3 meses
        $data = [];
        $data[0] = $this->trimestres[implode($arrayTrimestre)];
        foreach ($carrera as $carre) {
            //Obteniendo el total de resultados becados y otros
            $totalMined += $carre->getCountStudentsByMinedTrimestral($arrayTrimestre,$procesoId);
            $totalOtros += $carre->getCountStudentsByOtherBecaTrimestral($arrayTrimestre,$procesoId);

            $data[1] = array("totalMined" => $totalMined,"totalOtros"=>$totalOtros); 
            $data[$carre->id+1] = $collection = new Collection(["Carrera" => $carre->nombre,
            "BecadosMined" => $carre->getCountStudentsByMinedTrimestral($arrayTrimestre,$procesoId),
            "Otros" => $carre->getCountStudentsByOtherBecaTrimestral($arrayTrimestre,$procesoId) ]);
        }

        $mensuales[0] = [
            'mes1' => $mes1,
            'mes2' => $mes2,
            'mes3' => $mes3,
        ];

        $pdf = PDF::loadView('reportes.iniprocesos', ['mensuales' => $mensuales,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');;
        return $pdf->stream('Inicio Procesos.pdf');

    }else if($request->tipoRepo == 'M'){
        $arrayMeses = explode(",", $request->meses);
        $dataMensual = []; $collectionMensual; $data = array();
        $totalMined = 0;$totalMinedArray = [];$totalOtros = 0;$totalOtrosArray = [];$mesesSelectedArray=[];

        for ($i=0; $i < count($arrayMeses) ; $i++) { 

            $mesesSelectedArray[$i] = $this->meses[$arrayMeses[$i]];
            for ($j=0; $j < $carrera->count(); $j++) { 
                $totalMinedArray[$i] = $totalMined += $carrera[$j]->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId);
                $totalOtrosArray[$i] = $totalOtros += $carrera[$j]->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId);

            }
            $totalMined = 0;$totalOtros = 0;

            foreach ($carrera as $carre) {
                $dataMensual[0] = $this->meses[$arrayMeses[$i]];
                $dataMensual[1] = array(
                    "totalMined" => $totalMinedArray[$i],
                    "totalOtros" => $totalOtrosArray[$i]
                );
                 
                $dataMensual[$carre->id+1] = $collectionMensual = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId),
                    "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId),
                ]);
            };
            array_push($data, $dataMensual);
        }
       $pdf = PDF::loadView('reportes.iniprocesos', ['mensuales' => $data,'tipo' => 'M','meses'=>$mesesSelectedArray,'procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
       return $pdf->stream('Inicio Procesos.pdf');
    }else if($request->tipoRepo == 'A'){

        $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
        $dataMensual = [];
        $collectionMensual;
        $data = array();
        $totalMined = 0;
        $totalMinedArray = [];
        $totalOtros = 0;
        $totalOtrosArray = [];
        $mesesSelectedArray = [];
        $anio = date('Y');       

        for ($i = 0; $i < count($arrayMeses); $i++) {

            for ($j = 0; $j < $carrera->count(); $j++) {
                $totalMinedArray[$i] = $totalMined += $carrera[$j]->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId);
                $totalOtrosArray[$i] = $totalOtros += $carrera[$j]->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId);
            }
            $totalMined = 0;
            $totalOtros = 0;

            foreach ($carrera as $carre) {
                $dataMensual[0] = $this->meses[$arrayMeses[$i]];
                $dataMensual[1] = array(
                    "totalMined" => $totalMinedArray[$i],
                    "totalOtros" => $totalOtrosArray[$i],
                );

                $dataMensual[$carre->id + 1] = $collectionMensual = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId),
                    "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId),
                ]);
            };

            //Consolidado Anual
            $totalMinedAnual = 0;
            $totalOtrosAnual = 0;

            $dataAnual = [];
            $dataAnual[0] = $anio;
            foreach ($carrera as $carre) {
                //Obteniendo el total de resultados becados y otros
                $totalMinedAnual += $carre->getCountStudentsByMinedYear($anio, $procesoId);
                $totalOtrosAnual += $carre->getCountStudentsByOtherBecaYear($anio, $procesoId);

                $dataAnual[1] = array("totalMined" => $totalMinedAnual, "totalOtros" => $totalOtrosAnual);

                $dataAnual[$carre->id + 1] = $collection = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedYear($anio, $procesoId),
                    "Otros" => $carre->getCountStudentsByOtherBecaYear($anio, $procesoId)
                ]);
            }

            array_push($data, $dataMensual);
            $mesesTitulo = "ANUAL";

        }
        $pdf = PDF::loadView('reportes.iniprocesos', ['mensuales' => $data,'consolidadoAnual'=>$dataAnual,'tipo' => 'A', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
        return $pdf->stream('Inicio Procesos.pdf');
    }
}
//Pendientes de inicio de proceso
public function getPendientesIniProcessReporte(Request $request){

    $carrera = Carrera::get();
    $procesoId = 1;
    $procesoTitulo = "";
    $mesesTitulo = "";

    if($procesoId == 1)
            $procesoTitulo = "SERVICIO SOCIAL";
        else if($procesoId == 2)
            $procesoTitulo = "PRACTICA PROFESIONAL";

    if($request->tipoRepo == 'T'){
        $collection;
        $arrayTrimestre = explode(",",$request->meses);
    
        //Sacando datos mensuales
        $dataMensual = [];
        $mes1 = [];$mes2 = [];$mes3 = [];
        $total = 0;

        $mes1[0] = $this->meses[$arrayTrimestre[0]];
        $mes2[0] = $this->meses[$arrayTrimestre[1]];
        $mes3[0] = $this->meses[$arrayTrimestre[2]];

        $mesesTitulo = $mes1[0].", ".$mes2[0].", ".$mes3[0];

        $c1 = [];$c2 = [];$c3 = [];

        foreach($carrera as $carre){
            
            $estudiantesM1 = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                $query->where('procesos_estudiantes.proceso_id', $procesoId);
            })->whereIn(DB::raw('MONTH(updated_at)'), [$arrayTrimestre[0]])->get();

            $estudiantesM2 = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                $query->where('procesos_estudiantes.proceso_id', $procesoId);
            })->whereIn(DB::raw('MONTH(updated_at)'), [$arrayTrimestre[1]])->get();

            $estudiantesM3 = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                $query->where('procesos_estudiantes.proceso_id', $procesoId);
            })->whereIn(DB::raw('MONTH(updated_at)'), [$arrayTrimestre[2]])->get();

            $c1[0] = $carre->nombre;
            $c1[1] = $estudiantesM1;

            $c2[0] = $carre->nombre;
            $c2[1] = $estudiantesM2;

            $c3[0] = $carre->nombre;
            $c3[1] = $estudiantesM3;

            $mes1[$carre->id] = $c1;
            $mes2[$carre->id] = $c2;
            $mes3[$carre->id] = $c3;

        }
        //Sacando Consolidado por los 3 meses
        $data = [];
        $data[0] = $this->trimestres[implode($arrayTrimestre)];
        $totalFinal = 0;
        foreach ($carrera as $carre) {
            //Obteniendo el total de resultados becados y otros
            $estudiantes =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
            })->whereIn(DB::raw('MONTH(updated_at)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->count();

            $totalFinal += $estudiantes;

            $data[$carre->id] = array(
               "Carrera" => $carre->nombre,
               "total" => $estudiantes,
               "totalFooter" => $totalFinal
            );
            $totalFinal = 0;
        }

        $mensuales = array();
        array_push($mensuales,$mes1);
        array_push($mensuales,$mes2);
        array_push($mensuales,$mes3); 

        $pdf = PDF::loadView('reportes.repeninicio', ['mensuales' => $mensuales,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');;
        return $pdf->stream('Pendientes de Inicio.pdf');
        /* return $mensuales; */

    }else if($request->tipoRepo == 'M'){
        $arrayMeses = explode(",", $request->meses);
        $dataMensual = []; $collectionMensual; $data = array();
        $totalMined = 0;$totalMinedArray = [];$totalOtros = 0;$totalOtrosArray = [];$mesesSelectedArray=[];

        for ($i=0; $i < count($arrayMeses) ; $i++) { 

            $mesesSelectedArray[$i] = $this->meses[$arrayMeses[$i]];
            for ($j=0; $j < $carrera->count(); $j++) { 
                $totalMinedArray[$i] = $totalMined += $carrera[$j]->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId);
                $totalOtrosArray[$i] = $totalOtros += $carrera[$j]->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId);

            }
            $totalMined = 0;$totalOtros = 0;

            foreach ($carrera as $carre) {
                $dataMensual[0] = $this->meses[$arrayMeses[$i]];
                $dataMensual[1] = array(
                    "totalMined" => $totalMinedArray[$i],
                    "totalOtros" => $totalOtrosArray[$i]
                );
                 
                $dataMensual[$carre->id+1] = $collectionMensual = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId),
                    "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId),
                ]);
            };
            array_push($data, $dataMensual);
        }
       $pdf = PDF::loadView('reportes.iniprocesos', ['mensuales' => $data,'tipo' => 'M','meses'=>$mesesSelectedArray,'procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
       return $pdf->stream('Inicio Procesos.pdf');
    }else if($request->tipoRepo == 'A'){

        $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
        $dataMensual = [];
        $collectionMensual;
        $data = array();
        $totalMined = 0;
        $totalMinedArray = [];
        $totalOtros = 0;
        $totalOtrosArray = [];
        $mesesSelectedArray = [];
        $anio = date('Y');       

        for ($i = 0; $i < count($arrayMeses); $i++) {

            for ($j = 0; $j < $carrera->count(); $j++) {
                $totalMinedArray[$i] = $totalMined += $carrera[$j]->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId);
                $totalOtrosArray[$i] = $totalOtros += $carrera[$j]->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId);
            }
            $totalMined = 0;
            $totalOtros = 0;

            foreach ($carrera as $carre) {
                $dataMensual[0] = $this->meses[$arrayMeses[$i]];
                $dataMensual[1] = array(
                    "totalMined" => $totalMinedArray[$i],
                    "totalOtros" => $totalOtrosArray[$i],
                );

                $dataMensual[$carre->id + 1] = $collectionMensual = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId),
                    "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId),
                ]);
            };

            //Consolidado Anual
            $totalMinedAnual = 0;
            $totalOtrosAnual = 0;

            $dataAnual = [];
            $dataAnual[0] = $anio;
            foreach ($carrera as $carre) {
                //Obteniendo el total de resultados becados y otros
                $totalMinedAnual += $carre->getCountStudentsByMinedYear($anio, $procesoId);
                $totalOtrosAnual += $carre->getCountStudentsByOtherBecaYear($anio, $procesoId);

                $dataAnual[1] = array("totalMined" => $totalMinedAnual, "totalOtros" => $totalOtrosAnual);

                $dataAnual[$carre->id + 1] = $collection = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedYear($anio, $procesoId),
                    "Otros" => $carre->getCountStudentsByOtherBecaYear($anio, $procesoId)
                ]);
            }

            array_push($data, $dataMensual);
            $mesesTitulo = "ANUAL";

        }
        $pdf = PDF::loadView('reportes.iniprocesos', ['mensuales' => $data,'consolidadoAnual'=>$dataAnual,'tipo' => 'A', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
        return $pdf->stream('Inicio Procesos.pdf');
    }
}

public function generateConstancia($gp_id){
    $gp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion'])->findOrFail($gp_id);
    $proces = $gp->estudiante->proceso[0]->nombre;
    $date = Carbon::now();
    $date = $date->format('Y-m-d');

    if($gp->estudiante->proceso[0]->id == 1){

        $pdf = \PDF::loadView('reportes.constanciass',['gp'=>$gp, 'proceso' =>$proces,'fecha' => $date]);
        return base64_encode($pdf->stream('constancia ' .Carbon::parse($date).'.pdf'));
    }else{

        $pdf = \PDF::loadView('reportes.constanciapp',['gp'=>$gp, 'proceso' =>$proces,'fecha' => $date]);
        return base64_encode($pdf->stream('constancia ' .Carbon::parse($date).'.pdf'));
    }

}
}
