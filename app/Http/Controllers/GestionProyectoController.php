<?php

namespace App\Http\Controllers;
use App\Carrera;
use App\Documento;
use App\Estudiante;
use App\GestionProyecto;
use App\PreinscripcionProyecto;
use App\Proyecto;
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
    public $anio;

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
        "101112" => 'CUARTO TRIMESTRE');

     public function __construct()
    {
        $this->anio = config('app.app_year');
    }

    //Metodo que guarda los datos del perfil del proyecto y descarga el pdf y guarda los otros documentos relacionados al proceso
    public function initGestionProyecto(Request $request){

        try {
            DB::beginTransaction();
            $gp = new GestionProyecto();
            $gp->fecha_inicio = $request->fechaini; //Fecha Inicio
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
                $numeroFactura = Auth::user()->estudiante->pagoArancel()->where('proceso_id',$proceso)->get();

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
                $perfil->setText($nombre,385,490,20);//Nombre de Alumno
                $perfil->setText($apellido,385,550,20);//Apellido de Alumno
                $perfil->setText($carnet,1115,490,20);//Carnet de Alumno
                $perfil->setText($telefono,1160,550,20);//Telefono de Alumno
                $perfil->setText($carrera,385,605,20);//Carrera de Alumno
                $perfil->setText($email,1100,607,20);//Email de Alumno

                $perfil->setText($nombreI,120,775,20); //Nombre de la Institucion
                $perfil->setText($sectorI,1150,805,20); //Sector de la Institucion
                $perfil->setText($direccionI,120,900,20); //Direccion de la Institucion
                $perfil->setText($municipioI,120,990,20); //Municipio de la Institucion
                $perfil->setText($departamentoI,650,990,20); //Departamento de la Institucion
                $perfil->setText($emailI,920,990,20); //Email de la Institucion
                $perfil->setText($telefonoI,1350,990,20); //Telefono de la Institucion

                $perfil->setText($nombreP, 115, 1175, 20); //Nombre del Proyecto
                $perfil->setText(Html2Text::convert($actividadesP),120,1300,20); //Actividades a realizar
                $perfil->setText($request->hrsreal, 1465, 1210, 20); //Horas a Realizar del proyecto
                $perfil->setText($request->fechaini, 135, 1625, 20); //Fecha de Inicio  del proyecto
                $perfil->setText($request->super_name, 650, 1625, 20); //Supervisor del proyecto/Institucion
                $perfil->setText($request->super_cell, 1390, 1625, 20); //Telefono Supervisor del proyecto/Institucion
                $perfil->setText($numeroFactura[0]->no_factura,1235,1860,20); //Numero de factura de pago de arancel de proceso
                // Guardando el perfil segun el proceso del estudiante
                if ($proceso == 1) {$perfil->save(public_path('docs/docs_ss/')."PSS-".$carnet);}
                else{$perfil->save(public_path('docs/docs_pp/')."PPP-".$carnet);}

                // // //PROCESO PARA CONTROL DE HORAS
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

                // // //PROCESO PARA CONTROL DE PROYECTO
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
                // return $pdf->stream('Perfil de proyecto.pdf');
            }
        } catch (Exception $e) {
          DB::rollBack();
      }
    }

    //listado de los estudiantes que ya estan realizado su proyecto
    public function index(Request $request){
        //if (!$request->ajax()) return redirect('/');
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

    // Metodo que delvuelve toda la informacion del el proyecto que esta realizando un alumno
    public function getInfoGpById($id){

        $gestionp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion','documentos_entrega','estudiante.proceso'])->findOrFail($id);

        return $gestionp;
    }

    // Metodo que devuelve todos los proyectos que ha realizado un alumno en su proceso correspondiente
    public function getGestionProyectoByStudent($student_id){

        $gestionp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion'])->whereHas('estudiante', function ($query) use ($student_id) {
            $query->where('estudiantes.id',$student_id);
        })->get();

        return view('public.gestionPro',compact("gestionp"));
            //return $gestionp;
    }

    // Metodo que cierra un proyecto deacuerdo ala horas y fecha de finalizacion del estudiante
    public function closeProy(Request $request){

        $gp = GestionProyecto::findOrFail($request->gestionId);
        $gp->fecha_fin = $request->fechaFin;
        $gp->horas_realizadas = $request->horasRea;
        $gp->observacion_final = $request->obsFinal;
        $gp->estado = 'F';
        $gp->update();

        $a = $gp->estudiante_id;
        $e = Estudiante::findOrFail($a);

        if($request->horasRea == $e->proceso[0]->pivot->num_horas){
            $e->no_proyectos = 0;
            if ($e->nivel_academico_id == 1) {
                $e->nivel_academico_id = 2;
            }
            if($gp->tipo_gp == 1){
                $e->fecha_fin_ss = date('Y-m-d');

                if(file_exists(public_path('docs/docs_ss/')."PSS-".$e->codCarnet.".jpg"))
                  unlink(public_path('docs/docs_ss/')."PSS-".$e->codCarnet.".jpg");

                if(file_exists(public_path('docs/docs_ss/')."CHSS-".$e->codCarnet.".jpg"))
                    unlink(public_path('docs/docs_ss/')."CHSS-".$e->codCarnet.".jpg");

                if(file_exists(public_path('docs/docs_ss/')."CPSS-".$e->codCarnet.".jpg"))
                    unlink(public_path('docs/docs_ss/')."CPSS-".$e->codCarnet.".jpg");

            }else{
                $e->fecha_fin_pp = date('Y-m-d');

                if(file_exists(public_path('docs/docs_pp/')."PPP-".$e->codCarnet.".jpg"))
                  unlink(public_path('docs/docs_pp/')."PPP-".$e->codCarnet.".jpg");

                if(file_exists(public_path('docs/docs_pp/')."CHPP-".$e->codCarnet.".jpg"))
                    unlink(public_path('docs/docs_pp/')."CHPP-".$e->codCarnet.".jpg");

                if(file_exists(public_path('docs/docs_pp/')."CPPP-".$e->codCarnet.".jpg"))
                    unlink(public_path('docs/docs_pp/')."CPPP-".$e->codCarnet.".jpg");
            }

            $e->update();
            $e->proceso()->detach(1);
            if($e->proceso()->attach(2,array('num_horas' => '160'))){
                $a->proceso_actual = 'P';
            }
        }
    }

    //*****************************Funciones utilizadas para la generacion de reportes de SS Y PP*****************************//
    //Devuelve el reporte de los estudiantes que han iniciado un proceso determinado en una fecha dada por el usuario en la vista
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

                $totalMinedMes1 += $carre->getCountStudentsByMinedMensual($arrayTrimestre[0], $procesoId,$this->anio);
                $totalOtrosMes1 += $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[0], $procesoId,$this->anio);

                $mes1[1] = array(
                    "totalMined" => $totalMinedMes1,
                    "totalOtros"=> $totalOtrosMes1
                );


                $mes1[$carre->id+1] = $collection1 = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayTrimestre[0], $procesoId,$this->anio),
                    "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[0], $procesoId,$this->anio)
                ]);



                $totalMinedMes2 += $carre->getCountStudentsByMinedMensual($arrayTrimestre[1], $procesoId,$this->anio);
                $totalOtrosMes2 += $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[1], $procesoId,$this->anio);

                $mes2[1] = array(
                    "totalMined" => $totalMinedMes2,
                    "totalOtros" => $totalOtrosMes2,
                );

                $mes2[$carre->id+1] = $collection2 = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayTrimestre[1], $procesoId,$this->anio),
                    "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[1], $procesoId,$this->anio),
                ]);

                $totalMinedMes3 += $carre->getCountStudentsByMinedMensual($arrayTrimestre[2], $procesoId,$this->anio);
                $totalOtrosMes3 += $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[2], $procesoId,$this->anio);

                $mes3[1] = array(
                    "totalMined" => $totalMinedMes3,
                    "totalOtros" => $totalOtrosMes3,
                );


                $mes3[$carre->id+1] = $collection2 = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayTrimestre[2], $procesoId,$this->anio),
                    "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[2], $procesoId,$this->anio),
                ]);

            }


            //Sacando Consolidado por los 3 meses
            $data = [];
            $data[0] = $this->trimestres[implode($arrayTrimestre)];
            foreach ($carrera as $carre) {
                //Obteniendo el total de resultados becados y otros
                $totalMined += $carre->getCountStudentsByMinedTrimestral($arrayTrimestre,$procesoId,$this->anio);
                $totalOtros += $carre->getCountStudentsByOtherBecaTrimestral($arrayTrimestre,$procesoId,$this->anio);

                $data[1] = array("totalMined" => $totalMined,"totalOtros"=>$totalOtros);
                $data[$carre->id+1] = $collection = new Collection(["Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedTrimestral($arrayTrimestre,$procesoId,$this->anio),
                    "Otros" => $carre->getCountStudentsByOtherBecaTrimestral($arrayTrimestre,$procesoId,$this->anio)
                ]);
            }

            $mensuales[0] = [
                'mes1' => $mes1,
                'mes2' => $mes2,
                'mes3' => $mes3,
            ];

            $pdf = PDF::loadView('reportes.reporteInicioProcesos', ['mensuales' => $mensuales,'consolidado' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio'=> $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
            $pdf->setOption('margin-top',20);
            $pdf->setOption('margin-bottom',20);
            $pdf->setOption('margin-left',20);
            $pdf->setOption('margin-right',20);
            return $pdf->stream('Reporte Inicio Procesos '.date('Y-m-d').'.pdf');
            // return $data;

        }else if($request->tipoRepo == 'M'){

            $arrayMeses = explode(",", $request->meses);
            $dataMensual = []; $collectionMensual; $data = array();
            $totalMined = 0;$totalMinedArray = [];$totalOtros = 0;$totalOtrosArray = [];$mesesSelectedArray=[];

            for ($i=0; $i < count($arrayMeses) ; $i++) {

                $mesesSelectedArray[$i] = $this->meses[$arrayMeses[$i]];
                $arrayConsolidado[0] = implode($mesesSelectedArray,",");
                for ($j=0; $j < $carrera->count(); $j++) {

                    $totalMinedArray[$i] = $totalMined += $carrera[$j]->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId,$this->anio);
                    $totalOtrosArray[$i] = $totalOtros += $carrera[$j]->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId,$this->anio);
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
                        "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId,$this->anio),
                        "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId,$this->anio),
                    ]);
                };
                array_push($data, $dataMensual);
            }

            $pdf = PDF::loadView('reportes.reporteInicioProcesos', ['mensuales' => $data,'tipo' => 'M','meses'=>$mesesSelectedArray,'procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
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

            for ($i = 0; $i < count($arrayMeses); $i++) {

                for ($j = 0; $j < $carrera->count(); $j++) {
                    $totalMinedArray[$i] = $totalMined += $carrera[$j]->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId,$this->anio);
                    $totalOtrosArray[$i] = $totalOtros += $carrera[$j]->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId,$this->anio);
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
                        "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId,$this->anio),
                        "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId,$this->anio),
                    ]);
                };

                //Consolidado Anual
                $totalMinedAnual = 0;
                $totalOtrosAnual = 0;

                $dataAnual = [];
                $dataAnual[0] = $this->anio;
                foreach ($carrera as $carre) {
                    //Obteniendo el total de resultados becados y otros
                    $totalMinedAnual += $carre->getCountStudentsByMinedYear($this->anio, $procesoId);
                    $totalOtrosAnual += $carre->getCountStudentsByOtherBecaYear($this->anio, $procesoId);

                    $dataAnual[1] = array("totalMined" => $totalMinedAnual, "totalOtros" => $totalOtrosAnual);

                    $dataAnual[$carre->id + 1] = $collection = new Collection([
                        "Carrera" => $carre->nombre,
                        "BecadosMined" => $carre->getCountStudentsByMinedYear($this->anio, $procesoId),
                        "Otros" => $carre->getCountStudentsByOtherBecaYear($this->anio, $procesoId)
                    ]);
                }

                array_push($data, $dataMensual);
                $mesesTitulo = "ANUAL";

            }
            $pdf = PDF::loadView('reportes.reporteInicioProcesos', ['mensuales' => $data,'consolidadoAnual'=>$dataAnual,'tipo' => 'A', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
            $pdf->setOption('margin-top',20);
            $pdf->setOption('margin-bottom',20);
            $pdf->setOption('margin-left',20);
            $pdf->setOption('margin-right',20);
            return $pdf->stream('Reporte Inicio Procesos '.date('Y-m-d').'.pdf');
        }
    }
    // Devuelve el reporte de los estudiantes que estan pendientes de Iniciar sus procesos
    public function getPendientesIniProcessReporte(Request $request){

            $carrera = Carrera::get();
            $procesoId = $request->proceso_id;
            $procesoTitulo = "";
            $mesesTitulo = "";
            $nivelAcademico = "";

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

                    switch ($procesoId) {
                        case 1:
                            $estudiantesM1_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[0])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',1]])->get();

                            $estudiantesM2_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[1])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',1]])->get();

                            $estudiantesM3_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[2])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',1]])->get();

                             $estudiantesM1_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[0])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                            $estudiantesM2_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[1])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                            $estudiantesM3_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[2])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();
                            break;
                        case 2:
                            $estudiantesM1_PA = new Collection;$estudiantesM2_PA = new Collection;$estudiantesM3_PA = new Collection();
                            $estudiantesM1_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[0])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                            $estudiantesM2_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[1])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                            $estudiantesM3_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[2])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();
                            break;
                    }

                    $c1[0] = $carre->nombre;
                    if($procesoId == 2)
                      $c1[1] = array('Segundo Año' => $estudiantesM1_SA);
                    else
                      $c1[1] = array('Primer Año' => $estudiantesM1_PA,'Segundo Año' => $estudiantesM1_SA);


                    $c2[0] = $carre->nombre;
                    if($procesoId == 2)
                        $c2[1] = array('Segundo Año' => $estudiantesM2_SA);
                    else
                        $c2[1] = array('Primer Año' => $estudiantesM2_PA,'Segundo Año' => $estudiantesM2_SA);


                    $c3[0] = $carre->nombre;
                    if($procesoId == 2)
                        $c3[1] = array('Segundo Año' => $estudiantesM3_SA);
                    else
                        $c3[1] = array('Primer Año' => $estudiantesM3_PA,'Segundo Año' => $estudiantesM3_SA);


                    if(count($c1)>0)
                    $mes1[$carre->id] = $c1;
                    if(count($c2)>0)
                    $mes2[$carre->id] = $c2;
                    if(count($c3)>0)
                    $mes3[$carre->id] = $c3;

                }
                // Sacando Consolidado por los 3 meses(GENERAL)
                $dataGeneral = [];
                $dataGeneral[0] = $this->trimestres[implode($arrayTrimestre)];
                foreach ($carrera as $carre) {
                    //Obteniendo el total de resultados becados y otros
                    switch ($procesoId) {
                        case 1:
                            $estudiantesBM =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where('tipo_beca_id',1)->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where('tipo_beca_id',2)->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();
                            break;

                        case 2:
                            $estudiantesBM =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->where('tipo_beca_id',1)->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->where('tipo_beca_id',2)->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();
                            break;
                    }

                    $dataGeneral[$carre->id] = array(
                        "Carrera" => $carre->nombre,
                        "totalBecaMined" => $estudiantesBM,
                        "totalOtraBeca" => $estudiantesOB,
                    );
                }

                 // Sacando Consolidado por los 3 meses(POR NIVEL ACADEMICO)
                $dataByCarrer = [];
                $dataByCarrer[0] = $this->trimestres[implode($arrayTrimestre)];
                $dataPA = [];$dataSA = [];
                foreach ($carrera as $carre) {
                    //Obteniendo el total de resultados becados y otros
                    switch ($procesoId) {
                        case 1:
                            $estudiantesBM_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',1],['nivel_academico_id',1]])->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',2],['nivel_academico_id',1]])->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',1],['nivel_academico_id',2]])->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',2],['nivel_academico_id',2]])->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                            break;

                        case 2:
                            //Dejando colleciones vacia de primer año porque prectica es solo para segundo año
                            $estudiantesBM_PA = new Collection();$estudiantesOB_PA = new Collection();

                            $estudiantesBM_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->where('tipo_beca_id',1)->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->where('tipo_beca_id',2)->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();
                            break;
                    };


                   if ($procesoId == 1) {
                        $dataPA[0] = $this->trimestres[implode($arrayTrimestre)]." 1º AÑO";
                        // Asignando en la posicion 0 el titulo de la tabla
                        $dataPA[$carre->id] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_PA,
                          "totalOtraBeca" => $estudiantesOB_PA,
                        );

                   }

                    $dataSA[0] = $this->trimestres[implode($arrayTrimestre)]." 2º AÑO";
                    $dataSA[$carre->id] = array(
                      "Carrera" => $carre->nombre,
                      "totalBecaMined" => $estudiantesBM_SA,
                      "totalOtraBeca" => $estudiantesOB_SA,
                    );

                    $dataByCarrer[0] = $dataPA;
                    $dataByCarrer[1] = $dataSA;
                }


                $mensuales = array();
                array_push($mensuales,$mes1);
                array_push($mensuales,$mes2);
                array_push($mensuales,$mes3);

                $pdf = PDF::loadView('reportes.reportePendientesDeIniciar', ['mensuales' => $mensuales,'consolidado' => $dataGeneral,'consolidadoPorNivel' => $dataByCarrer,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');

                $pdf->setOption('margin-top',20);
                $pdf->setOption('margin-bottom',20);
                $pdf->setOption('margin-left',20);
                $pdf->setOption('margin-right',20);
                return $pdf->stream('Reporte Pendientes de Inicio '.date('Y-m-d').'.pdf');
                // return $mensuales;

            }else if($request->tipoRepo == 'M'){

                $arrayMeses = explode(",", $request->meses);
                $dataMensual = [];
                $data = array();
                $total = 0;
                $mesesTitulo = [];
                $dataByCarrer = [];

                $arrayMes = [];$arrayMesEstudiante = [];


                for ($i=0; $i < count($arrayMeses) ; $i++) {

                  $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

                    // Sacando Consolidado (POR NIVEL ACADEMICO)
                    $dataPA = [];$dataSA = [];
                    // Obteniendo Datos mensuales y consolidado Mensual
                    foreach($carrera as $carre){

                        switch ($procesoId) {
                            case 1:
                                $estudiantesPA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',1]])->get();

                                $estudiantesSA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                                // Obteniendo la cuenta para el conslidado de los meses
                                $estudiantesBM_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',1],['nivel_academico_id',1]])->whereYear('fecha_registro',$this->anio)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();

                                $estudiantesOB_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',2],['nivel_academico_id',1]])->whereYear('fecha_registro',$this->anio)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();

                                $estudiantesBM_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',1],['nivel_academico_id',2]])->whereYear('fecha_registro',$this->anio)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();

                                $estudiantesOB_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',2],['nivel_academico_id',2]])->whereYear('fecha_registro',$this->anio)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();
                                break;

                            case 2:
                                $estudiantesPA = new Collection();

                                $estudiantesSA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                                // Obteniendo la cuenta para el conslidado de los meses
                                $estudiantesBM_PA = new Collection();$estudiantesOB_PA = new Collection();

                                $estudiantesBM_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->where('tipo_beca_id',1)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->count();

                                $estudiantesOB_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->where('tipo_beca_id',2)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->count();

                                break;
                        }

                        // Datos para cada Mes
                        $arrayMesEstudiante[0] = $carre->nombre;
                        $arrayMesEstudiante[1] = array('Primer Año' => $estudiantesPA, 'Segundo Año' => $estudiantesSA );

                        $arrayMes[0] = $this->meses[$arrayMeses[$i]];
                        $arrayMes[$carre->id] = $arrayMesEstudiante;

                        // Datos para el consolidado de cada mes
                        if ($procesoId == 1) {
                        // Asignando en la posicion 0 el titulo de la tabla
                            $dataPA[0] = $this->meses[$arrayMeses[$i]]." 1º AÑO";
                            $dataPA[$carre->id] = array(
                              "Carrera" => $carre->nombre,
                              "totalBecaMined" => $estudiantesBM_PA,
                              "totalOtraBeca" => $estudiantesOB_PA,
                            );

                        }

                        $dataSA[0] = $this->meses[$arrayMeses[$i]]." 2º AÑO";
                        $dataSA[$carre->id] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_SA,
                          "totalOtraBeca" => $estudiantesOB_SA,
                        );
                    }
                    array_push($dataByCarrer,$dataPA);
                    array_push($dataByCarrer,$dataSA);
                    array_push($data, $arrayMes);

                }

                $pdf = PDF::loadView('reportes.reportePendientesDeIniciar', ['mensuales' => $data,'consolidado' => $dataByCarrer,'meses'=>$mesesTitulo,'tipo'=>'M','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
                $pdf->setOption('margin-top',20);
                $pdf->setOption('margin-bottom',20);
                $pdf->setOption('margin-left',20);
                $pdf->setOption('margin-right',20);
                return $pdf->stream('Reporte Pendientes de Inicio '.date('Y-m-d').'.pdf');
                // return $dataByCarrer;

            }else if($request->tipoRepo == 'A'){

                $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
                $data = array();
                $mesesTitulo = "";
                $dataMensual = array();
                $total = 0;
                $mesesTitulo = [];

                $arrayMes = [];$arrayMesEstudiante = [];

                for ($i=0; $i < count($arrayMeses) ; $i++) {

                      $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];


                      foreach($carrera as $carre){

                            switch ($procesoId) {
                                case 1:
                                    $estudiantesPA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereNull('fecha_inicio_ss')->whereYear('fecha_registro',$this->anio)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->where([['proceso_actual','P'],['nivel_academico_id',1]])->get();

                                    $estudiantesSA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereNull('fecha_inicio_ss')->whereYear('fecha_registro',$this->anio)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();
                                    break;
                                case 2:
                                    $estudiantesPA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                        $query->where('tipo_gp',$procesoId);
                                    })->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereNull('fecha_inicio_pp')->whereYear('fecha_registro',$this->anio)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->where([['proceso_actual','P'],['nivel_academico_id',1]])->get();

                                    $estudiantesSA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                        $query->where('tipo_gp',$procesoId);
                                    })->select('nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereNull('fecha_inicio_pp')->whereYear('fecha_registro',$this->anio)->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();
                                    break;
                            }



                            $arrayMesEstudiante[0] = $carre->nombre;
                            $arrayMesEstudiante[1] = array(
                                "Primer Año" => $estudiantesPA,
                                "Segundo Año" => $estudiantesSA
                            );

                            $arrayMes[0] = $this->meses[$arrayMeses[$i]];
                            $arrayMes[$carre->id] = $arrayMesEstudiante;
                      }

                     array_push($dataMensual, $arrayMes);
                }

                // Sacando Consolidado Anual POR NIVELES ACADEMICOS
                $dataByNivel = [];
                $dataGeneralByAnio = [];
                $dataGeneralByAnio[0] = $this->anio;

                $dataPA = []; $dataSA = [];
                foreach ($carrera as $carre) {

                    //Obteniendo el total de resultados becados y otros
                    switch ($procesoId) {
                        case 1:
                            $estudiantesBM_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',1],['nivel_academico_id',1]])->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',2],['nivel_academico_id',1]])->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',1],['nivel_academico_id',2]])->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',2],['nivel_academico_id',2]])->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                            // DATOS PARA EN CONSOLIDADO GENERAL
                             $estudiantesGeneralBM =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where('tipo_beca_id',1)->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                             $estudiantesGeneralOB =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where('tipo_beca_id',2)->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                            break;

                       case 2:
                            $estudiantesBM_PA = new Collection();$estudiantesOB_PA = new Collection();

                            $estudiantesBM_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->where([['tipo_beca_id',1],['nivel_academico_id',2]])->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->where([['tipo_beca_id',2],['nivel_academico_id',2]])->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                            // DATOS PARA EN CONSOLIDADO GENERAL
                            $estudiantesGeneralBM =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->where('tipo_beca_id',1)->whereYear('fecha_registro',$this->anio)->whereYear('ultimo_cambio',$this->anio)->count();

                            $estudiantesGeneralOB =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true], ['carrera_id', $carre->id]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->where('tipo_beca_id',2)->whereYear('fecha_registro',$this->anio)->whereYear('ultimo_cambio',$this->anio)->count();
                           break;
                    }

                    // Juntando los resultados en array individuales
                     // Datos para el consolidado de cada mes

                    if ($procesoId == 1) {
                            // Asignando en la posicion 0 el titulo de la tabla
                        $dataPA[0] = $this->anio." 1º AÑO";
                        $dataPA[$carre->id] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_PA,
                          "totalOtraBeca" => $estudiantesOB_PA,
                      );

                    }

                    $dataSA[0] = $this->anio." 2º AÑO";
                    $dataSA[$carre->id] = array(
                      "Carrera" => $carre->nombre,
                      "totalBecaMined" => $estudiantesBM_SA,
                      "totalOtraBeca" => $estudiantesOB_SA,
                    );

                    $dataGeneralByAnio[$carre->id] =  array(
                       "Carrera" => $carre->nombre,
                       "totalBecaMined" => $estudiantesGeneralBM,
                       "totalOtraBeca" => $estudiantesGeneralOB,
                    );

                }
                array_push($dataByNivel,$dataPA);
                array_push($dataByNivel,$dataSA);


                    $pdf = PDF::loadView('reportes.reportePendientesDeIniciar', ['consolidadoMensual' => $dataMensual,'consolidadoAnualNiveles' => $dataByNivel,'consolidadoAnualGeneral' => $dataGeneralByAnio,'meses'=>$mesesTitulo,'tipo'=>'A','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
                    $pdf->setOption('margin-top',20);
                    $pdf->setOption('margin-bottom',20);
                    $pdf->setOption('margin-left',20);
                    $pdf->setOption('margin-right',20);
                    return $pdf->stream('Reporte Pendientes de Inicio '.date('Y-m-d').'.pdf');
                    // return $dataByNivel;
            }
    }
    // Devuelve el reporte de los estudiantes que estan pendientes de Finalizar sus procesos
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

                        $estudiantesM1_PA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereMonth('ultimo_cambio','>=',$arrayTrimestre[0])->whereYear('fecha_registro',$this->anio)->get();

                        $estudiantesM1_SA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereMonth('ultimo_cambio','>=',$arrayTrimestre[0])->whereYear('fecha_registro',$this->anio)->get();

                        $estudiantesM2_PA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereMonth('ultimo_cambio','>=',$arrayTrimestre[1])->whereYear('fecha_registro',$this->anio)->get();

                         $estudiantesM2_SA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereMonth('ultimo_cambio','>=',$arrayTrimestre[1])->whereYear('fecha_registro',$this->anio)->get();

                        $estudiantesM3_PA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereMonth('ultimo_cambio','>=',$arrayTrimestre[2])->whereYear('fecha_registro',$this->anio)->get();

                        $estudiantesM3_SA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereMonth('ultimo_cambio','>=',$arrayTrimestre[2])->whereYear('fecha_registro',$this->anio)->get();


                        // OBTENIENDO LOS DOCUMENTOS RESTANTES DE CADA ESTUDIANTE
                        $estudianteM1_PA = "";
                        foreach ($estudiantesM1_PA as $value) {
                          $estudianteM1_PA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                          foreach ($estudianteM1_PA->gestionProyecto as $item) {
                              $value->setAttribute("documentosEntregados",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                          }
                        }

                        $estudianteM1_SA = "";
                        foreach ($estudiantesM1_SA as $value) {
                          $estudianteM1_SA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                          foreach ($estudianteM1_SA->gestionProyecto as $item) {
                              $value->setAttribute("documentosEntregados",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                          }
                        }


                        $estudianteM2_PA = "";
                        foreach ($estudiantesM2_PA as $value) {
                            $estudianteM2_PA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                            foreach ($estudianteM2_PA->gestionProyecto as $item) {
                                  $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                            }
                        }

                        $estudianteM2_SA = "";
                        foreach ($estudiantesM2_SA as $value) {
                            $estudianteM2_SA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                            foreach ($estudianteM2_SA->gestionProyecto as $item) {
                              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                          }
                        }

                        $estudianteM3_PA = "";
                        foreach ($estudiantesM3_PA as $value) {
                          $estudianteM3_PA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                          foreach ($estudianteM3_PA->gestionProyecto as $item) {
                              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                          }
                        }

                        $estudianteM3_SA = "";
                        foreach ($estudiantesM3_SA as $value) {
                          $estudianteM3_SA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                          foreach ($estudianteM3_SA->gestionProyecto as $item) {
                              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                          }
                    }

                      $c1[0] = $carre->nombre;
                      if($procesoId == 1)
                        $c1[1] = array("Primer Año" => $estudiantesM1_PA, "Segundo Año" => $estudiantesM1_SA);
                      else
                        $c1[1] = array("Segundo Año" => $estudiantesM1_SA);

                      $c2[0] = $carre->nombre;
                      if($procesoId == 1)
                        $c2[1] = array("Primer Año" => $estudiantesM2_PA, "Segundo Año" => $estudiantesM2_SA);
                      else
                        $c2[1] = array("Segundo Año" => $estudiantesM2_SA);

                      $c3[0] = $carre->nombre;
                      if($procesoId == 1)
                        $c3[1] = array("Primer Año" => $estudiantesM3_PA, "Segundo Año" => $estudiantesM3_SA);
                      else
                        $c3[1] = array("Segundo Año" => $estudiantesM3_SA);


                      $mes1[$carre->id] = $c1;
                      $mes2[$carre->id] = $c2;
                      $mes3[$carre->id] = $c3;

                      // Sacando Consolidado por los 3 meses(POR NIVEL ACADEMICO)
                      $dataByCarrer = [];
                      $dataByCarrer[0] = $this->trimestres[implode($arrayTrimestre)];
                      $dataPA = [];$dataSA = [];

                      foreach($carrera as $carre){

                          $estudiantesBM_PA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                          })->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                          $estudiantesOB_PA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                          })->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                          $estudiantesBM_SA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                          })->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                          $estudiantesOB_SA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                          })->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();



                            if ($procesoId == 1) {
                                $dataPA[0] = $this->trimestres[implode($arrayTrimestre)]." 1º AÑO";
                            // Asignando en la posicion 0 el titulo de la tabla
                                $dataPA[$carre->id] = array(
                                  "Carrera" => $carre->nombre,
                                  "totalBecaMined" => $estudiantesBM_PA,
                                  "totalOtraBeca" => $estudiantesOB_PA,
                              );

                            }

                            $dataSA[0] = $this->trimestres[implode($arrayTrimestre)]." 2º AÑO";
                            $dataSA[$carre->id] = array(
                              "Carrera" => $carre->nombre,
                              "totalBecaMined" => $estudiantesBM_SA,
                              "totalOtraBeca" => $estudiantesOB_SA,
                          );

                            $dataByCarrer[0] = $dataPA;
                            $dataByCarrer[1] = $dataSA;
                      }


                    // Sacando Consolidado por los 3 meses(GENERAL)
                      $dataGeneral = [];
                      $dataGeneral[0] = $this->trimestres[implode($arrayTrimestre)];
                      foreach ($carrera as $carre) {
                            //Obteniendo el total de resultados becados y otros
                            $estudiantesBM =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                              $estudiantesOB =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();


                            $dataGeneral[$carre->id] = array(
                                "Carrera" => $carre->nombre,
                                "totalBecaMined" => $estudiantesBM,
                                "totalOtraBeca" => $estudiantesOB,
                            );
                      }
                  }

                    $mensuales = array();
                    array_push($mensuales,$mes1);
                    array_push($mensuales,$mes2);
                    array_push($mensuales,$mes3);

                    $pdf = PDF::loadView('reportes.reportePendientesFinalizacion', ['mensuales' => $mensuales,'consolidadoByNivel' => $dataByCarrer,'consolidadoGeneral'=> $dataGeneral,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
                    $pdf->setOption('margin-top',15);
                    $pdf->setOption('margin-bottom',15);
                    $pdf->setOption('margin-left',15);
                    $pdf->setOption('margin-right',15);
                    return $pdf->stream('Pendientes de Finalización '.date('Y-m-d').'.pdf');
                 // return $mensuales;

            }else if($request->tipoRepo == 'M'){

                $arrayMeses = explode(",", $request->meses);
                $dataMensual = [];
                $data = array();
                $total = 0;
                $mesesTitulo = [];
                $dataByNivel = [];

                $arrayMes = [];$arrayMesEstudiante = [];

                for ($i=0; $i < count($arrayMeses) ; $i++) {

                    $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

                    // Sacando Consolidado (POR NIVEL ACADEMICO)
                    $dataPA = [];$dataSA = [];

                   foreach($carrera as $carre){

                        $estudiantes_PA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->get();

                        $estudiantes_SA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->get();


                        // DATOS PARA CONSOLIDADO MENSUAL POR NIVEL ACADEMICO

                         $estudiantesBM_PA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                          })->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->count();

                          $estudiantesOB_PA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                          })->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->count();

                          $estudiantesBM_SA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                          })->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->count();

                          $estudiantesOB_SA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                          })->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->count();


                        $estudiantePA = "";
                        foreach ($estudiantes_PA as $value) {
                            $estudiantePA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                            foreach ($estudiantePA->gestionProyecto as $item) {
                              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                            }
                        }

                        $estudianteSA = "";
                        foreach ($estudiantes_SA as $value) {
                            $estudianteSA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                            foreach ($estudianteSA->gestionProyecto as $item) {
                              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                            }
                        }

                        $arrayMesEstudiante[0] = $carre->nombre;
                        $arrayMesEstudiante[1] = array("Primer Año" => $estudiantes_PA,"Segundo Año" => $estudiantes_SA);

                        $arrayMes[0] = $this->meses[$arrayMeses[$i]];
                        $arrayMes[$carre->id] = $arrayMesEstudiante;

                         // Datos para el consolidado de cada mes
                        if ($procesoId == 1) {
                        // Asignando en la posicion 0 el titulo de la tabla
                            $dataPA[0] = $this->meses[$arrayMeses[$i]]." 1º AÑO";
                            $dataPA[$carre->id] = array(
                              "Carrera" => $carre->nombre,
                              "totalBecaMined" => $estudiantesBM_PA,
                              "totalOtraBeca" => $estudiantesOB_PA,
                            );

                        }

                        $dataSA[0] = $this->meses[$arrayMeses[$i]]." 2º AÑO";
                        $dataSA[$carre->id] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_SA,
                          "totalOtraBeca" => $estudiantesOB_SA,
                        );
                   }

                   array_push($data, $arrayMes);
                   array_push($dataByNivel,$dataPA);
                   array_push($dataByNivel,$dataSA);
                }


                $pdf = PDF::loadView('reportes.reportePendientesFinalizacion', ['mensuales' => $data,'consolidadoByNivel' => $dataByNivel,'meses'=>$mesesTitulo,'tipo'=>'M','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
                $pdf->setOption('margin-top',15);
                $pdf->setOption('margin-bottom',15);
                $pdf->setOption('margin-left',15);
                $pdf->setOption('margin-right',15);
                return $pdf->stream('Reporte Pendientes de Finalización '.date('Y-m-d').'.pdf');
                // return $dataByNivel;

            }else if($request->tipoRepo == 'A'){

                $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
                $data = array();
                $mesesTitulo = "";
                $dataMensual = array();
                $mesesTitulo = [];

                $arrayMes = [];$arrayMesEstudiante = [];

                for ($i=0; $i < count($arrayMeses) ; $i++) {

                    $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

                    foreach($carrera as $carre){

                            $estudiantes_PA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->get();

                            $estudiantes_SA = $carre->estudiantes()->has('gestionProyecto')->select('id','nombre','apellido')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->get();

                            $estudiantePA = "";
                            foreach ($estudiantes_PA as $value) {
                                $estudiantePA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                                foreach ($estudiantePA->gestionProyecto as $item) {
                                  $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                                }
                            }

                            $estudianteSA = "";
                            foreach ($estudiantes_SA as $value) {
                                $estudianteSA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                                foreach ($estudianteSA->gestionProyecto as $item) {
                                  $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                                }
                            }

                            $arrayMesEstudiante[0] = $carre->nombre;
                            if($procesoId == 1)
                                $arrayMesEstudiante[1] = array("Primer Año" => $estudiantes_PA,"Segundo Año" => $estudiantes_SA);
                            else
                                $arrayMesEstudiante[1] = array("Segundo Año" => $estudiantes_SA);

                            $arrayMes[0] = $this->meses[$arrayMeses[$i]];
                            $arrayMes[$carre->id] = $arrayMesEstudiante;
                    }

                    array_push($dataMensual, $arrayMes);
                }

               // Sacando Consolidado Anual POR NIVELES ACADEMICOS
                $dataByNivel = [];
                $dataGeneralByAnio = [];
                $dataGeneralByAnio[0] = $this->anio;

                $dataPA = []; $dataSA = [];
                foreach ($carrera as $carre) {

                    //Obteniendo el total de resultados becados y otros
                      $estudiantesBM_PA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                      })->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                      $estudiantesOB_PA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                      $estudiantesBM_SA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                      $estudiantesOB_SA =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();


                      // DATOS PARA EN CONSOLIDADO GENERAL
                       $estudiantesGeneralBM =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                       $estudiantesGeneralOB =  $carre->estudiantes()->has('gestionProyecto')->select('id')->where([['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                        })->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();


                         // Juntando los resultados en array individuales
                         // Datos para el consolidado de cada mes

                        if ($procesoId == 1) {
                                // Asignando en la posicion 0 el titulo de la tabla
                            $dataPA[0] = $this->anio." 1º AÑO";
                            $dataPA[$carre->id] = array(
                              "Carrera" => $carre->nombre,
                              "totalBecaMined" => $estudiantesBM_PA,
                              "totalOtraBeca" => $estudiantesOB_PA,
                            );
                        }

                        $dataSA[0] = $this->anio." 2º AÑO";
                        $dataSA[$carre->id] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_SA,
                          "totalOtraBeca" => $estudiantesOB_SA,
                        );

                        $dataGeneralByAnio[$carre->id] =  array(
                           "Carrera" => $carre->nombre,
                           "totalBecaMined" => $estudiantesGeneralBM,
                           "totalOtraBeca" => $estudiantesGeneralOB,
                        );
                }

                array_push($dataByNivel,$dataPA);
                array_push($dataByNivel,$dataSA);


                $pdf = PDF::loadView('reportes.reportePendientesFinalizacion', ['mensuales' => $dataMensual,'consolidadoAnualByNivel' => $dataByNivel,'consolidadoAnualGeneral' => $dataGeneralByAnio,'meses'=>$mesesTitulo,'tipo'=>'A','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
                $pdf->setOption('margin-top',15);
                $pdf->setOption('margin-bottom',15);
                $pdf->setOption('margin-left',15);
                $pdf->setOption('margin-right',15);
                return $pdf->stream('Reporte Pendientes de Finalización '.date('Y-m-d').'.pdf');
                // return $dataGeneralByAnio;
            }
    }

    //Devueve los estudiantes que han finzalido su proceso correspondiente
    public function getProcesosCulminadosReporte(Request $request){
        $carrera = Carrera::get();
        $procesoId = $request->proceso_id;
        $procesoTitulo = "";
        $mesesTitulo = "";
        $estudianteProceso = "";
        $campoFecha = "";
        $campoFechaConsolidados = "";

        if($procesoId == 1){
            $procesoTitulo = "SERVICIO SOCIAL";
            $estudianteProceso = "estado_ss";
            $campoFecha = 'fecha_fin_ss';
            $campoFechaConsolidados = 'MONTH(fecha_fin_ss)';
        }else if($procesoId == 2){
            $procesoTitulo = "PRACTICA PROFESIONAL";
            $estudianteProceso = "estado_pp";
            $campoFecha = 'fecha_fin_pp';
            $campoFechaConsolidados = 'MONTH(fecha_fin_pp)';
        }

        // REPORTE MENSUAL Y TRIMESTRAL
        if($request->tipoRepo == 'T'){

                $arrayTrimestre = explode(",",$request->meses);
                $totalMined = 0;$totalOtros = 0;
                $totalOtrosMes3 = 0;

                //Sacando datos mensuales
                $dataMensual = [];
                $mes1 = [];$mes2 = [];$mes3 = [];

                $mes1[0] = $this->meses[$arrayTrimestre[0]];
                $mes2[0] = $this->meses[$arrayTrimestre[1]];
                $mes3[0] = $this->meses[$arrayTrimestre[2]];
                $mesesTitulo = $mes1[0].", ".$mes2[0].", ".$mes3[0];

                foreach($carrera as $carre){


                    // DATOS MES 1
                    $estudiantesM1_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayTrimestre[0])->whereYear('fecha_registro',$this->anio)->get();

                    $estudiantesM1_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayTrimestre[0])->whereYear('fecha_registro',$this->anio)->get();

                    // DATOS MES 2
                    $estudiantesM2_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayTrimestre[1])->whereYear('fecha_registro',$this->anio)->get();

                    $estudiantesM2_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayTrimestre[1])->whereYear('fecha_registro',$this->anio)->get();

                    // DATOS MES 3
                    $estudiantesM3_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayTrimestre[2])->whereYear('fecha_registro',$this->anio)->get();

                    $estudiantesM3_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayTrimestre[2])->whereYear('fecha_registro',$this->anio)->get();


                    $c1[0] = $carre->nombre;
                    if($procesoId == 2)
                        $c1[1] = array("Segundo Año" => $estudiantesM1_SA);
                    else
                        $c1[1] = array("Primer Año" => $estudiantesM1_PA,"Segundo Año" => $estudiantesM1_SA);


                    $c2[0] = $carre->nombre;
                    if($procesoId == 2)
                        $c2[1] = array("Segundo Año" => $estudiantesM2_SA);
                    else
                        $c2[1] = array("Primer Año" => $estudiantesM2_PA,"Segundo Año" => $estudiantesM2_SA);

                    $c3[0] = $carre->nombre;
                    if($procesoId == 2)
                        $c3[1] = array("Segundo Año" => $estudiantesM3_SA);
                    else
                        $c3[1] = array("Primer Año" => $estudiantesM3_PA,"Segundo Año" => $estudiantesM3_SA);

                    $mes1[$carre->id] = $c1;
                    $mes2[$carre->id] = $c2;
                    $mes3[$carre->id] = $c3;
                }

                // Obteniendo CONSOLIDADO por los 3 meses pero dividido en niveles academico
                // Sacando Consolidado por los 3 meses
                $dataByNivel = [];
                $dataPA = []; $dataSA = [];
                $data[0] = $this->trimestres[implode($arrayTrimestre)];
                foreach ($carrera as $carre) {
                    //Obteniendo el total de resultados becados y otros

                        $estudiantesBM_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                        $carre->id],['tipo_beca_id',1],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),[$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                        $estudiantesOB_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                        $carre->id],['tipo_beca_id',2],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados), [$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                        $estudiantesBM_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                        $carre->id],['tipo_beca_id',1],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados), [$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                        $estudiantesOB_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                        $carre->id],['tipo_beca_id',2],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados), [$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();


                        if($procesoId == 1){
                            $dataPA[0] = $this->trimestres[implode($arrayTrimestre)]." 1º AÑO";
                            $dataPA[$carre->id] = array(
                               "Carrera" => $carre->nombre,
                               "totalBecaMined" => $estudiantesBM_PA,
                               "totalOtraBeca" => $estudiantesOB_PA
                            );
                        }

                        $dataSA[0] = $this->trimestres[implode($arrayTrimestre)]." 2º AÑO";
                        $dataSA[$carre->id] = array(
                            "Carrera" => $carre->nombre,
                            "totalBecaMined" => $estudiantesBM_SA,
                            "totalOtraBeca" => $estudiantesOB_SA
                        );
                }
                array_push($dataByNivel,$dataPA);
                array_push($dataByNivel,$dataSA);


                // Sacando Consolidado por los 3 meses
                $data = [];
                $data[0] = $this->trimestres[implode($arrayTrimestre)];
                foreach ($carrera as $carre) {
                    //Obteniendo el total de resultados becados y otros
                        $estudiantesBM = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                        $carre->id],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados), [$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();

                        $estudiantesOB = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                        $carre->id],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados), [$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->whereYear('fecha_registro',$this->anio)->count();


                        $data[$carre->id] = array(
                             "Carrera" => $carre->nombre,
                             "totalMined" => $estudiantesBM,
                             "totalOtraBeca" => $estudiantesOB
                        );
                }


                $mensuales = array();
                array_push($mensuales,$mes1);
                array_push($mensuales,$mes2);
                array_push($mensuales,$mes3);


                if($request->onlyConsolidado=='OC'){
                    $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['consolidadoByNivel' => $dataByNivel,'consolidadoGeneral' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio' => $this->anio,'onlyConsolidado' => true])->setOption('footer-center', 'Página [page] de [topage]');
                }else{
                    $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $mensuales,'consolidadoByNivel' => $dataByNivel,'consolidadoGeneral' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio' => $this->anio,'onlyConsolidado' => false])->setOption('footer-center', 'Página [page] de [topage]');
                }


                $pdf->setOption('margin-top',20);
                $pdf->setOption('margin-bottom',20);
                $pdf->setOption('margin-left',20);
                $pdf->setOption('margin-right',20);
                return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
                // return $data;

        }else if($request->tipoRepo == 'M'){

                $arrayMeses = explode(",", $request->meses);
                $data = array();
                $arrayMes = [];
                $arrayMesEstudiante = [];
                $mesesTitulo = [];
                $dataPA = []; $dataSA = [];
                $dataByNivel = [];

                for ($i=0; $i < count($arrayMeses) ; $i++) {

                    $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

                    foreach($carrera as $carre){

                          $estudiantes_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->get();

                          $estudiantes_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->get();

                            // Obteniendo Cuenta para consolidado Mensual
                            $estudiantesBM_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                            $carre->id],['tipo_beca_id',1],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                            $carre->id],['tipo_beca_id',2],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesBM_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                            $carre->id],['tipo_beca_id',1],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->whereYear('fecha_registro',$this->anio)->count();

                            $estudiantesOB_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id',
                            $carre->id],['tipo_beca_id',2],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->whereYear('fecha_registro',$this->anio)->count();


                           $arrayMesEstudiante[0] = $carre->nombre;
                           if($procesoId == 1)
                            $arrayMesEstudiante[1] = array("Primer Año" => $estudiantes_PA, "Segundo Año" => $estudiantes_SA);
                           else
                            $arrayMesEstudiante[1] = array("Segundo Año" => $estudiantes_SA);


                           $arrayMes[0] = $this->meses[$arrayMeses[$i]];
                           $arrayMes[$carre->id] = $arrayMesEstudiante;

                            // Datos para el consolidado de cada mes
                            if ($procesoId == 1) {
                            // Asignando en la posicion 0 el titulo de la tabla
                                $dataPA[0] = $this->meses[$arrayMeses[$i]]." 1º AÑO";
                                $dataPA[$carre->id] = array(
                                  "Carrera" => $carre->nombre,
                                  "totalBecaMined" => $estudiantesBM_PA,
                                  "totalOtraBeca" => $estudiantesOB_PA,
                                );

                            }

                            $dataSA[0] = $this->meses[$arrayMeses[$i]]." 2º AÑO";
                            $dataSA[$carre->id] = array(
                              "Carrera" => $carre->nombre,
                              "totalBecaMined" => $estudiantesBM_SA,
                              "totalOtraBeca" => $estudiantesOB_SA,
                            );
                    }

                   array_push($data, $arrayMes);
                   array_push($dataByNivel,$dataPA);
                   array_push($dataByNivel,$dataSA);
                }

               if($request->onlyConsolidado=='OC'){

                 $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['consolidadoMensuales' => $dataByNivel,'tipo' => 'M','meses'=>$mesesTitulo,'procesoTitulo' => $procesoTitulo,'anio'=>$this->anio,'onlyConsolidado' => true])->setOption('footer-center', 'Página [page] de [topage]');

                }else{
                 $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $data,'consolidadoMensuales' => $dataByNivel,'tipo' => 'M','meses'=>$mesesTitulo,'procesoTitulo' => $procesoTitulo,'anio'=>$this->anio,'onlyConsolidado' => false])->setOption('footer-center', 'Página [page] de [topage]');
                }

               $pdf->setOption('margin-top',20);
               $pdf->setOption('margin-bottom',20);
               $pdf->setOption('margin-left',20);
               $pdf->setOption('margin-right',20);
               return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
                 // return $dataByNivel;

        }else if($request->tipoRepo == 'A'){

            $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
            $dataMensual = array();
            $mesesTitulo = [];

            for ($i=0; $i < count($arrayMeses) ; $i++) {

              $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];

              foreach($carrera as $carre){

                    $estudiantes_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->get();

                    $estudiantes_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayMeses[$i])->whereYear('fecha_registro',$this->anio)->get();

                   $arrayMesEstudiante[0] = $carre->nombre;
                   $arrayMesEstudiante[1] = array("Primer Año" => $estudiantes_PA,"Segundo Año" => $estudiantes_SA);

                   $arrayMes[0] = $this->meses[$arrayMeses[$i]];
                   $arrayMes[$carre->id] = $arrayMesEstudiante;
              }

               array_push($dataMensual, $arrayMes);

                // Sacando Consolidado Anual POR NIVELES ACADEMICOS
                $dataByNivel = [];
                $dataGeneralByAnio = [];
                $dataGeneralByAnio[0] = $this->anio;

                $dataPA = []; $dataSA = [];
                foreach ($carrera as $carre) {

                    $estudiantesBM_PA =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                    $estudiantesOB_PA =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',1],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                    $estudiantesBM_SA =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                    $estudiantesOB_SA =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['nivel_academico_id',2],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                    // DATOS PARA EN CONSOLIDADO GENERAL
                     $estudiantesGeneralBM =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();

                     $estudiantesGeneralOB =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true], ['carrera_id', $carre->id],['tipo_beca_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear('ultimo_cambio',$this->anio)->whereYear('fecha_registro',$this->anio)->count();



                    // Juntando los resultados en array individuales
                     // Datos para el consolidado de cada mes

                    if ($procesoId == 1) {
                        // Asignando en la posicion 0 el titulo de la tabla
                        $dataPA[0] = $this->anio." 1º AÑO";
                        $dataPA[$carre->id] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_PA,
                          "totalOtraBeca" => $estudiantesOB_PA,
                      );

                    }

                    $dataSA[0] = $this->anio." 2º AÑO";
                    $dataSA[$carre->id] = array(
                      "Carrera" => $carre->nombre,
                      "totalBecaMined" => $estudiantesBM_SA,
                      "totalOtraBeca" => $estudiantesOB_SA,
                    );

                    $dataGeneralByAnio[$carre->id] =  array(
                       "Carrera" => $carre->nombre,
                       "totalBecaMined" => $estudiantesGeneralBM,
                       "totalOtraBeca" => $estudiantesGeneralOB,
                    );
                }
                array_push($dataByNivel,$dataPA);
                array_push($dataByNivel,$dataSA);
            }


            if($request->onlyConsolidado=='OC'){

               $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['consolidadoByNivel'=>$dataByNivel,'consolidadoGeneralByAnio' => $dataGeneralByAnio,'tipo' => 'A', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo,'anio' => $this->anio,'onlyConsolidado' => true])->setOption('footer-center', 'Página [page] de [topage]');

            }else{

              $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $dataMensual,'consolidadoByNivel'=>$dataByNivel,'consolidadoGeneralByAnio' => $dataGeneralByAnio,'tipo' => 'A', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo,'anio' => $this->anio,'onlyConsolidado' => false])->setOption('footer-center', 'Página [page] de [topage]');
            }

            $pdf->setOption('margin-top',20);
            $pdf->setOption('margin-bottom',20);
            $pdf->setOption('margin-left',20);
            $pdf->setOption('margin-right',20);
            return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
            // return $dataGeneralByAnio;
        }
    }
    // ***************** Fin de la funciones para reportes *****************//
    // Metodo que devuelve los estudiantes que han finalizado ss o pp y se le spuede generar constancia final
    public function constancias(Request $request){

        $buscar = $request->buscar;
        $proceso = $request->proceso_id;
        $carrera_id = $request->carre_id;
        if($proceso == 1){
            $gp = Estudiante::distinct('id')->with([
                'carrera',
                'gestionProyecto.constancia_entreg',
                'gestionProyecto'=> function($query){
                    $query->where('gestion_proyectos.tipo_gp',1)->get();
                }
            ])->nombre($buscar)->where('estado_ss',1)->where('carrera_id',$carrera_id)->paginate(10);

        }else if($proceso == 2){
            $gp = Estudiante::distinct('id')->with([
                'carrera',
                'gestionProyecto.constancia_entreg',
                'gestionProyecto'=> function($query){
                    $query->where('gestion_proyectos.tipo_gp',2)->get();
                }
            ])->nombre($buscar)->where('estado_pp', 2)->where('carrera_id',$carrera_id)->paginate(10);
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

    //Metodo que genera una constancia de finalizacion de un proceso determinado
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
            'nivelAcademico',
            'gestionProyecto.proyecto',
            'gestionProyecto.proyecto.institucion',
            'gestionProyecto' => function($query) use($procesoId){
                $query->where('gestion_proyectos.tipo_gp',$procesoId)->get();
            }

        ])->find($estudianteId);

        $proyectos =  [];
        $proyectos = $estudiante->gestionProyecto()->where('tipo_gp',$procesoId)->get();
        $infoEstudiante = $estudiante;


        foreach ($estudiante->gestionProyecto as $value) {

            if($value->constancia_entreg()->count() == 0){

                DB::table('constancias_entregadas')->insert(
                    ['gestion_proyecto_id' => $value->id,'created_at' => Carbon::now()->toDateTimeString(),'fecha_registro'=>date('Y-m-d')]
                );

            }
        }
        foreach ($proyectos as $item) {

            $totalHoras += $item->horas_realizadas;
        }

        $admin = User::select('nombre')->find(0);
        $pdf = PDF::loadView('reportes.constanciass', ['admin'=>$admin,'estudiante'=>$infoEstudiante,'proyectos' => $proyectos, 'proceso' =>$tituloProceso,'fecha' => $date,'totalHoras' => $totalHoras])->setOption('footer-center', '');
        return $pdf->stream('Perfil de proyecto.pdf');
        return $estudiante;
    }

    // Metodo que devuelve la descarga de los documentos relacionadoa con el proceso que realiza cada estudiante
    public function downloadDocs(Request $request){
            $procesoId = session('process_id');
            $codCarnet = Auth::user()->estudiante->codCarnet;
            $tipoDoc = $request->tipoDoc;

            if ($procesoId == 1)
              $ruta_img = public_path('docs/docs_ss/').$tipoDoc."SS-".$codCarnet.".jpg";
            else
            $ruta_img = public_path('docs/docs_pp/').$tipoDoc."PP-".$codCarnet.".jpg";

            $pdf = PDF::loadView('public.reportes.documents',['ruta'=>$ruta_img])->setOption('footer-center', '');
            $pdf->setOption('margin-top',15);
            $pdf->setOption('margin-bottom',0);
            $pdf->setOption('margin-left',0);
            $pdf->setOption('margin-right',0);

            //Informacion del estudiante
            $proceso = Auth::user()->estudiante->proceso[0]->id;
            $nombre = Auth::user()->estudiante->nombre;
            $apellido = Auth::user()->estudiante->apellido;
            $telefono = Auth::user()->estudiante->telefono;
            $carrera = Auth::user()->estudiante->carrera->nombre;
            $email = Auth::user()->estudiante->email;
            $numeroFactura = Auth::user()->estudiante->pagoArancel()->where('proceso_id',$proceso)->get();

            switch ($tipoDoc) {
                case 'P':
                    if(Auth::user()->rol_id >2){

                            if(Auth::user()->estudiante->no_proyectos == 2){
                                $gestion = GestionProyecto::where([
                                    ['estudiante_id',Auth::user()->estudiante->id],
                                    ['tipo_gp',$proceso],
                                ])->find($request->gestionId);

                            }else{
                                $gestion = GestionProyecto::where([
                                    ['estudiante_id',Auth::user()->estudiante->id],
                                    ['tipo_gp',$proceso],
                                    ['estado','I']
                                ])->first();
                            }

                            if($gestion->horas_a_realizar != $gestion->proyecto->horas_realizar){
                                $gestion->horas_a_realizar = $gestion->proyecto->horas_realizar;
                                $gestion->update();
                            }

                            // Informacion de la institucion
                            $nombreI = $gestion->proyecto->institucion->nombre;
                            $direccionI = $gestion->proyecto->institucion->direccion;
                            $departamentoI = $gestion->proyecto->institucion->municipio->departamento->nombre;
                            $municipioI =  $gestion->proyecto->institucion->municipio->nombre;
                            $sectorI = $gestion->proyecto->institucion->sectorInstitucion->sector;
                            $telefonoI = $gestion->proyecto->institucion->telefono;
                            $emailI = $gestion->proyecto->institucion->email;

                            // Informacion del proyecto
                            $nombreP = $gestion->proyecto->nombre;
                            $actividadesP = $gestion->proyecto->actividades;

                            $perfil = new TextPainter(public_path('images/controles/perfil.jpg'),'',public_path('fonts/arial.ttf'), 10);
                            $perfil->setTextColor(0,0,0);
                            if ($proceso == 1) {$perfil->setText("x",790,362,20);}else{$perfil->setText("x",1200,362,20);}//Proceso Verifcando la posicion
                            $perfil->setText($nombre,385,490,20);//Nombre de Alumno
                            $perfil->setText($apellido,385,550,20);//Apellido de Alumno
                            $perfil->setText($codCarnet,1115,490,20);//Carnet de Alumno
                            $perfil->setText($telefono,1160,550,20);//Telefono de Alumno
                            $perfil->setText($carrera,385,605,20);//Carrera de Alumno
                            $perfil->setText($email,1100,607,20);//Email de Alumno

                            $perfil->setText($nombreI,120,775,20); //Nombre de la Institucion
                            $perfil->setText($sectorI,1150,805,20); //Sector de la Institucion
                            $perfil->setText($direccionI,120,900,20); //Direccion de la Institucion
                            $perfil->setText($municipioI,120,990,20); //Municipio de la Institucion
                            $perfil->setText($departamentoI,650,990,20); //Departamento de la Institucion
                            $perfil->setText($emailI,920,990,20); //Email de la Institucion
                            $perfil->setText($telefonoI,1350,990,20); //Telefono de la Institucion

                            $perfil->setText($nombreP, 115, 1175, 20); //Nombre del Proyecto
                            $perfil->setText(Html2Text::convert($actividadesP),120,1300,20); //Actividades a realizar
                            $perfil->setText($gestion->horas_a_realizar, 1465, 1210, 20); //Horas a Realizar del proyecto
                            $perfil->setText($gestion->fecha_inicio, 135, 1625, 20); //Fecha de Inicio  del proyecto
                            $perfil->setText($gestion->nombre_supervisor, 650, 1625, 20); //Supervisor del proyecto/Institucion
                            $perfil->setText($gestion->tel_supervisor, 1390, 1625, 20); //Telefono Supervisor del proyecto/Institucion
                            $perfil->setText($numeroFactura[0]->no_factura,1235,1860,20); //Numero de factura de pago de arancel de proceso
                            // Guardando el perfil segun el proceso del estudiante
                            if ($proceso == 1) {$perfil->save(public_path('docs/docs_ss/')."PSS-".$codCarnet);}
                            else{$perfil->save(public_path('docs/docs_pp/')."PPP-".$codCarnet);}

                            return $pdf->download('Perfil de proyecto.pdf');
                    }
                break;
                case 'CH':
                    if(Auth::user()->estudiante->no_proyectos == 2){
                        $gestion = GestionProyecto::where([
                            ['estudiante_id',Auth::user()->estudiante->id],
                            ['tipo_gp',$proceso],
                        ])->find($request->gestionId);

                        $nombreP = $gestion->proyecto->nombre;
                        $nombreI = $gestion->proyecto->institucion->nombre;

                        $nombre_completo = $nombre." ".$apellido;
                        $control_horas = new TextPainter(public_path('images/controles/control-horas.jpg'),'',public_path('fonts/arial.ttf'), 10);
                        $control_horas->setTextColor(0,0,0);
                        if ($proceso == 1) {$control_horas->setText("x",391,468,30);}else{$control_horas->setText("x",866,468,30);}//Proceso
                        $control_horas->setText($nombre_completo,475,555,20);//Nombre del estudiante
                        $control_horas->setText($codCarnet,1335,555,20);//Carnet del estudiante
                        $control_horas->setText($carrera,275,610,20);//Carrera del estudiante
                        $control_horas->setText($nombreI,865,665,20);//Nombre de la institucion
                        $control_horas->setText($nombreP,600,725,20);//Nombre de la institucion
                        // Guardando el control de horas segun el proceso del estudiante
                        if ($proceso == 1) {$control_horas->save(public_path('docs/docs_ss/')."CHSS-".$codCarnet);}
                        else{$control_horas->save(public_path('docs/docs_pp/')."CHPP-".$codCarnet);}

                        return $pdf->download('Control de Asistencia de proyecto.pdf');
                    }else{
                      return $pdf->download('Control de Asistencia.pdf');
                    }
                break;
                case 'CP':
                    if (Auth::user()->estudiante->no_proyectos == 2) {

                        $gestion = GestionProyecto::where([
                            ['estudiante_id',Auth::user()->estudiante->id],
                            ['tipo_gp',$proceso],
                        ])->find($request->gestionId);

                        $nombreP = $gestion->proyecto->nombre;
                        $nombreI = $gestion->proyecto->institucion->nombre;

                        $nombre_completo = $nombre." ".$apellido;

                        $control_proy = new TextPainter(public_path('images/controles/control-proyecto.jpg'),'',public_path('fonts/arial.ttf'), 10);
                        $control_proy->setTextColor(0,0,0);
                        if ($proceso == 1) {$control_proy->setText("x",472,498,30);}else{$control_proy->setText("x",944,500,30);}//Proceso
                        $control_proy->setText($nombre_completo,300,620,20);//Nombre del estudiante
                        $control_proy->setText($codCarnet,1360,620,20);//Carnet del estudiante
                        $control_proy->setText($carrera,290,675,20);//Nombre del estudiante
                        $control_proy->setText($nombreI,150,875,20);//Nombre de la institucion
                        $control_proy->setText($nombreP,150,1100,20);//Nombre del proyecto
                        // Guardando el control de horas segun el proceso del estudiante
                        if ($proceso == 1) {$control_proy->save(public_path('docs/docs_ss/')."CPSS-".$codCarnet);}
                        else{$control_proy->save(public_path('docs/docs_pp/')."CPPP-".$codCarnet);}

                        return $pdf->download('Control de Proyecto.pdf');
                    }else{
                        return $pdf->download('Control de Proyecto.pdf');
                    }
                break;
            }
    }

    // Metodo que elimina un proyecto que estaba realizando el alumno sin contar las horas
    public function deleteProyectoEnMarcha(Request $request){

       $gp = GestionProyecto::findOrFail($request->gestionId);
       $estudiante = Estudiante::findOrFail($gp->estudiante_id);
       $estudiante->proceso_actual = 'P';
       $estudiante->update();

       $gp->delete();
    }

    //Metodo que devuelve la gestion de proyecto que se esta realizando el usuario logeado
    public function getActualGestionProyectos(){
        $proceso = Auth::user()->estudiante->proceso[0]->pivot->proceso_id;
        $gestiones = Auth::user()->estudiante->gestionProyecto()->where('tipo_gp',$proceso)->pluck('id');
        $proyectos = Auth::user()->estudiante->gestionProyecto()->where('tipo_gp',$proceso)->pluck('proyecto_id');
        $data = array();

        for ($i=0; $i < $proyectos->count() ; $i++) {
            array_push($data, array("gestionId" => $gestiones[$i],"proyecto" => Proyecto::select('nombre')->find($proyectos[$i])));
        }
        return $data;
    }

    //Metodo que cambia la fecha de inicio de un proyecto
    public function cambiarFechaInicio(Request $request){
        $estudiante_id = $request->estudiante_id;
        $gestion_id = $request->gestion_id;
        $nueva_fecha = $request->fecha;
        $proceso_id = $request->proceso_id;

        $gestion = GestionProyecto::where('estado','I')->find($gestion_id);
        $gestion->fecha_inicio = $nueva_fecha;

        $estudiante = Estudiante::where('estado',true)->find($estudiante_id);

        if ($estudiante->no_proyectos == 1) {
            if($proceso_id == 1)
                $estudiante->fecha_inicio_ss = $nueva_fecha;
            else
                $estudiante->fecha_inicio_pp = $nueva_fecha;

            $estudiante->update();
        }
        $gestion->update();
    }
}
