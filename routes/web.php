<?php
/*DB::listen(function($query){
echo "<pre>{$query->sql}</pre>";
echo "<pre>{$query->time}</pre>";
}); */
///////////////////////// ADMIN /////////////////////////////////
Route::get('/main', function () {
    return view('admin.index');
})->name('main');

//INSTITUCION
Route::get('/institucion', 'InstitucionController@index')->name('listInstituciones');
Route::post('/institucion/registrar', 'InstitucionController@store')->name('registrarInstitucion');
Route::put('/institucion/actualizar/{id}', 'InstitucionController@update')->name('update');
Route::put('/institucion/desactivar/{id}', 'InstitucionController@desactivar')->name('desactivar');
Route::put('/institucion/activar/{id}', 'InstitucionController@activar')->name('activar');
Route::get('GetInstituciones/{id}', 'InstitucionController@GetInstituciones')->name('getInstitucionById');
Route::get('/institucion/desactivadas', 'InstitucionController@getInstiDes')->name('institucionesDesactivadas');
Route::get('getProyectosByInstitucion', 'InstitucionController@getProyectosByInstitucion')->name('proyectosByinstitucion');
Route::get('GetInst', 'InstitucionController@GetInst');
Route::get('/institucion/validate', 'InstitucionController@validateInstitucion')->name('validateInstitucion');
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
Route::put('/institucion/supervisor/eliminar/{id}', 'SupervisorController@delete')->name('deleteSupervisor');
Route::put('institucion/supervisor/update', 'SupervisorController@update')->name('updSupervisor');


//SECTOR INSTITUCION
Route::get('sector/selectSectores', 'SectorInstitucionController@selectSectores')->name('selectSectores');
Route::get('/sector', 'SectorInstitucionController@index')->name('sectoresList');
Route::put('/sector/actualizar/{id}', 'SectorInstitucionController@update')->name('actualizarSector');
Route::get('/sector/eliminar/{id}', 'SectorInstitucionController@delete')->name('eliminarSector');
Route::get('/sector/validate','SectorInstitucionController@validateSector')->name('validateSector');
Route::get('sector/selectSectores', 'SectorInstitucionController@selectSectores')->name('selectSectores');
Route::post('sector/registrar','SectorInstitucionController@store')->name('registrarSector');

//MUNICIPIO
Route::get('GetDepartamentos', 'MunicipioController@GetDepartamentos')->name('getDepartamentos');
Route::get('GetMunicipios/{id}', 'MunicipioController@GetMunicipios')->name('getMunicipios');

//CARRERA
Route::get('carreras/GetCarreras', 'CarreraController@GetCarreras')->name('GetCarreras');
Route::get('/carrera', 'CarreraController@index')->name('carreraList');
Route::put('/carrera/actualizar/{id}', 'CarreraController@update')->name('actualizarCarrera');
Route::get('/carrera/validate','CarreraController@validateCarrera')->name('validateCarrera');
Route::put('/carrera/desactivar/{id}', 'CarreraController@desactivar')->name('desactivarCarrera');
Route::put('/carrera/activar/{id}', 'CarreraController@activar')->name('activarCarrera');
Route::get('/getCarreraDes', 'CarreraController@GetCarreraDes')->name('carrerasDesactivadas');

//ADMINISTRATIVA PROYECTO
Route::get('/proyecto', 'ProyectoController@index')->name('getAllProyectosInternos');
Route::get('/proyectos', 'ProyectoController@getExternalProjects')->name('getAllProyectosExternos');
Route::post('proyecto/registrar', 'ProyectoController@store')->name('saveProyectosInternos');
Route::post('/proyecto/actualizar', 'ProyectoController@update')->name('updateProyectos');
Route::get('GetProyectos/{id}', 'ProyectoController@GetProyectos')->name('getProyectosById');
Route::put('/proyecto/desactivar', 'ProyectoController@desactivar')->name('desactivarProyectosInternos');
Route::get('/proyecto/desactivadas', 'ProyectoController@getProyDes')->name('getAllProyectosInternosDesactivados');
Route::get('/proyecto/desactivados/externos', 'ProyectoController@getProyDesExternos')->name('getAllProyectosExternosDesactivados');
Route::put('/proyecto/activar', 'ProyectoController@activar')->name('activarProyectosInternos');
Route::get('proyectos/externos/asignar', 'ProyectoController@asignarProyectoExterno')->name('asignarProyectoExterno');
Route::get('proyectos/getNumeroPreinscripciones', 'ProyectoController@getNumeroPreinscripciones')->name('getNumeroPreinscripciones');
Route::get('GetProjectsByProcess', 'ProyectoController@getProjectsByProcess')->name('getProyectosByProcess');
Route::get('getPreregistrationByProject','ProyectoController@getPreregistrationByProject')->name('getPreinscripcionesByProyecto');
Route::get('/proyectos/obtenerAprobados', 'ProyectoController@getAllAcepted')->name('allAcepted');
Route::get('/proyectos/deleteAprobacion', 'ProyectoController@deleteProyectoAprobado')->name('deleteProyAceptted');
Route::get('/proyecto/vacantes/{id}','ProyectoController@verificarEstadoVacantes')->name('verificarEstadoVacantes');

