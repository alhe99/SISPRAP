<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
           //if(Auth::check()){
                $rol = Auth::user()->rol_id;
                if($rol == 2){

                    return redirect()->route('public');
                    //action('ProyectoController@getProjectsByCarrer');
                    //$this->authenticated($request,Auth::user());
                    //return redirect()->action('ProyectoController@getProjectsByCarrer');
                }else if($rol == 1){

                    return redirect()->route('main');
                }
           //}
    
        //return $next($request);
    }
}
