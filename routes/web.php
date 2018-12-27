<?php
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
/*DB::listen(function($query){
echo "<pre>{$query->sql}</pre>";
echo "<pre>{$query->time}</pre>";
}); */

///////////////////////// ADMIN /////////////////////////////////
Route::get('/main', function () {
    return view('admin.index');
})->name('main');

Route::get('/institucion', 'InstitucionController@index');
Route::post('/institucion/registrar', 'InstitucionController@store');
Route::put('/institucion/actualizar', 'InstitucionController@update');
Route::put('/institucion/desactivar', 'InstitucionController@desactivar');
Route::put('/institucion/activar', 'InstitucionController@activar');
Route::get('GetInstituciones/{id}', 'InstitucionController@GetInstituciones');
Route::get('/institucion/desactivadas', 'InstitucionController@getInstiDes');
Route::get('GetInstitucion/', 'InstitucionController@GetInstitucion');
Route::get('GetInst', 'InstitucionController@GetInst');
Route::get('/institucion/validate/{nombre}/{proceso}','InstitucionController@ifInstitucionExist');


Route::get('GetDepartamentos', 'MunicipioController@GetDepartamentos');
Route::get('GetMunicipios/{id}', 'MunicipioController@GetMunicipios');

Route::get('carreras/GetCarreras', 'CarreraController@GetCarreras');

Route::get('sector/selectSectores', 'SectorInstitucionController@selectSectores');

Route::get('/proyecto', 'ProyectoController@index');
Route::post('proyecto/registrar', 'ProyectoController@store');
Route::put('/proyecto/actualizar', 'ProyectoController@update');
Route::get('GetProyectos/{id}', 'ProyectoController@GetProyectos');
Route::put('/proyecto/desactivar', 'ProyectoController@desactivar');
Route::get('/proyecto/desactivadas', 'ProyectoController@getProyDes');
Route::put('/proyecto/activar', 'ProyectoController@activar');
Route::post('proyecto/registrar/supervision', 'SupervisionController@store');
Route::get('/proyecto/obtenerProyecto', 'ProyectoController@obtenerProyecto');
Route::get('/proyecto/allProjects', 'ProyectoController@getProjectsByCarrer');
Route::get('GetSupervision/{id}', 'SupervisionController@GetSupervision');

Route::get('/permiso', 'PermisoController@index');

//usuarios
Route::post('rol/registrar','RolController@store');
Route::get('/rol', 'RolController@GetRol');

Route::get('/', 'Auth\LoginController@showLoginForm')->name("showLogin");
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
//Para Preincripciones Proyectos

Route::get('GetProjectsByProcess', 'ProyectoController@getProjectsByProcess');
Route::get('getPreregistrationByProject','ProyectoController@getPreregistrationByProject');
Route::get('stundentById/{id}','EstudianteController@getStudentById');
Route::post('admin/registrar', 'EstudianteController@store');

Route::get('/perfil_proy', function () {
    return view('public.perfilProject');
})->name('show_perfil');

//Notificaciones

Route::post('notifications/get', 'NotificationController@get');

///////////////////////// PUBLIC /////////////////////////////////

Route::get('/public', 'PublicController@index')->name('public');

Route::get('/viewProject/{process}/{slug}', 'ProyectoController@getProjectBySlug')->name('viewProject');
Route::get('/preRegister/{studentId}/{projectId}', 'ProyectoController@preRegistrationProject')->name('preRegister');
Route::get('/my-pre-register/{studentId}/{proceso_id}', 'ProyectoController@getPreregisterProjects')->name('myPreregister');
Route::get('/deletePreRegister/{studentId}/{projectId}', 'ProyectoController@deletePreRegistration')->name('deletePreRegister');
Route::get('/proyecto/validatess/{nombre}','ProyectoController@ifProjectExist');
Route::get('/my-proyect/saveDataOfPerfil','GestionProyectoController@initGestionProyecto')->name('save_perfil');
Route::get('/gestion_proy/{perfil}','GestionProyectoController@generatePerfil')->name('generate_perfil');

//Recharaz una preinscripcion

