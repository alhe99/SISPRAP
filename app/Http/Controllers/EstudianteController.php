<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estudiante;

class EstudianteController extends Controller
{
    public function store(Request $request)
    {
       if($request->genero == 'F'){

            $estudiante = new Estudiante();
            $estudiante->nombre = $request->nombre;
            $estudiante->fechaNac = $request->fecha;
            $estudiante->genero = 'F';
            $estudiante->telefono = $request->telefono;
            $estudiante->codCarnet = $request->codcarnet;
            $estudiante->password = $request->password;
            $estudiante->email = $request->email;
            $estudiante->direccion = $request->direccion;
            $estudiante->tipo_beca_id = $request->beca_id;
            $estudiante->carrera_id = $request->carrera_id;
            $estudiante->municipio_id = $request->municipio_id;
            $estudiante->no_proyectos = 0;
            $estudiante->estado = 1;
            $estudiante->save();
       }else{
            $estudiante = new Estudiante();
            $estudiante->nombre = $request->nombre;
            $estudiante->fechaNac = $request->fecha;
            $estudiante->genero = 'M';
            $estudiante->telefono = $request->telefono;
            $estudiante->codCarnet = $request->codcarnet;
            $estudiante->password = $request->password;
            $estudiante->email = $request->email;
            $estudiante->direccion = $request->direccion;
            $estudiante->tipo_beca_id = $request->beca_id;
            $estudiante->carrera_id = $request->carrera_id;
            $estudiante->municipio_id = $request->municipio_id;
            $estudiante->no_proyectos = 0;
            $estudiante->estado = 1;
            $estudiante->save();

       }
    }
    public function getStudentById($id){

        $e = Estudiante::findOrFail($id);
        $e->setAttribute('carrer',$e->carrera->nombre);
        $e->setAttribute('direccion',$e->direccion.', '.$e->municipio->nombre.','.$e->municipio->departamento->nombre);
        return $e;
    }
    public function getStudentsToRecepcion(Request $request){

        $carrera_id = $request->carre_id;
        $process_id = $request->proceso_id;
        $buscar = $request->buscar;
        
        if($buscar != ""){
            $estu = Estudiante::with(['carrera','pagoArancel'])->whereHas('preinscripciones', function ($query) {

                $query->where('preinscripciones_proyectos.estado','A')->where('preinscripciones_proyectos.pago_arancel',false);
    
            })->whereHas('proceso', function ($query) use($process_id) {
    
                $query->where('procesos_estudiantes.proceso_id',$process_id);
    
            })->where('carrera_id',$carrera_id)->where('nombre', 'like', '%' . $buscar . '%')->paginate(8);
        }else{
            $estu = Estudiante::with(['carrera','pagoArancel'])->whereHas('preinscripciones', function ($query) {

                $query->where('preinscripciones_proyectos.estado','A')->where('preinscripciones_proyectos.pago_arancel',false);
    
            })->whereHas('proceso', function ($query) use($process_id) {
    
                $query->where('procesos_estudiantes.proceso_id',$process_id);
    
            })->where('carrera_id',$carrera_id)->paginate(8);
        }

         return [
            'pagination' => [
                'total' => $estu->total(),
                'current_page' => $estu->currentPage(),
                'per_page' => $estu->perPage(),
                'last_page' => $estu->lastPage(),
                'from' => $estu->firstItem(),
                'to' => $estu->lastItem(),
            ],
            'estudiante' => $estu,
        ];
        
    }
    public function getStudentsHasPayArancel(Request $request){

        $carrera_id = $request->carre_id;
        $process_id = $request->proceso_id;
        $buscar = $request->buscar;
        
        if($buscar != ""){
            $estu = Estudiante::with(['carrera','pagoArancel','preinscripciones'])->whereHas('preinscripciones', function ($query) {

                $query->where('preinscripciones_proyectos.estado','A')->where('preinscripciones_proyectos.pago_arancel',true);
    
            })->whereHas('proceso', function ($query) use($process_id) {
    
                $query->where('procesos_estudiantes.proceso_id',$process_id);
    
            })->where('carrera_id',$carrera_id)->where('nombre', 'like', '%' . $buscar . '%')->paginate(5);

        }else{
            $estu = Estudiante::with(['carrera','pagoArancel','preinscripciones'])->whereHas('preinscripciones', function ($query) {

                $query->where('preinscripciones_proyectos.estado','A')->where('preinscripciones_proyectos.pago_arancel',true);
    
            })->whereHas('proceso', function ($query) use($process_id) {
    
                $query->where('procesos_estudiantes.proceso_id',$process_id);
    
            })->where('carrera_id',$carrera_id)->paginate(5);
        }

         return [
            'pagination' => [
                'total' => $estu->total(),
                'current_page' => $estu->currentPage(),
                'per_page' => $estu->perPage(),
                'last_page' => $estu->lastPage(),
                'from' => $estu->firstItem(),
                'to' => $estu->lastItem(),
            ],
            'estudiante' => $estu,
        ];
        
    }
}
