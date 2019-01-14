<?php

namespace App\Http\Controllers;

use App\Estudiante;
use App\Jobs\sendNotificationToAdmin;
use App\Notifications\NotifyAcceptProjectToStudent;
use App\Notifications\NotifyPreRegisterProject;
use App\Notifications\NotifyStudentGoToRecep;
use App\Proyecto;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class ProyectoController extends Controller
{
    //listado de proyectos por proceso y busqueda
    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $proceso = $request->proceso;

        $proyecto = Proyecto::with(['institucion', 'tipoProceso','carre_proy'])->whereHas('tipoProceso', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);
        })->nombre($buscar)->where([['proyectos.estado', '1'],['proyectos.tipo_proyecto','I']])
        ->orderBy('proyectos.id', 'desc')->paginate(10);

        return [
            'pagination' => [
                'total' => $proyecto->total(),
                'current_page' => $proyecto->currentPage(),
                'per_page' => $proyecto->perPage(),
                'last_page' => $proyecto->lastPage(),
                'from' => $proyecto->firstItem(),
                'to' => $proyecto->lastItem(),
            ],
            'proyecto' => $proyecto,
        ];
    }

    //Publicacion de proyectos verificando proceso y tipo de proyecto
    public function store(Request $request)
    {
        $date = date('Y-m-d');
        switch ($request->tipoProyecto) {
            case 'I':
            switch ($request->proceso_id) {
                case 1:
                $img_recv = $request->imagen;
                $proyecto = new Proyecto();
                $proyecto->nombre = $request->nombre;
                $proyecto->fecha = Carbon::parse($date);
                $proyecto->actividades = $request->actividadSS;
                $proyecto->institucion_id = $request->institucion_id;
                $proyecto->horas_realizar = $request->horas;
                $proyecto->cantidades_vacantes = $request->cantidadAlumnos;
                $proyecto->tipo_proyecto = 'I';
                $proyecto->proceso_id = 1;
                if ($img_recv) {
                    $name_img = Carbon::now()->format('Y-m-d') . 'SS' . uniqid() . '.' . explode('/', explode(':', substr($img_recv, 0, strpos($img_recv, ';')))[1])[1];
                    $proyecto->img = $name_img;
                    Image::make($request->imagen)->save(public_path('/images/img_projects/') . $name_img);
                } else {
                    $proyecto->img = $request->imageG;
                }
                $proyecto->estado = 1;
                $proyecto->save();
                break;
                case 2:
                try {
                    for ($i = 0; $i < count($request->actividades); $i++) {
                        DB::beginTransaction();
                        $obj = $request->actividades[$i];
                        $img_recv = $request->imagen;
                        $proyecto = new Proyecto();
                        $proyecto->nombre = $request->nombre;
                        $proyecto->fecha = Carbon::parse($date);
                        $proyecto->actividades = $obj['actividades'];
                        $proyecto->institucion_id = $request->institucion_id;
                        $proyecto->proceso_id = 2;
                        if ($img_recv) {
                            $name_img = Carbon::now()->format('Y-m-d') . 'PP' . uniqid() . '.' . explode('/', explode(':', substr($img_recv, 0, strpos($img_recv, ';')))[1])[1];
                            $proyecto->img = $name_img;
                            Image::make($request->imagen)->save(public_path('/images/img_projects/') . $name_img);
                        } else {
                            $proyecto->img = $request->imageG;
                        }
                        $proyecto->estado = 1;
                        $proyecto->save();

                        $proyecto->carre_proy()->attach($obj['carrera_id']);
                        DB::commit();
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                }
                break;
            }
            break;
            case 'E':
            $proyecto = new Proyecto();
            $proyecto->nombre = $request->nombre;
            $proyecto->fecha = $date;
            $proyecto->actividades = $request->actividadSS;
            $proyecto->institucion_id = $request->institucion_id;
            $proyecto->horas_realizar = $request->horas;
            $proyecto->cantidades_vacantes = null;
            $proyecto->tipo_proyecto = 'E';
            $proyecto->proceso_id = $request->proceso_id;
            $proyecto->estado = 1;
            $proyecto->año_registro = date('Y');
            $proyecto->save();
            break;
        }
    }

    //actualizacion de proyecto
    public function update(Request $request)
    {
        $date = date('Y-m-d');
        switch ($request->tipoProyecto) {
            case 'I':
            switch ($request->proceso_id) {
                case 1:
                $proyecto = Proyecto::findOrFail($request->id);
                $img_recv = $request->imagen;
                $proyecto->nombre = $request->nombre;
                $proyecto->fecha = Carbon::parse($date);
                $proyecto->actividades = $request->actividades;
                $proyecto->institucion_id = $request->institucion_id;
                $proyecto->estado = $request->estado;
                if ($img_recv) {
                    $name_img = Carbon::now()->format('Y-m-d') . 'PP' . uniqid() . '.' . explode('/', explode(':', substr($img_recv, 0, strpos($img_recv, ';')))[1])[1];
                    $proyecto->img = $name_img;
                    Image::make($request->imagen)->save(public_path('images_proyect/') . $name_img);
                } else {
                    $proyecto->img = $request->imagenG;
                }
                $proyecto->proceso_id = 1;
                $proyecto->save();
                break;
                case 2:
                $proyecto = Proyecto::findOrFail($request->id);
                $img_recv = $request->imagen;
                $proyecto->nombre = $request->nombre;
                $proyecto->fecha = Carbon::parse($date);
                $proyecto->actividades = $request->actividades;
                $proyecto->institucion_id = $request->institucion_id;
                $proyecto->estado = $request->estado;
                if ($img_recv) {
                    $name_img = Carbon::now()->format('Y-m-d') . 'PP' . uniqid() . '.' . explode('/', explode(':', substr($img_recv, 0, strpos($img_recv, ';')))[1])[1];
                    $proyecto->img = $name_img;
                    Image::make($request->imagen)->save(public_path('images_proyect/') . $name_img);
                } else {
                    $proyecto->img = $request->imagenG;
                }
                $proyecto->proceso_id = 2;
                $proyecto->save();
                break;
            }
            break;
            case 'E':
            $proyecto = Proyecto::findOrFail($request->id);
            $proyecto->nombre = $request->nombre;
            $proyecto->actividades = $request->actividades;
            $proyecto->institucion_id = $request->institucion_id;
            $proyecto->horas_realizar = $request->horasRealizar;
            $proyecto->proceso_id = $request->procesoId;
            $proyecto->tipo_proyecto = 'E';
            $proyecto->update();
            break;
        }
    }

    //listado de proyectos por proceso y id
    public function GetProyectos($id)
    {
        $proceso = $id;
        $proyecto = Proyecto::select('id', 'nombre')->proceso($proceso)->where('proyectos.estado', '1')->orderBy('proyectos.id', 'desc')->get();
        $data = [];
        foreach ($proyecto as $key => $value) {
            $data[$key] = [
                'value' => $value->id,
                'label' => $value->nombre,
            ];

        }
        return response()->json($data);
    }

    //cambiar de estado para desactivar el proyecto
    public function desactivar(Request $request)
    {
        if (!$request->ajax())
         return redirect('/');
     $proyecto = Proyecto::findOrFail($request->id);
     $proyecto->estado = '0';
     $proyecto->save();
 }

   //cambiar de estado para activar el proyecto
 public function activar(Request $request)
 {
    if (!$request->ajax())
        return redirect('/');
    $proyecto = Proyecto::findOrFail($request->id);
    $proyecto->estado = '1';
    $proyecto->save();
}

//obtener proyectos desactivados INTERNOS
public function getProyDes(Request $request)
{
    $buscar = $request->buscar;
    $proceso = $request->proceso;

    $proyecto = Proyecto::whereHas('tipoProceso', function ($query) use ($proceso) {
        $query->where('proceso_id', $proceso);
    })->where([['proyectos.estado','0'],['proyectos.tipo_proyecto','I']])->nombre($buscar)->orderBy('proyectos.id', 'desc')->paginate(5);

    return [
        'pagination' => [
            'total' => $proyecto->total(),
            'current_page' => $proyecto->currentPage(),
            'per_page' => $proyecto->perPage(),
            'last_page' => $proyecto->lastPage(),
            'from' => $proyecto->firstItem(),
            'to' => $proyecto->lastItem(),
        ],
        'proyecto' => $proyecto,
    ];

}

// Obtener proyectos desactivados EXTERNOS
public function getProyDesExternos(Request $request)
{
    $buscar = $request->buscar;
    $proceso = $request->proceso;

    $proyecto = Proyecto::whereHas('tipoProceso', function ($query) use ($proceso) {
        $query->where('proceso_id', $proceso);
    })->where([['proyectos.estado','0'],['proyectos.tipo_proyecto','E']])->nombre($buscar)->orderBy('proyectos.id', 'desc')->paginate(5);

    return [
        'pagination' => [
            'total' => $proyecto->total(),
            'current_page' => $proyecto->currentPage(),
            'per_page' => $proyecto->perPage(),
            'last_page' => $proyecto->lastPage(),
            'from' => $proyecto->firstItem(),
            'to' => $proyecto->lastItem(),
        ],
        'proyectos' => $proyecto,
    ];

}

//listado de proyectos en base a su carrera y actividades por carrera
public function obtenerProyecto(Request $request)
{

    $proy = Proyecto::findOrFail($request->id);
    $proyecto = Proyecto::where("nombre", $proy->nombre)->select("actividades", "id")->get();
    $carreActvidad = Proyecto::where("nombre", $proy->nombre)->with(["carre_proy"])->select("id")->get();
    for ($i = 0; $i < count($proyecto); $i++) {
        $proyecto[$i]->setAttribute("Carrera", $carreActvidad[$i]["carre_proy"][0]["nombre"]);
    }
    return $proyecto;
}

//Funcion que obtiene los proyectos deacuerdo ala carerra del alumno logeado y verifica si ya se preisncribio a ese proyecto no lo muestra
public function getProjectsByCarrer(Request $request)
{
    $carre_id = $request->estudent_carrer;
    $tp = $request->estudent_process;
    $pre_register = Auth::user()->estudiante->preinscripciones;
    $buscar = $request->buscar;

    if ($tp == 1) {

        if(collect($pre_register)->isNotEmpty()){

            $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($tp) {
                $query->where('proceso_id', $tp);
            })->nombre($buscar)->orderby('id', 'desc')->where([['estado',1],['estado_vacantes','D'],['proyectos.tipo_proyecto','I']])->get();

            for ($i=0; $i < count($pre_register) ; $i++) {
             $proyectos = $proyectos->except([$pre_register[$i]->id]);
         }

     }else{

        $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($tp) {
            $query->where('proceso_id', $tp);
        })->nombre($buscar)->orderby('id', 'desc')->where([['estado',1],['estado_vacantes','D'],['proyectos.tipo_proyecto','I']])->get();

    }


} else if ($tp == 2) {

    if(collect($pre_register)->isNotEmpty()){

        $proyectos = Proyecto::with(["carre_proy", "tipoProceso", "institucion"])->whereHas('carre_proy', function ($query) use ($carre_id) {
            $query->where('carrera_id', $carre_id);
        })->nombre($buscar)->orderby('id', 'desc')->where([['estado',1],['estado_vacantes','D'],['proyectos.tipo_proyecto','I']])->get();

        for ($i=0; $i < count($pre_register) ; $i++) {

            $proyectos = $proyectos->except([$pre_register[$i]->id]);

        }

    }else{

        $proyectos = Proyecto::with(["carre_proy", "tipoProceso", "institucion"])->whereHas('carre_proy', function ($query) use ($carre_id) {
            $query->where('carrera_id', $carre_id);
        })->nombre($buscar)->orderby('id', 'desc')->where([['estado',1],['estado_vacantes','D'],['proyectos.tipo_proyecto','I']])->get();

    }

}
$projects_ids = $proyectos->pluck('id');
$result_paginate = Proyecto::whereIn('id', $projects_ids)->orderby('id', 'desc')->paginate(9);
return $result_paginate;

}

