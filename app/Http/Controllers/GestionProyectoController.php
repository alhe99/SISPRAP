<?php

namespace App\Http\Controllers;
use App\Carrera;
use App\Documento;
use App\Estudiante;
use App\GestionProyecto;
use App\PreinscripcionProyecto;
use App\TextPainter as TextPainter;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use PDF;
use \Html2Text\Html2Text as Html2Text;

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
            $gp->tipo_gp = Auth::user()->estudiante->proceso[0]->id;

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

            if($gp->save())
            {

                $perfil = new TextPainter(public_path('images/controles/perfil.jpg'),'',public_path('fonts/arial.ttf'), 10);
                $perfil->setTextColor(0,0,0);
                if ($proceso == 1) {$perfil->setText("x",790,362,20);}else{$perfil->setText("x",1200,362,20);}//Proceso Verifcando la posicion
                $perfil->setText($nombre,450,490,20);//Nombre de Alumno
                $perfil->setText($apellido,450,550,20);//Apellido de Alumno
                $perfil->setText($carnet,1155,490,20);//Carnet de Alumno
                $perfil->setText($telefono,1200,550,20);//Telefono de Alumno
                $perfil->setText($carrera,450,605,20);//Carrera de Alumno
                $perfil->setText($email,1135,607,20);//Email de Alumno

                $perfil->setText($nombreI,120,775,20); //Nombre de la Institucion
                $perfil->setText($sectorI,1150,805,20); //Sector de la Institucion
                $perfil->setText($direccionI,120,900,20); //Direccion de la Institucion
                $perfil->setText($municipioI,120,990,20); //Municipio de la Institucion
                $perfil->setText($departamentoI,445,990,20); //Departamento de la Institucion
                $perfil->setText($emailI,820,990,20); //Email de la Institucion
                $perfil->setText($telefonoI,1350,990,20); //Telefono de la Institucion

                $perfil->setText($nombreP, 115, 1175, 20); //Nombre del Proyecto
                $perfil->setText(Html2Text::convert($actividadesP),115,1300,20); //Actividades a realizar
                $perfil->setText($request->hrsreal, 1465, 1210, 20); //Horas a Realizar del proyecto
                $perfil->setText($request->fechaini, 135, 1625, 20); //Fecha de Inicio  del proyecto
                $perfil->setText($request->fechafin, 400, 1625, 20); //Fecha de Finalizacion  del proyecto
                $perfil->setText($request->super_name, 650, 1625, 20); //Supervisor del proyecto/Institucion
                $perfil->setText($request->super_cell, 1390, 1625, 20); //Telefono Supervisor del proyecto/Institucion
                // Guardando el perfil segun el proceso del estudiante
                if ($proceso == 1) {$perfil->save(public_path('docs/docs_ss/')."PSS-".$carnet);}
                else{$perfil->save(public_path('docs/docs_pp/')."PPP-".$carnet);}

                //PROCESO PARA CONTROL DE HORAS
                $nombre_completo = $nombre." ".$apellido;
                $control_horas = new TextPainter(public_path('images/controles/control-horas.jpg'),'',public_path('fonts/arial.ttf'), 10);
                $control_horas->setTextColor(0,0,0);
                if ($proceso == 1) {$control_horas->setText("x",391,468,30);}else{$control_horas->setText("x",866,468,30);}//Proceso
                $control_horas->setText($nombre_completo,475,555,20);//Nombre del estudiante
                $control_horas->setText($carnet,1335,555,20);//Carnet del estudiante
                $control_horas->setText($carrera,275,610,20);//Carrera del estudiante
                $control_horas->setText($nombreI,865,665,20);//Nombre de la institucion
                $control_horas->setText($nombreP,600,725,20);//Nombre de la institucion
                // Guardando el control de horas segun el proceso del estudiante
                if ($proceso == 1) {$control_horas->save(public_path('docs/docs_ss/')."CHSS-".$carnet);}
                else{$control_horas->save(public_path('docs/docs_pp/')."CHPP-".$carnet);}

                //PROCESO PARA CONTROL DE PROYECTO
                $control_proy = new TextPainter(public_path('images/controles/control-proyecto.jpg'),'',public_path('fonts/arial.ttf'), 10);
                $control_proy->setTextColor(0,0,0);
                if ($proceso == 1) {$control_proy->setText("x",472,498,30);}else{$control_proy->setText("x",944,500,30);}//Proceso
                $control_proy->setText($nombre_completo,300,620,20);//Nombre del estudiante
                $control_proy->setText($carnet,1360,620,20);//Carnet del estudiante
                $control_proy->setText($carrera,290,675,20);//Nombre del estudiante
                $control_proy->setText($nombreI,150,875,20);//Nombre de la institucion
                $control_proy->setText($nombreP,150,1100,20);//Nombre del proyecto
                // Guardando el control de horas segun el proceso del estudiante
                if ($proceso == 1) {$control_proy->save(public_path('docs/docs_ss/')."CPSS-".$carnet);}
                else{$control_proy->save(public_path('docs/docs_pp/')."CPPP-".$carnet);}

                DB::table('preinscripciones_proyectos')->where([
                    ['estudiante_id', $request->student_id],
                    ['estado', 'F']])->delete();

                if ($proceso == 1) { $ruta_img = public_path('docs/docs_ss/')."PSS-".$carnet.".jpg";}
                else{$ruta_img = public_path('docs/docs_pp/')."PPP-".$carnet.".jpg";}

                $pdf = PDF::loadView('public.reportes.documents',['ruta'=>$ruta_img])->setOption('footer-center', '');
                $pdf->setOption('margin-top',15);
                $pdf->setOption('margin-bottom',0);
                $pdf->setOption('margin-left',0);
                $pdf->setOption('margin-right',0);

                DB::commit();
                return base64_encode($pdf->download('Perfil de proyecto.pdf'));
            }
        } catch (Exception $e) {
          DB::rollBack();
      }

  }

  //listado de los estudiantes que ya estan realizado su proyecto
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

        })->where('tipo_gp',$proceso)->paginate(8);

    } else {

        $gp = GestionProyecto::with(['estudiante.carrera', 'proyecto'])
        ->whereHas('estudiante.proceso', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);

        })->whereHas('estudiante', function ($query) use ($buscar) {
            $query->where('nombre', 'like', '%' . $buscar . '%');

        })->whereHas('estudiante', function ($query) use ($carrera_id) {
            $query->where('carrera_id', $carrera_id);
        })->where('tipo_gp',$proceso)->paginate(8);
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
    if($proceso == 1){
        $gp = Estudiante::distinct('id')->with([
            'carrera',
            'gestionProyecto',
            'gestionProyecto.constancia_entreg',
            'nivelAcademico'
        ])->nombre($buscar)->where('estado_ss',1)->where('carrera_id',$carrera_id)->paginate(8);
    }else if($proceso == 2){
        $gp = Estudiante::distinct('id')->with([
            'carrera',
            'gestionProyecto',
            'gestionProyecto.constancia_entreg',
            'nivelAcademico'
        ])->nombre($buscar)->where('estado_pp', 2)->where('carrera_id',$carrera_id)->paginate(8);
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

    $gestionp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion','documentos_entrega','estudiante.proceso'])->findOrFail($id);

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

    if($gp->horas_realizadas == $gp->horas_a_realizar){
        $a = $gp->estudiante_id;
        $e = Estudiante::findOrFail($a);
        $e->no_proyectos = 0;
        $e->update();

        // if($e->no_proyectos == 1){
        //       DB::table('preinscripciones_proyectos')->where([
        //     ['estudiante_id', $gp->estudiante_id],
        //     ['estado', 'F']])->orWhere('estado','P')->delete();
        // }

        $e->proceso()->detach(1);
        if($e->proceso()->attach(2,array('num_horas' => '160'))){
            $a->proceso_actual = 'P';
        }

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

        foreach($carrera as $carre){

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

        $pdf = PDF::loadView('reportes.iniprocesos', ['mensuales' => $mensuales,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
        $pdf->setOption('margin-top',20);
        $pdf->setOption('margin-bottom',20);
        $pdf->setOption('margin-left',20);
        $pdf->setOption('margin-right',20);
        return $pdf->stream('Reporte Inicio Procesos '.date('Y-m-d').'.pdf');
        // return $mensuales;

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
        $pdf->setOption('margin-top',20);
        $pdf->setOption('margin-bottom',20);
        $pdf->setOption('margin-left',20);
        $pdf->setOption('margin-right',20);
        return $pdf->stream('Reporte Inicio Procesos '.date('Y-m-d').'.pdf');

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
        $pdf->setOption('margin-top',20);
        $pdf->setOption('margin-bottom',20);
        $pdf->setOption('margin-left',20);
        $pdf->setOption('margin-right',20);
        return $pdf->stream('Reporte Inicio Procesos '.date('Y-m-d').'.pdf');
    }
}
//Pendientes de inicio de proceso
public function getPendientesIniProcessReporte(Request $request){

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

            $estudiantesM1 = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                $query->where('procesos_estudiantes.proceso_id', $procesoId);
            })->whereMonth('updated_at',$arrayTrimestre[0])->where('proceso_actual','P')->get();

            $estudiantesM2 = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                $query->where('procesos_estudiantes.proceso_id', $procesoId);
            })->whereMonth('updated_at',$arrayTrimestre[1])->where('proceso_actual','P')->get();

            $estudiantesM3 = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                $query->where('procesos_estudiantes.proceso_id', $procesoId);
            })->whereMonth('updated_at',$arrayTrimestre[2])->where('proceso_actual','P')->get();

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
        // Sacando Consolidado por los 3 meses
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
         );
            $data[1] = $totalFinal;
        }

        $mensuales = array();
        array_push($mensuales,$mes1);
        array_push($mensuales,$mes2);
        array_push($mensuales,$mes3);

        $pdf = PDF::loadView('reportes.repeninicio', ['mensuales' => $mensuales,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
        $pdf->setOption('margin-top',20);
        $pdf->setOption('margin-bottom',20);
        $pdf->setOption('margin-left',20);
        $pdf->setOption('margin-right',20);
        return $pdf->stream('Reporte Pendientes de Inicio '.date('Y-m-d').'.pdf');

    }else if($request->tipoRepo == 'M'){

        $arrayMeses = explode(",", $request->meses);
        $dataMensual = [];
        $data = array();
        $total = 0;
        $mesesTitulo = [];

        $arrayMes = [];$arrayMesEstudiante = [];

        for ($i=0; $i < count($arrayMeses) ; $i++) {

            $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

            for ($j=0; $j < $carrera->count() ; $j++) {

              $total += $carrera[$j]->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carrera[$j]->id]])->whereHas('proceso', function ($query) use ($procesoId) {
               $query->where('procesos_estudiantes.proceso_id', $procesoId);
           })->whereMonth('updated_at',$arrayMeses[$i])->where('proceso_actual','P')->count();
          }


          foreach($carrera as $carre){

            $estudiantes = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                $query->where('procesos_estudiantes.proceso_id', $procesoId);
            })->whereMonth('updated_at',$arrayMeses[$i])->where('proceso_actual','P')->get();


            $arrayMesEstudiante[0] = $carre->nombre;
            $arrayMesEstudiante[1] = $estudiantes;

            $arrayMes[0] = $this->meses[$arrayMeses[$i]];
            $arrayMes[1] = $total;
            $arrayMes[$carre->id+1] = $arrayMesEstudiante;

        }
        $total = 0;

        array_push($data, $arrayMes);
    }

    $pdf = PDF::loadView('reportes.repeninicio', ['mensuales' => $data,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'M','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
    $pdf->setOption('margin-top',20);
    $pdf->setOption('margin-bottom',20);
    $pdf->setOption('margin-left',20);
    $pdf->setOption('margin-right',20);
    return $pdf->stream('Reporte Pendientes de Inicio '.date('Y-m-d').'.pdf');
        // return $data;

}else if($request->tipoRepo == 'A'){

    $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
    $data = array();
    $mesesTitulo = "";
    $anio = date('Y');
    $dataMensual = array();
    $total = 0;
    $mesesTitulo = [];

    $arrayMes = [];$arrayMesEstudiante = [];

    for ($i=0; $i < count($arrayMeses) ; $i++) {

        $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

        for ($j=0; $j < $carrera->count() ; $j++) {

          $total += $carrera[$j]->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carrera[$j]->id]])->whereHas('proceso', function ($query) use ($procesoId) {
             $query->where('procesos_estudiantes.proceso_id', $procesoId);
         })->whereMonth('updated_at',$arrayMeses[$i])->where('proceso_actual','P')->count();
      }


      foreach($carrera as $carre){

        $estudiantes = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
            $query->where('procesos_estudiantes.proceso_id', $procesoId);
        })->whereMonth('updated_at',$arrayMeses[$i])->where('proceso_actual','P')->get();


        $arrayMesEstudiante[0] = $carre->nombre;
        $arrayMesEstudiante[1] = $estudiantes;

        $arrayMes[0] = $this->meses[$arrayMeses[$i]];
        $arrayMes[1] = $total;
        $arrayMes[$carre->id+1] = $arrayMesEstudiante;

    }
    $total = 0;

    array_push($dataMensual, $arrayMes);
}

         // Sacando Consolidado Anual
