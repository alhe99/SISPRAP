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
        "ENERO", 
        "FEBRERO",
        "MARZO", 
        "ABRIL", 
        "MAYO", 
        "JUNIO", 
        "JULIO", 
        "AGOSTO", 
        "SEPTIEMBRE", 
        "OCTUBRE", 
        "NOVIEMBRE", 
        "DICIEMBRE");
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
public function getInitialProcessReporte($proceso_id){

    $collection;
    $carrera = Carrera::with(['estudiantes'])->get();
    $test = [1,2,3];
    $totalMined = 0;
    $totalOtros = 0;
    

    $data = [];
    $data[0] = "CONSOLIDADO ".$this->trimestres[implode($test)];
    foreach ($carrera as $carre) {
        //Obteniendo el total de resultados becados y otros
        $totalMined += $carre->getCountStudentsByMinedTrimestral($test);
        $totalOtros += $carre->getCountStudentsByOtherBecaTrimestral($test);

        $data[1] = array("totalMined" => $totalMined,"totalOtros"=>$totalOtros); 
        $data[$carre->id+1] = $collection = new Collection(["Carrera" => $carre->nombre,
         "BecadosMined" => $carre->getCountStudentsByMinedTrimestral($test),
         "Otros" => $carre->getCountStudentsByOtherBecaTrimestral($test) ]);
    }


    $pdf = PDF::loadView('reportes.iniprocesos', ['consolidado' => $data])->setOption('footer-center', 'Página [page] de [topage]');;
    return $pdf->stream('Inicio Procesos.pdf');
    /* return view('reportes.iniprocesos'); */
   /*  return $data; */

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