//listado de proyectos por proceso
public function getProjectsByProcess(Request $request)
{
    $process_id = $request->process_id;
    $carre_id = $request->carre_id;
    $tipoProyecto = $request->tipoProyecto;
    $data = [];
    $data[0] = [];

    switch ($tipoProyecto) {
        case 'I':
        if ($process_id == 1) {

            $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($process_id) {
             $query->where('proceso_id', $process_id);
           })->select('id','nombre','cantidades_vacantes',DB::raw("(SELECT COUNT(id) FROM preinscripciones_proyectos WHERE proyecto_id = proyectos.id AND estado != 'R') AS solicitudes"))->orderby('id','desc')->where([['estado',1],['proyectos.tipo_proyecto','I']])->get();


        } else if ($process_id == 2){

            $proyectos = Proyecto::with(["carre_proy", "tipoProceso", "institucion"])->whereHas('carre_proy', function ($query) use ($carre_id) {
                $query->where('carrera_id', $carre_id);
            })->select('id','nombre','cantidades_vacantes',DB::raw("(SELECT COUNT(id) FROM preinscripciones_proyectos WHERE proyecto_id = proyectos.id AND estado != 'R') AS solicitudes"))->orderby('id', 'desc')->where([['estado',1],['proyectos.tipo_proyecto','I']])->get();

        }
        break;
        case 'E':

        $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($process_id) {
           $query->where('proceso_id', $process_id);
       })->orderby('id','desc')->where([['estado',1],['proyectos.tipo_proyecto','E']])->get();

        break;
    }

    foreach ($proyectos as $key => $value) {
        $data[$key+1] =[
            'value'   => $value->id,
            'label' => $value->nombre,
            'vacantes' => $value->cantidades_vacantes,
            'preinscripciones' => $value->solicitudes
        ];

    }
    return response()->json($data);
}