$data = [];
$data[0] = $anio;
$totalFinal = 0;
foreach ($carrera as $carre) {

            //Obteniendo el total de resultados becados y otros
    $estudiantes = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
        $query->where('procesos_estudiantes.proceso_id', $procesoId);
    })->whereYear('updated_at',$anio)->where('proceso_actual','P')->count();

    $totalFinal += $estudiantes;

    $data[$carre->id+1] = array(
     "Carrera" => $carre->nombre,
     "total" => $estudiantes,
 );

    $data[1] = $totalFinal;
}
$mesesTitulo[0] = "ANUAL";

$pdf = PDF::loadView('reportes.repeninicio', ['consolidadoMensual' => $dataMensual,'consolidadoAnual' => $data,'meses'=>$mesesTitulo,'tipo'=>'A','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
$pdf->setOption('margin-top',20);
$pdf->setOption('margin-bottom',20);
$pdf->setOption('margin-left',20);
$pdf->setOption('margin-right',20);
return $pdf->stream('Reporte Pendientes de Inicio '.date('Y-m-d').'.pdf');
}
}

public function getPendientesFinProcessReporte(Request $request){
   $carrera = Carrera::get();
   $procesoId = $request->proceso_id;
   $procesoTitulo = "";
   $mesesTitulo = "";
   $documentos = Documento::all();

   if($procesoId == 1)
    $procesoTitulo = "SERVICIO SOCIAL";
else if($procesoId == 2)
    $procesoTitulo = "PRÁCTICA PROFESIONAL";

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

        $estudiantesM1 = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
            $query->where('procesos_estudiantes.proceso_id', $procesoId);
        })->whereMonth('updated_at',$arrayTrimestre[0])->where('proceso_actual','I')->get();

        $estudiantesM2 = $carre->estudiantes()->has('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
            $query->where('procesos_estudiantes.proceso_id', $procesoId);
        })->whereMonth('updated_at',$arrayTrimestre[1])->where('proceso_actual','I')->get();

        $estudiantesM3 = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
            $query->where('procesos_estudiantes.proceso_id', $procesoId);
        })->whereMonth('updated_at',$arrayTrimestre[2])->where('proceso_actual','I')->get();

        $estudiante = "";
        foreach ($estudiantesM1 as $value) {
          $estudiante = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
          foreach ($estudiante->gestionProyecto as $item) {
              $value->setAttribute("documentosEntregados",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
          }
      }

      $estudiante2 = "";
      foreach ($estudiantesM2 as $value) {
          $estudiante2 = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
          foreach ($estudiante2->gestionProyecto as $item) {
              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
          }
      }

      $estudiante3 = "";
      foreach ($estudiantesM3 as $value) {
          $estudiante3 = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
          foreach ($estudiante3->gestionProyecto as $item) {
              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
          }
      }

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
        // Sacando Consolidado por los 3 meses
  $data = [];
  $data[0] = $this->trimestres[implode($arrayTrimestre)];
  $totalFinal = 0;
  foreach ($carrera as $carre) {
                    //Obteniendo el total de resultados becados y otros
    $estudiantes =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
        $query->where('procesos_estudiantes.proceso_id', $procesoId);
    })->whereIn(DB::raw('MONTH(updated_at)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->count();

    $totalFinal += $estudiantes;

    $data[$carre->id+1] = array(
       "Carrera" => $carre->nombre,
       "total" => $estudiantes,
   );
    $data[1] = $totalFinal;
}


$mensuales = array();
array_push($mensuales,$mes1);
array_push($mensuales,$mes2);
array_push($mensuales,$mes3);

$pdf = PDF::loadView('reportes.repenfinalizacion', ['mensuales' => $mensuales,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
$pdf->setOption('margin-top',20);
$pdf->setOption('margin-bottom',20);
$pdf->setOption('margin-left',20);
$pdf->setOption('margin-right',20);
return $pdf->stream('Pendientes de Inicio.pdf');
        // return $mensuales;

}else if($request->tipoRepo == 'M'){

    $arrayMeses = explode(",", $request->meses);
    $dataMensual = [];
    $data = array();
    $total = 0;
    $mesesTitulo = [];

    $arrayMes = [];$arrayMesEstudiante = [];

    for ($i=0; $i < count($arrayMeses) ; $i++) {

        $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

        for ($j=0; $j < $carrera->count() ; $j++) {

           $total += $carrera[$j]->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carrera[$j]->id]])->whereHas('proceso', function ($query) use ($procesoId) {
            $query->where('procesos_estudiantes.proceso_id', $procesoId);
        })->whereMonth('updated_at',$arrayMeses[$i])->where('proceso_actual','I')->count();

       }


       foreach($carrera as $carre){

        $estudiantes = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
            $query->where('procesos_estudiantes.proceso_id', $procesoId);
        })->whereMonth('updated_at',$arrayMeses[$i])->where('proceso_actual','I')->get();

        $estudiantesDoc = "";
        foreach ($estudiantes as $value) {
            $estudiantesDoc = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
            foreach ($estudiantesDoc->gestionProyecto as $item) {
              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
          }
      }

      $arrayMesEstudiante[0] = $carre->nombre;
      $arrayMesEstudiante[1] = $estudiantes;

      $arrayMes[0] = $this->meses[$arrayMeses[$i]];
      $arrayMes[1] = $total;
      $arrayMes[$carre->id+1] = $arrayMesEstudiante;

  }
  $total = 0;

  array_push($data, $arrayMes);
}

