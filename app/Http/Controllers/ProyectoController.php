<?php

namespace App\Http\Controllers;

use App\Proyecto;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Database\Eloquent\Collection;
use App\Notifications\NotifyPreRegisterProject;
use App\Notifications\NotifyAcceptProjectToStudent;
use App\Notifications\NotifyStudentGoToRecep;

class ProyectoController extends Controller
{
    public function __construct()
    {

        //$this->middleware('guestVerify');

    }
    public function index(Request $request)
    {
        //if (!$request->ajax()) 
        //return redirect('/');

        $buscar = $request->buscar;
        $proceso = $request->proceso;

        if ($buscar == '') {
          
            $proyecto = Proyecto::distinct('nombre')->with(['institucion', 'tipoProceso','carre_proy'])
                ->whereHas('tipoProceso', function ($query) use ($proceso) {
                    $query->where('proceso_id', $proceso);
                })->where('proyectos.estado', '1')->orderBy('proyectos.id', 'desc')->paginate(10);

        } else {

            $proyecto = Proyecto::distinct('nombre')->with(['institucion', 'tipoProceso','carre_proy'])
                ->whereHas('tipoProceso', function ($query) use ($proceso) {
                    $query->where('proceso_id', $proceso);
            })->where('proyectos.nombre', 'like', '%' . $buscar . '%')->where('proyectos.estado', '1')
            ->orderBy('proyectos.id', 'desc')->paginate(10);

            //

        }
        
        
       // return $proyecto;

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

    public function store(Request $request)
    {
        $date = Carbon::now();
        $date = $date->format('Y-m-d');

        switch ($request->proceso_id) {
            case 1:
                $img_recv = $request->imagen;
                $proyecto = new Proyecto();
                $proyecto->nombre = $request->nombre;
                $proyecto->fecha = Carbon::parse($date);
                $proyecto->actividades = $request->actividadSS;
                $proyecto->institucion_id = $request->institucion_id;
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
    }
    public function update(Request $request)
    {
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        switch ($request->proceso_id) {
            case 1:
                $proyecto = Proyecto::findOrFail($request->id);
                $img_recv = $request->imagen;
                $proyecto->nombre = $request->nombre;
                $proyecto->descripcion = $request->descripcion;
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
                $proyecto->descripcion = $request->descripcion;
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
    }
    public function GetProyectos($id)
    {
        $proceso = $id;
        $proyecto = Proyecto::select('id', 'nombre')->whereHas('tipoProceso', function ($query) use ($proceso) {
            $query->where('proceso_id', $proceso);
        })->where('proyectos.estado', '1')->orderBy('proyectos.id', 'desc')->get();

        $data = [];
        foreach ($proyecto as $key => $value) {
            $data[$key] = [
                'value' => $value->id,
                'label' => $value->nombre,
            ];

        }
        return response()->json($data);
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $proyecto = Proyecto::findOrFail($request->id);
        $proyecto->estado = '0';
        $proyecto->save();
    }
    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $proyecto = Proyecto::findOrFail($request->id);
        $proyecto->estado = '1';
        $proyecto->save();
    }
    public function getProyDes(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $proceso = $request->proceso;
        if ($buscar == '') {

            $proyecto = Proyecto::whereHas('tipoProceso', function ($query) use ($proceso) {
                $query->where('proceso_id', $proceso);
            })->where('proyectos.estado', '=', '0')->orderBy('proyectos.id', 'desc')->paginate(5);

        } else {

            $proyecto = Proyecto::whereHas('tipoProceso', function ($query) use ($proceso) {
                $query->where('proceso_id', $proceso);
            })->where('proyectos.nombre', 'like', '%' . $buscar . '%')->where('proyectos.estado', '=', '0')->orderBy('proyectos.id', 'desc')->paginate(5);
        }

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

    public function getProjectsByCarrer(Request $request)
    {
        $carre_id = $request->estudent_carrer;
        $tp = $request->estudent_process;
        $pre_register = Auth::user()->estudiante->preinscripciones;
        $buscar = $request->buscar;

        if ($tp == 1) {

            if($buscar != ""){
                if(collect($pre_register)->isNotEmpty()){

                    $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($tp) {
                        $query->where('proceso_id', $tp);
                    })->where('proyectos.nombre', 'like', '%' . $buscar . '%')->orderby('id', 'desc')->where('estado',1)->get();
                    
                        for ($i=0; $i < count($pre_register) ; $i++) { 
    
    
                           $proyectos = $proyectos->except([$pre_register[$i]->id]);
    
                         }
                                    
                }else{
    
                    $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($tp) {
                        $query->where('proceso_id', $tp);
                    })->where('proyectos.nombre', 'like', '%' . $buscar . '%')->orderby('id', 'desc')->where('estado',1)->get();
    
                }
            }else{
                if($buscar != ""){
                    if(collect($pre_register)->isNotEmpty()){

                        $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($tp) {
                            $query->where('proceso_id', $tp);
                        })->where('proyectos.nombre', 'like', '%' . $buscar . '%')->orderby('id', 'desc')->where('estado',1)->get();
                        
                            for ($i=0; $i < count($pre_register) ; $i++) { 
        
        
                               $proyectos = $proyectos->except([$pre_register[$i]->id]);
        
                             }
                                        
                    }else{
        
                        $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($tp) {
                            $query->where('proceso_id', $tp);
                        })->where('proyectos.nombre', 'like', '%' . $buscar . '%')->orderby('id', 'desc')->where('estado',1)->get();
        
                    }
                }else{
                    if(collect($pre_register)->isNotEmpty()){

                        $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($tp) {
                            $query->where('proceso_id', $tp);
                        })->orderby('id', 'desc')->where('estado',1)->get();
                        
                            for ($i=0; $i < count($pre_register) ; $i++) { 
        
        
                               $proyectos = $proyectos->except([$pre_register[$i]->id]);
        
                             }
                                        
                    }else{
        
                        $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($tp) {
                            $query->where('proceso_id', $tp);
                        })->orderby('id', 'desc')->where('estado',1)->get();
        
                    }
                }
                
            }

        } else if ($tp == 2) {

            if(collect($pre_register)->isNotEmpty()){

                $proyectos = Proyecto::with(["carre_proy", "tipoProceso", "institucion"])->whereHas('carre_proy', function ($query) use ($carre_id) {
                    $query->where('carrera_id', $carre_id);
                })->orderby('id', 'desc')->where('estado',1)->get();

                      for ($i=0; $i < count($pre_register) ; $i++) { 

                        $proyectos = $proyectos->except([$pre_register[$i]->id]);

                    }
         
            }else{

                $proyectos = Proyecto::with(["carre_proy", "tipoProceso", "institucion"])->whereHas('carre_proy', function ($query) use ($carre_id) {
                    $query->where('carrera_id', $carre_id);
                })->orderby('id', 'desc')->where('estado',1)->get();

             }

        }

              
                $projects_ids = $proyectos->pluck('id');

                $result_paginate = Proyecto::whereIn('id', $projects_ids)->orderby('id', 'desc')->paginate(9);

        

            return $result_paginate;

    }

    public function getProjectsByProcess(Request $request)
    {
        $process_id = $request->process_id;
        $carre_id = $request->carre_id;
        $data = [];
        $data[0] = [];

        if ($process_id == 1) {

            $proyectos = Proyecto::with(["tipoProceso", "institucion"])->whereHas('tipoProceso', function ($query) use ($process_id) {
                 $query->where('proceso_id', $process_id);
            })->orderby('id','desc')->where('estado',1)->get();
                        

        } else if ($process_id == 2){

            $proyectos = Proyecto::with(["carre_proy", "tipoProceso", "institucion"])->whereHas('carre_proy', function ($query) use ($carre_id) {
                $query->where('carrera_id', $carre_id);
            })->orderby('id', 'desc')->where('estado',1)->get();

        }
       
        foreach ($proyectos as $key => $value) {
            $data[$key+1] =[
                'value'   => $value->id,
                'label' => $value->nombre,  
            ];
   
        }
        return response()->json($data);
    }
    

    public function getProjectBySlug($process, $slug)
    {

        if ($process == 1) {

            $proyecto = Proyecto::with(["tipoProceso", "institucion.sectorInstitucion"])->where('proceso_id', $process)->where('slug',$slug)->firstOrFail();

        } else if ($process == 2) {

            $proyecto = Proyecto::with(["carre_proy", "tipoProceso", "institucion.sectorInstitucion"])->where('proceso_id', $process)->where('slug',$slug)->firstOrFail();

        }

        return view('public.viewProject', compact("proyecto"));
        //return $proyecto;
    }

    public function preRegistrationProject($estudent_id, $project_id)
    {
        $proyect = Proyecto::findOrFail($project_id);
        $proyect_name = $proyect->nombre;
        $fechaActual= date('Y-m-d');
        try {
                DB::beginTransaction(); 
            if (!$proyect->preRegistration()->attach($estudent_id)) {
               
                //$count_pre = $proyect->preRegistration()->whereDate('created_at', $fechaActual)->count();
                $count_pre = $proyect->preRegistration()->count();
                
                $arrayData = [ 
                            'cantidad' => $count_pre, 
                            'msj' =>  "Nueva Preinscripcion al proyecto de: ".$proyect_name,
                            'fecha' => now()->toDateTimeString(),
                ];
                User::FindOrFail(0)->notify(new NotifyPreRegisterProject($arrayData));

                DB::commit();
                return "true";
            } else {return "false";} 
       
        } catch (Exception $e) {
          DB::rollBack();
        }

        return $admin = User::Find(0);

    }
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
    
    public function getPreregisterProjects($estudent_id,$process_id){

        $proyectos = Proyecto::with(['institucion', 'tipoProceso','preRegistration'])
        ->whereHas('tipoProceso', function ($query) use ($process_id) {
            $query->where('proceso_id', $process_id);
        }) ->whereHas('preRegistration', function ($query) use ($estudent_id) {
            $query->where('estudiante_id', $estudent_id)->where('preinscripciones_proyectos.estado','!=','F');
        })->paginate(5);

        return view('public.myProjects', compact("proyectos"));
    }
    public function deletePreRegistration($estudent_id, $project_id){

        $proyect = Proyecto::findOrFail($project_id);
        if ($proyect->preRegistration()->detach($estudent_id)) {
            return "true";
        } else {
            return "false";
        }
    }
    public function rechazPreregistration($estudent_id,$project_id){

        DB::table('preinscripciones_proyectos')->where('estudiante_id', $estudent_id)->
        where('proyecto_id',$project_id)->update(array('estado' => 'R'));
    }
    public function aceptarPreregistration($estudent_id,$project_id){

        if(DB::table('preinscripciones_proyectos')->where('estudiante_id', $estudent_id)->
           where('proyecto_id',$project_id)->update(array('estado' => 'A'))){

            DB::table('preinscripciones_proyectos')->where('estudiante_id', $estudent_id)->where('estado','P')->delete();
        }

        $p = Proyecto::findOrFail($project_id);

        $arrayData = [ 
                'msj' =>  "Tu solictud al proyecto de ".$p->nombre." ha sido procesada, el siguiente paso es que apertures el expediente de tu proceso en recepción",
                'fecha' => now()->toDateTimeString(),
        ];
        
        User::FindOrFail($estudent_id)->notify(new NotifyStudentGoToRecep($arrayData));

    }
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
    public function ifProjectExist($nombre){
        
        if(Proyecto::where('nombre',$nombre)->where('proceso_id',1)->first()){
            return "true";
        }            
        else{
            return "false";
        }
            
    }
}
