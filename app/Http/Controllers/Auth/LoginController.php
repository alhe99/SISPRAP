<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*public function __construct(){
    $this->middleware('guestVerify',['except'=>['showLoginForm','authenticated','login']]);
    }*/

    public function showLoginForm()
    {
        if (Auth::check()) {
            if (session('rol_id') == 3) {

                return redirect()->route('public');

            } else if (session('rol_id') == 1) {

                return redirect()->route('main');
            }
            else if (session('rol_id') == 2) {

                return redirect()->route('main');
            }

        } else {
            return view('auth.login');
        }
    }

    protected function authenticated(Request $request, $user)
    {

        return redirect('/main');
    }

    public function login(Request $request)
    {
        //$this->middleware('roles');

        $this->validate($request, [
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password, 'estado' => 1])) {

            session(['rol_id' => Auth::user()->rol_id]);
            
            if (session('rol_id') == 3) {

                if(count(Auth::user()->estudiante->preinscripciones) != 0){
                    if(Auth::user()->estudiante->preinscripciones[0]->pivot->estado == 'F'){
                        session(['provide_perfil' => true]);
                    }else{
                        session(['provide_perfil' => false]);
                    }
              }
                session(['process_name' => Auth::user()->estudiante->proceso[0]->nombre]);
                session(['process_id' => Auth::user()->estudiante->proceso[0]->id]);
                session(['student_id' => Auth::user()->estudiante->id]);
            
                return redirect()->route('public');

            } else if (session('rol_id') == 1 ) {

                return redirect()->route('main');

            } else if (session('rol_id') == 2 ) {

                return redirect()->route('main');
            }
            
            
        } else {

            return back()->withErrors(['usuario' => trans('auth.failed')])->withInput(request(['usuario']));

        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->forget('rol_id');
        $request->session()->forget('process_name');
        $request->session()->forget('process_id');
        return redirect()->route('showLogin');
    }

}
