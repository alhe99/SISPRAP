<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProyectoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    //listado de los proyectos que el estudiante se ha preinscrito, tomando la informacion de la carrera del estudiante y su proceso
    public function index(Request $request2)
    {

        $userCarrer = Auth::user()->estudiante->carrera_id;
        $userProcess = Auth::user()->estudiante->proceso[0]->id;
        $request = new Request();
        $request->setMethod('POST');
        $request->request->add(['estudent_carrer' => $userCarrer]);
        $request->request->add(['estudent_process' => $userProcess]);
        $request->request->add(['buscar' => $request2->buscar]);
        $data_search = $request2->buscar;
        // app(\App\Http\Controllers\GestionProyectoController::class)->getActualGestionProyectos();
        return view('public.index', compact(['proyectos','data_search']));
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