Route::get('/destroyPreregister/{sId}/{pId}','ProyectoController@rechazPreregistration');
Route::get('/acceptPreregister/{sId}/{pId}','ProyectoController@aceptarPreregistration');
Route::get('/recepcion/getAllStudents','EstudianteController@getStudentsToRecepcion');
Route::post('/admin/provideAccessToPerfil/{sId}/{pId}','ProyectoController@provideAccessToPerfil');


//RUTAS PARA REPORTES
Route::get('institucion/getHojaSupervision','InstitucionController@getReportByMunicipio')->name('hojasupervigen');
Route::get('institucion/getInstituciones/{id}','InstitucionController@getReportInstituciones')->name('reginst');
Route::get('institucion/getSupervisiones/{id}','InstitucionController@getSupervisiones')->name('supervisiones');
Route::get('institucion/regSupervision','InstitucionController@regSupervision')->name('regSupervision');

//Reportes GP

Route::get('/gestionProy/reportes/initialprocess/{pId}/{mes}','GestionProyectoController@getInitialProcessReporte');
//metodo de carrerActividadProyecto

///////PARTE DE RECEPCION ////////

Route::get('/admin/studentsHasPayArancel','EstudianteController@getStudentsHasPayArancel');
Route::get('/becas/getAll','TipoBecaController@getAllBecas');
Route::post('/recepcion/payArancel','PagoArancelController@payArancel');



//PARA GESTION PROYECTOS

Route::get('/gestionproyectos','GestionProyectoController@index');
Route::get('/gestionproyectos/constancias','GestionProyectoController@constancias');
Route::get('/getMoreInfoGP/{id}','GestionProyectoController@getInfoGpById');
Route::get('/getCostancia/{id}','GestionProyectoController@generateConstancia');
Route::get('/my_projects_now/{id}','GestionProyectoController@getGestionProyectoByStudent')->name('proyects_now');

//Reportes GP

Route::get('/gestionProy/reportes/initialprocess/{pId}','GestionProyectoController@getInitialProcessReporte');

//Documentos
Route::get('/getDocuments','DocumentoController@getDocumentsByStudent');
Route::get('/saveDoc/{gp}/{doc}/{obs}','DocumentoController@addDocToStudent');
Route::get('/closeProyect/{gpId}/{fechaFin}/{hrs}/{obs}','GestionProyectoController@closeProy')->name('close_proyect');

Route::post('sector/registrar','SectorInstitucionController@store');
Route::post('admin/registrar', 'EstudianteController@store');

