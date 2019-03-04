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
            $gp->fecha_registro = $this->anio;
            $gp->tipo_gp = Auth::user()->estudiante->proceso[0]->id;
            $estudiante = Estudiante::find($request->student_id);
            $estudiante->proceso_actual = 'I';
            $estudiante->no_proyectos = $estudiante->no_proyectos + 1;

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
                $estudiante->update();
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
        if($request->proceso_id == 1)
          $nivelAcad = $request->nivelAcad;
      else
         $nivelAcad = 2;

        if ($buscar == '') {

            $gp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion'])
            ->whereHas('estudiante.proceso', function ($query) use ($proceso) {
                $query->where('proceso_id', $proceso);

            })->whereHas('estudiante', function ($query) use ($carrera_id,$nivelAcad) {
                $query->where([['carrera_id', $carrera_id],['nivel_academico_id',$nivelAcad]]);

            })->year($this->anio)->where('tipo_gp',$proceso)->paginate(8);

        } else {

            $gp = GestionProyecto::with(['estudiante.carrera', 'proyecto'])
            ->whereHas('estudiante.proceso', function ($query) use ($proceso) {
                $query->where('proceso_id', $proceso);

            })->whereHas('estudiante', function ($query) use ($buscar) {
                $query->where('nombre', 'like', '%' . $buscar . '%');

            })->whereHas('estudiante', function ($query) use ($carrera_id,$nivelAcad) {
               $query->where([['carrera_id', $carrera_id],['nivel_academico_id',$nivelAcad]]);
            })->year($this->anio)->where('tipo_gp',$proceso)->paginate(8);
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

        $gestionp = GestionProyecto::with(['estudiante.carrera', 'proyecto.institucion','documentos_entrega','estudiante.proceso'])->year($this->anio)->findOrFail($id);

        return $gestionp;
    }

    // Metodo que devuelve todos los proyectos que ha realizado un alumno en su proceso correspondiente
    public function getGestionProyectoByStudent($student_id){

        $gestionp = GestionProyecto::with(['proyecto.institucion'])->whereHas('estudiante', function ($query) use ($student_id) {
            $query->where('estudiantes.id',$student_id);
        })->get();

        return view('public.gestionPro',compact("gestionp"));
    }

    // Metodo que cierra un proyecto deacuerdo ala horas y fecha de finalizacion del estudiante
    public function closeProy(Request $request){

       try {
                DB::beginTransaction();
                $gp = GestionProyecto::year($this->anio)->findOrFail($request->gestionId);
                $gp->fecha_fin = $request->fechaFin;
                $gp->horas_realizadas = $request->horasRea;
                $gp->observacion_final = $request->obsFinal;
                $gp->estado = 'F';
                $gp->update();

                $e = Estudiante::findOrFail($gp->estudiante_id);

                if($request->horasRea == $e->proceso[0]->pivot->num_horas){
                    $e->no_proyectos = 0;
                   /*  if ($e->nivel_academico_id == 1) {
                        $e->nivel_academico_id = 2;
                    } */
                    if($gp->tipo_gp == 1){
                        $e->fecha_fin_ss = $request->fechaFin;

                        if(file_exists(public_path('docs/docs_ss/')."PSS-".$e->codCarnet.".jpg"))
                          unlink(public_path('docs/docs_ss/')."PSS-".$e->codCarnet.".jpg");

                        if(file_exists(public_path('docs/docs_ss/')."CHSS-".$e->codCarnet.".jpg"))
                            unlink(public_path('docs/docs_ss/')."CHSS-".$e->codCarnet.".jpg");

                        if(file_exists(public_path('docs/docs_ss/')."CPSS-".$e->codCarnet.".jpg"))
                            unlink(public_path('docs/docs_ss/')."CPSS-".$e->codCarnet.".jpg");

                        $e->proceso()->detach(1);
                        if($e->proceso()->attach(2,array('num_horas' => '160'))){
                            $e->proceso_actual = 'P';
                        }

                    }else{
                        $e->fecha_fin_pp = $request->fechaFin;

                        if(file_exists(public_path('docs/docs_pp/')."PPP-".$e->codCarnet.".jpg"))
                          unlink(public_path('docs/docs_pp/')."PPP-".$e->codCarnet.".jpg");

                        if(file_exists(public_path('docs/docs_pp/')."CHPP-".$e->codCarnet.".jpg"))
                            unlink(public_path('docs/docs_pp/')."CHPP-".$e->codCarnet.".jpg");

                        if(file_exists(public_path('docs/docs_pp/')."CPPP-".$e->codCarnet.".jpg"))
                            unlink(public_path('docs/docs_pp/')."CPPP-".$e->codCarnet.".jpg");

                        DB::table('procesos_estudiantes')->where([['estudiante_id', $e->id],['proceso_id',2],['pago_arancel',true]])->update(array('estado' => true));
                    }

                    $e->update();
                   
                }
                DB::commit();
           } catch (Exception $e) {
              DB::rollBack();
           }
    }

    //*****************************Funciones utilizadas para la generacion de reportes de SS Y PP*****************************//
    //Devuelve el reporte de los estudiantes que han iniciado un proceso determinado en una fecha dada por el usuario en la vista
    public function getInitialProcessReporte(Request $request){
        $carrera = Carrera::where('estado',true)->get();
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
            $iteratorMensuales = 2;
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


                $mes1[$iteratorMensuales] = $collection1 = new Collection([
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

                $mes2[$iteratorMensuales] = $collection2 = new Collection([
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


                $mes3[$iteratorMensuales] = $collection2 = new Collection([
                    "Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayTrimestre[2], $procesoId,$this->anio),
                    "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayTrimestre[2], $procesoId,$this->anio),
                ]);

               ++$iteratorMensuales;

            }


            //Sacando Consolidado por los 3 meses
            $data = [];
            $data[0] = $this->trimestres[implode($arrayTrimestre)];
            $iteratorConso = 2;
            foreach ($carrera as $carre) {
                //Obteniendo el total de resultados becados y otros
                $totalMined += $carre->getCountStudentsByMinedTrimestral($arrayTrimestre,$procesoId,$this->anio);
                $totalOtros += $carre->getCountStudentsByOtherBecaTrimestral($arrayTrimestre,$procesoId,$this->anio);

                $data[1] = array("totalMined" => $totalMined,"totalOtros"=>$totalOtros);
                $data[$iteratorConso] = $collection = new Collection(["Carrera" => $carre->nombre,
                    "BecadosMined" => $carre->getCountStudentsByMinedTrimestral($arrayTrimestre,$procesoId,$this->anio),
                    "Otros" => $carre->getCountStudentsByOtherBecaTrimestral($arrayTrimestre,$procesoId,$this->anio)
                ]);
                ++$iteratorConso;
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
            /* return $data; */

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

                $iterator =  2;
                foreach ($carrera as $carre) {
                    $dataMensual[0] = $this->meses[$arrayMeses[$i]];
                    $dataMensual[1] = array(
                        "totalMined" => $totalMinedArray[$i],
                        "totalOtros" => $totalOtrosArray[$i]
                    );

                    $dataMensual[$iterator] = $collectionMensual = new Collection([
                        "Carrera" => $carre->nombre,
                        "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId,$this->anio),
                        "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId,$this->anio),
                    ]);

                    ++$iterator;
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

                $iterator = 2;
                foreach ($carrera as $carre) {
                    $dataMensual[0] = $this->meses[$arrayMeses[$i]];
                    $dataMensual[1] = array(
                        "totalMined" => $totalMinedArray[$i],
                        "totalOtros" => $totalOtrosArray[$i],
                    );

                    $dataMensual[$iterator] = $collectionMensual = new Collection([
                        "Carrera" => $carre->nombre,
                        "BecadosMined" => $carre->getCountStudentsByMinedMensual($arrayMeses[$i], $procesoId,$this->anio),
                        "Otros" => $carre->getCountStudentsByOtherBecaMensual($arrayMeses[$i], $procesoId,$this->anio),
                    ]);

                    ++$iterator;
                };

                //Consolidado Anual
                $totalMinedAnual = 0;
                $totalOtrosAnual = 0;

                $dataAnual = [];
                $dataAnual[0] = $this->anio;
                $iteratorYear = 2;
                foreach ($carrera as $carre) {
                    //Obteniendo el total de resultados becados y otros
                    $totalMinedAnual += $carre->getCountStudentsByMinedYear($this->anio, $procesoId);
                    $totalOtrosAnual += $carre->getCountStudentsByOtherBecaYear($this->anio, $procesoId);

                    $dataAnual[1] = array("totalMined" => $totalMinedAnual, "totalOtros" => $totalOtrosAnual);

                    $dataAnual[$iteratorYear] = $collection = new Collection([
                        "Carrera" => $carre->nombre,
                        "BecadosMined" => $carre->getCountStudentsByMinedYear($this->anio, $procesoId),
                        "Otros" => $carre->getCountStudentsByOtherBecaYear($this->anio, $procesoId)
                    ]);
                    ++$iteratorYear;
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

            $carrera = Carrera::where('estado', true)->get();
            $procesoId = $request->proceso_id;
            $procesoTitulo = "";
            $mesesTitulo = "";
            $nivelAcademico = "";

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

                $iterator = 1;
                foreach($carrera as $carre){

                    switch ($procesoId) {
                        case 1:
                        if($this->anio == date('Y')){
                            $estudiantesM1_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[0])->where('proceso_actual','P')->get();

                            $estudiantesM2_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[1])->where('proceso_actual','P')->get();

                            $estudiantesM3_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[2])->where('proceso_actual','P')->get();

                             $estudiantesM1_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[0])->where('proceso_actual','P')->get();

                            $estudiantesM2_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[1])->where('proceso_actual','P')->get();

                            $estudiantesM3_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[2])->where('proceso_actual','P')->get();
                        }else {
                            $estudiantesM1_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->where('proceso_actual','P')->get();

                            $estudiantesM2_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->where('proceso_actual','P')->get();

                            $estudiantesM3_PA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->where('proceso_actual','P')->get();

                             $estudiantesM1_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->where('proceso_actual','P')->get();

                            $estudiantesM2_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->where('proceso_actual','P')->get();

                            $estudiantesM3_SA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',3]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id',1);
                            })->whereNull('fecha_inicio_ss')->where('proceso_actual','P')->get();
                        }
                            break;
                        case 2:
                            $estudiantesM1_PA = new Collection;$estudiantesM2_PA = new Collection;$estudiantesM3_PA = new Collection();
                            if($this->anio == date('Y')){
                                $estudiantesM1_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[0])->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                                $estudiantesM2_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[1])->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                                $estudiantesM3_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayTrimestre[2])->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();
                            }else {
                                 $estudiantesM1_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                                $estudiantesM2_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                                $estudiantesM3_SA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();
                            }

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
                    $mes1[$iterator] = $c1;
                    if(count($c2)>0)
                    $mes2[$iterator] = $c2;
                    if(count($c3)>0)
                    $mes3[$iterator] = $c3;

                }

                 // Sacando Consolidado por los 3 meses(POR NIVEL ACADEMICO)
                $dataByCarrer = [];
                $dataByCarrer[0] = $this->trimestres[implode($arrayTrimestre)];
                $dataPA = [];$dataSA = [];
                $iteratorConso = 1;
                foreach ($carrera as $carre) {
                    //Obteniendo el total de resultados becados y otros
                    switch ($procesoId) {
                        case 1:
                        if($this->anio == date('Y')){
                             $estudiantesBM_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where('tipo_beca_id',1)->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where('tipo_beca_id',2)->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',1],['nivel_academico_id',2]])->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',2],['nivel_academico_id',2]])->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->count();
                        }else {
                             $estudiantesBM_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where('tipo_beca_id',1)->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where('tipo_beca_id',2)->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',1],['nivel_academico_id',2]])->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->where([['tipo_beca_id',2],['nivel_academico_id',2]])->count();

                        }

                            break;

                        case 2:
                            //Dejando colleciones vacia de primer año porque prectica es solo para segundo año
                            $estudiantesBM_PA = new Collection();$estudiantesOB_PA = new Collection();

                            if($this->anio == date('Y')){
                                $estudiantesBM_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['tipo_beca_id',1],['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->count();

                                $estudiantesOB_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['tipo_beca_id',2],['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereIn(DB::raw('MONTH(ultimo_cambio)'), [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]])->count();
                            }else{
                                $estudiantesBM_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['tipo_beca_id',1],['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->count();

                                $estudiantesOB_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['tipo_beca_id',2],['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->count();
                            }
                            break;
                    };


                   if ($procesoId == 1) {
                        $dataPA[0] = $this->trimestres[implode($arrayTrimestre)]." 1º AÑO";
                        // Asignando en la posicion 0 el titulo de la tabla
                        $dataPA[$iteratorConso] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_PA,
                          "totalOtraBeca" => $estudiantesOB_PA,
                        );

                   }

                    $dataSA[0] = $this->trimestres[implode($arrayTrimestre)]." 2º AÑO";
                    $dataSA[$iteratorConso] = array(
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

                $pdf = PDF::loadView('reportes.reportePendientesDeIniciar', ['mensuales' => $mensuales,'consolidadoPorNivel' => $dataByCarrer,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');

                $pdf->setOption('margin-top',15);
                $pdf->setOption('margin-bottom',15);
                $pdf->setOption('margin-left',15);
                $pdf->setOption('margin-right',15);
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
                    $iterator = 1;
                    foreach($carrera as $carre){

                        switch ($procesoId) {
                            case 1:
                            if($this->anio == date('Y')){
                                $estudiantesPA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->where([['proceso_actual','P'],['nivel_academico_id',1]])->get();

                                $estudiantesSA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                                // Obteniendo la cuenta para el conslidado de los meses
                                $estudiantesBM_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();

                                $estudiantesOB_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();

                                $estudiantesBM_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();

                                $estudiantesOB_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();
                            }else {
                                 $estudiantesPA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->where([['proceso_actual','P'],['nivel_academico_id',1]])->get();

                                $estudiantesSA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->where([['proceso_actual','P'],['nivel_academico_id',2]])->get();

                                // Obteniendo la cuenta para el conslidado de los meses
                                $estudiantesBM_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->count();

                                $estudiantesOB_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->count();

                                $estudiantesBM_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->count();

                                $estudiantesOB_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_ss')->where([['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->count();
                            }
                                break;

                            case 2:
                                $estudiantesPA = new Collection();
                            if($this->anio == date('Y')){
                                $estudiantesSA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['proceso_actual','P'],['nivel_academico_id',2],['estado', true]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->get();

                                // Obteniendo la cuenta para el conslidado de los meses
                                $estudiantesBM_PA = new Collection();$estudiantesOB_PA = new Collection();

                                $estudiantesBM_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['tipo_beca_id',1],['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();

                                $estudiantesOB_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['tipo_beca_id',2],['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->count();
                            }else{
                                 $estudiantesSA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre','apellido')->where([['proceso_actual','P'],['nivel_academico_id',2],['estado', true]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->get();

                                // Obteniendo la cuenta para el conslidado de los meses
                                $estudiantesBM_PA = new Collection();$estudiantesOB_PA = new Collection();

                                $estudiantesBM_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['tipo_beca_id',1],['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->count();

                                $estudiantesOB_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                                })->select('nombre')->where([['tipo_beca_id',2],['estado', true],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereNull('fecha_inicio_pp')->count();
                            }
                                break;
                        }

                        // Datos para cada Mes
                        $arrayMesEstudiante[0] = $carre->nombre;
                        $arrayMesEstudiante[1] = array('Primer Año' => $estudiantesPA, 'Segundo Año' => $estudiantesSA );

                        $arrayMes[0] = $this->meses[$arrayMeses[$i]];
                        $arrayMes[$iterator] = $arrayMesEstudiante;

                        // Datos para el consolidado de cada mes
                        if ($procesoId == 1) {
                        // Asignando en la posicion 0 el titulo de la tabla
                            $dataPA[0] = $this->meses[$arrayMeses[$i]]." 1º AÑO";
                            $dataPA[$iterator] = array(
                              "Carrera" => $carre->nombre,
                              "totalBecaMined" => $estudiantesBM_PA,
                              "totalOtraBeca" => $estudiantesOB_PA,
                            );

                        }

                        $dataSA[0] = $this->meses[$arrayMeses[$i]]." 2º AÑO";
                        $dataSA[$iterator] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_SA,
                          "totalOtraBeca" => $estudiantesOB_SA,
                        );

                        ++$iterator;
                    }
                    array_push($dataByCarrer,$dataPA);
                    array_push($dataByCarrer,$dataSA);
                    array_push($data, $arrayMes);

                }

                $pdf = PDF::loadView('reportes.reportePendientesDeIniciar', ['mensuales' => $data,'consolidado' => $dataByCarrer,'meses'=>$mesesTitulo,'tipo'=>'M','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
                $pdf->setOption('margin-top',15);
                $pdf->setOption('margin-bottom',15);
                $pdf->setOption('margin-left',15);
                $pdf->setOption('margin-right',15);
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
                      $iterator = 1;
                      foreach($carrera as $carre){

                            switch ($procesoId) {
                                case 1:
                                if($this->anio == date('Y')){
                                    $estudiantesPA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['proceso_actual','P'],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->get();

                                    $estudiantesSA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['proceso_actual','P'],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereNull('fecha_inicio_ss')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->get();
                                }else {
                                    $estudiantesPA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['proceso_actual','P'],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereNull('fecha_inicio_ss')->get();

                                    $estudiantesSA = $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre','apellido')->where([['estado', true],['proceso_actual','P'],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereNull('fecha_inicio_ss')->get();
                                }
                                    break;
                                case 2:
                                    $estudiantesPA = new Collection();
                                    if($this->anio == date('Y')){
                                        $estudiantesSA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                            $query->where('tipo_gp',2);
                                        })->select('nombre','apellido')->where([['proceso_actual','P'],['estado', true], ['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                        })->whereNull('fecha_inicio_pp')->whereMonth('ultimo_cambio','>=',$arrayMeses[$i])->get();
                                    }else {
                                         $estudiantesSA = $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                            $query->where('tipo_gp',2);
                                        })->select('nombre','apellido')->where([['proceso_actual','P'],['estado', true], ['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                            $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                        })->whereNull('fecha_inicio_pp')->get();
                                    }
                                    break;
                            }



                            $arrayMesEstudiante[0] = $carre->nombre;
                            $arrayMesEstudiante[1] = array(
                                "Primer Año" => $estudiantesPA,
                                "Segundo Año" => $estudiantesSA
                            );

                            $arrayMes[0] = $this->meses[$arrayMeses[$i]];
                            $arrayMes[$iterator] = $arrayMesEstudiante;

                            ++$iterator;
                      }

                     array_push($dataMensual, $arrayMes);
                }

                // Sacando Consolidado Anual POR NIVELES ACADEMICOS
                $dataByNivel = [];
                $dataGeneralByAnio = [];
                $dataGeneralByAnio[0] = $this->anio;

                /* PENDIENTE ANUAL DE AÑO ANTERIOR */
                $dataPA = []; $dataSA = [];
                $iteratorYear = 1;
                foreach ($carrera as $carre) {

                    //Obteniendo el total de resultados becados y otros
                    switch ($procesoId) {
                        case 1:
                            $estudiantesBM_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['tipo_beca_id',1],['nivel_academico_id',1],['estado', true]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['tipo_beca_id',2],['nivel_academico_id',1],['estado', true]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['tipo_beca_id',1],['nivel_academico_id',2],['estado', true]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->doesntHave('gestionProyecto')->select('nombre')->where([['tipo_beca_id',2],['nivel_academico_id',2],['estado', true]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_ss')->count();

                            break;

                       case 2:
                            $estudiantesBM_PA = new Collection();$estudiantesOB_PA = new Collection();
                            $estudiantesBM_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                    $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->whereDoesntHave('gestionProyecto',function($query) use($procesoId){
                                $query->where('tipo_gp',$procesoId);
                            })->select('nombre')->where([['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereNull('fecha_inicio_pp')->count();
                           break;
                    }

                    // Juntando los resultados en array individuales
                     // Datos para el consolidado de cada mes

                    if ($procesoId == 1) {
                            // Asignando en la posicion 0 el titulo de la tabla
                        $dataPA[0] = $this->anio." 1º AÑO";
                        $dataPA[$iteratorYear] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_PA,
                          "totalOtraBeca" => $estudiantesOB_PA,
                      );

                    }

                    $dataSA[0] = $this->anio." 2º AÑO";
                    $dataSA[$iteratorYear] = array(
                      "Carrera" => $carre->nombre,
                      "totalBecaMined" => $estudiantesBM_SA,
                      "totalOtraBeca" => $estudiantesOB_SA,
                    );
                    ++$iteratorYear;
                }
                array_push($dataByNivel,$dataPA);
                array_push($dataByNivel,$dataSA);


                    $pdf = PDF::loadView('reportes.reportePendientesDeIniciar', ['consolidadoMensual' => $dataMensual,'consolidadoAnualNiveles' => $dataByNivel,'meses'=>$mesesTitulo,'tipo'=>'A','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
                    $pdf->setOption('margin-top',15);
                    $pdf->setOption('margin-bottom',15);
                    $pdf->setOption('margin-left',15);
                    $pdf->setOption('margin-right',15);
                    return $pdf->stream('Reporte Pendientes de Inicio '.date('Y-m-d').'.pdf');
                    // return $dataByNivel;
            }
    }
    // Devuelve el reporte de los estudiantes que estan pendientes de Finalizar sus procesos
    public function getPendientesFinProcessReporte(Request $request){

           $carrera = Carrera::where('estado', true)->get();
           $procesoId = $request->proceso_id;
           $procesoTitulo = "";
           $mesesTitulo = "";
           $documentos = Documento::all();
           $proceso_campo = "";
           $proceso_consolidado = "";

            if($procesoId == 1){
              $procesoTitulo = "SERVICIO SOCIAL";
              $proceso_campo = "fecha_inicio_ss";
              $proceso_consolidado = 'MONTH(fecha_inicio_ss)';
            }else if($procesoId == 2){
              $procesoTitulo = "PRÁCTICA PROFESIONAL";
              $proceso_campo = "fecha_inicio_pp";
              $proceso_consolidado = 'MONTH(fecha_inicio_pp)';

            }

            if($request->tipoRepo == 'T'){

                    $collection;
                    $arrayTrimestre = explode(",",$request->meses);
                    $arrayValuesOfConso = [];
                    //Sacando datos mensuales
                    $dataMensual = [];
                    $mes1 = [];$mes2 = [];$mes3 = [];
                    $total = 0;

                    $mes1[0] = $this->meses[$arrayTrimestre[0]];
                    $mes2[0] = $this->meses[$arrayTrimestre[1]];
                    $mes3[0] = $this->meses[$arrayTrimestre[2]];

                    $mesesTitulo = $mes1[0].", ".$mes2[0].", ".$mes3[0];

                    $c1 = [];$c2 = [];$c3 = [];

                    $iterator = 1;
                    foreach($carrera as $carre){

                        if($this->anio == date('Y')){
                            if($arrayTrimestre[0] <= date('m')){
                                $estudiantesM1_PA = $carre->estudiantes()->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[0])->select('id','nombre','apellido')->where([['nivel_academico_id',1],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->get();

                                $estudiantesM1_SA = $carre->estudiantes()->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[0])->select('id','nombre','apellido')->where([['nivel_academico_id',2],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->get();

                                $arrayValuesOfConso = [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]];

                            }else{$estudiantesM1_PA = new Collection();$estudiantesM1_SA = new Collection();$arrayValuesOfConso = [$arrayTrimestre[1],$arrayTrimestre[2]];}

                            if($arrayTrimestre[1] <= date('m')){
                                $estudiantesM2_PA = $carre->estudiantes()->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[1])->select('id','nombre','apellido')->where([['nivel_academico_id',1],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->get();

                                $estudiantesM2_SA = $carre->estudiantes()->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[1])->select('id','nombre','apellido')->where([['nivel_academico_id',2],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->get();

                                $arrayValuesOfConso = [$arrayTrimestre[0],$arrayTrimestre[1],$arrayTrimestre[2]];

                            }else{$estudiantesM2_PA = new Collection();$estudiantesM2_SA = new Collection();$arrayValuesOfConso = [$arrayTrimestre[0],$arrayTrimestre[2]];}

                            if($arrayTrimestre[2] <= date('m')){
                                $estudiantesM3_PA = $carre->estudiantes()->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[2])->select('id','nombre','apellido')->where([['nivel_academico_id',1],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->get();

                                $estudiantesM3_SA = $carre->estudiantes()->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[2])->select('id','nombre','apellido')->where([['nivel_academico_id',2],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->get();

                                $arrayValuesOfConso = [$arrayTrimestre[0], $arrayTrimestre[1], $arrayTrimestre[2]];

                            }else{$estudiantesM3_PA = new Collection();$estudiantesM3_SA = new Collection();$arrayValuesOfConso = [$arrayTrimestre[0],$arrayTrimestre[1]];}

                            if(date('m') == '01' and $arrayTrimestre[0] == '1')
                                $arrayValuesOfConso = [$arrayTrimestre[0]];

                            /* Verficando que si el trimestre ingresado es mayor al mes actual no se sacan datos */
                            if($arrayTrimestre[0] > date('m') and $arrayTrimestre[1] > date('m') and $arrayTrimestre[1] > date('m') )
                                $arrayValuesOfConso = [];

                        }else{
                            // PROCESO CUANDO ES REPORTE DIFERENTE AL AÑO ACTUAL

                            $estudiantesM1_PA = $carre->estudiantes()->whereMonth($proceso_campo,'<=',$arrayTrimestre[0])->select('id','nombre','apellido')->where([['estado', true],['proceso_actual','I'],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->get();

                            $estudiantesM1_SA =  $carre->estudiantes()->whereMonth($proceso_campo,'<=',$arrayTrimestre[0])->select('id','nombre','apellido')->where([['estado', true],['proceso_actual','I'],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->get();

                            $estudiantesM2_PA =  $carre->estudiantes()->whereMonth($proceso_campo,'<=',$arrayTrimestre[1])->select('id','nombre','apellido')->where([['estado', true],['proceso_actual','I'],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->get();

                             $estudiantesM2_SA =  $carre->estudiantes()->whereMonth($proceso_campo,'<=',$arrayTrimestre[1])->select('id','nombre','apellido')->where([['estado', true],['proceso_actual','I'],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->get();

                            $estudiantesM3_PA =  $carre->estudiantes()->whereMonth($proceso_campo,'<=',$arrayTrimestre[2])->select('id','nombre','apellido')->where([['estado', true],['proceso_actual','I'],['nivel_academico_id',1]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->get();

                            $estudiantesM3_SA =  $carre->estudiantes()->whereMonth($proceso_campo,'<=',$arrayTrimestre[2])->select('id','nombre','apellido')->where([['estado', true],['proceso_actual','I'],['nivel_academico_id',2]])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->get();

                        }


                        // OBTENIENDO LOS DOCUMENTOS RESTANTES DE CADA ESTUDIANTE
                        $estudianteM1_PA = "";
                        foreach ($estudiantesM1_PA as $value) {
                          $estudianteM1_PA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                          foreach ($estudianteM1_PA->gestionProyecto as $item) {
                              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
                          }
                        }

                        $estudianteM1_SA = "";
                        foreach ($estudiantesM1_SA as $value) {
                          $estudianteM1_SA = Estudiante::with(['gestionProyecto.documentos_entrega'])->findOrFail($value->id);
                          foreach ($estudianteM1_SA->gestionProyecto as $item) {
                              $value->setAttribute("documentosRestantes",Documento::whereNotIn('id',$item->documentos_entrega->pluck('id'))->get());
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


                      $mes1[$iterator] = $c1;
                      $mes2[$iterator] = $c2;
                      $mes3[$iterator] = $c3;
                      ++$iterator;

                      // Sacando Consolidado por los 3 meses(POR NIVEL ACADEMICO)
                      $dataByCarrer = [];
                      $dataByCarrer[0] = $this->trimestres[implode($arrayTrimestre)];
                      $dataPA = [];$dataSA = [];
                      $iteratorConso = 1;

                      foreach($carrera as $carre){

                         if($this->anio == date('Y')){
                              if(!array_search(date('m'),$arrayTrimestre)){
                                    $estudiantesBM_PA =  $carre->estudiantes()->select('id')->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereYear($proceso_campo,$this->anio)->whereIn(DB::raw($proceso_consolidado), $arrayValuesOfConso)->count();

                                    $estudiantesOB_PA =  $carre->estudiantes()->select('id')->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereYear($proceso_campo,$this->anio)->whereIn(DB::raw($proceso_consolidado), $arrayValuesOfConso)->count();

                                    $estudiantesBM_SA =  $carre->estudiantes()->select('id')->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereYear($proceso_campo,$this->anio)->whereIn(DB::raw($proceso_consolidado), $arrayValuesOfConso)->count();

                                    $estudiantesOB_SA =  $carre->estudiantes()->select('id')->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereYear($proceso_campo,$this->anio)->whereIn(DB::raw($proceso_consolidado), $arrayValuesOfConso)->count();
                              }else{
                                    $indiceMes = array_search(date('m'), $arrayTrimestre);

                                    $estudiantesBM_PA =  $carre->estudiantes()->select('id')->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[$indiceMes])->count();

                                    $estudiantesOB_PA =  $carre->estudiantes()->select('id')->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[$indiceMes])->count();

                                    $estudiantesBM_SA =  $carre->estudiantes()->select('id')->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[$indiceMes])->count();

                                    $estudiantesOB_SA =  $carre->estudiantes()->select('id')->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                        $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                    })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayTrimestre[$indiceMes])->count();
                              }
                                  if ($procesoId == 1) {
                                    $dataPA[0] = $this->trimestres[implode($arrayTrimestre)]." 1º AÑO";
                                // Asignando en la posicion 0 el titulo de la tabla
                                    $dataPA[$iteratorConso] = array(
                                      "Carrera" => $carre->nombre,
                                      "totalBecaMined" => $estudiantesBM_PA,
                                      "totalOtraBeca" => $estudiantesOB_PA,
                                  );

                                }

                                $dataSA[0] = $this->trimestres[implode($arrayTrimestre)]." 2º AÑO";
                                $dataSA[$iteratorConso] = array(
                                  "Carrera" => $carre->nombre,
                                  "totalBecaMined" => $estudiantesBM_SA,
                                  "totalOtraBeca" => $estudiantesOB_SA,
                              );

                                $dataByCarrer[0] = $dataPA;
                                $dataByCarrer[1] = $dataSA;
                          }else{
                                $estudiantesBM_PA =  $carre->estudiantes()->select('id',$proceso_campo)->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereYear($proceso_campo,$this->anio)->get();

                                foreach ($estudiantesBM_PA as $key => $value) {
                                    if (substr($value->$proceso_campo,6,1) > $arrayTrimestre[0] and substr($value->$proceso_campo,6,1) > $arrayTrimestre[1] and substr($value->$proceso_campo,6,1) > $arrayTrimestre[2] ) {
                                        $estudiantesBM_PA = $estudiantesBM_PA->except($value->id);
                                    }
                                }

                                $estudiantesOB_PA =  $carre->estudiantes()->select('id',$proceso_campo)->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereYear($proceso_campo,$this->anio)->get();

                                foreach ($estudiantesOB_PA as $key => $value) {
                                    if (substr($value->$proceso_campo,6,1) > $arrayTrimestre[0] and substr($value->$proceso_campo,6,1) > $arrayTrimestre[1] and substr($value->$proceso_campo,6,1) > $arrayTrimestre[2] ) {
                                        $estudiantesOB_PA = $estudiantesOB_PA->except($value->id);
                                    }
                                }

                                $estudiantesBM_SA =  $carre->estudiantes()->select('id',$proceso_campo)->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereYear($proceso_campo,$this->anio)->get();

                                foreach ($estudiantesBM_SA as $key => $value) {
                                    if (substr($value->$proceso_campo,6,1) > $arrayTrimestre[0] and substr($value->$proceso_campo,6,1) > $arrayTrimestre[1] and substr($value->$proceso_campo,6,1) > $arrayTrimestre[2] ) {
                                        $estudiantesBM_SA = $estudiantesBM_SA->except($value->id);
                                    }
                                }

                                  $estudiantesOB_SA =  $carre->estudiantes()->select('id',$proceso_campo)->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereYear($proceso_campo,$this->anio)->get();


                                foreach ($estudiantesOB_SA as $key => $value) {
                                    if (substr($value->$proceso_campo,6,1) > $arrayTrimestre[0] and substr($value->$proceso_campo,6,1) > $arrayTrimestre[1] and substr($value->$proceso_campo,6,1) > $arrayTrimestre[2] ) {
                                        $estudiantesOB_SA = $estudiantesOB_SA->except($value->id);
                                    }
                                }

                                if ($procesoId == 1) {
                                    $dataPA[0] = $this->trimestres[implode($arrayTrimestre)]." 1º AÑO";
                            // Asignando en la posicion 0 el titulo de la tabla
                                    $dataPA[$iteratorConso] = array(
                                      "Carrera" => $carre->nombre,
                                      "totalBecaMined" => $estudiantesBM_PA->count(),
                                      "totalOtraBeca" => $estudiantesOB_PA->count(),
                                  );

                                }

                                $dataSA[0] = $this->trimestres[implode($arrayTrimestre)]." 2º AÑO";
                                $dataSA[$iteratorConso] = array(
                                  "Carrera" => $carre->nombre,
                                  "totalBecaMined" => $estudiantesBM_SA->count(),
                                  "totalOtraBeca" => $estudiantesOB_SA->count(),
                              );

                                $dataByCarrer[0] = $dataPA;
                                $dataByCarrer[1] = $dataSA;
                          }

                          ++$iteratorConso;
                      }

                    }

                    $mensuales = array();
                    array_push($mensuales,$mes1);
                    array_push($mensuales,$mes2);
                    array_push($mensuales,$mes3);

                    $pdf = PDF::loadView('reportes.reportePendientesFinalizacion', ['mensuales' => $mensuales,'consolidadoByNivel' => $dataByCarrer,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
                    $pdf->setOption('margin-top',15);
                    $pdf->setOption('margin-bottom',15);
                    $pdf->setOption('margin-left',15);
                    $pdf->setOption('margin-right',15);
                    return $pdf->stream('Pendientes de Finalización '.date('Y-m-d').'.pdf');
                    /* return $indiceMes; */

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
                    $iterator = 1;
                   foreach($carrera as $carre){

                    if ($this->anio == date('Y') ) {
                        if($arrayMeses[$i] > date('m')){
                            /* En este caso significa que uno de los meses seleccionados es mayor al actual por lo tanto no se sacan datos */
                            $estudiantes_PA = new Collection();
                            $estudiantes_SA = new Collection();
                        // DATOS PARA CONSOLIDADO MENSUAL POR NIVEL ACADEMICO
                            $estudiantesBM_PA =  0;
                            $estudiantesOB_PA =  0;
                            $estudiantesBM_SA = 0;
                            $estudiantesOB_SA =  0;

                        }else{
                            /* Aqui es lo meses menores o iguales al mes actual y si se sacan datos */
                            $estudiantes_PA = $carre->estudiantes()->select('id','nombre','apellido')->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->where([['nivel_academico_id',1],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->get();

                            $estudiantes_SA = $carre->estudiantes()->select('id','nombre','apellido')->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->where([['nivel_academico_id',2],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->get();
                        // DATOS PARA CONSOLIDADO MENSUAL POR NIVEL ACADEMICO
                            $estudiantesBM_PA =  $carre->estudiantes()->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->count();
                        }
                    }else{

                         $estudiantes_PA = $carre->estudiantes()->select('id','nombre','apellido')->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->where([['nivel_academico_id',1],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->get();

                            $estudiantes_SA = $carre->estudiantes()->select('id','nombre','apellido')->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->where([['nivel_academico_id',2],['estado', true],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->get();
                        // DATOS PARA CONSOLIDADO MENSUAL POR NIVEL ACADEMICO
                            $estudiantesBM_PA =  $carre->estudiantes()->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->where([['nivel_academico_id',1],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->where([['nivel_academico_id',2],['estado', true],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->count();

                    }

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
                        $arrayMes[$iterator] = $arrayMesEstudiante;

                         // Datos para el consolidado de cada mes
                        if ($procesoId == 1) {
                        // Asignando en la posicion 0 el titulo de la tabla
                            $dataPA[0] = $this->meses[$arrayMeses[$i]]." 1º AÑO";
                            $dataPA[$iterator] = array(
                              "Carrera" => $carre->nombre,
                              "totalBecaMined" => $estudiantesBM_PA,
                              "totalOtraBeca" => $estudiantesOB_PA,
                            );

                        }

                        $dataSA[0] = $this->meses[$arrayMeses[$i]]." 2º AÑO";
                        $dataSA[$iterator] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_SA,
                          "totalOtraBeca" => $estudiantesOB_SA,
                        );

                        ++$iterator;
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
                    $iterator = 1;
                    foreach($carrera as $carre){

                       if($this->anio == date('Y')){
                            if($arrayMeses[$i] > date('m')){
                                $estudiantes_PA = new Collection();
                                $estudiantes_SA = new Collection();
                            }else{
                                $estudiantes_PA = $carre->estudiantes()->select('id','nombre','apellido')->where([['estado', true],['nivel_academico_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->get();

                                $estudiantes_SA = $carre->estudiantes()->select('id','nombre','apellido')->where([['estado', true],['nivel_academico_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                    $query->where('procesos_estudiantes.proceso_id', $procesoId);
                                })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->get();
                            }
                       }else{
                             $estudiantes_PA = $carre->estudiantes()->select('id','nombre','apellido')->where([['estado', true],['nivel_academico_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->get();

                               $estudiantes_SA = $carre->estudiantes()->select('id','nombre','apellido')->where([['estado', true],['nivel_academico_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',$arrayMeses[$i])->get();
                       }
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
                            $arrayMes[$iterator] = $arrayMesEstudiante;

                            ++$iterator;
                    }

                    array_push($dataMensual, $arrayMes);
                }

               // Sacando Consolidado Anual POR NIVELES ACADEMICOS
                $dataByNivel = [];
                $dataGeneralByAnio = [];
                $dataGeneralByAnio[0] = $this->anio;
                $iteratorYear = 1;
                $dataPA = []; $dataSA = [];

                foreach ($carrera as $carre) {

                        if ($this->anio == date('Y')) {
                            $estudiantesBM_PA =  $carre->estudiantes()->select('id')->where([['estado', true],['nivel_academico_id',1],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',date('m'))->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->select('id')->where([['estado', true],['nivel_academico_id',1],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',date('m'))->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->select('id')->where([['estado', true],['nivel_academico_id',2],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',date('m'))->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->select('id')->where([['estado', true],['nivel_academico_id',2],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->whereMonth($proceso_campo,'<=',date('m'))->count();
                        }else{

                            $estudiantesBM_PA =  $carre->estudiantes()->select('id')->where([['estado', true],['nivel_academico_id',1],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->count();

                            $estudiantesOB_PA =  $carre->estudiantes()->select('id')->where([['estado', true],['nivel_academico_id',1],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->count();

                            $estudiantesBM_SA =  $carre->estudiantes()->select('id')->where([['estado', true],['nivel_academico_id',2],['tipo_beca_id',1],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->count();

                            $estudiantesOB_SA =  $carre->estudiantes()->select('id')->where([['estado', true],['nivel_academico_id',2],['tipo_beca_id',2],['proceso_actual','I']])->whereHas('proceso', function ($query) use ($procesoId) {
                                $query->where('procesos_estudiantes.proceso_id', $procesoId);
                            })->whereYear($proceso_campo,$this->anio)->count();
                        }
                        // Juntando los resultados en array individuales
                        // Datos para el consolidado de cada mes

                        if ($procesoId == 1) {
                                // Asignando en la posicion 0 el titulo de la tabla
                            $dataPA[0] = $this->anio." 1º AÑO";
                            $dataPA[$iteratorYear] = array(
                            "Carrera" => $carre->nombre,
                            "totalBecaMined" => $estudiantesBM_PA,
                            "totalOtraBeca" => $estudiantesOB_PA,
                            );
                        }

                        $dataSA[0] = $this->anio." 2º AÑO";
                        $dataSA[$iteratorYear] = array(
                        "Carrera" => $carre->nombre,
                        "totalBecaMined" => $estudiantesBM_SA,
                        "totalOtraBeca" => $estudiantesOB_SA,
                        );
                    ++$iteratorYear;
                }
                array_push($dataByNivel,$dataPA);
                array_push($dataByNivel,$dataSA);




                $pdf = PDF::loadView('reportes.reportePendientesFinalizacion', ['mensuales' => $dataMensual,'consolidadoAnualByNivel' => $dataByNivel,'meses'=>$mesesTitulo,'tipo'=>'A','procesoTitulo' => $procesoTitulo,'anio' => $this->anio])->setOption('footer-center', 'Página [page] de [topage]');
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
        $carrera = Carrera::where('estado', true)->get();
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
                $dataPA_M1 = []; $dataSA_M1 = [];
                $dataPA_M2 = []; $dataSA_M2 = [];
                $dataPA_M3 = []; $dataSA_M3 = [];
                $consolidadoM1_PA = [];$consolidadoM2_PA = [];$consolidadoM3_PA = [];
                $consolidadoM1_SA = [];$consolidadoM2_SA = [];$consolidadoM3_SA = [];
                //Sacando datos mensuales
                $dataMensual = [];
                $mes1 = [];$mes2 = [];$mes3 = [];

                $mes1PA[0] = $this->meses[$arrayTrimestre[0]];
                $mes2PA[0] = $this->meses[$arrayTrimestre[1]];
                $mes3PA[0] = $this->meses[$arrayTrimestre[2]];

                $mes1SA[0] = $this->meses[$arrayTrimestre[0]];
                $mes2SA[0] = $this->meses[$arrayTrimestre[1]];
                $mes3SA[0] = $this->meses[$arrayTrimestre[2]];

                $mesesTitulo = $mes1SA[0].", ".$mes2SA[0].", ".$mes3SA[0];
                $iteratorMes = 3;
                $iterator = 1;
                foreach($carrera as $carre){

                    // DATOS MES 1
                    $estudiantesM1_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereMonth($campoFecha,$arrayTrimestre[0])->get();

                    $estudiantesM1_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereMonth($campoFecha,$arrayTrimestre[0])->get();

                    /* Consolidado para el mes 1 */
                    $estudiantesBM_PA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[0]])->count();

                    $estudiantesOB_PA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[0]])->count();

                    $estudiantesBM_SA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[0]])->count();

                    $estudiantesOB_SA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[0]])->count();

                    // DATOS MES 2
                    $estudiantesM2_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereMonth($campoFecha,$arrayTrimestre[1])->get();

                    $estudiantesM2_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereMonth($campoFecha,$arrayTrimestre[1])->get();

                    /* Consolidado para el mes 2  */
                    $estudiantesBM_PA_M2 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[1]])->count();

                    $estudiantesOB_PA_M2 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[1]])->count();

                    $estudiantesBM_SA_M2 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[1]])->count();

                    $estudiantesOB_SA_M2 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[1]])->count();

                    // DATOS MES 3
                    $estudiantesM3_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereMonth($campoFecha,$arrayTrimestre[2])->get();

                    $estudiantesM3_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereMonth($campoFecha,$arrayTrimestre[2])->get();

                    /* Consolidado para el mes 3  */
                    $estudiantesBM_PA_M3 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[2]])->count();

                    $estudiantesOB_PA_M3 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[2]])->count();

                    $estudiantesBM_SA_M3 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[2]])->count();

                    $estudiantesOB_SA_M3 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereNotNull(trim($estudianteProceso))->whereYear($campoFecha,$this->anio)->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayTrimestre[2]])->count();


                    /* Primer Año */
                    $c1PA[0] = $carre->nombre;
                    if($procesoId == 1)
                        $c1PA[1] = $estudiantesM1_PA;
                    else
                        $c1PA[1] = [];

                    $c2PA[0] = $carre->nombre;
                    if($procesoId == 1)
                        $c2PA[1] = $estudiantesM2_PA;
                    else
                        $c2PA[1] = [];

                    $c3PA[0] = $carre->nombre;
                    if($procesoId == 1)
                        $c3PA[1] = $estudiantesM3_PA;
                    else
                        $c3PA[1] = [];

                    /* Segundo Año */
                    $c1SA[0] = $carre->nombre;
                    $c1SA[1] = $estudiantesM1_SA;


                    $c2SA[0] = $carre->nombre;
                    $c2SA[1] = $estudiantesM2_SA;


                    $c3SA[0] = $carre->nombre;
                    $c3SA[1] = $estudiantesM3_SA;

                    $mes1PA[1] = "PRIMER AÑO";$mes2PA[1] = "PRIMER AÑO";$mes3PA[1] = "PRIMER AÑO";
                    $mes1PA[$iteratorMes] = $c1PA;
                    $mes2PA[$iteratorMes] = $c2PA;
                    $mes3PA[$iteratorMes] = $c3PA;

                    $mes1SA[1] = "SEGUNDO AÑO";$mes2SA[1] = "SEGUNDO AÑO";$mes3SA[1] = "SEGUNDO AÑO";
                    $mes1SA[$iteratorMes] = $c1SA;
                    $mes2SA[$iteratorMes] = $c2SA;
                    $mes3SA[$iteratorMes] = $c3SA;

                    /* Agregando al arreglo de cada mes el consolidado */
                    /* PRIMER AÑO */
                    if($procesoId == 1){
                        $dataPA_M1[0] = $mes1PA[0]." PRIMER AÑO";
                        $dataPA_M1[$iterator] = array(
                            "Carrera" => $carre->nombre,
                            "totalBecaMined" => $estudiantesBM_PA_M1,
                            "totalOtraBeca" => $estudiantesOB_PA_M1
                        );

                        $dataPA_M2[0] = $mes2PA[0]." PRIMER AÑO";
                        $dataPA_M2[$iterator] = array(
                            "Carrera" => $carre->nombre,
                            "totalBecaMined" => $estudiantesBM_PA_M2,
                            "totalOtraBeca" => $estudiantesOB_PA_M2
                        );

                        $dataPA_M3[0] = $mes3PA[0]." PRIMER AÑO";
                        $dataPA_M3[$iterator] = array(
                            "Carrera" => $carre->nombre,
                            "totalBecaMined" => $estudiantesBM_PA_M3,
                            "totalOtraBeca" => $estudiantesOB_PA_M3
                        );
                    }
                    /* SEGUNDO AÑO */
                    $dataSA_M1[0] = $mes1SA[0]." SEGUNDO AÑO";
                    $dataSA_M1[$iterator] = array(
                        "Carrera" => $carre->nombre,
                        "totalBecaMined" => $estudiantesBM_SA_M1,
                        "totalOtraBeca" => $estudiantesOB_SA_M1
                    );

                    $dataSA_M2[0] = $mes2SA[0]." SEGUNDO AÑO";
                    $dataSA_M2[$iterator] = array(
                        "Carrera" => $carre->nombre,
                        "totalBecaMined" => $estudiantesBM_SA_M2,
                        "totalOtraBeca" => $estudiantesOB_SA_M2
                    );

                    $dataSA_M3[0] = $mes3SA[0]." SEGUNDO AÑO";
                    $dataSA_M3[$iterator] = array(
                        "Carrera" => $carre->nombre,
                        "totalBecaMined" => $estudiantesBM_SA_M3,
                        "totalOtraBeca" => $estudiantesOB_SA_M3
                    );
                    /* Fin de proceso para consolidado */
                    ++$iterator;
                    ++$iteratorMes;
                }
                array_push($consolidadoM1_PA,$dataPA_M1);
                array_push($consolidadoM2_PA,$dataPA_M2);
                array_push($consolidadoM3_PA, $dataPA_M3);

                array_push($consolidadoM1_SA,$dataSA_M1);
                array_push($consolidadoM2_SA,$dataSA_M2);
                array_push($consolidadoM3_SA,$dataSA_M3);

                /* Agregando el consolidado a cada mes */
                $mes1PA[2] = $consolidadoM1_PA[0];
                $mes1SA[2] = $consolidadoM1_SA[0];

                $mes2PA[2] = $consolidadoM2_PA[0];
                $mes2SA[2] = $consolidadoM2_SA[0];

                $mes3PA[2] = $consolidadoM3_PA[0];
                $mes3SA[2] = $consolidadoM3_SA[0];

                // Obteniendo CONSOLIDADO por los 3 meses pero dividido en niveles academico
                // Sacando Consolidado por los 3 meses
                $dataByNivel = [];
                $dataPA = []; $dataSA = [];
                $data[0] = $this->trimestres[implode($arrayTrimestre)];
                $iteratorConso = 1;
                foreach ($carrera as $carre) {
                       //Obteniendo el total de resultados becados y otros
                        $estudiantesBM_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),[$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->count();

                        $estudiantesOB_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados), [$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->count();

                        $estudiantesBM_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados), [$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->count();

                        $estudiantesOB_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados), [$arrayTrimestre[0],
                        $arrayTrimestre[1],$arrayTrimestre[2]])->count();

                        if($procesoId == 1){
                            $dataPA[0] = $this->trimestres[implode($arrayTrimestre)]." 1º AÑO";
                            $dataPA[$iteratorConso] = array(
                               "Carrera" => $carre->nombre,
                               "totalBecaMined" => $estudiantesBM_PA,
                               "totalOtraBeca" => $estudiantesOB_PA
                            );
                        }

                        $dataSA[0] = $this->trimestres[implode($arrayTrimestre)]." 2º AÑO";
                        $dataSA[$iteratorConso] = array(
                            "Carrera" => $carre->nombre,
                            "totalBecaMined" => $estudiantesBM_SA,
                            "totalOtraBeca" => $estudiantesOB_SA
                        );
                        ++$iteratorConso;
                }
                array_push($dataByNivel,$dataPA);
                array_push($dataByNivel,$dataSA);

                $mensuales = array();
                if($procesoId==1)
                    array_push($mensuales,array($mes1PA , $mes1SA));
                else
                    array_push($mensuales,array($mes1SA));

                if($procesoId==1)
                    array_push($mensuales,array($mes2PA , $mes2SA));
                else
                    array_push($mensuales, array($mes2SA));

                if($procesoId==1)
                    array_push($mensuales,array($mes3PA , $mes3SA));
                else
                    array_push($mensuales,array($mes3SA));


                if($request->onlyConsolidado=='OC'){
                    $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $mensuales,'consolidadoByNivel' => $dataByNivel,'consolidadoGeneral' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio' => $this->anio,'onlyConsolidado' => true])->setOption('footer-center', 'Página [page] de [topage]');
                }else{
                    $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $mensuales,'consolidadoByNivel' => $dataByNivel,'consolidadoGeneral' => $data,'meses'=>$mesesTitulo,'tipo'=>'T','procesoTitulo' => $procesoTitulo,'anio' => $this->anio,'onlyConsolidado' => false])->setOption('footer-center', 'Página [page] de [topage]');
                }

                $pdf->setOption('margin-top',15);
                $pdf->setOption('margin-bottom',15);
                $pdf->setOption('margin-left',15);
                $pdf->setOption('margin-right',15);
                return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
                /* return $mensuales; */

        }else if($request->tipoRepo == 'M'){

                $arrayMeses = explode(",", $request->meses);
                $arrayMesEstudiantePA = [];
                $arrayMesEstudianteSA = [];
                $mesesTitulo = [];
                $dataPA = []; $dataSA = [];
                $dataByNivel = [];
                $arrayGeneral = [];


                for ($i=0; $i < count($arrayMeses) ; $i++) {

                    $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];
                    $arrayMesPA = [];
                    $arrayMesSA = [];
                    $dataMensual = array();
                    $iterator = 1;
                    $iteratorMes = 3;
                    foreach($carrera as $carre){

                          $estudiantes_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayMeses[$i])->get();

                          $estudiantes_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayMeses[$i])->get();

                            // Obteniendo Cuenta para consolidado Mensual
                            $estudiantesBM_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->count();

                            $estudiantesOB_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->count();

                            $estudiantesBM_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->count();

                            $estudiantesOB_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->count();

                            /* PROCESO PARA OBTENER EL CONSOLIDADO DE CADA MES */
                            $estudiantesBM_PA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->count();

                            $estudiantesOB_PA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->count();

                            $estudiantesBM_SA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->count();

                            $estudiantesOB_SA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                            [$arrayMeses[$i]])->count();


                           $arrayMesEstudiantePA[0] = $carre->nombre;$arrayMesEstudianteSA[0] = $carre->nombre;
                           if($procesoId == 1)
                                $arrayMesEstudiantePA[1] = $estudiantes_PA;
                            else
                            $arrayMesEstudiantePA[1] = [];

                           $arrayMesEstudianteSA[1] = $estudiantes_SA;
                           /* else */
                            /* $arrayMesEstudiante[1] = array("Segundo Año" => $estudiantes_SA); */

                           $arrayMesPA[0] = $this->meses[$arrayMeses[$i]];
                           $arrayMesPA[1] = "PRIMER AÑO";
                           $arrayMesPA[$iteratorMes] = $arrayMesEstudiantePA;

                           $arrayMesSA[0] = $this->meses[$arrayMeses[$i]];
                           $arrayMesSA[1] = "SEGUNDO AÑO";
                           $arrayMesSA[$iteratorMes] = $arrayMesEstudianteSA;

                            // Datos para el consolidado de cada mes
                            /* if ($procesoId == 1) { */
                            // Asignando en la posicion 0 el titulo de la tabla
                            $dataPA[0] = $this->meses[$arrayMeses[$i]]." PRIMER AÑO";
                            $dataPA[$iterator] = array(
                                "Carrera" => $carre->nombre,
                                "totalBecaMined" => $estudiantesBM_PA,
                                "totalOtraBeca" => $estudiantesOB_PA,
                            );

                            /* } */

                            $dataSA[0] = $this->meses[$arrayMeses[$i]]." SEGUNDO AÑO";
                            $dataSA[$iterator] = array(
                              "Carrera" => $carre->nombre,
                              "totalBecaMined" => $estudiantesBM_SA,
                              "totalOtraBeca" => $estudiantesOB_SA,
                            );

                            $arrayMesPA[2] = $dataPA;
                            $arrayMesSA[2] = $dataSA;

                            ++$iterator;
                            ++$iteratorMes;
                    }
                   if($procesoId ==1)
                   array_push($dataMensual, $arrayMesPA);

                   array_push($dataMensual, $arrayMesSA);
                   array_push($arrayGeneral, $dataMensual);
                }

               if($request->onlyConsolidado=='OC'){
                 $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $arrayGeneral,'tipo' => 'M', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo, 'anio' => $this->anio, 'onlyConsolidado' => true])->setOption('footer-center', 'Página [page] de [topage]');
                }else{
                 $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $arrayGeneral,'tipo' => 'M','meses'=>$mesesTitulo,'procesoTitulo' => $procesoTitulo,'anio'=>$this->anio,'onlyConsolidado' => false])->setOption('footer-center', 'Página [page] de [topage]');
                }

               $pdf->setOption('margin-top',15);
               $pdf->setOption('margin-bottom',15);
               $pdf->setOption('margin-left',15);
               $pdf->setOption('margin-right',15);
               return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
               /* return $arrayGeneral; */

        }else if($request->tipoRepo == 'A'){

            $arrayMeses = explode(",", "1,2,3,4,5,6,7,8,9,10,11,12");
            $arrayMesEstudiantePA = [];
            $arrayMesEstudianteSA = [];
            $mesesTitulo = [];
            $dataPA = []; $dataSA = [];
            $dataByNivel = [];
            $arrayGeneral = [];

            for ($i=0; $i < count($arrayMeses) ; $i++) {

              $mesesTitulo[$i] = $this->meses[$arrayMeses[$i]];
              $arrayMesPA = [];
              $arrayMesSA = [];
              $dataMensual = array();
              $iteratorMes = 3;
              $iterator = 1;
              foreach($carrera as $carre){

                    $estudiantes_PA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayMeses[$i])->get();

                    $estudiantes_SA = $carre->estudiantes()->select('nombre','apellido','tipo_beca_id')->where([['articulado',false],['estado', true],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereMonth($campoFecha,$arrayMeses[$i])->get();

                    // Obteniendo Cuenta para consolidado Mensual
                    $estudiantesBM_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayMeses[$i]])->count();

                    $estudiantesOB_PA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayMeses[$i]])->count();

                    $estudiantesBM_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayMeses[$i]])->count();

                    $estudiantesOB_SA = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayMeses[$i]])->count();

                    /* PROCESO PARA OBTENER EL CONSOLIDADO DE CADA MES */
                    $estudiantesBM_PA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayMeses[$i]])->count();

                    $estudiantesOB_PA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayMeses[$i]])->count();

                    $estudiantesBM_SA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',1],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayMeses[$i]])->count();

                    $estudiantesOB_SA_M1 = $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['tipo_beca_id',2],['nivel_academico_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->whereIn(DB::raw($campoFechaConsolidados),
                    [$arrayMeses[$i]])->count();


                    $arrayMesEstudiantePA[0] = $carre->nombre;$arrayMesEstudianteSA[0] = $carre->nombre;
                    if($procesoId == 1)
                        $arrayMesEstudiantePA[1] = $estudiantes_PA;
                    else
                    $arrayMesEstudiantePA[1] = [];

                    $arrayMesEstudianteSA[1] = $estudiantes_SA;
                    /* else */
                    /* $arrayMesEstudiante[1] = array("Segundo Año" => $estudiantes_SA); */

                    $arrayMesPA[0] = $this->meses[$arrayMeses[$i]];
                    $arrayMesPA[1] = "PRIMER AÑO";
                    $arrayMesPA[$iteratorMes] = $arrayMesEstudiantePA;

                    $arrayMesSA[0] = $this->meses[$arrayMeses[$i]];
                    $arrayMesSA[1] = "SEGUNDO AÑO";
                    $arrayMesSA[$iteratorMes] = $arrayMesEstudianteSA;

                    // Datos para el consolidado de cada mes
                    /* if ($procesoId == 1) { */
                    // Asignando en la posicion 0 el titulo de la tabla
                    $dataPA[0] = $this->meses[$arrayMeses[$i]]." PRIMER AÑO";
                    $dataPA[$iterator] = array(
                        "Carrera" => $carre->nombre,
                        "totalBecaMined" => $estudiantesBM_PA,
                        "totalOtraBeca" => $estudiantesOB_PA,
                    );

                    /* } */

                    $dataSA[0] = $this->meses[$arrayMeses[$i]]." SEGUNDO AÑO";
                    $dataSA[$iterator] = array(
                        "Carrera" => $carre->nombre,
                        "totalBecaMined" => $estudiantesBM_SA,
                        "totalOtraBeca" => $estudiantesOB_SA,
                    );

                    $arrayMesPA[2] = $dataPA;
                    $arrayMesSA[2] = $dataSA;

                    ++$iterator;
                    ++$iteratorMes;
              }
               if($procesoId ==1)
                array_push($dataMensual, $arrayMesPA);

                array_push($dataMensual, $arrayMesSA);
                array_push($arrayGeneral, $dataMensual);

                // Sacando Consolidado Anual POR NIVELES ACADEMICOS
                $dataByNivel = [];

                $dataPA = []; $dataSA = [];
                $iteratorYear = 1;
                foreach ($carrera as $carre) {

                    $estudiantesBM_PA =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['nivel_academico_id',1],['tipo_beca_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->count();

                    $estudiantesOB_PA =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['nivel_academico_id',1],['tipo_beca_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->count();

                    $estudiantesBM_SA =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['nivel_academico_id',2],['tipo_beca_id',1]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->count();

                    $estudiantesOB_SA =  $carre->estudiantes()->select('id')->where([['articulado',false],['estado', true],['nivel_academico_id',2],['tipo_beca_id',2]])->whereYear($campoFecha,$this->anio)->whereNotNull(trim($estudianteProceso))->count();

                    // Juntando los resultados en array individuales
                     // Datos para el consolidado de cada mes

                    if ($procesoId == 1) {
                        // Asignando en la posicion 0 el titulo de la tabla
                        $dataPA[0] = $this->anio." PRIMER AÑO";
                        $dataPA[$iteratorYear] = array(
                          "Carrera" => $carre->nombre,
                          "totalBecaMined" => $estudiantesBM_PA,
                          "totalOtraBeca" => $estudiantesOB_PA,
                      );

                    }

                    $dataSA[0] = $this->anio." SEGUNDO AÑO";
                    $dataSA[$iteratorYear] = array(
                      "Carrera" => $carre->nombre,
                      "totalBecaMined" => $estudiantesBM_SA,
                      "totalOtraBeca" => $estudiantesOB_SA,
                    );
                    ++$iteratorYear;
                }
                array_push($dataByNivel,$dataPA);
                array_push($dataByNivel,$dataSA);
            }


            if($request->onlyConsolidado=='OC'){
              $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $arrayGeneral,'consolidadoByNivel'=>$dataByNivel,'tipo' => 'A', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo,'anio' => $this->anio,'onlyConsolidado' => true])->setOption('footer-center', 'Página [page] de [topage]');
            }else{
              $pdf = PDF::loadView('reportes.reporteProcesosCulminados', ['mensuales' => $arrayGeneral,'consolidadoByNivel'=>$dataByNivel,'tipo' => 'A', 'meses' => $mesesTitulo, 'procesoTitulo' => $procesoTitulo,'anio' => $this->anio,'onlyConsolidado' => false])->setOption('footer-center', 'Página [page] de [topage]');
            }

            $pdf->setOption('margin-top',15);
            $pdf->setOption('margin-bottom',15);
            $pdf->setOption('margin-left',15);
            $pdf->setOption('margin-right',15);
            return $pdf->stream('Reporte Procesos Culminados '.date('Y-m-d').'.pdf');
            /* return $arrayGeneral; */
        }
    }
    // ***************** Fin de la funciones para reportes *****************//
    // Metodo que devuelve los estudiantes que han finalizado ss o pp y se le spuede generar constancia final
    public function constancias(Request $request){

        $buscar = $request->buscar;
        $proceso = $request->proceso_id;
        $carrera_id = $request->carre_id;
        if ($request->proceso_id == 1) {
            $nivelAcad = $request->nivelAcad;
        } else {
            $nivelAcad = 2;
        }

        if($proceso == 1){
            $gp = Estudiante::distinct('id')->with([
                'carrera',
                'gestionProyecto.constancia_entreg',
                'gestionProyecto'=> function($query){
                    $query->year($this->anio)->where('gestion_proyectos.tipo_gp',1)->get();
                }
            ])->nombre($buscar)->where([['nivel_academico_id',$nivelAcad],['estado_ss',1],['carrera_id',$carrera_id],[DB::raw('YEAR(fecha_inicio_ss)'),$this->anio],[DB::raw('YEAR(fecha_fin_ss)'),$this->anio]])->paginate(10);

        }else if($proceso == 2){
            $gp = Estudiante::distinct('id')->with([
                'carrera',
                'gestionProyecto.constancia_entreg',
                'gestionProyecto'=> function($query){
                    $query->year($this->anio)->where('gestion_proyectos.tipo_gp',2)->get();
                }
            ])->nombre($buscar)->where([['nivel_academico_id', $nivelAcad],['estado_pp',2],['carrera_id',$carrera_id],[DB::raw('YEAR(fecha_inicio_pp)'),$this->anio],[DB::raw('YEAR(fecha_fin_pp)'),$this->anio]])->paginate(10);
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
                $query->year($this->anio)->where('gestion_proyectos.tipo_gp',$procesoId)->get();
            }

        ])->find($estudianteId);

        $proyectos =  [];
        $proyectos = $estudiante->gestionProyecto()->where('tipo_gp',$procesoId)->get();
        $infoEstudiante = $estudiante;


        foreach ($estudiante->gestionProyecto as $value) {

            if($value->constancia_entreg()->count() == 0){

                DB::table('constancias_entregadas')->insert(
                    ['gestion_proyecto_id' => $value->id,'created_at' => Carbon::now()->toDateTimeString(),'fecha_registro'=> $this->anio]
                );

            }
        }
        foreach ($proyectos as $item) {

            $totalHoras += $item->horas_realizadas;
        }

        $admin = User::select('nombre')->find(0);
        $pdf = PDF::loadView('reportes.constanciass', ['admin'=>$admin,'estudiante'=>$infoEstudiante,'proyectos' => $proyectos, 'proceso' =>$tituloProceso,'fecha' => $date,'totalHoras' => $totalHoras])->setOption('footer-center', '');
        return $pdf->stream('Constancia '.$tituloProceso.'.pdf');
    }

    // Metodo que devuelve la descarga de los documentos relacionadoa con el proceso que realiza cada estudiante
    public function downloadDocs(Request $request){
            $procesoId = Auth::user()->estudiante->proceso[0]->id;
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
            $numeroProyectos = Auth::user()->estudiante->no_proyectos;
            switch ($tipoDoc) {
                case 'P':
                    if(Auth::user()->rol_id >2){

                            if(Auth::user()->estudiante->no_proyectos == 2){
                                $gestion = GestionProyecto::where([
                                    ['estudiante_id',Auth::user()->estudiante->id],
                                    ['tipo_gp',$proceso],
                                ])->year($this->anio)->find($request->gestionId);

                            }else{
                                $gestion = GestionProyecto::where([
                                    ['estudiante_id',Auth::user()->estudiante->id],
                                    ['tipo_gp',$proceso],
                                    ['estado','I']
                                ])->year($this->anio)->first();
                            }

                            if($numeroProyectos == 1){
                                if($gestion->horas_a_realizar != $gestion->proyecto->horas_realizar){
                                    $gestion->horas_a_realizar = $gestion->proyecto->horas_realizar;
                                    $gestion->update();
                                }
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
                            if($gestion->estado == 'F')
                             $perfil->setText($gestion->horas_realizadas, 1465, 1210, 20); //Horas a Realizar del proyecto
                            else
                             $perfil->setText($gestion->horas_a_realizar, 1465, 1210, 20); //Horas a Realizar del proyecto
                            $perfil->setText($gestion->fecha_inicio, 135, 1625, 20); //Fecha de Inicio  del proyecto
                            $perfil->setText($gestion->fecha_fin, 400, 1625, 20); //Fecha Fin  del proyecto
                            $perfil->setText($gestion->nombre_supervisor, 650, 1625, 20); //Supervisor del proyecto/Institucion
                            $perfil->setText($gestion->tel_supervisor, 1390, 1625, 20); //Telefono Supervisor del proyecto/Institucion
                            $perfil->setText($numeroFactura[0]->no_factura,1235,1860,20); //Numero de factura de pago de arancel de proceso
                            // Guardando el perfil segun el proceso del estudiante
                            if ($proceso == 1) {$perfil->save(public_path('docs/docs_ss/')."PSS-".$codCarnet);}
                            else{$perfil->save(public_path('docs/docs_pp/')."PPP-".$codCarnet);}

                            return $pdf->download('Perfil Proyecto - '.$codCarnet.'.pdf');
                    }
                break;
                case 'CH':
                    if(Auth::user()->estudiante->no_proyectos == 2){
                        $gestion = GestionProyecto::where([
                            ['estudiante_id',Auth::user()->estudiante->id],
                            ['tipo_gp',$proceso],
                        ])->year($this->anio)->find($request->gestionId);

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
                        ])->year($this->anio)->find($request->gestionId);

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
                        if ($gestion->horas_realizadas > 0) {
                            $control_proy->setText($gestion->horas_realizadas,1425,1125,20);//Horas realizadas
                        }
                        $control_proy->setText($nombreP,150,1100,20);//Nombre del proyecto
                        // Guardando el control de horas segun el proceso del estudiante
                        if ($proceso == 1) {$control_proy->save(public_path('docs/docs_ss/')."CPSS-".$codCarnet);}
                        else{$control_proy->save(public_path('docs/docs_pp/')."CPPP-".$codCarnet);}

                        return $pdf->download('Control Proyecto - '.$codCarnet.'.pdf');
                    }else{
                        return $pdf->download('Control de Proyecto - '.$codCarnet.'.pdf');
                    }
                break;
            }
    }

    // Metodo que elimina un proyecto que estaba realizando el alumno sin contar las horas
    public function deleteProyectoEnMarcha(Request $request){

       $gp = GestionProyecto::year($this->anio)->findOrFail($request->gestionId);
       $estudiante = Estudiante::findOrFail($gp->estudiante_id);
       $estudiante->proceso_actual = 'P';
       $estudiante->update();
       DB::table('documentos_entregados')->where('gestion_proyecto_id',$gp->id)->delete();
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

    public function getFullDataByGestion(Request $request){
        $gestionId = $request->gestionId;

        $data = GestionProyecto::with(['proyecto.institucion','documentos_entrega'])->find($gestionId);
        $documentosId = $data->documentos_entrega->pluck('id');

        $documentos = Documento::whereNotIn('id',$documentosId)->get();
        foreach ($documentos as $value) {
            $value->setAttribute("pivot",(object)[]);
            $data->documentos_entrega->push($value);
        }
        return view('public.detailGestion',compact(['data']));
    }
    /* Funcion que obtiene la fecha minima que iniciara un proyecto */
    public function getMinDateInicio($proyectoId,$process_id){

        $sql = "SELECT MIN(fecha_inicio) as fecha FROM gestion_proyectos WHERE proyecto_id = :idProy AND tipo_gp = :idProceso";
        $fecha_min = DB::select($sql, [ 'idProy' => $proyectoId,'idProceso' => $process_id ]);
        return $fecha_min[0]->fecha;
    }
}
