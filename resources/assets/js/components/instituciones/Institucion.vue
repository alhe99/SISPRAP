<template>
  <!--INICIO DE DIV PARA TABLA,REGISTRO,BUSQUEDA DE INSTITUCIONES-->
  <div class="col-lg-12 col-md-12">
    <div class="card" v-show="verCard == 1">
      <div class="card-body">
       <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <fieldset >
                <legend class="text-center">Seleccione un proceso para ver las instituciones</legend>
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="row md-radio">
                      <div class="col-md-6 text-center">
                        <input id="radioSS" value="1" v-model="proceso" type="radio" name="radioP" >
                        <label for="radioSS">Servicio Social</label>
                      </div>
                      <div class="col-md-6 text-center">
                        <input id="radioPP" value="2" v-model="proceso" type="radio" name="radioP" >
                        <label for="radioPP">Práctica Profesional</label>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card" v-if="proceso !== 0 && verCard == 1">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 col-sm-12 col-lg-6  ">
          <h3 class=" align-baseline font-weight-bold" v-if="proceso == 1" >Listado de instituciones de Servicio Social</h3>
          <h3 class=" align-baseline font-weight-bold" v-if="proceso == 2">Listado de instituciones de Practica Profesional</h3>
        </div>
        <div class="col-md-5 col-sm-12 col-lg-5">
          <div class="form-group row">
            <mdc-textfield type="text" class="col-md-12" @keyup="listarInstitucion(1,proceso,buscar)"  label="Nombre de la institución" v-model="buscar"></mdc-textfield>
          </div>
        </div>
        <div class="col-md-1 col-sm-1 col-lg-1 text-center">
         <div class="btn-group pull-xs-right">
          <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="mw2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Más opciones">
            <i class="mdi mdi-dots-vertical"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right"  aria-labelledby="mw2">
            <button class="dropdown-item d-block menu" type="button" @click="abrirModal('institucion','registrar')"><i class="mdi mdi-plus-box"></i> Registrar Institución</button>
            <button class="dropdown-item d-block menu" type="button" @click="abrirModalID()"><i class="mdi mdi-delete-empty"></i> Instituciones Desactivadas</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
     <div class="col-md-12 col-lg-12 col-sm-12">
      <div class="table-responsive">
       <table id="myTable" class="table table-striped table-bordered table-mc-light-blue">
        <thead class="thead-primary">
         <tr>
           <th>Nombre de institución</th>
           <th class="text-center">Email</th>
           <th class="text-center">Sector perteneciente</th>
           <th class="text-center">Municipio</th>
           <th class="text-center">Acciones</th>
         </tr>
       </thead>
       <tbody>
        <tr v-for="institucion in arrayInstitucion" :key="institucion.id">
          <td v-text="institucion.nombre"></td>
          <td class="text-center" v-if="institucion.email != null">{{institucion.email | truncate(15)}}</td>
          <td class="text-center" v-else></td>
          <td class="text-center" v-text="institucion.sector_institucion.sector"></td>
          <td class="text-center" v-text="institucion.municipio.nombre"></td>
          <td class="text-center">
            <button type="button" @click="abrirModal('institucion','actualizar',institucion)" class="btn btn-primary " data-toggle="tooltip" title="Editar datos de la institución"><i class="mdi mdi-border-color i-crud"></i></button>
            <template v-if="institucion.estado">
              <button type="button" @click="desactivarInstitucion(institucion.id)" class="btn btn-primary " data-toggle="tooltip" title="Desactivar institución"><i class="mdi mdi-delete-variant i-crud"></i></button>
            </template>
            <template v-else>
              <button type="button" @click="activarInstitucion(institucion.id)" class="btn btn-primary" data-toggle="tooltip" title="Activar institución"><i class="mdi mdi-check-circle i-crud"></i></button>
            </template>
            <button type="button" @click="verMasInfo(institucion)" class="btn btn-primary " data-toggle="tooltip" title="Ver más información"><i class="mdi mdi-playlist-plus i-crud"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
    <nav>
      <ul class="pagination">
        <li class="page-item" v-if="pagination.current_page > 1">
          <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page -1,proceso,buscar)">Ant</a>
        </li>
        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
          <a class="page-link" href="#" @click.prevent="cambiarPagina(page,proceso,buscar)" v-text="page"></a>

          <li class="page-item" v-if="pagination.current_page < pagination.last_page">
            <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,proceso,buscar)">Sig</a>
          </li>
          <small v-show="arrayInstitucion.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayInstitucion.length + ' de ' + pagination.total + ' registros)'"></small>
        </ul>
      </nav>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-lg-12">
   <div v-show="search == 1"  class="alert alert-primary h6 font-weight-bold text-center" role="alert" v-text="'No se encontraron resultados o No hay registros'"></div>
 </div>
