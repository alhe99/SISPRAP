<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Documento;
use App\GestionProyecto;

class DocumentoController extends Controller
{
    public function getDocumentsByStudent()
    {
        $doc = Documento::all();
        $data = [];
        $data[0] = [];
        foreach ($doc as $key => $value) {
            $data[$key+1] =[
                'value'   => $value->id,
                'label' => $value->nombre,  
            ];
   
        }
        return  response()->json($data);
    }
    public function addDocToStudent($gp,$doc_id,$obser){
        $e = GestionProyecto::findOrFail($gp);
        $e->documentos_entrega()->attach($doc_id,['observacion'=>$obser,'estado'=>1]);
    }
}
