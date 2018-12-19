<?php
use App\Institucion;
use Illuminate\Database\Seeder;

class InstitucionTableSeeder extends Seeder
{

    public function run()
    {

       // Institucion::truncate();

        for ($i=1; $i < 11 ; $i++){ 

            $institucion = new App\Institucion;
            $institucion->nombre = "InstitucionSS " . $i;
            $institucion->direccion = "Direccion" . $i;
            $institucion->telefono = 223344 .$i;
            $institucion->email = "instiSS".$i."@gmail.com";
            $institucion->sector_institucion_id = 1;
            $institucion->municipio_id = 4;
            $institucion->estado = 1;
            $institucion->save();
            $institucion->procesos()->attach(1);
        }
/*
        for ($i=1; $i < 11 ; $i++){ 

            $institucion = new App\Institucion;
            $institucion->nombre = "InstitucionPP " . $i;
            $institucion->direccion = "Direccion" . $i;
            $institucion->telefono = 789678 .$i;
            $institucion->email = "institiPP".$i."@gmail.com";
            $institucion->sector_institucion_id = 2;
            $institucion->municipio_id = 4;
            $institucion->estado = 1;
            $institucion->save();
            $institucion->procesos()->attach(2);
        }

        

      	for ($i=1; $i < 10 ; $i++){ 

        $institucion = new Institucion();
        $institucion->nombre = 'InstitucionSS '.$i;
        $institucion->direccion = 'Direccion de la institucionSS '.$i;
        $institucion->telefono = '7899675'.$i;
        $institucion->email = 'institucionSS'.$i.'@gmail.com';
        $institucion->sector_institucion_id = 2;
        $institucion->municipio_id = $i;
        $institucion->estado = '1';
        $institucion->save();
        $institucion->procesos()->attach(1);
      }
      for ($i=1; $i < 10 ; $i++){ 

        $institucion = new Institucion();
        $institucion->nombre = 'InstitucionPP '.$i;
        $institucion->direccion = 'Direccion de la institucionPP '.$i;
        $institucion->telefono = '345267'.$i;
        $institucion->email = 'institucionPP'.$i.'@hotmail.com';
        $institucion->sector_institucion_id = 3;
        $institucion->municipio_id = $i;
        $institucion->estado = '1';
        $institucion->save();
        $institucion->procesos()->attach(2);
      }
      */
      /*
            for ($i=1; $i < 101 ; $i++){ 

            Institucion::create([
            'nombre' => 'Empresa '.$i,
            'direccion' => 'Direccion de la empresa '.$i,
            'telefono' => '223478'.$i,
            'email' => 'empresa'.$i.'@hotmail.com',
            'municipio_id' => $i,
            'sector_institucion_id' => 2
            ]);

            }
        for ($i=1; $i < 101 ; $i++){ 

            Institucion::create([
            'nombre' => 'Industria '.$i,
            'direccion' => 'Direccion de la industria '.$i,
            'telefono' => '432216'.$i,
            'email' => 'industria'.$i.'@yahoo.com',
            'municipio_id' => $i,
            'sector_institucion_id' => 3
            ]);
       
        }
        for ($i=1; $i < 101 ; $i++){ 

            Institucion::create([
            'nombre' => 'Alcaldia '.$i,
            'direccion' => 'Direccion de la alcaldia '.$i,
            'telefono' => '687654'.$i,
            'email' => 'alcaldia'.$i.'@ymail.com',
            'municipio_id' => $i,
            'sector_institucion_id' => 4
            ]);
       
        }

        for ($i=1; $i < 101 ; $i++){ 

            Institucion::create([
            'nombre' => 'Restaurante '.$i,
            'direccion' => 'Direccion del resturante '.$i,
            'telefono' => '098765'.$i,
            'email' => 'restaurante'.$i.'SV@yahoo.com',
            'municipio_id' => $i,
            'sector_institucion_id' => 5
            ]);
       
        }
        */
    }
}

