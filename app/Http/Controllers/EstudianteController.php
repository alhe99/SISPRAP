<?php
namespace App\Http\Controllers;

use App\User;
use App\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public $anio;

    public function __construct()
    {
        $this->anio = config('app.app_year');
    }
    //Registrar un estudiante
    public function store(Request $request)
    {
       $estudiante = new Estudiante();
       $estudiante->nombre = $request->nombre;
       $estudiante->apellido = $request->apellido;
       $estudiante->fechaNac = $request->fecha;
         if($request->genero == 'F')
            $estudiante->genero = 'F';
         else
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

    //obtener estudiantes por su id
    public function getStudentById($id){
        $e = Estudiante::findOrFail($id);
        $e->setAttribute('carrer',$e->carrera->nombre);
        $e->setAttribute('direccion',$e->direccion.', '.$e->municipio->nombre.','.$e->municipio->departamento->nombre);
        return $e;
    }

    //apartado de la recepcion, listado de todos los estudiantes en general
    public function getStudentsToRecepcion(Request $request){

        $carrera_id = $request->carre_id;
        $process_id = $request->proceso_id;
        $buscar = $request->buscar;
        if($request->proceso_id == 1)
          $nivelAcad = $request->nivelAcad;
        else
           $nivelAcad = 2;

        $estu = Estudiante::with(['carrera','proceso','pagoArancel' => function($query) use($process_id){
            $query->where('proceso_id',$process_id);
        }])->where('carrera_id',$carrera_id)->whereHas('proceso', function ($query) use($process_id) {
            $query->where([['procesos_estudiantes.proceso_id',$process_id],['procesos_estudiantes.estado',false]]);
        })->where('nivel_academico_id',$nivelAcad)->nombre($buscar)->paginate(10);

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

    //listado de los estudiantes que han pagado el arancel por proceso
    public function getStudentsHasPayArancel(Request $request){

        $carrera_id = $request->carre_id;
        $process_id = $request->proceso_id;
        $buscar = $request->buscar;
        if ($request->proceso_id == 1) {
            $nivelAcad = $request->nivelAcad;
        } else {
            $nivelAcad = 2;
        }

        $estu = Estudiante::with(['carrera','pagoArancel','preinscripciones'])->whereHas('preinscripciones', function ($query) {
            $query->where([['preinscripciones_proyectos.estado','A'],['preinscripciones_proyectos.fecha_registro',$this->anio]]);
        })->whereHas('proceso', function ($query) use($process_id) {
            $query->where('procesos_estudiantes.proceso_id',$process_id)->where('procesos_estudiantes.pago_arancel',true);;
        })->where([['carrera_id',$carrera_id],['nivel_academico_id',$nivelAcad]])->nombre($buscar)->paginate(5);

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

    //Devuelve todos los estudiantes por carrera y su proceso
    public function getStudensByCarrerAndProcess(Request $request){

        $carrera_id = $request->carrera_id;
        $proceso_id = $request->proceso_id;
        $nivelAcad = $request->nivelAcad;
        $nombre = $request->buscar;

        $estudiantes = Estudiante::with(['carrera','nivelAcademico'])->whereHas('carrera', function ($query) use($carrera_id) {
            $query->where('id',$carrera_id);
        })->whereHas('proceso', function ($query) use($proceso_id) {
            $query->where('procesos_estudiantes.proceso_id',$proceso_id);
        })->whereDoesntHave('preinscripciones', function ($query) {
            $query->where('preinscripciones_proyectos.estado','A')->orWhere('preinscripciones_proyectos.estado','F');
        })->where([['nivel_academico_id',$nivelAcad],['proceso_actual','P']])->nombre($nombre)->paginate(5);
        return [
            'pagination' => [
                'total' => $estudiantes->total(),
                'current_page' => $estudiantes->currentPage(),
                'per_page' => $estudiantes->perPage(),
                'last_page' => $estudiantes->lastPage(),
                'from' => $estudiantes->firstItem(),
                'to' => $estudiantes->lastItem(),
            ],
            'estudiantes' => $estudiantes,
        ];
    }
    public function getEstudianteToOtherOpctions(Request $request){
        $carrera_id = $request->carrera_id;
        $nivelAcad = $request->nivelAcad;
        $nombre = $request->buscar;

        $estudiantes = Estudiante::with(['carrera', 'nivelAcademico'])->whereHas('carrera', function ($query) use ($carrera_id) {
            $query->where('id', $carrera_id);
        })->whereNull('estado_pp')->where([['nivel_academico_id', $nivelAcad],['estado',true]])->nombre($nombre)->paginate(10);

        return [
            'pagination' => [
                'total' => $estudiantes->total(),
                'current_page' => $estudiantes->currentPage(),
                'per_page' => $estudiantes->perPage(),
                'last_page' => $estudiantes->lastPage(),
                'from' => $estudiantes->firstItem(),
                'to' => $estudiantes->lastItem(),
            ],
            'estudiantes' => $estudiantes,
        ];
    }
    public function getEstudianteToOtherOpctionsDesactivados(Request $request){
   
        $carrera_id = $request->carrera_id;
        $nivelAcad = $request->nivelAcad;
        $nombre = $request->buscar;

        $estudiantes = Estudiante::with(['carrera', 'nivelAcademico'])->whereHas('carrera', function ($query) use ($carrera_id) {
            $query->where('id', $carrera_id);
        })->whereNull('estado_pp')->where([['nivel_academico_id', $nivelAcad],['estado',false]] )->nombre($nombre)->paginate(5);

        return [
            'pagination' => [
                'total' => $estudiantes->total(),
                'current_page' => $estudiantes->currentPage(),
                'per_page' => $estudiantes->perPage(),
                'last_page' => $estudiantes->lastPage(),
                'from' => $estudiantes->firstItem(),
                'to' => $estudiantes->lastItem(),
            ],
            'estudiantes' => $estudiantes,
        ];
    }
    public function changeNivel(Request $request){
        $estudianteId = $request->estudiante_id;
        $newNivelId = $request->newNivel;

        $estudiante = Estudiante::find($estudianteId);
        $estudiante->nivel_academico_id = $newNivelId;
        $estudiante->update();
    }
    public function desactivarEstudiante($estudiante_id){
        $estudiante = Estudiante::find($estudiante_id);
        $usuario = User::find($estudiante_id);
        $estudiante->estado = false;
        $usuario->estado = false;
        $estudiante->update();
        $usuario->update();
    }
    public function activarEstudiante($estudiante_id)
    {
        $estudiante = Estudiante::find($estudiante_id);
        $usuario = User::find($estudiante_id);
        $estudiante->estado = true;
        $usuario->estado = true;
        $estudiante->update();
        $usuario->update();
    }
}
