<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Documento;
use App\GestionProyecto;

class DocumentoController extends Controller
{
    public $anio;

    public function __construct()
    {
        $this->anio = config('app.app_year');
    }
    public function getDocumentsByStudent()
    {
        $doc = Documento::all();
        $data = [];
        foreach ($doc as $key => $value) {
            $data[$key] =[
                'value'   => $value->id,
                'label' => $value->nombre,
            ];

        }
        return  response()->json($data);
    }
    public function addDocToStudent(Request $request){

        $arraydoc = explode(',',$request->objDoc);
        $e = GestionProyecto::findOrFail($request->gestionId);
        for ($i=0; $i < count($arraydoc); $i++) {
            $e->documentos_entrega()->attach($arraydoc[$i],['observacion'=>$request->observacion,'estado'=>true,'fecha_registro' => $this->anio]);
        }
    }
}