///////PUBLICA PROYECTO///////
Route::get('/viewProject/{process}/{slug}', 'ProyectoController@getProjectBySlug')->name('viewProject');
Route::get('/preRegister/{studentId}/{projectId}', 'ProyectoController@preRegistrationProject')->name('preRegister');
Route::get('/my-pre-register/{studentId}/{proceso_id}', 'ProyectoController@getPreregisterProjects')->name('myPreregister');
Route::get('/deletePreRegister/{studentId}/{projectId}', 'ProyectoController@deletePreRegistration')->name('deletePreRegister');
Route::get('/destroyPreregister/{sId}/{pId}','ProyectoController@rechazPreregistration')->name('rechazarPreinscripcion');
Route::get('acceptPreregister','ProyectoController@aceptarPreregistration')->name("preregister");
Route::post('/admin/provideAccessToPerfil/{sId}/{pId}','ProyectoController@provideAccessToPerfil')->name('darAccesoPerfil');
Route::get('deleteAllPreregister/{pId}','ProyectoController@deleteAllPreregistration')->name('deleteAllPreinscripciones');

//SUPERVISIONES
Route::post('proyecto/registrar/supervision', 'SupervisionController@store')->name('saveSupervision');
Route::get('/proyecto/obtenerProyecto', 'ProyectoController@obtenerProyecto');
Route::get('/proyecto/allProjects', 'ProyectoController@getProjectsByCarrer');
Route::get('GetSupervision/{id}', 'SupervisionController@GetSupervision')->name('getSupervisionById');
Route::get('imgSuperv/{id}', 'SupervisionController@imgSuperv')->name('getImagenesBySupervision');
Route::put('/supervision/actualizar', 'SupervisionController@update');
Route::get('proyectos/getNumeroPreinscripciones', 'ProyectoController@getNumeroPreinscripciones')->name('getNumeroPreinscripciones');
Route::get('getFullInfo', 'GestionProyectoController@getFullDataByGestion')->name('getFullDataByGestion');
Route::get('/supervision/deteleImg/{id}', 'SupervisionController@delete')->name('deleteImgSupervision');

//LOGIN
Route::get('/', 'Auth\LoginController@showLoginForm')->name("showLogin");
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

//ESTUDIANTE
Route::get('stundentById/{id}','EstudianteController@getStudentById')->name('getFullInfoEstudiante');
//Route::post('admin/registrar', 'EstudianteController@store');
Route::get('stundentByCarrer','EstudianteController@getStudensByCarrerAndProcess')->name('getEstudiantesByCarrera');
///
Route::get('/recepcion/getAllStudents','EstudianteController@getStudentsToRecepcion')->name('getEstudiantesToRecepcion');
//RECEPCION ESTUDIANTE
Route::get('/admin/studentsHasPayArancel','EstudianteController@getStudentsHasPayArancel')->name('accessToPerfil');

//PARTE PUBLICA, PERFIL DEL PROYECTO
Route::get('/perfil_proy',function(){exec('php artisan view:clear');return view('public.perfilProject');})->name('show_perfil');
Route::get('/public', 'PublicController@index')->name('public');

//GESTION PROYECTO
Route::get('public/downloadDocs','GestionProyectoController@downloadDocs')->name('downloadDocs');
Route::get('getActualGestionProyectos', 'GestionProyectoController@getActualGestionProyectos')->name('getActualGestionProyectos');
Route::get('changeFechaInicio', 'GestionProyectoController@cambiarFechaInicio')->name('changeFechaInicio');
Route::get('/gestionproyectos','GestionProyectoController@index')->name('getGestionByCarrera');
Route::get('/gestionproyectos/constancias','GestionProyectoController@constancias')->name('getEstudiantesToConstacias');
Route::get('/getMoreInfoGP/{id}','GestionProyectoController@getInfoGpById')->name('getFullInfoGestion');
Route::get('/getCostancia','GestionProyectoController@generateConstancia')->name('getConstancia');
Route::get('/my_projects_now/{id}','GestionProyectoController@getGestionProyectoByStudent')->name('proyects_now');
Route::get('/gestionproyectos/delete','GestionProyectoController@deleteProyectoEnMarcha')->name('deleteGestionProyecto');
Route::get('/gestion/getMinDateInicio/{proyectoId}/{procesoId}', 'GestionProyectoController@getMinDateInicio')->name('getMinDateInicio');

