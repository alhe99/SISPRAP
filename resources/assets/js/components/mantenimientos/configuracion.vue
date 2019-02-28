<template>
<div class="col-lg-12 col-md-12">
   <div class="row">
      <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
      </div>
   </div>
   <!-- <div class="card"> -->
   <div id="tabs" class="container col-md-12">
      <div class="tabs">
         <a v-on:click="activetab=1" v-bind:class="[activetab===1?'active':'']"><i class="mdi mdi-format-list-bulleted mdi-18px"></i> Carreras</a>
         <a v-on:click="activetab=2" v-bind:class="[activetab===2?'active':'']"><i class="mdi mdi-face mdi-18px"></i> Estudiantes</a>
      </div>
      <div class="content">
         <div v-if="activetab === 1" class="tabcontent" style="background-color: #fff">
            <br><br>
            <h2 class="text-left font-weight-bold">Listado de carreras activas</h2>
            <div class="panel panel-default">
               <div class="panel-body">
                  <div class="form-group row">
                     <div class="col-md-12">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <mdc-textfield type="text" class="col-md-12" @keyup="listarCarreras(1,  buscar)"  label="Nombre de la carrera" v-model="buscar"></mdc-textfield>
                              </div>
                           </div>
                           <div class="col-md-6 text-right">
                              <button type="button" @click="abrirModalID()" class="button secondary text-rigth " data-toggle="tooltip" title="Carreras Desactivadas"><i class="mdi mdi-delete-empty"></i>&nbsp;Registros desactivados</button>
                           </div>
                        </div>
                     </div>
                     <!--tabla de carreras-->
                     <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="table-responsive">
                           <table id="myTable" class="table table-striped table-bordered table-mc-light-blue">
                              <thead class="thead-primary">
                                 <tr>
                                    <th>Nombre de Carrera</th>
                                    <th class="text-right" style="padding-right: 35px;">Acciones</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr v-for="carrera in arrayCar" :key="carrera.id">
                                    <td v-text="carrera.nombre"></td>
                                    <td class="text-right">
                                       <button type="button" @click="abrirModal('car','actualizar',carrera)" class="button blue" data-toggle="tooltip" title="Editar datos de la carrera"><i class="mdi mdi-border-color"></i></button>
                                       <template v-if="carrera.estado">
                                          <button type="button" @click="desactivarCarrera(carrera.id)" class="button red" data-toggle="tooltip" title="Desactivar carrera"><i class="mdi mdi-delete-variant"></i></button>
                                       </template>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <nav>
                              <ul class="pagination">
                                 <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page -1, buscar)">Ant</a>
                                 </li>
                                 <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar)" v-text="page"></a>
                                 <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                                 </li>
                                 <small v-show="arrayCar.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayCar.length + ' de ' + pagination.total + ' registros)'"></small>
                              </ul>
                           </nav>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                           <div v-show="search == 1"  class="alert alert-primary h6 font-weight-bold text-center" role="alert" v-text="'No se encontraron resultados o No hay registros'"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- MODAL PARA REGISTRAR Y ACTUALIZAR DATOS  -->
               <div class="modal fade" :class="{'mostrar' : modalId }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                              <div class="col-md-12 col-xs-12 col-lg-12">
                                 <br><label for="nombre">Nombre de la carrera*</label>
                                 <input type="text" v-model="car" id="car" name="car" class="form-control" autocomplete="off">
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <div class="row">
                              <div class="col-md-12">
                                 <button type="button"  @click="cerrarModal()" class="button red"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
                                 <button type="button" :disabled="validate == true" class="button blue" @click="actualizarCarrera" dense><i class="mdi mdi-content-save"></i>&nbsp;Actualizar Carrera</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!--- FIN MODAL PARA REGISTRAR Y ACTUALIZAR DATOS -->
               <!-- MODAL LISTADO DE CARRERAS DESACTIVADAS  -->
               <div class="modal fade" :class="{'mostrar' : modalId2 }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title text-white" v-text="tituloModal"></h4>
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
                              <input v-model="buscarDes" @keyup="listarCarrerasDes(1, buscarDes)" class="form-control" data-toggle="tooltip" title="Buscar Registros" type="text" id="search" placeholder="Ingrese Nombre de la Carrera">
                              </span>
                           </div>
                           <br>
                           <div class="col-md-12 col-lg-12 col-sm-12">
                              <div class="table-responsive">
                                 <table id="myTable" class="table table-striped table-bordered table-mc-light-blue">
                                    <thead class="thead-primary">
                                       <tr>
                                          <th>Nombre de Carrera</th>
                                          <th class="text-center">Estado</th>
                                          <th class="text-right" style="padding-right: 35px;">Acciones</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr v-for="carrera in arrayCarDes" :key="carrera.id">
                                          <td v-text="carrera.nombre"></td>
                                          <td class="text-center">
                                             <template>
                                                <h4>
                                                   <span v-if="carrera.estado == 0" class="badge badge-pill badge-info">Desactivada</span>
                                                </h4>
                                             </template>
                                          <td class="text-right">
                                             <template v-if="carrera.estado == 0">
                                                <button type="button" @click="activarCarrera(carrera.id)" class="button blue" data-toggle="tooltip" title="Desactivar carrera"><i class="mdi mdi-delete-variant"></i></button>
                                             </template>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <nav>
                                    <ul class="pagination">
                                       <li class="page-item" v-if="paginationID.current_page > 1">
                                          <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaDes(paginationID.current_page -1, buscar)">Ant</a>
                                       </li>
                                       <li class="page-item" v-for="page in pagesNumberID" :key="page" :class="[page == isActivedID ? 'active' : '']">
                                          <a class="page-link" href="#" @click.prevent="cambiarPaginaDes(page, buscar)" v-text="page"></a>
                                       <li class="page-item" v-if="paginationID.current_page < paginationID.last_page">
                                          <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaDes(paginationID.current_page + 1,buscar)">Sig</a>
                                       </li>
                                       <small v-show="arrayCarDes.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayCarDes.length + ' de ' + paginationID.total + ' registros)'"></small>
                                    </ul>
                                 </nav>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12 col-sm-12 col-lg-12">
                                 <div v-show="searchID == 1"  class="alert alert-primary h6 font-weight-bold text-center" role="alert" v-text="'No se encontraron resultados o No hay registros'"></div>
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <div class="row">
                              <div class="col-md-12">
                                 <button type="button"  @click="cerrarModalID()" class="button red"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!--- FIN MODAL PARA  LISTADOS DE CARRERAS DESACTIVADAS -->
            </div>
         </div>
         <div v-if="activetab === 2" class="tabcontent" style="background-color: #fff">
            <br><br>
            <h2 class="text-left font-weight-bold">Listado de alumnos en el sistema</h2>
            <br>
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-8">
                        <v-select v-model="carrera_selected" :options="arrayCarrerasToSelect" placeholder="Seleccione Una Carrera Para ver el listado de estudiantes">
                           <span slot="no-options">
                           No hay datos disponibles
                           </span>
                        </v-select>
                     </div>
                     <div class="col-md-4">
                        <v-select v-model="nivelSelected" :options="arrayNiveles" placeholder="Seleccione nivel academico">
                           <span slot="no-options">No hay datos disponibles</span>
                        </v-select>
                        <span class="text-danger" v-if="carrera_selected != 0 && nivelSelected == 0 || nivelSelected == null">Seleccione nivel academico</span>
                     </div>
                  </div>
               </div>
               <br>
               <div v-if="carrera_selected != 0 && carrera_selected != null && nivelSelected != null" class="col-md-12 col-sm-12 col-lg-12">
                  <div class="row">
                     <div class="col-md-5">
                        <mdc-textfield type="text" style="margin-left: -10px" class="col-md-12"  @keyup="getAlumnos(1,buscarAlumno)"  label="Nombre del estudiante" v-model="buscarAlumno"></mdc-textfield>
                     </div>
                     <div class="col-md-6" v-if="nivelSelected != null && nivelSelected != 0">
                        <br>
                        <div style="border-radius: 8px;" class="alert alert-primary font-weight-bold" role="alert">
                           Carrera: {{carrera_selected.label}} - Nivel: {{nivelSelected.label}}
                        </div>
                     </div>
                     <div class="col-md-1 text-center" v-if="nivelSelected != null && nivelSelected != 0">
                        <br><button class="button red" @click="abrirModalEstudiantesDesactivados" style="top: -8px;"><i class="mdi mdi-delete-empty"></i></button>
                     </div>
                  </div>
               </div>
               <div v-if="carrera_selected != 0 && carrera_selected != null && nivelSelected != null" class="col-md-12 col-lg-12 col-sm-12">
                  <br>
                  <div class="table-responsive">
                     <table class="table table-striped table-bordered table-mc-light-blue">
                        <thead class="thead-primary">
                           <tr>
                              <th>Código</th>
                              <th>Nombre Estudiante</th>
                              <th class="text-left">Nivel Académico</th>
                              <th class="text-center">Acciones</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-for="item in arrayEstudiantes" :key="item.id">
                              <td v-text="item.codCarnet"></td>
                              <td v-text="item.nombre+' '+item.apellido"></td>
                              <td class="text-left" v-text="item.nivel_academico_id == 1 ? 'Primer Año' : 'Segundo Año'"></td>
                              <td class="text-center">
                                 <button type="button" @click="abrirModalChangeNivel(item)" class="btn-sm button blue " data-toggle="tooltip" title="Cambiar Nivel Académico"><i class="mdi mdi-bookmark-check"></i></button>
                                 <button type="button" @click="desactivarEstudiante(item.id,item.nombre)" class="btn-sm button secondary" data-toggle="tooltip" title="Desactivar Alumno"><i class="mdi mdi-delete-variant"></i></button>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <nav>
                     <ul class="pagination">
                        <li class="page-item" v-if="paginationEstudiantes.current_page > 1">
                           <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaEstudiante(paginationEstudiantes.current_page -1,buscarAlumno)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumberEstudiante" :key="page" :class="[page == isActivedEstudiante ? 'active' : '']">
                           <a class="page-link" href="#" @click.prevent="cambiarPaginaEstudiante(page,buscarAlumno)" v-text="page"></a>
                        <li class="page-item" v-if="paginationEstudiantes.current_page < paginationEstudiantes.last_page">
                           <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaEstudiante(paginationEstudiantes.current_page + 1,buscarAlumno)">Sig</a>
                        </li>
                        <small v-show="arrayEstudiantes.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayEstudiantes.length + ' de ' + paginationEstudiantes.total + ' registros)'"></small>
                     </ul>
                  </nav>
                  <div v-if="arrayEstudiantes.length == 0" class="alert alert-warning" role="alert">
                     <h4 class="font-weight-bold text-center">No hay datos registrados</h4>
                  </div>
               </div>
               <!--///////// MODAL PARA EDITAR FECHA DE INCIO DE PROYECTO/////////-->
               <div class="modal fade" :class="{'mostrar' : modalNivel }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title text-white">Nivel académico de {{nombre_estudiante_selected | truncate(25)}}</h4>
                           <button type="button" @click="cerrarModalChangeNivel()" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true" class="text-white">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="select">
                                    <select @change="activateButton" width="100%" class="select-text" id="nivelAcademico">
                                       <option v-for="nivel in arrayNiveles" :selected="nivel.value == nivelSelected.value" :key="nivel.value" :value="nivel.value" v-text="nivel.label"></option>
                                    </select>
                                    <br><label class="select-label">Seleccione Nivel Académico</label>
                                 </div>
                                 <br>
                              </div>
                              <br>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <div class="row">
                              <div class="col-md-12">
                                 <button type="button" @click="cerrarModalChangeNivel()" class="button red"><i class="mdi  mdi-close-box"></i>&nbsp;Cancelar</button>
                                 <button type="button" @click="changeNivelAcademico()" id="btnChangeNivel" disabled class="button blue disabled"><i class="mdi mdi-content-save"></i>&nbsp;Guardar Datos</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!--///////// FIN DE MODAL PARA EDITAR FECHA DE INCIO DE PROYECTO/////////-->
              <!-- MODAL LISTADO DE ESTUDINTES DESACTIVADOS  -->
               <div class="modal fade" :class="{'mostrar' : modalEstudiantesDesactivados }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" style="min-width: 950px;">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title text-white">Estudiantes desactivados de {{carrera_selected.label}} - {{nivelSelected.label}}</h4>
                           <button type="button" @click="cerrarModalEstudiantesDesactivados()" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true" class="text-white">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <div class="bmd-form-group bmd-collapse-inline pull-xs-right">
                              <button class="btn bmd-btn-icon" for="search" data-toggle="collapse" data-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search">
                              <i class="mdi mdi-magnify"></i>
                              </button>
                              <span id="collapse-search" class="collapse">
                              <input v-model="buscarEstudianteDesactivados" @keyup="getAlumnosDesactivados(1,buscarEstudianteDesactivados)" class="form-control" data-toggle="tooltip" title="Buscar Registros" type="text" id="search" placeholder="Ingrese Nombre del estudiante">
                              </span>
                           </div>
                           <br>
                           <div class="col-md-12 col-lg-12 col-sm-12">
                              <div class="table-responsive">
                                 <table id="myTable" class="table table-striped table-bordered table-mc-light-blue">
                                    <thead class="thead-primary">
                                       <tr>
                                            <th>Código</th>
                                            <th>Nombre Estudiante</th>
                                            <th class="text-left">Nivel Académico</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr v-for="estudiante in arrayEstudiantesDesactivados" :key="estudiante.id">
                                          <td  v-text="estudiante.codCarnet"></td>
                                          <td v-text="estudiante.nombre+''+estudiante.apellido"></td>
                                          <td class="text-left" v-text="estudiante.nivel_academico_id == 1 ? 'Primer Año' : 'Segundo Año'"></td>
                                          <td class="text-center">
                                             <template>
                                                <h4>
                                                   <span v-if="estudiante.estado == 0" class="badge badge-pill badge-info">Desactivado</span>
                                                </h4>
                                             </template>
                                          <td class="text-center">
                                                <button type="button" @click="activarEstudiante(estudiante.id,estudiante.nombre)" class="button blue" data-toggle="tooltip" title="Desactivar estudiante"><i class="mdi mdi-restart"></i></button>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <nav>
                                    <ul class="pagination">
                                       <li class="page-item" v-if="paginationEstudiantesDesactivados.current_page > 1">
                                          <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaEstudianteDesactivados(paginationEstudiantesDesactivados.current_page -1, buscarEstudianteDesactivados)">Ant</a>
                                       </li>
                                       <li class="page-item" v-for="page in pagesNumberEstudianteDesactivados" :key="page" :class="[page == isActivedEstudianteDesactivados ? 'active' : '']">
                                          <a class="page-link" href="#" @click.prevent="cambiarPaginaEstudianteDesactivados(page, buscarEstudianteDesactivados)" v-text="page"></a>
                                       <li class="page-item" v-if="paginationEstudiantesDesactivados.current_page < paginationEstudiantesDesactivados.last_page">
                                          <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaEstudianteDesactivados(paginationEstudiantesDesactivados.current_page + 1,buscarEstudianteDesactivados)">Sig</a>
                                       </li>
                                       <small v-show="arrayEstudiantesDesactivados.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayEstudiantesDesactivados.length + ' de ' + paginationEstudiantesDesactivados.total + ' registros)'"></small>
                                    </ul>
                                 </nav>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12 col-sm-12 col-lg-12">
                                 <div v-show="arrayEstudiantesDesactivados.length == 0"  class="alert alert-primary h6 font-weight-bold text-center" role="alert" v-text="'No se encontraron resultados o No hay registros'"></div>
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <div class="row">
                              <div class="col-md-12">
                                 <button type="button"  @click="cerrarModalEstudiantesDesactivados()" class="button red"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!--- FIN MODAL PARA  LISTADOS DE CARRERAS DESACTIVADAS -->
            </div>
         </div>
      </div>
      <br>
   </div>