</div>
</div>
<!-- CIERRE DE CARDBODY -->
<!--MODAL PARA REGISTRAR UNA SUPERVICION-->
<!--MODAL PARA VER LAS INSTITUCIONE DESACTIVADAS-->
<div class="modal fade" :class="{'mostrar' : modalID }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
   <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title text-white">Instituciones Desactivadas</h4>
      <button type="button" @click="cerrarModalID()" class="close" data-dismiss="modal" aria-label="Close">
       <span aria-hidden="true" class="text-white">&times;</span>
     </button>
   </div>
   <div class="modal-body">
    <div class="bmd-form-group bmd-collapse-inline pull-xs-right">
     <button class="btn bmd-btn-icon" for="search" data-toggle="collapse" data-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search">
      <i class="mdi mdi-magnify"></i>
    </button>
    <span id="collapse-search" class="collapse">
     <input v-model="buscarID" @keyup="listarInstitucionDes(1,proceso,buscarID)" class="form-control" data-toggle="tooltip" title="Buscar Registros" type="text" id="search" placeholder="Ingrese Nombre de la Institución/Empresa">
   </span>
 </div><br>
 <div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-mc-light-blue">
        <thead class="thead-primary">
          <tr>
            <th>Nombre de institucion</th>
            <th>Telefono</th>
            <th>Email</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="instituciondes in arrayInstitucionDes" :key="instituciondes.id">
            <td v-text="instituciondes.nombre"></td>
            <td v-text="instituciondes.telefono"></td>
            <td v-text="instituciondes.email"></td>
            <td class="text-center">
             <template>
              <h4>
               <span v-if="instituciondes.estado == 0" class="badge badge-pill badge-danger">Desactivada</span>
             </h4>
           </template>
         </td>
         <td class="text-center">
          <template>
            <button type="button" @click="activarInstitucion(instituciondes.id)" class="btn btn-primary" data-toggle="tooltip" title="Activar institución"><i class="mdi mdi-check-circle i-crud"></i></button>
          </template>
        </td>
      </tr>
    </tbody>
  </table>
  <nav>
    <ul class="pagination">
      <li class="page-item" v-if="paginationInstiDes.current_page > 1">
        <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaPID(paginationInstiDes.current_page -1,proceso,buscar)">Ant</a>
      </li>
      <li class="page-item" v-for="page in pagesNumberPID" :key="page" :class="[page == isActivedPID ? 'active' : '']">
        <a class="page-link" href="#" @click.prevent="cambiarPaginaPID(page,proceso,buscar)" v-text="page"></a>

        <li class="page-item" v-if="paginationInstiDes.current_page < paginationInstiDes.last_page">
          <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaPID(paginationInstiDes.current_page + 1,proceso,buscar)">Sig</a>
        </li>
        <small v-show="arrayInstitucionDes.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayInstitucionDes.length + ' de ' + paginationInstiDes.total + ' registros)'"></small>
      </ul>
    </nav>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
      <div v-show="searchID == 1" class="alert alert-primary h6 font-weight-bold text-center" role="alert" v-text="'No se encontraron resultados o No hay registros'"></div>
      <pulse-loader class="text-center" :loading="loading" :color="color" :size="size"></pulse-loader>
    </div>
  </div>
</div>
</div>
</div>
<div class="modal-footer">
  <div class="row">
    <div class="col-md-12">
      <button type="button" @click="cerrarModalID()" class="btn btn-danger">Cerrar</button>
    </div>
  </div>
</div>
</div>
</div>
</div>
<!--FIN DE MODAL PARA REGISTRAR UNA SUPERVICION-->
<!-- FIN DE MODAL PARA VER LAS INSTTUCIONES DESACTIVADAS-->
<!-- MODAL PARA REGISTRAR Y ACTUALIZAR DATOS  -->
<div class="modal fade" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-white" v-text="tituloModal"></h4>
        <button type="button" @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <fieldset >
                  <legend class="text-center">Seleccione Tipo de proceso de la institución</legend>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row md-radio">
                        <div class="col-md-4 text-center">
                          <input id="ss" value="1" v-model="tipoproceso_id" type="radio" name="radiosP" required >
                          <label for="ss">Servicio Social</label>
                        </div>
                        <div class="col-md-4 text-center">
                          <input id="pp" value="2" v-model="tipoproceso_id" type="radio" name="radiosP" required >
                          <label for="pp" class="d-inline">Práctica Profesional</label>
                        </div>
                        <div class="col-md-4 text-center">
                          <input id="a" value="3" v-model="tipoproceso_id" type="radio" name="ambos" required >
                          <label for="a">Ambos</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12"><br>
            <pulse-loader class="text-center" :loading="loading" :color="color" :size="size"></pulse-loader>
          </div>
          <div class="col-md-12 col-xs-12 col-lg-12">
            <br><label for="nombre">Nombre de la institución*</label>
            <input type="text" v-model="nombre" id="nombre" name="nombre" class="form-control" autocomplete="off">
            <!-- <span v-if="errors.nombre" class="text-danger" v-text="errors.nombre[0]" ></span> -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-xs-12 col-lg-12">
            <br><label for="direccion">Dirección exacta*</label><br>
            <textarea v-model="direccion" id="direccion" name="direccion" class="form-control" rows="3"></textarea>
            <!-- <span v-if="errors.direccion" class="text-danger" v-text="errors.direccion[0]"></span>-->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-xs-12 col-lg-12">
            <br><label for="phone">Teléfono</label>
            <input type="text" v-mask="'########'" v-model="phone" name="phone" id="phone" class="form-control" required>
            <!-- <span v-if="errors.phone" class="text-danger" v-text="errors.phone[0]"></span> -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-xs-12 col-lg-12">
            <br><label for="email">Correo Electrónico</label>
            <input type="email" v-model="email" name="email" id="email" class="form-control" autocomplete="off">
            <!-- <span v-if="errors.email" class="text-danger" v-text="errors.email[0]"></span> -->
            <!-- <span>{{ errors.first('email') v-validate="'email'" data-vv-as="email" }}</span> -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-xs-12 col-lg-12">
            <br><label for="email">Seleccione un Departamento* </label>
            <br><v-select label="label" v-model="departamento_id" :onChange="watchDepa" placeholder="Seleccione un departamento" :options="arrayDepartamentos"></v-select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-xs-12 col-lg-12" v-if="departamento_id !== null">
            <br><label for="email">Seleccione un Municipio*</label>
            <br><v-select label="label" v-model="municipio_id"  placeholder="Seleccione un municipio"  :options="arrayMunicipios"></v-select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-xs-12 col-lg-12">
            <br><label for="email">Seleccione un Sector de la Institución*</label>
            <br><v-select label="label" v-model="sector_id" placeholder="Seleccione un sector de la institucion"  :options="arraySectores"></v-select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-md-12">
            <button type="button"  @click="cerrarModal()" class="button red"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
            <button type="button" :disabled="validate == true" :class="[validate == true ? 'disabled' : '']" v-if="tipoAccion==1" class="button blue" @click="registrarInstitucion" dense><i class="mdi mdi-content-save"></i>&nbsp;Guardar Institución</button>
            <button type="button" :disabled="validate == true" :class="[validate == true ? 'disabled' : '']" v-if="tipoAccion==2" class="button blue" @click="actualizarInstitucion" dense><i class="mdi mdi-content-save"></i>&nbsp;Actualizar Institución</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--- FIN MODAL PARA REGISTRAR Y ACTUALIZAR DATOS -->