//PUBLICA GESTION PROYECTO
Route::get('/my-proyect/saveDataOfPerfil','GestionProyectoController@initGestionProyecto')->name('save_perfil');
Route::get('/gestion_proy/{perfil}','GestionProyectoController@generatePerfil')->name('generate_perfil');
//REPORTE GESTION PROYECTO
Route::get('/gestionProy/reportes/initialprocess','GestionProyectoController@getInitialProcessReporte')->name('reporteIniProd');
Route::get('/gestionProy/reportes/pendienteInicio', 'GestionProyectoController@getPendientesIniProcessReporte')->name('reportePenIni');
Route::get('/gestionProy/reportes/pendienteFin', 'GestionProyectoController@getPendientesFinProcessReporte')->name('reportePenFin');
Route::get('/gestionProy/reportes/procesosCulminados', 'GestionProyectoController@getProcesosCulminadosReporte')->name('reporteProcesosCulminados');
//DOCUMENTO GESTION PROYECTO
Route::get('/closeProyect','GestionProyectoController@closeProy')->name('close_proyect');

//DOCUMENTOS
Route::get('/getDocuments','DocumentoController@getDocumentsByStudent')->name('getDocumentosByEstudiante');
Route::get('/saveDoc','DocumentoController@addDocToStudent')->name('savedoc');

//NOTIFICACIONES
Route::post('notifications/get', 'NotificationController@get')->name('getNotifications');

//////PARTE DE RECEPCION ////////
Route::post('/recepcion/payArancel','PagoArancelController@payArancel')->name('cancelarArancel');
Route::get('/recepcion/payArancel/validate/{no_factura}','PagoArancelController@validateIfExiste')->name('validateIfExisteArancel');

//Backup
Route::get('/backup', 'BackupController@backup');

//Usuarios
Route::post('users/update','UsuarioController@update')->name('updateUsuario');
Route::get('users/changeYear/{year}','UsuarioController@changeYearApp')->name('changeYearApp');
Route::get('estudents/getCurrenthMonth', 'UsuarioController@getCurrentMonth')->name('getCurrentMonth');
Route::post('estudents/changeMonth', 'UsuarioController@changeCurrentMonth')->name('updateMonth');
Route::get('users/getAll','UsuarioController@index')->name('getUsers');
Route::put('users/delete/{id}','UsuarioController@delete')->name('deleteUser');
Route::post('users/create','UsuarioController@create')->name('createUser');


// RUTAS PARA CHAT
Route::get('/private-messages/{user}', 'MessageController@privateMessages')->name('privateMessages');
Route::post('/user/messages/store', 'MensajeController@sendPrivateMessage')->name('messages.store');
Route::get('/user/getMessages', 'MensajeController@getMessagesToStudent')->name('getMessagesStudent');

Route::get('/test', function () {

    // $carrera = App\Carrera::find(1);
    // $data = $carrera->estudiantes()->select('id','fecha_inicio_ss')->where([['articulado',false],['tipo_beca_id',1],['estado',true]])->whereYear('fecha_registro','2019')->get();
    // foreach ($data as $key => $value) {
    //     if(substr($value->fecha_inicio_ss,5,2) == date('m') and substr($value->fecha_inicio_ss,8,2) > date('d')){
    //         $data = $data->except($value->id);
    //     }
    // }
    return App\Alumno::whereHas('aspirante',function($query){
        $query->where('articulado',true);
    })->get();
/*     if(date('m') > date('m',strtotime('-1 month')))
      return "Actualizar mes";
    else
      return "Es el mismo"; */
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
    //
/*     $path = base_path('.env');

    if(file_exists($path)){
        file_put_contents($path,str_replace(
            'APP_YEAR='.'2020','APP_YEAR='.$key,file_get_contents($path)
        ));
    }
    exec('php artisan config:cache');
    return "Hecho"; */
});

/* Route::get('/_debugbar/assets/stylesheets', [
'as' => 'debugbar-css',
'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@css'
]);

Route::get('/_debugbar/assets/javascript', [
'as' => 'debugbar-js',
'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@js'
]);

Route::get('/_debugbar/open', [
'as' => 'debugbar-open',
'uses' => '\Barryvdh\Debugbar\Controllers\OpenController@handler'
]); */


