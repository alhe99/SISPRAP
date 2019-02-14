<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Artisan;
use Config;

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
}