//apartado de la publica, en la verificacion de la url solo se presenta el nombre del proyecto que el alumno ha seleccionado
public function getProjectBySlug($process, $slug)
{
    if ($process == 1) {

        $proyecto = Proyecto::with(["tipoProceso", "institucion.sectorInstitucion"])->where(
            [
                ['proceso_id', $process],
                ['slug',$slug],['estado',1],
                ['proyectos.tipo_proyecto','I']
            ])->firstOrFail();

    } else if ($process == 2) {

        $proyecto = Proyecto::with(["carre_proy", "tipoProceso", "institucion.sectorInstitucion"])->where(
            [
                ['proceso_id', $process],
                ['slug',$slug],['estado',1],
                ['proyectos.tipo_proyecto','I']

            ])->firstOrFail();
    }
    return view('public.viewProject', compact("proyecto"));
}

//apartado de la publica, preinscripcion de proyectos
public function preRegistrationProject($estudent_id, $project_id)
{
    $proyect = Proyecto::findOrFail($project_id);
    $proyect_name = $proyect->nombre;
    $fechaActual= date('Y-m-d');
    try {
        DB::beginTransaction();
        if (!$proyect->preRegistration()->attach($estudent_id)) {

            $count_pre = $proyect->preRegistration()->count();

            $arrayData = [
                'cantidad' => $count_pre,
                'msj' =>  "Nueva Preinscripcion al proyecto de: ".$proyect_name,
                'fecha' => now()->toDateTimeString(),
            ];
            dispatch(new sendNotificationToAdmin($arrayData));
            DB::commit();
            return "true";
        } else {return "false";}

    } catch (Exception $e) {
      DB::rollBack();
  }
  return $admin = User::Find(0);
}

