<?php

use App\Carrera;
use Illuminate\Database\Seeder;

class CarrerasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Carrera::truncate();

        $carrera = new App\Carrera;
        $carrera->nombre = "Técnico en Agroindustria ";
        $carrera->articulado = 0;
        $carrera->save();

        $carrera2 = new App\Carrera;
        $carrera2->nombre = "Técnico en Ingeniería de Desarrollo de Software ";
        $carrera2->articulado = 1;
        $carrera2->save();

        $carrera3 = new App\Carrera;
        $carrera3->nombre = "Técnico en Ingeniería de Computación ";
        $carrera3->articulado = 1;
        $carrera3->save();

        $carrera4 = new App\Carrera;
        $carrera4->nombre = "Técnico en Mantenimiento de Computadoras";
        $carrera4->articulado = 1;
        $carrera4->save();

        $carrera5 = new App\Carrera;
        $carrera5->nombre = "Técnico de Mercadeo ";
        $carrera5->articulado = 1;
        $carrera5->save();

        $carrera6 = new App\Carrera;
        $carrera6->nombre = "Técnico en Géstion de Turismo Alternativo ";
        $carrera6->articulado = 0;
        $carrera6->save();

        $carrera7 = new App\Carrera;
        $carrera7->nombre = "Técnico en Ingeniería Civil ";
        $carrera7->articulado = 0;
        $carrera7->save();

        $carrera8 = new App\Carrera;
        $carrera8->nombre = "Técnico en Ingeniería Eléctrica ";
        $carrera8->articulado = 1;
        $carrera8->save();
        
    }
}