$pdf = PDF::loadView('reportes.repenfinalizacion', ['mensuales' => $data,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'M','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
$pdf->setOption('margin-top',20);
$pdf->setOption('margin-bottom',20);
$pdf->setOption('margin-left',20);
$pdf->setOption('margin-right',20);
return $pdf->stream('Reporte Pendientes de Finalización '.date('Y-m-d').'.pdf');
            // return $data;

}else if($request->tipoRepo == 'A'){

    $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
    $data = array();
    $mesesTitulo = "";
    $anio = date('Y');
    $dataMensual = array();
    $total = 0;
    $mesesTitulo = [];

    $arrayMes = [];$arrayMesEstudiante = [];

    for ($i=0; $i < count($arrayMeses) ; $i++) {

        $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

        for ($j=0; $j < $carrera->count() ; $j++) {

          $total += $carrera[$j]->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carrera[$j]->id]])->whereHas('proceso', function ($query) use ($procesoId) {
              $query->where('procesos_estudiantes.proceso_id', $procesoId);
          })->whereMonth('updated_at',$arrayMeses[$i])->where('proceso_actual','I')->count();
      }


      foreach($carrera as $carre){

        $estudiantes = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
            $query->where('procesos_estudiantes.proceso_id', $procesoId);
        })->whereMonth('updated_at',$arrayMeses[$i])->where('proceso_actual','I')->get();

        $estudiantesDoc = "";
        foreach ($estudiantes as $value) {
            $estudiantesDoc = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
            foreach ($estudiantesDoc->gestionProyecto as $item) {
              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
          }
      }

      $arrayMesEstudiante[0] = $carre->nombre;
      $arrayMesEstudiante[1] = $estudiantes;

      $arrayMes[0] = $this->meses[$arrayMeses[$i]];
      $arrayMes[1] = $total;
      $arrayMes[$carre->id+1] = $arrayMesEstudiante;

  }
  $total = 0;

  array_push($dataMensual, $arrayMes);
}

             // Sacando Consolidado Anual