//listado de estudiantes que se han preinscrito a un proyecto especificado
public function getPreregistrationByProject(Request $request)
{
    $proyect = Proyecto::findOrFail($request->project_id);
    $fechaActual= date('Y-m-d');
    $buscar = $request->buscar;

    if($request->project_id != 0)
       if($buscar != "")
           $projects = $proyect->preRegistration()->where('estudiantes.nombre', 'like', '%' . $buscar . '%')->where('preinscripciones_proyectos.estado','P')->where('estudiantes.estado','1')->orderBy('created_at','desc')->paginate(5);
       else
           $projects = $proyect->preRegistration()->where('estudiantes.estado',1)->where('preinscripciones_proyectos.estado','P')->orderBy('created_at','desc')->paginate(5);

       return [
        'pagination' => [
            'total' => $projects->total(),
            'current_page' => $projects->currentPage(),
            'per_page' => $projects->perPage(),
            'last_page' => $projects->lastPage(),
            'from' => $projects->firstItem(),
            'to' => $projects->lastItem(),
        ],
        'projects' => $projects,
    ];

}

    //apartado publico, listado de preinscripciones por estudiante
public function getPreregisterProjects($estudent_id,$process_id){

    $cuenta = Estudiante::whereHas('proceso', function ($query) use ($process_id) {
        $query->where('proceso_id', $process_id);
    })->find($estudent_id)->preinscripciones()->count();

    if($cuenta != 0 or $cuenta != null){
     $proyectos = Estudiante::whereHas('proceso', function ($query) use ($process_id) {
        $query->where('proceso_id', $process_id);
    })->find($estudent_id)->preinscripciones()->paginate(5);
 }else{
    $proyectos = [];
}

return view('public.myProjects', compact("proyectos"));
}

//apartado publico, eliminar preinscripcion por estudiante
public function deletePreRegistration($estudent_id, $project_id){

    $proyect = Proyecto::findOrFail($project_id);
    if ($proyect->preRegistration()->detach($estudent_id)) {
        return "true";
    } else {
        return "false";
    }
}

//rechazar la solicitud del estudiante referente a la preinscripcion realizada
public function rechazPreregistration($estudent_id,$project_id){

    DB::table('preinscripciones_proyectos')->where('estudiante_id', $estudent_id)->
    where('proyecto_id',$project_id)->update(array('estado' => 'R'));
}

