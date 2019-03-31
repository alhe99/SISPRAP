<?php

use App\Alumno;
use App\Estudiante;
use App\User;
use Illuminate\Database\Seeder;

class CreateEstudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$alumno = Alumno::whereHas('aspirante',function($query){
    		$query->where('articulado',true);
    	})->get();

		    foreach ($alumno as $value) {
		        $estudiante = Estudiante::where([
		        	['nombre',$value->aspirante->nombres],
		        	['apellido',$value->aspirante->apellidos],
		        	['codCarnet',$value->aspirante->codigo]
		        ])->first();

		        $usuario = User::find($estudiante->id);
		        $usuario->delete();

		        $estudiante->proceso()->detach(1);
		        $estudiante->delete();


		        $e = new Estudiante;
		        $e->nombre = $value->aspirante->nombres;
		        $e->apellido = $value->aspirante->apellidos;
		        $e->fechaNac = $value->fecha_nac;
		        $e->genero = substr($value->genero, 0, 1);
		        $e->telefono = $value->celular;
		        $e->codCarnet = $value->aspirante->codigo;--
		        $e->email = $value->correo;
		        $e->direccion = $value->direccion;
		        $e->estado = true;
		        $e->nivel_academico_id = $value->aspirante->articulado ? 2 : 1;
		        $e->carrera_id = $value->id_carrera;
		        $e->municipio_id = $value->id_municipio;
		        $e->articulado = $value->aspirante->articulado ? true : false;
		        $e->supero_limite = 0;
		        $e->password = bcrypt($value->aspirante->password);
		        $e->foto_name = $value->foto;

		        $e->no_proyectos = 0;
		        $e->ultimo_cambio = date('Y-m-d');
		        $e->tipo_beca_id = $value->aspirante->id_beca == 2 ? '1' : '2';
			
		        $e->proceso_actual = 'P';
		        $e->fecha_registro = date('Y-m-d');
		        $e->save();

		        $e->proceso()->attach(1, array('num_horas' => 300, 'fecha_registro' => date('Y')));

		    }
    }
}