$data = [];
$data[0] = $anio;
$totalFinal = 0;
foreach ($carrera as $carre) {

                //Obteniendo el total de resultados becados y otros
    $estudiantesCount = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
      $query->where('procesos_estudiantes.proceso_id', $procesoId);
  })->whereYear('updated_at',$anio)->where('proceso_actual','I')->count();

    $totalFinal += $estudiantesCount;

    $data[$carre->id+1] = array(
     "Carrera" => $carre->nombre,
     "total" => $estudiantesCount,
 );

    $data[1] = $totalFinal;
}
$mesesTitulo[0] = "ANUAL";

$pdf = PDF::loadView('reportes.repenfinalizacion', ['consolidadoMensual' => $dataMensual,'consolidadoAnual' => $data,'meses'=>$mesesTitulo,'tipo'=>'A','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
$pdf->setOption('margin-top',20);
$pdf->setOption('margin-bottom',20);
$pdf->setOption('margin-left',20);
$pdf->setOption('margin-right',20);
return $pdf->stream('Reporte Pendientes de Finalización '.date('Y-m-d').'.pdf');
            // return $data;
}

}

public function getProcesosCulminadosReporte(Request $request){
    $carrera = Carrera::get();
    $procesoId = $request->proceso_id;
    $procesoTitulo = "";
    $mesesTitulo = "";
    $estudianteProceso = "";

    if($procesoId == 1){
        $procesoTitulo = "SERVICIO SOCIAL";
        $estudianteProceso = "estado_ss";
    }else if($procesoId == 2){
        $procesoTitulo = "PRACTICA PROFESIONAL";
        $estudianteProceso = "estado_pp";
    }

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

        foreach($carrera as $carre){

            // SUMA MES 1
            $totalMinedMes1 += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[0])->count();

            $totalOtrosMes1 += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[0])->count();

            // DATOS MES 1

            $estudiantesM1 = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['estado', true], ['carrera_id', $carre->id]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[0])->get();

            // SUMA MES 2
            $totalMinedMes2 += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[1])->count();

            $totalOtrosMes2 += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[1])->count();

            // DATOS MES 2
            $estudiantesM2 = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['estado', true], ['carrera_id', $carre->id]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[1])->get();

            // SUMA MES 4
            $totalMinedMes3 += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[2])->count();

            // DATOS MES 3
            $totalOtrosMes3 += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[2])->count();

            $estudiantesM3 = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['estado', true], ['carrera_id', $carre->id]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayTrimestre[2])->get();


            $c1[0] = $carre->nombre;
            $c1[1] = array('totalMined' => $totalMinedMes1,'totalOtros'=>$totalOtrosMes1);
            $c1[2] = $estudiantesM1;
            $totalMinedMes1 = 0;$totalOtrosMes1 = 0;


            $c2[0] = $carre->nombre;
            $c2[1] = array('totalMined' => $totalMinedMes2,'totalOtros'=>$totalOtrosMes2);
            $c2[2] = $estudiantesM2;
            $totalMinedMes2 = 0;$totalOtrosMes2 = 0;

            $c3[0] = $carre->nombre;
            $c3[1] = array('totalMined' => $totalMinedMes2,'totalOtros'=>$totalOtrosMes2);
            $c3[2] = $estudiantesM3;
            $totalMinedMes3 = 0;$totalOtrosMes3 = 0;

            $mes1[$carre->id] = $c1;
            $mes2[$carre->id] = $c2;
            $mes3[$carre->id] = $c3;

        }

        // Sacando Consolidado por los 3 meses
        $data = [];
        $data[0] = $this->trimestres[implode($arrayTrimestre)];
        $totalFinal = 0;
        foreach ($carrera as $carre) {
            //Obteniendo el total de resultados becados y otros
            $estudiantes =  $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['estado', true], ['carrera_id',
                $carre->id]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw('MONTH(updated_at)'), [$arrayTrimestre[0],
                $arrayTrimestre[1],$arrayTrimestre[2]])->count();

                $totalFinal += $estudiantes;

                $data[$carre->id] = array(
                 "Carrera" => $carre->nombre,
                 "total" => $estudiantes,
             );
                $data[1] = $totalFinal;
            }

            $mensuales = array();
            array_push($mensuales,$mes1);
            array_push($mensuales,$mes2);
            array_push($mensuales,$mes3);


            $pdf = PDF::loadView('reportes.reprocesoculminado', ['mensuales' => $mensuales,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
            $pdf->setOption('margin-top',20);
            $pdf->setOption('margin-bottom',20);
            $pdf->setOption('margin-left',20);
            $pdf->setOption('margin-right',20);
            return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
        //return $mensuales;

        }else if($request->tipoRepo == 'M'){

            $arrayMeses = explode(",", $request->meses);
            $data = array();
            $arrayMes = [];
            $arrayMesEstudiante = [];
            $totalMined = 0;$totalOtros = 0;
            $mesesTitulo = [];

            for ($i=0; $i < count($arrayMeses) ; $i++) {

              $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

              foreach($carrera as $carre){

               $totalMined += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',
                $arrayMeses[$i])->count();

               $totalOtros += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',
                $arrayMeses[$i])->count();

               $estudiantes = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['estado', true], ['carrera_id', $carre->id]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayMeses[$i])->get();

               $arrayMesEstudiante[0] = $carre->nombre;
               $arrayMesEstudiante[1] = array('totalMined' => $totalMined,'totalOtros' => $totalOtros);
               $arrayMesEstudiante[2] = $estudiantes;
              //Limpiando Variables de Suma
               $totalMined = 0;$totalOtros = 0;

               $arrayMes[0] = $this->meses[$arrayMeses[$i]];
               $arrayMes[$carre->id] = $arrayMesEstudiante;

           }

           array_push($data, $arrayMes);
       }

       $pdf = PDF::loadView('reportes.reprocesoculminado', ['mensuales' => $data,'tipo' => 'M','meses'=>$mesesTitulo,'procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
       $pdf->setOption('margin-top',20);
       $pdf->setOption('margin-bottom',20);
       $pdf->setOption('margin-left',20);
       $pdf->setOption('margin-right',20);
       return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
        // return $mesesTitulo;


   }else if($request->tipoRepo == 'A'){

    $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
    $dataMensual = array();
    $totalMined = 0;
    $totalOtros = 0;
    $mesesTitulo = [];
    $anio = date('Y');


    for ($i=0; $i < count($arrayMeses) ; $i++) {

      $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

      foreach($carrera as $carre){

       $totalMined += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',
        $arrayMeses[$i])->count();

       $totalOtros += $carre->estudiantes()->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',
        $arrayMeses[$i])->count();

       $estudiantes = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['estado', true], ['carrera_id', $carre->id]])->whereNotNull(trim($estudianteProceso))->whereMonth('updated_at',$arrayMeses[$i])->get();

       $arrayMesEstudiante[0] = $carre->nombre;
       $arrayMesEstudiante[1] = array('totalMined' => $totalMined,'totalOtros' => $totalOtros);
       $arrayMesEstudiante[2] = $estudiantes;
              //Limpiando Variables de Suma
       $totalMined = 0;$totalOtros = 0;

       $arrayMes[0] = $this->meses[$arrayMeses[$i]];
       $arrayMes[$carre->id] = $arrayMesEstudiante;

   }

   array_push($dataMensual, $arrayMes);
}

           // Sacando Consolidado por los 3 meses
