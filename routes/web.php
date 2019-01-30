<?php
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
/*DB::listen(function($query){
echo "<pre>{$query->sql}</pre>";
echo "<pre>{$query->time}</pre>";
}); */

///////////////////////// ADMIN /////////////////////////////////
Route::get('/main', function () {
    return view('admin.index');
})->name('main');

//INSTITUCION
Route::get('/institucion', 'InstitucionController@index');
Route::post('/institucion/registrar', 'InstitucionController@store');
Route::put('/institucion/actualizar', 'InstitucionController@update');
Route::put('/institucion/desactivar', 'InstitucionController@desactivar');
Route::put('/institucion/activar', 'InstitucionController@activar');
Route::get('GetInstituciones/{id}', 'InstitucionController@GetInstituciones');
Route::get('/institucion/desactivadas', 'InstitucionController@getInstiDes');
Route::get('getProyectosByInstitucion', 'InstitucionController@getProyectosByInstitucion');
Route::get('GetInst', 'InstitucionController@GetInst');
Route::get('/institucion/validate','InstitucionController@validateInstitucion')->name('validateInstitucion');
Route::get('getInstitucionesByProcess', 'InstitucionController@getInstitucionesByProcess')->name('getInstitucionesByProcess');
//REPORTE INSTITUCION
Route::get('institucion/getHojaSupervision','InstitucionController@getHojaSupervision')->name('getHojaSupervision');
Route::get('institucion/reporteGen','InstitucionController@getReportInstituciones')->name('generalInstitucion');
Route::get('institucion/getSupervisiones','InstitucionController@getSupervisiones')->name('getReporteSupervisiones');
Route::get('institucion/regSupervision','InstitucionController@regSupervision')->name('regSupervision');

//SUPERVISORES
Route::get('institucion/supervisor/index', 'SupervisorController@index')->name('getSupervisores');
Route::post('institucion/supervisor/save', 'SupervisorController@store')->name('saveSupervisor');
Route::get('/institucion/supervisor/validate','SupervisorController@validateSupervisor')->name('validateSupervisor');
Route::put('/institucion/supervisor/eliminar/{id}', 'SupervisorController@delete');
Route::put('institucion/supervisor/update', 'SupervisorController@update')->name('updSupervisor');


//SECTOR INSTITUCION
Route::get('sector/selectSectores', 'SectorInstitucionController@selectSectores');
Route::get('getSectores/', 'SectorInstitucionController@getSectores');
Route::get('/sector', 'SectorInstitucionController@index');
Route::put('/sector/actualizar', 'SectorInstitucionController@update');
Route::get('/sector/eliminar/{id}', 'SectorInstitucionController@delete');
Route::get('/sector/validate','SectorInstitucionController@validateSector')->name('validateSector');
Route::get('sector/selectSectores', 'SectorInstitucionController@selectSectores');
Route::post('sector/registrar','SectorInstitucionController@store');

//MUNICIPIO
Route::get('GetDepartamentos', 'MunicipioController@GetDepartamentos');
Route::get('GetMunicipios/{id}', 'MunicipioController@GetMunicipios');

//CARRERA
Route::get('carreras/GetCarreras', 'CarreraController@GetCarreras');
Route::get('/carrera', 'CarreraController@index');
Route::put('/carrera/actualizar', 'CarreraController@update');
Route::get('/carrera/validate','CarreraController@validateCarrera')->name('validateCarrera');
Route::put('/carrera/desactivar', 'CarreraController@desactivar');
Route::put('/carrera/activar', 'CarreraController@activar');
Route::get('/getCarreraDes', 'CarreraController@GetCarreraDes');