//aprobar la preinscripcion del estudiante
public function aceptarPreregistration(Request $request){

    $query =  DB::table('preinscripciones_proyectos')->where('estudiante_id', $request->estudent_id)->where('proyecto_id',$request->project_id)->update(array('estado' => 'A'));

    if($query){
        DB::table('preinscripciones_proyectos')->where([
            ['estudiante_id', $request->estudent_id],
            ['estado','P']
        ])->orWhere('estado','R')->delete();
    }

    $p = Proyecto::findOrFail($request->project_id);
    $arrayData = [
        'msj' =>  "Tu solictud al proyecto de ".$p->nombre." ha sido procesada, el siguiente paso es que apertures el expediente de tu proceso en recepción",
        'fecha' => now()->toDateTimeString(),
    ];
    User::FindOrFail($request->estudent_id)->notify(new NotifyStudentGoToRecep($arrayData));

}

 //Asignacion de proyectos de tipo externo a uno o mas estudiantes del estudiante
public function asignarProyectoExterno(Request $request){

    $arrayEstudiante = explode(',',$request->estudent_id);

    for ($i=0; $i < count($arrayEstudiante); $i++) {

       $proyect = Proyecto::find($request->project_id);
       $proyect->preRegistration()->attach($arrayEstudiante[$i],array('estado' => 'A','año_registro' => date('Y')));

       $estudiante = Estudiante::findOrFail($arrayEstudiante[$i]);
       if ($estudiante->proceso[0]->pago_arancel == 0) {
          $arrayData = [
            'msj' =>  "Se te ha asignado un proyecto,el siguiente paso es que apertures el expediente de tu proceso en recepción",
            'fecha' => now()->toDateTimeString(),
        ];
        User::FindOrFail($request->estudent_id)->notify(new NotifyStudentGoToRecep($arrayData));
    }
}
}

//dar acceso al llenado de perfil del proyecto al estudiante
public function provideAccessToPerfil($estudent_id,$project_id){

    DB::table('preinscripciones_proyectos')->where('estudiante_id', $estudent_id)->
    where('proyecto_id',$project_id)->update(array('estado' => 'F'));
    $p = Proyecto::findOrFail($project_id);
    $arrayData = [
        'msj' =>  "Tu solictud al proyecto de ".$p->nombre."<a class='btn btn-link' href='route{{showPerfilProy}}'> puedes completar el perfil del proyecto ahora</a>",
        'fecha' => now()->toDateTimeString(),
    ];

    User::FindOrFail($estudent_id)->notify(new NotifyPreRegisterProject($arrayData));

        //Enviar Notificacion Aqui
}
// Funcion para obtener los almnos aprobados por proyecto
public function getAllAcepted(Request $request){
    $proyectoId = $request->proyectoId;
    $buscar = $request->buscar;
    $proyectos = Proyecto::find($proyectoId)->preRegistration()->whereHas('preinscripciones',function($query){
        $query->where('preinscripciones_proyectos.estado','A')->orWhere('preinscripciones_proyectos.estado','F');
    })->nombre($buscar)->paginate(10);

    return [
        'pagination' => [
            'total' => $proyectos->total(),
            'current_page' => $proyectos->currentPage(),
            'per_page' => $proyectos->perPage(),
            'last_page' => $proyectos->lastPage(),
            'from' => $proyectos->firstItem(),
            'to' => $proyectos->lastItem(),
        ],
        'proyectos' => $proyectos,
    ];

}
// Funcion para eliminar un proyecto aprobado
public function deleteProyectoAprobado(Request $request){
    $proyectoId = $request->proyectoId;
    $estudianteId = $request->estudianteId;
    $preinscripcion = Estudiante::find($estudianteId)->preinscripciones()->whereHas('preRegistration',function($query){
        $query->where('preinscripciones_proyectos.estado','A')->orWhere('preinscripciones_proyectos.estado','F');
    })->detach();
}
// Funcion que obtiene los proyectos externos
public function getExternalProjects(Request $request){
    $buscar = $request->buscar;
    $proceso = $request->proceso;
    $proyecto = Proyecto::with(['institucion', 'tipoProceso'])->whereHas('tipoProceso', function ($query) use ($proceso) {
        $query->where('proceso_id', $proceso);
    })->nombre($buscar)->where([['proyectos.estado', '1'],['proyectos.tipo_proyecto','E']])
    ->orderBy('proyectos.id', 'desc')->paginate(10);

    return [
        'pagination' => [
            'total' => $proyecto->total(),
            'current_page' => $proyecto->currentPage(),
            'per_page' => $proyecto->perPage(),
            'last_page' => $proyecto->lastPage(),
            'from' => $proyecto->firstItem(),
            'to' => $proyecto->lastItem(),
        ],
        'proyectos' => $proyecto,
    ];
}
public function ifProjectExist($nombre){
    if(Proyecto::where('nombre',$nombre)->where('proceso_id',1)->first()){
        return "true";
    }
    else{
        return "false";
    }
}
}