Route::get('/test', function () {

    /*$id= 36;

    $proy = App\Proyecto::findOrFail($id);

    $proyecto = App\Proyecto::where("nombre",$proy->nombre)->select("actividades","id")->get();

    $carreActvidad = App\Proyecto::where("nombre",$proy->nombre)->with(["carre_proy"])->select("id")->get();

    for ($i=0; $i < count($proyecto) ; $i++) {
    $proyecto[$i]->setAttribute("carrera",$carreActvidad[$i]["carre_proy"][0]["nombre"]);
} */

    //return $proyecto;

    //Guardando en la tala de Nivel Academico
    /*$na = new App\NivelAcademico;
    $na->nivel = "SegundoAño";
    $na->save(); */

    //Guardando en la tabla Roles
    /* $r = new App\Rol;
    $r->rol = "Recepcion";
    $r->save(); */

    //Guardando en la tabla Permisos
    /*$p = new App\Permiso;
    $p->nombre = "Verificar pago de arancel";
    $p->rol_id = 3;
    $p->save();¨*/

    //return App\Rol::find(3)->permisos()->get();

    // $u = new App\User();
    // $u->id = 0;
    // $u->nombre = "Carlos Francisco Orellana";
    // $u->usuario = "admin";
    // $u->password = bcrypt('sisprap');
    // $u->estado = 1;
    // $u->rol_id = 1;
    // $u->save();

    // $u = new App\User();
    // $u->id = 1;
    // $u->nombre = "Mirta Lilian Orellana";
    // $u->usuario = "recepcion";
    // $u->password = bcrypt('sisprap');
    // $u->estado = 1;
    // $u->rol_id = 2;
    // $u->save();


    // $e = new App\Estudiante;
    // $e->nombre = "Juan Arnol";
    // $e->apellido = "Sosa Suarez";
    // $e->fechaNac = date('Y-m-d');
    // $e->genero = "M";
    // $e->telefono = "79086578";
    // $e->codCarnet = "SS17001001";
    // $e->email = "romulo@gmail.com";
    // $e->direccion = "Chalatenango";
    // $e->tipo_beca_id = 1;
    // $e->estado = 1;
    // $e->carrera_id = 1;
    // $e->municipio_id = 48;
    // $e->supero_limite = 0;
    // $e->no_proyectos = 0;
    // $e->password = bcrypt('123');
    // $e->foto_name = "SS17001001".".JPG";
    // $e->save();

    // $e = new App\Estudiante;
    // $e->nombre = "Juan Maria Parada Presa";
    // $e->apellido = "Parada Presa";
    // $e->fechaNac = date('Y-m-d');
    // $e->genero = "M";
    // $e->telefono = "22345678";
    // $e->codCarnet = "PP17001001";
    // $e->email = "jchema@gmail.com";
    // $e->direccion = "San Salvador";
    // $e->tipo_beca_id = 1;
    // $e->estado = 1;
    // $e->carrera_id = 5; //Civil
    // $e->municipio_id = 48;
    // $e->supero_limite = 0;
    // $e->password = bcrypt('123');
    // $e->foto_name = "PP17001001".".JPG";
    // $e->no_proyectos = 0;
    // $e->save();

    // $e->proceso()->attach(2);
    // //return App\Notification::all();

    $gp = new App\GestionProyecto();
        $gp->fecha_inicio = date('Y-m-d'); //Fecha Inicio
        $gp->horas_a_realizar = "300"; //Total de Horas
        $gp->proyecto_id = 1;
        $gp->estudiante_id = 2;
        $gp->nombre_supervisor = "Juan Sosa"; //Nombre del supervisor
        $gp->tel_supervisor = "3463847";//Telefono del supervisor

            //Informacion del estudiante
        $proceso = Auth::user()->estudiante->proceso[0]->id;
        $nombre = Auth::user()->estudiante->nombre;
        $apellido = Auth::user()->estudiante->apellido;
        $carnet = Auth::user()->estudiante->codCarnet;
        $telefono = Auth::user()->estudiante->telefono;
        $carrera = Auth::user()->estudiante->carrera->nombre;
        $email = Auth::user()->estudiante->email;

            //Informacion de la institucion
        $nombreI = Auth::user()->estudiante->preinscripciones[0]->institucion->nombre;
        $direccionI = Auth::user()->estudiante->preinscripciones[0]->institucion->direccion;
        $departamentoI = Auth::user()->estudiante->preinscripciones[0]->institucion->municipio->departamento->nombre;
        $municipioI = Auth::user()->estudiante->preinscripciones[0]->institucion->municipio->nombre;
        $sectorI = Auth::user()->estudiante->preinscripciones[0]->institucion->sectorInstitucion->sector;
        $telefonoI = Auth::user()->estudiante->preinscripciones[0]->institucion->telefono;
        $emailI = Auth::user()->estudiante->preinscripciones[0]->institucion->email;

            //Informacion del proyecto
        $nombreP = Auth::user()->estudiante->preinscripciones[0]->nombre;
        $actividadesP = Auth::user()->estudiante->preinscripciones[0]->actividades;


        $data = new Collection([
            "proceso" => $proceso,
            "nombreE" => $nombre,
            "apellidoE" => $apellido,
            "carnetE" => $carnet,
            "telefonoE" => $telefono,
            "carreraE" => $carrera,
            "emailE" => $email,
            "nombreI" => $nombreI,
            "direccionI" => $direccionI,
            "departamentoI" => $departamentoI,
            "municipioI" => $municipioI,
            "sectorI" => $sectorI,
            "telefonoI" => $telefonoI,
            "emailI" => $emailI,
            "nombreP" => $nombreP,
            "actividadesP" => $actividadesP,
            "hrasRealizar" => "300",
            "fechaInicio" => date('Y-m-d'),
            "fechaFin" => "",
            "nombreS" => "Juan Sosa",
            "telefonoS" => "3463847",
        ]);

    // return "True";
        return view('public.reportes.rellenarperfil',compact('data'));
    //return Auth::user()->estudiante->proceso[0]->id;

    });
