<?php
use App\Proyecto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProyectosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        //Proyecto::truncate();
        for ($i = 1; $i < 10; $i++) {

            $proyecto = new App\Proyecto;
            $proyecto->nombre = "Test Project SS " . $i;
            $proyecto->fecha = Carbon::parse($date);
            $proyecto->actividades = "Enim amet nulla veniam esse nulla id magna aliquip dolor. Incididunt id sint do ullamco cupidatat. Ut ea eiusmod minim officia ipsum.
            Ullamco id aliqua ullamco eiusmod irure tempor nisi adipisicing consequat. Aute labore ad est proident duis nostrud. Ad ea laboris amet esse labore magna cillum minim. Incididunt cupidatat esse ea velit eu.";
            $proyecto->institucion_id = 1;
            $proyecto->proceso_id = 1;
            $proyecto->img = "computacion.jpg";
            $proyecto->estado = 1;
            $proyecto->save();
            //$proyecto->procesos()->sync(1);
        }

        for ($i = 1; $i < 10; $i++) {

            $proyecto = new App\Proyecto;
            $proyecto->nombre = "Test Project PP " . $i;
            $proyecto->fecha = Carbon::parse($date);
            $proyecto->actividades = "Laboris laboris aute commodo quis est. Aliquip dolore magna aute eu ad amet id esse. Quis cupidatat cillum magna quis enim adipisicing nulla id ea.
            Nisi esse sint nostrud quis pariatur commodo ea dolore est. Sit tempor incididunt ex fugiat cupidatat non duis incididunt enim aliqua non dolore. Exercitation minim dolore sit consectetur commodo eiusmod aute anim nostrud ad veniam. Ipsum eiusmod consectetur qui Lorem officia ullamco sunt nisi laborum sit aliquip. Occaecat incididunt exercitation nostrud tempor enim esse. Aliqua ea veniam proident esse culpa nostrud ea.
            Sint pariatur nisi sit consectetur officia proident non. Incididunt cupidatat ipsum voluptate dolor proident dolore cupidatat. Duis deserunt et incididunt exercitation.
            Sunt occaecat exercitation minim minim est tempor nostrud. Duis reprehenderit mollit tempor ex in. Pariatur veniam id nisi incididunt magna dolor amet sunt exercitation irure cillum aliquip.";

            $proyecto->institucion_id = 1;
            $proyecto->proceso_id = 2;
            $proyecto->img = "computacion.jpg";
            $proyecto->estado = 1;
            $proyecto->save();
            
            $proyecto->carre_proy()->attach(3);
            
            //$proyecto->procesos()->sync(1);
        }
    }
}
