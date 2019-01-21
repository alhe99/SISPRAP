<?php

namespace App\Http\Controllers;

use App\SectorInstitucion;
use Illuminate\Http\Request;

class SectorInstitucionController extends Controller
{

    //listado de los sectores registrados
    public function index(Request $request)
    {

            if (!$request->ajax()) return redirect('/');
            $buscar = $request->buscar;
            if($buscar==''){

                $sector = SectorInstitucion::orderBy('id','desc')->paginate(5);

            }else{

                $sector = SectorInstitucion::where('sector','like','%'.$buscar.'%')->orderBy('id','desc')->paginate(5);
            }

            return [
                'pagination' => [
                    'total'        => $sector->total(),
                    'current_page' => $sector->currentPage(),
                    'per_page'     => $sector->perPage(),
                    'last_page'    => $sector->lastPage(),
                    'from'         => $sector->firstItem(),
                    'to'           => $sector->lastItem(),
                ],
                'sectores' => $sector
            ];
    }

    //registrar sector
    public function store(Request $request)
    {
        $sector = new SectorInstitucion();
        $sector->sector = $request->sector;
        $sector->save();
    }

    //actualizar sector
    public function update(Request $request)
    {

        $sector = SectorInstitucion::findOrFail($request->id);
        $sector->sector = $request->sector;
        $sector->update();
    }

    public function delete($id){

        $sector = SectorInstitucion::findOrFail($id);
        $sector->delete();
    }

    //listado de sectores
    public function selectSectores(Request $request){

        $sectores = SectorInstitucion::orderBy('sector','asc')->get();
        $data = [];
        $data[0] = [];
        foreach ($sectores as $key => $value) {
            $data[$key+1] =[
                'value'   => $value->id,
                'label' => $value->sector,
            ];
        }
        return  response()->json($data);
    }

    public function getSectores(){

        $sector = SectorInstitucion::select('id', 'sector')->orderBy('id','desc')->get();
        $data = [];
        foreach ($sector as $key => $value) {
        $data[$key] =[
            'value'   => $value->id,
            'label' => $value->sector,
        ];
        }
        return  response()->json($data);
    }
    //Validar nombre del sector ya existente
    public function validateSector(Request $request){

        if(SectorInstitucion::where('sector',$request->sector)->exists()){
            return response('existe', 200);
        }
    }
}