</div>
<div class="row">
  <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
  </div>
</div>
<!--DIV PARA MOSTRAR MAS INFORMACION DE LA INSTITUCION-->
<template>
 <div class="row" v-show="verCard == 2">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                    <fieldset >
                      <legend class="text-center font-weight-bold h5">Datos completos de la institución</legend>
                      <div class="panel panel-default">
                        <div class="panel-body" id="info">
                          <h5><strong>Nombre: </strong> {{ institucion["nombre"] }} </h5>
                          <h5><strong>Dirección: </strong>{{ institucion["direccion"] }} </h5>
                          <h5><strong>Telefono: </strong>{{ institucion["telefono"] }} </h5>
                          <div v-for="sector in institucion.sector_institucion" :key="sector.id">
                           <h5 v-show="sector.length > 4"><strong>Sector de la institucion: </strong> {{ sector }}</h5>
                         </div>
                         <h5><strong>Correo: </strong><a :href="'mailto:'+ institucion['email']" id="link-email" class="btn btn-link text-lowercase" >{{ institucion["email"]}}</a></h5>
                       </div>
                     </div>
                   </fieldset><br>
                   <div class="row">
                    <div class="col-md-6 col-sm-12 col-lg-6  ">
                      <h3 class=" align-baseline font-weight-bold" >Proyectos de la institucion {{ institucion["nombre"] }}</h3>
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-6">
                      <div class="form-group row">
                        <mdc-textfield type="text" class="col-md-12" @keyup="getProyectosInsti(institucion.id,1,buscar,proceso)"  label="Buscar por Nombre del proyecto" v-model="buscar"></mdc-textfield>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="table-responsive">
                        <table  class="table table-striped table-bordered table-mc-light-blue">
                          <thead class="thead-primary">
                            <tr>
                              <th>Nombre del proyecto</th>
                              <!-- <th>Descripción</th> -->
                              <th class="text-center">Fecha de publicación</th>
                              <th class="text-center"  v-if="proceso == 2">Carrera del proyecto</th>
                              <th class="text-center"  v-if="proceso == 2">Estado Supervisión</th>
                              <th class="text-center"  v-if="proceso == 2">Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="proyecto in arrayProyectos" :key="proyecto.id">
                              <td v-text="proyecto.nombre"></td>
                              <td class="text-center" v-text="proyecto.created_at.substring(0,10)"></td>
                              <td class="text-center" v-if="proceso == 2" v-text="proyecto.carrera.nombre"></td>
                              <td class="text-center" v-if="proceso == 2">
                                <template>
                                  <h4>
                                    <span v-if="proyecto.supervision == 0" class="badge h1 badge-pill badge-primary">Supervisado</span>
                                    <span v-else class="badge badge-pill badge-danger">No Supervisado</span>
                                  </h4>
                                </template>
                              </td>
                              <td class="text-center" v-if="proceso == 2">
                                <button type="button" v-if="proyecto.supervision == 1"  class="btn btn-primary text-capitalize" @click="abrirModalSuper('registrar',proyecto.id,proyecto.nombre)" data-toggle="tooltip" title="Registrar nueva supervisión a este proyecto" >
                                  <i class="mdi mdi-folder-plus h4"></i>Registrar Supervisión</button>
                                  <button type="button" v-if="proyecto.supervision == 0"  class="btn btn-primary text-capitalize" @click="abrirModalSuper('actualizar',proyecto.id,proyecto.nombre)" data-toggle="tooltip" title="Editar datos de la supervisión realizada" >
                                    <i class="mdi mdi-border-color h4"></i>Editar Supervisión</button>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <nav>
                              <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                  <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page -1,proceso,buscar)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                  <a class="page-link" href="#" @click.prevent="cambiarPagina(page,proceso,buscar)" v-text="page"></a>

                                  <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,proceso,buscar)">Sig</a>
                                  </li>
                                  <small v-show="arrayProyectos.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayProyectos.length + ' de ' + pagination.total + ' registros)'"></small>
                                </ul>
                              </nav>
                              <!--MODAL PARA REGISTRAR UNA SUPERVICION-->
                              <div class="modal fade" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h4 class="modal-title text-white" >{{titleMRS +  modalsTitle | truncate(55)}}</h4>
                                      <button type="button" @click="cerrarModalSuper()" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" class="text-white">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="form-group">
                                        <textarea name="observacion" v-model="observacion" id="observacion" class="form-control" placeholder="Observación" rows="10"></textarea>
                                      </div>
                                      <div class="form-group row">
                                        <div class="col-md-12 col-sm-6 col-lg-12">
                                         <datetime type="date" :max-datetime="maxDatetime" v-model="date" value-zone="America/El_Salvador" input-class="form-control" placeholder="Fecha en la que fue realizada la supervisión"></datetime>
                                       </div>
                                     </div>
                                     <div class="form-group">
                                      <div class="uploader"
                                      @dragenter="OnDragEnter"
                                      @dragleave="OnDragLeave"
                                      @dragover.prevent
                                      @drop="onDrop"
                                      :class="{ dragging: isDragging }">
                                      <div class="upload-control" v-show="images.length">
                                        <label for="file">Seleccione una o mas imagenes</label>
                                      </div>
                                      <div v-show="!images.length">
                                        <i class="fa fa-cloud-upload"></i>
                                        <p>Arrastre sus imagenes aqui!</p>
                                        <div>o</div>
                                        <div class="file-input">
                                          <label for="file">Seleccione</label>
                                          <input type="file" id="file" @change="onInputChange" multiple>
                                        </div>
                                      </div>
                                      <div class="images-preview" v-show="images.length">
                                                          <!-- <div  class="img-wrapper" v-for="(image, index) in images" :key="index">
                                                               <button class="remove" @click="removeImage(index)"><i class="mdi mdi-close-circle"></i></button>
                                                                <img v-if="supervision != {}" :src="'images_superv/'+image.img" :alt="`Imagen ${index}`">
                                                                <div class="details">
                                                                  PROBLEMA AL VOLVER CARGAR UNA IMAGEM DESPUES DE HABERLAS ELIMINADO
                                                                  <span class="name" v-text="files[index].name"></span>
                                                                    <span class="size" v-text="getFileSize(files[index].size)"></span>
                                                                </div>
                                                              </div> -->
                                                              <div class="img-wrapper" v-for="(image, index) in images" :key="index">
                                                               <button class="remove" @click="removeImage(index)"><i class="mdi mdi-close-circle"></i></button>
                                                               <img  :src="image" :alt="`Imagen ${index}`">
                                                               <div class="details">
                                                                 <span class="name" v-text="files[index].name"></span>
                                                                 <span class="size" v-text="getFileSize(files[index].size)"></span>
                                                               </div>
                                                             </div>
                                                           </div>
                                                         </div>
                                                       </div>
                                                       <!--<pulse-loader class="text-center" :loading="loading" :color="color" :size="size"></pulse-loader>-->
                                                     </div>
                                                     <div class="modal-footer">
                                                      <div class="row">
                                                        <div class="col-md-12">
                                                          <button type="button" class="button red" @click="cerrarModalSuper()">Cancelar</button>
                                                          <button type="button" class="button blue" v-if="tipoAccion2 == 1" @click="registrarSupervision()" dense>Registrar Supervisión</button>
                                                          <button type="button" class="button blue" v-if="tipoAccion2 == 2" @click="actualizarSupervision()" dense>Actualizar Supervisión</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <!--FIN DE MODAL PARA REGISTRAR UNA SUPERVICION-->
                                            </div>
                                          </div>
                                        </div>
                                        <div v-if="arrayProyectos.length == 0" class="row">
                                          <div class="col-md-12 col-sm-12 col-lg-12">
                                            <div class="alert alert-warning h6 font-weight-bold text-center" role="alert" v-text="'No existen proyectos registrados en esta institución'"></div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <button type="button" @click="back()" class="btn btn-primary text-capitalize  font-weight-bold" data-toggle="tooltip" id="back" title="Regresar"><i class="mdi mdi-chevron-double-left" ></i>Regresar</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>
                  <!--FIN DE DIV PARA MOSTRAR MAS INFORMACION DE LA INSTITUCION-->
                </div>
                <!--FIN DE DIV PARA TABLA,REGISTRO,BUSQUEDA DE INSTITUCIONES-->
              </template>
              <script>
              export default {
                data() {
                  return {
                    //declaracion de variables
                    loadSpinner: 0,
                    verCard: 1,
                    img: 0,
                    institucion: [],
                    arrayInstitucion: [],
                    arrayProyectos: [],
                    arrayImages: [],
                    arrayImag:[],
                    nombre: "",
                    direccion: "",
                    phone: "",
                    tipoproceso_id: 0,
                    infoInsti: 0,
                    estado: 0,
                    email: "",
                    sector_id: 0,
                    institucion_id: 0,
                    buscar: "",
                    message: 0,
                    search: 0,
                    searchID: 0,
                    modal: 0,
                    tituloModal: "",
                    tipoAccion: 0,
                    tipoAccion2: 0,
                    paginationInsti: {},
                    paginationInstiDes: {},
                    pagination: {
                      total: 0,
                      current_page: 0,
                      per_page: 0,
                      last_page: 0,
                      from: 0,
                      to: 0
                    },
                    offset: 3,
                    arraySectores: [],
                    arrayDepartamentos: [],
                    departamento_id: 0,
                    arrayMunicipios: [],
                    municipio_id: 0,
                    proceso: 0,
                    isDragging: false,
                    dragCount: 0,
                    files: [],
                    images: [],
                    imgS: [],
                    loading: false,
                    color: "#533fd0",
                    size: "20px",
                    date: "",
                    observacion: "",
                    supervision_id: 0,
                    proyecto_id: 0,
                    modalsTitle: "",
                    modalID: 0,
                    arrayInstitucionDes: [],
                    buscarID: "",
                    titleMRS: "",
                    supervision: {},
                    maxDatetime:  new Date().toISOString().substring(0, 10),
                    nombreUpd: "",
                  };
                },
                computed: {
                  //paginacion
                  isActived: function() {
                    return this.pagination.current_page;
                  },
                  isActivedPID: function() {
                    return this.paginationInstiDes.current_page;
                  },
                  pagesNumber: function() {
                    if (!this.pagination.to) {
                      return [];
                    }
                    var from = this.pagination.current_page - this.offset;
                    if (from < 1) {
                      from = 1;
                    }
                    var to = from + this.offset * 2;
                    if (to >= this.pagination.last_page) {
                      to = this.pagination.last_page;
                    }
                    var pagesArray = [];
                    while (from <= to) {
                      pagesArray.push(from);
                      from++;
                    }
                    return pagesArray;
                  },
                  pagesNumberPID: function() {
                    if (!this.paginationInstiDes.to) {
                      return [];
                    }
                    var from = this.paginationInstiDes.current_page - this.offset;
                    if (from < 1) {
                      from = 1;
                    }
                    var to = from + this.offset * 2;
                    if (to >= this.paginationInstiDes.last_page) {
                      to = this.paginationInstiDes.last_page;
                    }
                    var pagesArray = [];
                    while (from <= to) {
                      pagesArray.push(from);
                      from++;
                    }
                    return pagesArray;
                  },
                  //verificar si no ha seleccionado un departamento
                  watchDepa: function() {
                    if (this.departamento_id == null) {
                      this.municipio_id = 0;
                    }
                  },
                  validate: function(){
                    if((this.nombre == "") || (this.direccion == "") || (this.departamento_id == 0) || (this.municipio_id == 0) || (this.sector_id == 0))
                    {
                      return true;
                    }else{
                      return false;
                    }
                  },
                 /* removeOpcion: function(){
                    let me = this;
                    return $("#nombre").on('keyup',function(){
                      //me.opcion = "";
                      me.validateIfExist($(this).val().trim(),me.proceso);
                    });
                  } */
                },
                watch: {
                  departamento_id: function() {
                    this.getMunicipios();

                    if (this.tipoAccion == 1) {
                      this.municipio_id = 0;
                    }
                  },
                  proceso: function() {
                    this.listarInstitucion(1, this.proceso, "");
                    this.buscar = "";
                  },
                  infoInsti: function() {
                    if (this.institucion.length > 0) {
                      this.getProyectosInsti(this.institucion["id"], 1, "", this.proceso);
                    }
                  },
                  supervision: function(){
            // if(this.supervision != {})
            // {
              this.observacion = this.supervision["observacion"];
              this.date = this.supervision["fecha"];
              //this.images = this.supervision["imagenes"];
            // }
          },

        },
        methods: {
  //listado de instituciones por busqueda
  listarInstitucion(page, proceso, buscar) {
    let me = this;
    var url =
    "/institucion?page=" +
    page +
    "&proceso=" +
    proceso +
    "&buscar=" +
    buscar;
    me.loadSpinner = 1;
    axios
    .get(url)
    .then(function(response) {
      var respuesta = response.data;
      me.arrayInstitucion = respuesta.institucion.data;
      me.paginationInsti = respuesta.pagination;
      me.pagination = me.paginationInsti;
          //Por si no devuelve datos
          me.loadSpinner = 0;
          me.searchEmpty();

        })
    .catch(function(error) {
     me.loadSpinner = 0;
     console.log(error);
   });
  },
  //listado de instituciones desactivadas
  listarInstitucionDes(page, proceso, buscar) {
    let me = this;
    var url ="/institucion/desactivadas?page=" + page + "&proceso=" + proceso + "&buscar=" + buscar;
    me.loading = true;
    axios.get(url).then(function(response) {
      var respuesta = response.data;
      me.arrayInstitucionDes = respuesta.institucion.data;
      me.paginationInstiDes = respuesta.pagination;
          //Por si no devuelve datos
          if (me.arrayInstitucionDes.length == 0) {
           me.searchID = 1;
         } else {
          me.searchID = 0;
        }
        me.loading = false;
      })
    .catch(function(error) {
      console.log(error);

    });
  },
  getSectores() {
    let me = this;
    var url = "sector/selectSectores";
    axios
    .get(url)
    .then(function(response) {
      var respuesta = response.data;
      me.arraySectores = respuesta;
    })
    .catch(function(error) {
      console.log(error);
    });
  },
  getMunicipios() {
    let me = this;
    var url = "GetMunicipios/" + this.departamento_id["value"];
    axios
    .get(url)
    .then(function(response) {
      var respuesta = response.data;
      me.arrayMunicipios = respuesta;
    })
    .catch(function(error) {
      console.log(error);
    });
  },
  getDepartamentos() {
    axios.get("GetDepartamentos").then(response => {
      this.arrayDepartamentos = response.data;
    });
  },
  //obtener todas las instituciones relacionadas a proyectos de SS o PP
  getProyectosInsti(id, page, buscar, proceso) {
    let me = this;
    var url =
    "/GetInstitucion?id=" + id +"&page=" + page +"&buscar=" + buscar +"&proceso=" +  proceso;
    me.loadSpinner = 1;
    me.pagination = "";
    axios
    .get(url)
    .then(function(response) {
      var respuesta = response.data;
      me.arrayProyectos = respuesta.institucion.data;
      me.pagination = respuesta.pagination;
          //Por si no devuelve datos
          me.loadSpinner = 0;
          me.searchEmpty();

        })
    .catch(function(error) {
      me.loadSpinner = 0;
      console.log(error);
    });
  },
  //imagenes
  getImg(id) {
    let me = this;
    var url = "imgSuperv/" + id;
    me.loading = true;
    axios.get(url).then(function(response) {
      var respuesta = response.data;
      me.arrayImag = respuesta;
      me.loading = false;
    })
    .catch(function(error) {
      console.log(error);
    });
  },
  //termina
  getSupervision(id) {
    let me = this;
    var url = "GetSupervision/" + id;
    me.loading = true;
    axios.get(url).then(function(response) {
      var respuesta = response.data;
      me.supervision = respuesta;
      me.loading = false;
    })
    .catch(function(error) {
      console.log(error);
    });
  },
  registrarInstitucion() {
    let me = this;
    me.loading = true;

    var url = route('validateInstitucion',{"nombre": me.nombre,"proceso_id":me.proceso});
    axios.get(url).then(function(response) {
     var respuesta = response.data;
     console.log(respuesta);
     if(respuesta == 'existe'){
      swal({
        position: "center",
        type: "warning",
        title: "Institución existente! Ingrese otro nombre",
        showConfirmButton: true,
        timer: 5000
      });
      me.nombre = "";
      me.loading = false;
      me.exist = false;
    }else {
      axios.post("/institucion/registrar", {
        nombre: me.nombre,
        direccion: me.direccion,
        telefono: me.phone,
        email: me.email,
        sector_institucion_id: me.sector_id["value"],
        municipio_id: me.municipio_id["value"],
        proceso_id: me.tipoproceso_id
      })
      .then(function(response) {
        me.loadSpinner = 0;
        swal({
          position: "center",
          type: "success",
          title: "¡Institución agregada correctamente!",
          showConfirmButton: false,
          timer: 1000
        });
        me.cerrarModal();
        me.listarInstitucion(1, me.proceso, "");
      })
      .catch(error => {
        me.loading = false;
        console.log(error);
      });
    }
  });
  },
  actualizarInstitucion() {
    let me = this;
    me.loading = true;
    var url = route('validateInstitucion',{"nombre": me.nombre,"proceso_id":me.proceso});
    axios.get(url).then(function(response) {
     var respuesta = response.data;
     console.log(respuesta);
     if((me.nombre != me.nombreUpd) && (respuesta == 'existe')){
      swal({
        position: "center",
        type: "warning",
        title: "Institución existente! Ingrese otro nombre",
        showConfirmButton: true,
        timer: 5000
      });
      me.loading = false;
      me.exist = false;
    }else{
     axios
     .put("/institucion/actualizar", {
      id: me.institucion_id,
      nombre: me.nombre,
      direccion: me.direccion,
      telefono: me.phone,
      email: me.email,
      sector_institucion_id: me.sector_id["value"],
      municipio_id: me.municipio_id["value"],
      estado: me.estado,
      proceso_id: me.tipoproceso_id
    })
     .then(function(response) {
      me.loadSpinner = 0;
      swal({
        position: "center",
        type: "success",
        title: "¡Institución actualizada correctamente!",
        showConfirmButton: false,
        timer: 1000
      });
      me.cerrarModal();
      me.listarInstitucion(1, me.proceso, "");
    })
     .catch(function(error) {
      swal({
        position: "center",
        type: "warning",
        title: "Ocurrio un error al actualizar una institucion",
        showConfirmButton: false,
        timer: 1000
      });
      me.loading = false;
      console.log(error);
    });
   }
 });
  },
  registrarSupervision() {
    let me = this;
    //me.loading = true;
    axios
    .post("/proyecto/registrar/supervision", {
      proyecto_id: this.proyecto_id,
      observacion: this.observacion,
      fecha: this.date.substring(0, 10),
      imagenes: this.images
    })
    .then(function(response) {
      //me.loading = false;
      swal({
        position: "center",
        type: "success",
        title: "¡Supervisión realizada correctamente!",
        showConfirmButton: false,
        timer: 1000
      });
      me.clearDatos();
    })
    .catch(error => {
      console.log(error.response.data.errors);
    });
  },
  validateIfExist(institucion,proceso_id){
    let me = this;


  },
  actualizarSupervision(){
   let me = this;
   axios
   .put("/supervision/actualizar", {
    id: this.proyecto_id,
    fecha: this.date.substring(0, 10),
    observacion: this.observacion,

  })
   .then(function(response) {
    swal({
      position: "center",
      type: "success",
      title: "Supervision actualizado correctamente!",
      showConfirmButton: false,
      timer: 1000
    });
    me.cerrarModalSuper();
  })
   .catch(function(error) {
    swal({
      position: "center",
      type: "warning",
      title: "Ocurrio un error al actualizar el proyecto",
      showConfirmButton: false,
      timer: 1000
    });
    console.log(error);
  });
 },
 abrirModal(modelo, accion, data = []) {
  const el = document.body;
  el.classList.add("abrirModal");
  switch (modelo) {
    case "institucion": {
      switch (accion) {
        case "registrar": {
          this.modal = 1;
          this.loading = false;
          this.tipoproceso_id = this.proceso;
          this.nombre = "";
          this.direccion = "";
          this.phone = "";
          this.email = "";
          this.municipio_id = 0;
          this.departamento_id = { value: 3, label: "Chalatenango" };
          this.sector_id = 0;
          this.tipoAccion = 1;
          this.tituloModal = "Registrar Institución";
          break;
        }
        case "actualizar": {
              //Para obtener el departamento primero lo converti a JSON por el formato que pide vue-select y luego lo converti
              // a objeto mismo proceso con sector institucion y con municipios
              var depa = JSON.stringify({
                value: data.municipio.departamento["id"],
                label: data.municipio.departamento["nombre"]
              });
              var muni = JSON.stringify({
                value: data.municipio["id"],
                label: data.municipio["nombre"]
              });
              var sect = JSON.stringify({
                value: data.sector_institucion["id"],
                label: data.sector_institucion["sector"]
              });
              //Asignando los datos traidos a los controles del formulario
              this.modal = 1;
              this.loading = false;
              this.tituloModal = "Actualizar Institución";
              this.tipoAccion = 2;
              this.institucion_id = data["id"];
              this.idSector = data.sector_institucion;

              if(data.procesos.length > 1){
                this.tipoproceso_id = 3;
               /*  else if(this.proceso == 2)
               this.tipoproceso_id = data.procesos[1].id; */
             }else{
              this.tipoproceso_id = data.procesos[0].id;
            }

            this.nombre = data["nombre"];
            this.nombreUpd = data["nombre"];
            this.direccion = data["direccion"];
            this.phone = data["telefono"];
            this.email = data["email"];
            this.departamento_id = JSON.parse(depa);
            this.municipio_id = JSON.parse(muni);
            this.sector_id = JSON.parse(sect);
            this.estado = data["estado"];
            break;
          }
        }
      }
    }
    this.getSectores();
    this.getMunicipios();
    this.getDepartamentos();
  },
  abrirModalSuper(accion, id, nombre, data = []) {
    const el = document.body;
    el.classList.add("abrirModal");
    this.modal = 1;
    this.proyecto_id = id;
    this.modalsTitle = nombre;
    switch(accion){
      case 'registrar':
      this.titleMRS = 'Registrar supervisión en: ';
      this.tipoAccion2 = 1;

      break;
      case 'actualizar':
      this.getSupervision(id);
      this.titleMRS = 'Actualizar supervisión de: ';
      this.tipoAccion2 = 2;

      break;
    }
  },
  abrirModalID() {
    const el = document.body;
    el.classList.add("abrirModal");
    this.modalID = 1;
    this.listarInstitucionDes(1, this.proceso, "");
  },
  cerrarModalID() {
    const el = document.body;
    el.classList.remove("abrirModal");
    this.modalID = 0;
    this.arrayInstitucionDes = [];
    this.paginationInstiDes = "";
  },
  cerrarModalSuper() {
    const el = document.body;
    el.classList.remove("abrirModal");
    this.modal = 0;
    this.date = "";
    this.descripcion = "";
      //this.proyecto_id = 0;
      //this.images = [];
      //this.files = [];
      //this.supervision = {};
      this.modalsTitle = "";
      this.titleMRS = "";
      this.tipoAccion2 = 0;
    },
    cerrarModal() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modal = 0;
      this.tituloModal = "";
      this.tipoproceso_id = 0;
      this.tipoAccion = 0;
      this.nombre = "";
      this.direccion = "";
      this.phone = "";
      this.email = "";
      this.arrayMunicipios = [];
      this.arrayDepartamentos = [];
      this.arraySectores = [];
      this.municipio_id = 0;
      this.sector_id = 0;
      this.departamento_id = 0;
      this.institucion_id = 0;
      this.estado = 0;
      this.departamento_id = 0;
      // this.errors = [];
      this.nombreUpd = "";
      this.loading = true;
   },
   cambiarPagina(page, proceso, buscar) {
    let me = this;
      //Actualiza la pagina actual
      me.pagination.current_page = page;
      //Envia la pericion para visualizar los datos
      if (me.arrayProyectos.length > 0) {
        me.getProyectosInsti(me.institucion["id"], page, "", this.proceso);

      }else {
        me.listarInstitucion(page, proceso, buscar);
      }
    },
    cambiarPaginaPID(page, proceso, buscar) {
      let me = this;
      //Actualiza la pagina actual
      me.paginationInstiDes.current_page = page;
      //Envia la pericion para visualizar los datos
      me.listarInstitucionDes(page,this.proceso,"");
    },
    searchEmpty() {
      let me = this;
      //Aqui hice la verificacion si hay o no datos para mostrar mensaje
      if (me.arrayInstitucion.length == 0) {
        me.search = 1;
      } else {
        me.search = 0;
      }
      return me.search;
    },
    desactivarInstitucion(id) {
      swal({
        title: "Esta seguro de desactivar esta Institucion?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn update",
        cancelButtonClass: "btn edit",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          me.loadSpinner = 1;
          axios.put("/institucion/desactivar", {
            id: id
          })
          .then(function(response) {
            me.listarInstitucion(1, me.proceso, "");
            swal(
              "Desactivado!",
              "El Registro ha sido desactivado con exito",
              "success"
              );
            me.loadSpinner = 0;
          })
          .catch(function(error) {
            me.loadSpinner = 0;
            console.log(error);
          });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
          ) {
        }
      });
    },
    activarInstitucion(id) {
      swal({
        title: "Esta seguro de activar esta Institucion?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn update",
        cancelButtonClass: "btn edit",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          me.loadSpinner = 1;
          axios.put("/institucion/activar", {
            id: id
          })
          .then(function(response) {
            me.listarInstitucionDes(1,me.proceso,"");
            me.listarInstitucion(1, me.proceso, "");
            swal(
              "Activada!",
              "La Institucion ha sido activada con exito",
              "success"
              );
            me.loadSpinner = 0;
          })
          .catch(function(error) {
            me.loadSpinner = 0;
            console.log(error);
          });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
          ) {
        }
      });
    },
    verMasInfo(data = []) {
      this.institucion = data;
      this.infoInsti = this.institucion["id"];
      this.getProyectosInsti(this.infoInsti, 1, "", this.proceso);
      this.verCard = 2;
      this.buscar = "";
    },
    back() {
      this.loadSpinner = 1;
      this.institucion = [];
      this.arrayProyectos = [];
      this.infoInsti = 0;
      this.pagination = this.paginationInsti;
      this.verCard = 1;
      this.buscar = "";
      this.loadSpinner = 0;
    },
    OnDragEnter(e) {
      e.preventDefault();
      this.dragCount++;
      this.isDragging = true;
      return false;
    },
    OnDragLeave(e) {
      e.preventDefault();
      this.dragCount--;

      if (this.dragCount <= 0) this.isDragging = false;
    },
    onInputChange(e) {
      const files = e.target.files;
      Array.from(files).forEach(file => this.addImage(file));
    },
    onDrop(e) {
      e.preventDefault();
      e.stopPropagation();
      this.isDragging = false;
      const files = e.dataTransfer.files;
      Array.from(files).forEach(file => this.addImage(file));
    },
    addImage(file) {
      if (!file.type.match("image.*")) {
        return;
      }
      this.loading = true;
      this.files.push(file);
      const img = new Image(),
      reader = new FileReader();
      reader.onload = e => this.images.push(e.target.result);
      reader.readAsDataURL(file);
      this.loading = false;
    },
    getFileSize(size) {
      const fSExt = ["Bytes", "KB", "MB", "GB"];
      let i = 0;
      while (size > 900) {
        size /= 1024;
        i++;
      }
      return `${Math.round(size * 100) / 100} ${fSExt[i]}`;
    },
    removeImage(index) {
      this.loading = true;
      this.files.splice(index, 1);
      this.images.splice(index, 1);
      this.loading = false;
    },
    clearDatos() {
      this.getProyectosInsti(this.infoInsti, 1, "", this.proceso);
      this.date = "";
      this.observacion = "";
      this.proyecto_id = 0;
      this.images = [];
      this.files = [];
      this.cerrarModalSuper();
    }
  },
  components: {},
  mounted() {
    this.maxDatetime;
    this.getImg();
  }
};
</script>
<style lang="scss" scoped>
.uploader {
  width: 100%;
  background: #533fd0;
  color: #fff;
  padding: 40px 15px;
  text-align: center;
  border-radius: 10px;
  border: 3px dashed #fff;
  font-size: 20px;
  position: relative;

  &.dragging {
    background: #fff;
    color: #533fd0;
    border: 3px dashed #533fd0;

    .file-input label {
      background: #533fd0;
      color: #fff;
    }
  }

  i {
    font-size: 85px;
  }

  .file-input {
    width: 200px;
    margin: auto;
    height: 68px;
    position: relative;

    label,
    input {
      background: #fff;
      color: #533fd0;
      width: 100%;
      position: absolute;
      left: 0;
      top: 0;
      padding: 10px;
      border-radius: 4px;
      margin-top: 7px;
      cursor: pointer;
    }

    input {
      opacity: 0;
      z-index: -2;
    }
  }

  .images-preview {
    display: flex;
    flex-wrap: wrap;
    margin-top: 20px;

    .img-wrapper {
      width: 160px;
      display: flex;
      flex-direction: column;
      margin: 10px;
      height: 150px;
      justify-content: space-between;
      background: #fff;
      box-shadow: 5px 5px 20px #3e3737;

      img {
        max-height: 105px;
      }
      .remove {
        background-color: Transparent;
        background-repeat: no-repeat;
        border: none;
        cursor: pointer;
        color: rgba(0, 0, 0, 0.5);
        position: relative;
        right: 80px;
        bottom: 12px;
        left: -16px;
        margin-bottom: -40px;
        will-change: transform, opacity;
        box-shadow: 0 0 0 0 hsla(0, 0%, 100%, 0);
        transition: box-shadow 0.25s ease-in;
        width: 1px;
        i {
          font-size: 28px;
          color: #fff;
        }
      }
    }

    .details {
      font-size: 12px;
      background: #fff;
      color: #000;
      display: flex;
      flex-direction: column;
      align-items: self-start;
      padding: 3px 6px;

      .name {
        overflow: hidden;
        height: auto;
      }
    }
  }

  .upload-control {
    position: absolute;
    width: 100%;
    background: #fff;
    top: 0;
    left: 0;
    border-top-left-radius: 7px;
    border-top-right-radius: 7px;
    padding: 10px;
    padding-bottom: 4px;
    text-align: right;

    button,
    label {
      background: #533fd0;
      border: 2px solid #533fd0;
      border-radius: 3px;
      color: #fff;
      font-size: 15px;
      cursor: pointer;
    }

    label {
      padding: 2px 5px;
      margin-right: 10px;
    }
  }
}
.menu {
  cursor: pointer;
}
</style>
<style>
.button {
  display: inline-block;
  margin: 0.3em;
  padding: 1.0em 1em;
  overflow: hidden;
  position: relative;
  text-decoration: none;
  text-transform: capitalize;
  border-radius: 3px;
  -webkit-transition: 0.3s;
  -moz-transition: 0.3s;
  -ms-transition: 0.3s;
  -o-transition: 0.3s;
  transition: 0.3s;
  box-shadow: 0 2px 10px rgba(0,0,0,0.5);
  border: none;
  font-size: 15px;
  text-align: center;
}

.button:hover {
  box-shadow: 1px 6px 15px rgba(0,0,0,0.5);
}

.green {
  background-color: #4CAF50;
  color: white;
}

.red {
  background-color: #F44336;
  color: white;
}

.blue {
  background-color: #6200EC;
  color: white;
}

.ripple {
  position: absolute;
  background: rgba(0,0,0,.25);
  border-radius: 100%;
  transform: scale(0.2);
  opacity:0;
  pointer-events: none;
  -webkit-animation: ripple .75s ease-out;
  -moz-animation: ripple .75s ease-out;
  animation: ripple .75s ease-out;
}

@-webkit-keyframes ripple {
  from {
    opacity:1;
  }
  to {
    transform: scale(2);
    opacity: 0;
  }
}

@-moz-keyframes ripple {
  from {
    opacity:1;
  }
  to {
    transform: scale(2);
    opacity: 0;
  }
}

@keyframes ripple {
  from {
    opacity:1;
  }
  to {
    transform: scale(2);
    opacity: 0;
  }
}


</style>