//ADMINISTRATIVA PROYECTO
Route::get('/proyecto', 'ProyectoController@index');
Route::post('proyecto/registrar', 'ProyectoController@store');
Route::put('/proyecto/actualizar', 'ProyectoController@update');
Route::get('GetProyectos/{id}', 'ProyectoController@GetProyectos');
Route::put('/proyecto/desactivar', 'ProyectoController@desactivar');
Route::get('/proyecto/desactivadas', 'ProyectoController@getProyDes');
Route::get('/proyecto/desactivados/externos', 'ProyectoController@getProyDesExternos');
Route::put('/proyecto/activar', 'ProyectoController@activar');
Route::get('proyectos/externos', 'ProyectoController@getExternalProjects');
Route::get('proyectos/externos/asignar', 'ProyectoController@asignarProyectoExterno')->name('asinarProyectoExterno');
Route::get('proyectos/getNumeroPreinscripciones', 'ProyectoController@getNumeroPreinscripciones')->name('getNumeroPreinscripciones');
Route::get('GetProjectsByProcess', 'ProyectoController@getProjectsByProcess');
Route::get('getPreregistrationByProject','ProyectoController@getPreregistrationByProject');
Route::get('/proyectos/obtenerAprobados', 'ProyectoController@getAllAcepted')->name('allAcepted');
Route::get('/proyectos/deleteAprobacion', 'ProyectoController@deleteProyectoAprobado')->name('deleteProyAceptted');
///////PUBLICA PROYECTO///////
Route::get('/viewProject/{process}/{slug}', 'ProyectoController@getProjectBySlug')->name('viewProject');
Route::get('/preRegister/{studentId}/{projectId}', 'ProyectoController@preRegistrationProject')->name('preRegister');
Route::get('/my-pre-register/{studentId}/{proceso_id}', 'ProyectoController@getPreregisterProjects')->name('myPreregister');
Route::get('/deletePreRegister/{studentId}/{projectId}', 'ProyectoController@deletePreRegistration')->name('deletePreRegister');
Route::get('/proyecto/validatess/{nombre}','ProyectoController@ifProjectExist');
Route::get('/destroyPreregister/{sId}/{pId}','ProyectoController@rechazPreregistration');
Route::get('acceptPreregister','ProyectoController@aceptarPreregistration')->name("preregister");
Route::post('/admin/provideAccessToPerfil/{sId}/{pId}','ProyectoController@provideAccessToPerfil');
Route::get('deleteAllPreregister/{pId}','ProyectoController@deleteAllPreregistration');

//SUPERVISIONES
Route::post('proyecto/registrar/supervision', 'SupervisionController@store');
Route::get('/proyecto/obtenerProyecto', 'ProyectoController@obtenerProyecto');
Route::get('/proyecto/allProjects', 'ProyectoController@getProjectsByCarrer');
Route::get('GetSupervision/{id}', 'SupervisionController@GetSupervision');
Route::get('imgSuperv/{id}', 'SupervisionController@imgSuperv');
Route::put('/supervision/actualizar', 'SupervisionController@update');
Route::get('/supervision/eliminar/{id}', 'SupervisionController@delete');

//PERMISO
Route::get('/permiso', 'PermisoController@index');

//ROL
Route::post('rol/registrar','RolController@store');
Route::get('/rol', 'RolController@GetRol');

//LOGIN
Route::get('/', 'Auth\LoginController@showLoginForm')->name("showLogin");
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

//ESTUDIANTE
Route::get('stundentById/{id}','EstudianteController@getStudentById');
Route::post('admin/registrar', 'EstudianteController@store');
Route::get('stundentByCarrer','EstudianteController@getStudensByCarrerAndProcess');
Route::post('admin/registrar', 'EstudianteController@store');
///
Route::get('/recepcion/getAllStudents','EstudianteController@getStudentsToRecepcion');
//RECEPCION ESTUDIANTE
Route::get('/admin/studentsHasPayArancel','EstudianteController@getStudentsHasPayArancel');

//PARTE PUBLICA, PERFIL DEL PROYECTO
Route::get('/perfil_proy',function(){return view('public.perfilProject');})->name('show_perfil');
Route::get('/public', 'PublicController@index')->name('public');