</div>
</template>
<script>
 export default {
  data() {
    return {
      activetab: 1,
      loadSpinner: 0,
      modalId: 0,
      modalId2: 0,
      carrera: 0,
      car_id: 0,
      search: 0,
      searchID: 0,
      tituloModal: "",
      carUpd: "",
      nombre: "",
      buscar: "",
      buscarDes: "",
      admin_id: 0,
      carrerasProy: 0,
      car: '',
      tipoAccion: 0,
      arrayCarreras: [],
      arrayCarrerasToSelect: [],
      carrera_selected: 0,
      arrayCar: [],
      arrayCarDes: [],
      paginationID: {},
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      paginationEstudiantes: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
       paginationEstudiantesDesactivados: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      nivelSelected: 0,
      arrayNiveles: [{
          value: 1,
          label: "Primer Año"
        },
        {
          value: 2,
          label: "Segundo Año"
        }
      ],
      buscarAlumno: '',
      arrayEstudiantes: [],
      modalNivel: 0,
      nivelByEstudiante: 0,
      estudiante_selected: 0,
      nombre_estudiante_selected: '',
      modalEstudiantesDesactivados: 0,
      arrayEstudiantesDesactivados: [],
      buscarEstudianteDesactivados:''
    };
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    isActivedEstudiante: function () {
      return this.paginationEstudiantes.current_page;
    },
    isActivedEstudianteDesactivados: function () {
      return this.paginationEstudiantesDesactivados.current_page;
    },
    isActivedID: function () {
      return this.paginationID.current_page;
    },
    pagesNumber: function () {
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
    pagesNumberEstudiante: function () {
      if (!this.paginationEstudiantes.to) {
        return [];
      }
      var from = this.paginationEstudiantes.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.paginationEstudiantes.last_page) {
        to = this.paginationEstudiantes.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    pagesNumberEstudianteDesactivados: function () {
      if (!this.paginationEstudiantesDesactivados.to) {
        return [];
      }
      var from = this.paginationEstudiantesDesactivados.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.paginationEstudiantesDesactivados.last_page) {
        to = this.paginationEstudiantesDesactivados.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    pagesNumberID: function () {
      if (!this.paginationID.to) {
        return [];
      }
      var from = this.paginationID.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.paginationID.last_page) {
        to = this.paginationID.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  watch: {
    activetab: function () {
      if (this.activetab == 2) {
        this.getCarreras(1, '');
      }
    },
    nivelSelected: function () {
      if ((this.nivelSelected != 0) && (this.nivelSelected != null))
        this.getAlumnos(1, '');
    },
    carrera_selected: function () {
      if ((this.carrera_selected != 0) && (this.nivelSelected != 0) && (this.carrera_selected != null) && (this.nivelSelected != null)) {
        this.getAlumnos(1, '');
      }
    },
  },
  methods: {
    getAlumnos(page, buscar) {
      let me = this;
      me.loadSpinner = 1;
      var url = route('getEstudianteToOtherOpctions', {
        'carrera_id': me.carrera_selected.value,
        'nivelAcad': me.nivelSelected.value,
        'page': page,
        'buscar': buscar
      });
      axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayEstudiantes = respuesta.estudiantes.data;
          me.paginationEstudiantes = respuesta.pagination;
          me.loadSpinner = 0;
        })
        .catch(function (error) {
          me.loadSpinner = 0;
          console.log(error);
        });
    },
    getAlumnosDesactivados(page,buscar){
      let me = this;
      me.loadSpinner = 1;
      var url = route('getEstudianteToOtherOpctionsDesactivados', {
        'carrera_id': me.carrera_selected.value,
        'nivelAcad': me.nivelSelected.value,
        'page': page,
        'buscar': buscar
      });
      axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayEstudiantesDesactivados = respuesta.estudiantes.data;
          me.paginationEstudiantesDesactivados = respuesta.pagination;
          me.loadSpinner = 0;
        })
        .catch(function (error) {
          me.loadSpinner = 0;
          console.log(error);
        });
    },
    changeNivelAcademico(){
      swal({
        title: "Desea Guardar Los Datos Cambiados Realizados",
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "button blue",
        cancelButtonClass: "button red",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          me.loadSpinner = 1;
          var url = route('changeNivelToEstudiante',{'estudiante_id': me.estudiante_selected,'newNivel': $("#nivelAcademico").val().trim()});
          axios.post(url)
            .then(function (response) {
              me.getAlumnos(1, '');
              me.cerrarModalChangeNivel();
              me.loadSpinner = 0;
            })
            .catch(function (error) {
              me.loadSpinner = 0;
              console.log(error);
            });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
        ) {}
      });
    },
    //Activar boton de cambiar fecha
    activateButton(){
       $("#btnChangeNivel").prop('disabled',false);
       $("#btnChangeNivel").removeClass('disabled');
    },
    //obtener todas las carreras
    getCarreras() {
      let me = this;
      var url = route('GetCarreras');
      axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayCarrerasToSelect = respuesta;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    desactivarEstudiante(estudiante_id,estudiante_nombre){
      swal({
        title: "Esta seguro de desactivar a " + estudiante_nombre,
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "button blue",
        cancelButtonClass: "button red",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          var url = route('desactivarEstudiante',estudiante_id);
          me.loadSpinner = 1;
          axios.put(url)
            .then(function (response) {
              me.getAlumnos(1,'');
              swal(
                "Desactivado!",
                "El Registro ha sido desactivado con éxito",
                "success"
              );
              me.loadSpinner = 0;
            })
            .catch(function (error) {
              me.loadSpinner = 0;
              console.log(error);
            });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
        ) {}
      });
    },
    activarEstudiante(estudiante_id,estudiante_nombre){
      swal({
        title: "Esta seguro de activar a " + estudiante_nombre,
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "button blue",
        cancelButtonClass: "button red",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          var url = route('activarEstudiante',estudiante_id);
          me.loadSpinner = 1;
          axios.post(url)
            .then(function (response) {
              me.getAlumnos(1,'');
              me.getAlumnosDesactivados(1,'');
              swal(
                "Activado!",
                "El Registro ha sido activado con éxito",
                "success"
              );
              me.loadSpinner = 0;
            })
            .catch(function (error) {
              me.loadSpinner = 0;
              console.log(error);
            });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
        ) {}
      });
    },
    //carrera
    listarCarreras(page, buscar) {
      let me = this;
      var urlCarreraList = route('carreraList', {
        "page": page,
        "buscar": buscar
      });
      me.loadSpinner = 1;
      axios
        .get(urlCarreraList)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayCar = respuesta.carrera.data;
          me.pagination = respuesta.pagination;
          //Por si no devuelve datos
          me.loadSpinner = 0;
          me.searchEmpty();

        })
        .catch(function (error) {
          me.loadSpinner = 0;
          console.log(error);
        });
    },
    listarCarrerasDes(page, buscar) {
      let me = this;
      var urlDesactivadasCar = route('carrerasDesactivadas', {
        "page": page,
        "buscar": buscar
      });
      me.loadSpinner = 1;
      axios
        .get(urlDesactivadasCar)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayCarDes = respuesta.carrera.data;
          me.paginationID = respuesta.pagination;
          //Por si no devuelve datos
          me.loadSpinner = 0;
          //Por si no devuelve datos
          if (me.arrayCarDes.length == 0) {
            me.searchID = 1;
          } else {
            me.searchID = 0;
          }

        })
        .catch(function (error) {
          me.loadSpinner = 0;
          console.log(error);
        });
    },
    cambiarPagina(page, buscar) {
      let me = this;
      me.pagination.current_page = page;
      me.listarCarreras(page, buscar);
    },
    cambiarPaginaEstudiante(page, buscar) {
      let me = this;
      me.paginationEstudiantes.current_page = page;
      me.getAlumnos(page, buscar);
    },
    cambiarPaginaEstudianteDesactivados(page, buscar) {
      let me = this;
      me.paginationEstudiantesDesactivados.current_page = page;
      me.getAlumnosDesactivados(page, buscar);
    },
    cambiarPaginaDes(page, buscar) {
      let me = this;
      me.paginationID.current_page = page;
      me.listarCarrerasDes(page, buscar);
    },
    searchEmpty() {
      let me = this;
      //Aqui hice la verificacion si hay o no datos para mostrar mensaje
      if (me.arrayCar.length == 0) {
        me.search = 1;
      } else {
        me.search = 0;
      }
      return me.search;
    },
    abrirModal(modelo, accion, data = []) {
      const el = document.body;
      el.classList.add("abrirModal");
      switch (modelo) {
        case "car":
          {
            switch (accion) {
              case "actualizar":
                {
                  //Asignando los datos traidos a los controles del formulario
                  this.modalId = 1;
                  this.tituloModal = "Actualización de Carreras"
                  this.car_id = data["id"];
                  this.carrera = data["id"];
                  this.car = data["nombre"];
                  this.carUpd = data["nombre"];
                  break;
                }
            }
          }
      }
    },
    abrirModalID() {
      const el = document.body;
      el.classList.add("abrirModal");
      this.modalId2 = 2;
      this.tituloModal = "Carreras Desactivadas";
      this.listarCarrerasDes(1, "");
    },
    abrirModalEstudiantesDesactivados() {
      const el = document.body;
      el.classList.add("abrirModal");
      this.modalEstudiantesDesactivados = 2;
      this.getAlumnosDesactivados(1,"");
    },
    cerrarModalEstudiantesDesactivados() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modalEstudiantesDesactivados = 0;
      this.arrayEstudiantesDesactivados = [];
      this.paginationEstudiantesDesactivados = {};
    },
    abrirModalChangeNivel(item = []) {
      let me = this;
      const el = document.body;
      el.classList.add("abrirModal");
      me.nivelByAlumno = item.nivel_academico_id;
      me.estudiante_selected = item.id;
      me.nombre_estudiante_selected = item.nombre +" "+item.apellido;
      $("#nivelAcademico option:contains(" + item.nivel_academico_id + ")").attr("selected", true);
      me.modalNivel = 1;
    },
    cerrarModalChangeNivel() {
      const el = document.body;
      el.classList.remove("abrirModal");
      /* $('#nivelAcademico').empty(); */
      this.nivelByEstudiante = 0;
      this.estudiante_selected = 0;
      this.modalNivel = 0;
    },
    cerrarModal() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modalId = 0;
      this.car_id = 0;
      this.car = "";
      this.carUpd = "";
    },
    cerrarModalID() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modalId2 = 0;
    },
    desactivarCarrera(id) {
      var urlDesactivarCarrera = route('desactivarCarrera', {
        "id": id
      });
      swal({
        title: "Esta seguro de desactivar esta Carrera?",
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "button blue",
        cancelButtonClass: "button red",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          me.loadSpinner = 1;
          axios.put(urlDesactivarCarrera)
            .then(function (response) {
              me.listarCarreras(1, "");
              swal(
                "Desactivado!",
                "El Registro ha sido desactivado con éxito",
                "success"
              );
              me.loadSpinner = 0;
            })
            .catch(function (error) {
              me.loadSpinner = 0;
              console.log(error);
            });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
        ) {}
      });
    },
    activarCarrera(id) {
      var urlActivarcarrera = route('activarCarrera', {
        "id": id
      });
      swal({
        title: "Esta seguro de activar esta Carrera?",
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "button blue",
        cancelButtonClass: "button red",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          me.loadSpinner = 1;
          axios.put(urlActivarcarrera)
            .then(function (response) {
              me.listarCarrerasDes(1, "");
              me.listarCarreras(1, "");
              swal(
                "Activada!",
                "La Carrera ha sido activada con exito",
                "success"
              );
              me.loadSpinner = 0;
            })
            .catch(function (error) {
              me.loadSpinner = 0;
              console.log(error);
            });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
        ) {}
      });
    },
    actualizarCarrera() {
      let me = this;
      var urlActualizar = route('actualizarCarrera', {
        "id": me.car_id,
        "nombre": me.car
      });
      var url = route('validateCarrera', {
        "nombre": me.car
      });
      me.loadSpinner = 1;
      axios.get(url).then(function (response) {
        var respuesta = response.data;
        console.log(respuesta);
        if ((me.car != me.carUpd) && (respuesta == 'existe')) {
          swal({
            position: "center",
            type: "warning",
            title: "Nombre de Carrera Existente!",
            showConfirmButton: true,
            timer: 5000
          });
          me.car = "";
          me.loadSpinner = 0;
          me.exist = false;
        } else {
          axios
            .put(urlActualizar)
            .then(function (response) {
              me.loadSpinner = 0;
              swal({
                position: "center",
                type: "success",
                title: "Datos actualizada correctamente!",
                showConfirmButton: false,
                timer: 1000
              });
              me.cerrarModal();
              me.listarCarreras(1, "");
            })
            .catch(function (error) {
              swal({
                position: "center",
                type: "warning",
                title: "Ocurrio un error al actualizar el dato",
                showConfirmButton: false,
                timer: 1000
              });
              me.loadSpinner = 0;
              console.log(error);
            });
        }
      });
    },

  },
  mounted() {
    let me = this;
    me.listarCarreras(1, "");
  }
};
</script>
<style>
  /* RESET */
  * {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  }

  .container {
  margin: 8px auto;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 0.9em;
  color: rgb(108, 108, 108);
  }
  /* Style the tabs */
  .tabs a {
  float: left;
  cursor: pointer;
  padding: 12px 24px;
  transition: background-color 0.2s;
  border: 1px solid #ccc;
  border-right: none;
  background-color: #f1f1f1;
  border-radius: 10px 10px 0 0;
  font-weight: bold;
  }
  .tabs a:last-child {
  border-right: 1px solid #ccc;
  }
  .tabs a:hover {
  background-color: #aaa;
  color: rgb(54, 54, 54);
  }

  /* Styling for active tab */
  .tabs a.active {
  background-color: rgb(255, 255, 255);
  color: rgb(108, 108, 108);
  border-bottom: 2px solid #fff;
  cursor: default;
  }

  /* Style the tab content */
  .tabcontent {
  padding: 30px;
  border: 1px solid #ccc;
  border-radius: 10px;
  box-shadow: 3px 3px 6px #e1e1e1;
  background-color: #f1f1f1;
  }
  /* select starting stylings ------------------------------*/
  .select {
  font-family:
  'Roboto','Helvetica','Arial',sans-serif;
  position: relative;
  width: 100%;
  }

  .select-text {
  position: relative;
  font-family: inherit;
  background-color: transparent;
  width: 100%;
  padding: 25px 10px 10px 0;
  font-size: 18px;
  border-radius: 0;
  border: none;
  border-bottom: 1px solid rgba(0,0,0, 0.12);
  }

  /* Remove focus */
  .select-text:focus {
  outline: none;
  border-bottom: 1px solid rgba(0,0,0, 0);
  }

  /* Use custom arrow */
  .select .select-text {
  appearance: none;
  -webkit-appearance:none
  }

  .select:after {
  position: absolute;
  top: 18px;
  right: 10px;
  /* Styling the down arrow */
  width: 0;
  height: 0;
  padding: 0;
  content: '';
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-top: 6px solid rgba(0, 0, 0, 0.12);
  pointer-events: none;
  }


  /* LABEL ======================================= */
  .select-label {
  color: rgba(0,0,0, 0.26);
  font-size: 18px;
  font-weight: normal;
  position: absolute;
  pointer-events: none;
  left: 0;
  top: 10px;
  transition: 0.2s ease all;
  }

  /* active state */
  .select-text:focus ~ .select-label, .select-text:valid ~ .select-label {
  color: #2F80ED;
  top: 0px;
  transition: 0.2s ease all;
  font-size: 14px;
  }

  /* BOTTOM BARS ================================= */
  .select-bar {
  position: relative;
  display: block;
  width: 100%;
  }

  .select-bar:before, .select-bar:after {
  content: '';
  height: 2px;
  width: 0;
  bottom: 1px;
  position: absolute;
  background: #2F80ED;
  transition: 0.2s ease all;
  }

  .select-bar:before {
  left: 50%;
  }

  .select-bar:after {
  right: 50%;
  }

  /* active state */
  .select-text:focus ~ .select-bar:before, .select-text:focus ~ .select-bar:after {
  width: 50%;
  }

  /* HIGHLIGHTER ================================== */
  .select-highlight {
  position: absolute;
  height: 60%;
  width: 100px;
  top: 25%;
  left: 0;
  pointer-events: none;
  opacity: 0.5;
  }
</style>