$data = [];
$data[0] = $anio;
$totalFinal = 0;
foreach ($carrera as $carre) {
                //Obteniendo el total de resultados becados y otros
    $estudiantes =  $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['estado', true], ['carrera_id',
        $carre->id]])->whereNotNull(trim($estudianteProceso))->whereYear('updated_at',$anio)->count();

    $totalFinal += $estudiantes;

    $data[$carre->id] = array(
     "Carrera" => $carre->nombre,
     "total" => $estudiantes,
 );

    $data[1] = $totalFinal;
}

$mesesTitulo = "ANUAL";

}
$pdf = PDF::loadView('reportes.reprocesoculminado', ['mensuales' => $dataMensual,'consolidadoAnual'=>$data,'tipo' => 'A', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo])->setOption('footer-center', 'Página [page] de [topage]');
$pdf->setOption('margin-top',20);
$pdf->setOption('margin-bottom',20);
$pdf->setOption('margin-left',20);
$pdf->setOption('margin-right',20);
return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
        // return $data;
}

public function generateConstancia(Request $request){

    $estudianteId = $request->estudianteId;
    $procesoId = $request->procesoId;
    $date = date('Y-m-d');
    $tituloProceso = "";
    $totalHoras = 0;

    if($procesoId == 1)
        $tituloProceso = "Servicio Social";
    else
        $tituloProceso = "Práctica Profesional";

    $estudiante = Estudiante::with([
        'carrera',
        'gestionProyecto',
        'nivelAcademico',
        'gestionProyecto.proyecto',
        'gestionProyecto.proyecto.institucion',
    ])->whereHas('gestionProyecto', function ($query) use ($procesoId) {
        $query->where('tipo_gp', $procesoId);
    })->find($estudianteId);

    $proyectos =  [];
    $proyectos = $estudiante->gestionProyecto()->where('tipo_gp',$procesoId)->get();
    $infoEstudiante = $estudiante;


    foreach ($estudiante->gestionProyecto as $value) {

        if($value->constancia_entreg()->count() == 0){

            DB::table('constancias_entregadas')->insert(
                ['gestion_proyecto_id' => $value->id,'created_at' => Carbon::now()->toDateTimeString()]
          );

        }
    }
    foreach ($proyectos as $item) {

        $totalHoras += $item->horas_realizadas;
    }

    $admin = User::select('nombre')->find(0);
    $pdf = PDF::loadView('reportes.constanciass', ['admin'=>$admin,'estudiante'=>$infoEstudiante,'proyectos' => $proyectos, 'proceso' =>$tituloProceso,'fecha' => $date,'totalHoras' => $totalHoras])->setOption('footer-center', '');
    return $pdf->stream('Perfil de proyecto.pdf');

}
public function downloadDocs(Request $request){
    $procesoId = $request->procesoId;
    $codCarnet = $request->codCarnet;
    $tipoDoc = $request->tipoDoc;

    if ($procesoId == 1) {
      $ruta_img = public_path('docs/docs_ss/').$tipoDoc."SS-".$codCarnet.".jpg";
  }
  else{
    $ruta_img = public_path('docs/docs_pp/').$tipoDoc."PP-".$codCarnet.".jpg";
}

$pdf = PDF::loadView('public.reportes.documents',['ruta'=>$ruta_img])->setOption('footer-center', '');
$pdf->setOption('margin-top',15);
$pdf->setOption('margin-bottom',0);
$pdf->setOption('margin-left',0);
$pdf->setOption('margin-right',0);

switch ($tipoDoc) {
    case 'P':
    return $pdf->download('Perfil de Proyecto.pdf');
    break;
    case 'CH':
    return $pdf->download('Control de Asistencia.pdf');
    break;
    case 'CP':
    return $pdf->download('Control de Proyecto.pdf');
    break;
}

}
}