//GESTION PROYECTO
Route::get('public/downloadDocs','GestionProyectoController@downloadDocs')->name('downloadDocs');
Route::get('getActualGestionProyectos', 'GestionProyectoController@getActualGestionProyectos')->name('getActualGestionProyectos');
Route::get('changeFechaInicio', 'GestionProyectoController@cambiarFechaInicio')->name('changeFechaInicio');
Route::get('/gestionproyectos','GestionProyectoController@index');
Route::get('/gestionproyectos/constancias','GestionProyectoController@constancias');
Route::get('/getMoreInfoGP/{id}','GestionProyectoController@getInfoGpById');
Route::get('/getCostancia','GestionProyectoController@generateConstancia')->name('getConstancia');
Route::get('/my_projects_now/{id}','GestionProyectoController@getGestionProyectoByStudent')->name('proyects_now');
Route::get('/gestionproyectos/delete','GestionProyectoController@deleteProyectoEnMarcha')->name('deleteGestionProyecto');
//PUBLICA GESTION PROYECTO
Route::get('/my-proyect/saveDataOfPerfil','GestionProyectoController@initGestionProyecto')->name('save_perfil');
Route::get('/gestion_proy/{perfil}','GestionProyectoController@generatePerfil')->name('generate_perfil');
//REPORTE GESTION PROYECTO
Route::get('/gestionProy/reportes/initialprocess','GestionProyectoController@getInitialProcessReporte')->name('reporteIniProd');
Route::get('/gestionProy/reportes/pendienteInicio', 'GestionProyectoController@getPendientesIniProcessReporte')->name('reportePenIni');
Route::get('/gestionProy/reportes/pendienteFin', 'GestionProyectoController@getPendientesFinProcessReporte')->name('reportePenFin');
Route::get('/gestionProy/reportes/procesosCulminados', 'GestionProyectoController@getProcesosCulminadosReporte')->name('reporteProcesosCulminados');
Route::get('/gestionProy/reportes/initialprocess/{pId}','GestionProyectoController@getInitialProcessReporte');
//DOCUMENTO GESTION PROYECTO
Route::get('/closeProyect','GestionProyectoController@closeProy')->name('close_proyect');

//DOCUMENTOS
Route::get('/getDocuments','DocumentoController@getDocumentsByStudent');
Route::get('/saveDoc','DocumentoController@addDocToStudent')->name('savedoc');

//NOTIFICACIONES
Route::post('notifications/get', 'NotificationController@get');

//////PARTE DE RECEPCION ////////
Route::get('/becas/getAll','TipoBecaController@getAllBecas');
Route::post('/recepcion/payArancel','PagoArancelController@payArancel');
Route::get('/recepcion/payArancel/validate/{no_factura}','PagoArancelController@validateIfExiste')->name('validateIfExisteArancel');

//Backup
Route::get('/backup','BackupController@backup');

Route::get('/test', function () {

    // $test = "2019-01-21SS5c4605667c309.jpeg";
    // if(file_exists(public_path('images/img_projects/').$test))
    // {
    //     unlink(public_path('images/img_projects/').$test);
    // }
    // unlink(public_path('images/img_projects/').$test);
     // return "hecho";

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
/*
    $e = new App\Estudiante;
    $e->nombre = "Luis Alonso";
    $e->apellido = "Hernandez Orellana";
    $e->fechaNac = date('Y-m-d');
    $e->genero = "M";
    $e->telefono = "70893823";
    $e->codCarnet = "HO17001001";
    $e->email = "HO17001001@itcha.edu.sv";
    $e->direccion = "La Cabaña";
    $e->tipo_beca_id = 1;
    $e->estado = 1;
    $e->carrera_id = 1; //Civil
    $e->municipio_id = 48;
    $e->supero_limite = 0;
    $e->password = bcrypt('123');
    $e->foto_name = "HO17001001.JPG";
    $e->no_proyectos = 0;
    $e->save();

    $e->proceso()->attach(1);
    // //return App\Notification::all();


    return "True";
    //return Auth::user()->estudiante->proceso[0]->id;*/

    //obtener imagenes

    // $id = 4;
    // $s = App\Proyecto::findOrFail($id)->supervision;
    // $img = App\ImgSupervision::where('supervision_id',$s->id)->select('img')->get();
    // return $img;
    //return Auth::user()->estudiante->gestionproyecto[0]->proyecto;


    $id = 29;
    $img = App\ImgSupervision::findOrFail($id);
    $img->delete();

     

});
