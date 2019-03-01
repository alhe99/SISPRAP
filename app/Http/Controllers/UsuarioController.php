<?php

namespace App\Http\Controllers;

use App\User;
use App\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Artisan;
use Config;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
     public function setEnvironmentValue($envKey, $envValue)
     {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $str .= "\n";
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        $str = substr($str, 0, -1);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }
    public function changeYearApp($newYear){
       $this->setEnvironmentValue('APP_YEAR', $newYear);
       exec('php artisan config:cache');
    }
    public function update(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->nombre = $request->nombre;
        $usuario->usuario = $request->usuario;
        if($request->password)
        $usuario->password = bcrypt($request->password);

        $usuario->update();
        return redirect()->back()->with('updateDataUser','Datos Actualizados Correctamente');
    }
    public function getCurrentMonth(){
        $estudiante = Estudiante::where('estado',true)->whereNull('fecha_fin_pp')->select('ultimo_cambio')->limit(1)->get();
        return substr($estudiante[0]->ultimo_cambio,5,2);
    }
    public function changeCurrentMonth(){
        $date = date('Y-m-d');
        DB::table('estudiantes')->where('estado',true)->whereNull('fecha_fin_pp')->update(array('ultimo_cambio' => $date));
    }
    public function desactivarAllProjectsInDB(){
        
    }
    public function index(Request $request){

        $buscar = $request->buscar;
        $usuarios = User::where([['id','!=','0'],['rol_id','1']])->orderBy('id','desc')->usuario($buscar)->paginate(10);
        return [
            'pagination' => [
                'total' => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page' => $usuarios->perPage(),
                'last_page' => $usuarios->lastPage(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios,
        ];
    }
    public function delete($id){
        $usuario = User::find($id);
        $usuario->delete();
    }
    public function create(Request $request){

        $user = new User();
        $user->id = User::max('id') + 80000;
        $user->nombre = $request->nombre;
        $user->usuario = $request->usuario;
        $user->password = bcrypt($request->pass);
        $user->estado = true;
        $user->rol_id = 1;
        $user->fecha_registro = date('Y-m-d');
        $user->save();
    }
}
