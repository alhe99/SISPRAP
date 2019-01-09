<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estudiante;

class EstudianteController extends Controller
{
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

    $estu = Estudiante::with(['carrera','pagoArancel','proceso'])->whereHas('proceso', function ($query) use($process_id) {
        $query->where('procesos_estudiantes.proceso_id',$process_id);
    })->orWhereHas('pagoArancel', function ($query) use($process_id) {
        $query->where('pago_aranceles.proceso_id',$process_id);
    })->where('carrera_id',$carrera_id)->nombre($buscar)->paginate(8);


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


    $estu = Estudiante::with(['carrera','pagoArancel','preinscripciones'])->whereHas('preinscripciones', function ($query) {

        $query->where('preinscripciones_proyectos.estado','A');

    })->whereHas('proceso', function ($query) use($process_id) {

        $query->where('procesos_estudiantes.proceso_id',$process_id)->where('procesos_estudiantes.pago_arancel',true);;

    })->where('carrera_id',$carrera_id)->nombre($buscar)->paginate(5);

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
public function getStudensByCarrerAndProcess(Request $request){

    $carrera_id = $request->carrera_id;
    $proceso_id = $request->proceso_id;
    $nombre = $request->buscar;

    $estudiantes = Estudiante::with(['carrera','nivelAcademico'])->whereHas('carrera', function ($query) use($carrera_id) {

        $query->where('id',$carrera_id);

    })->whereHas('proceso', function ($query) use($proceso_id) {

        $query->where('procesos_estudiantes.proceso_id',$proceso_id);

    })->whereDoesntHave('preinscripciones', function ($query) {

        $query->where('preinscripciones_proyectos.estado','A')->where('preinscripciones_proyectos.proyecto_id',0);

    })->nombre($nombre)->paginate(5);

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
}
