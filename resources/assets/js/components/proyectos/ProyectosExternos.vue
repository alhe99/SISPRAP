<template>
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <fieldset>
                  <legend class="text-center">Seleccione un proceso para la búsqueda de proyectos</legend>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row md-radio">
                        <div class="col-md-6 text-center">
                          <input
                            id="radioSS"
                            value="1"
                            v-model="proceso"
                            type="radio"
                            name="radioP"
                          >
                          <label for="radioSS">Servicio Social</label>
                        </div>
                        <div class="col-md-6 text-center">
                          <input
                            id="radioPP"
                            value="2"
                            v-model="proceso"
                            type="radio"
                            name="radioP"
                          >
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
    <div class="card" v-if="proceso != 0 ">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 col-sm-12 col-lg-6">
            <h3
              class="align-baseline font-weight-bold"
              v-if="proceso == 1"
            >Listado de proyectos externos de Servicio Social</h3>
            <h3
              class="align-baseline font-weight-bold"
              v-if="proceso == 2"
            >Listado de proyectos externos de Practica Profesional</h3>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-5 col-sm-12 col-lg-5">
                <div class="form-group row">
                  <mdc-textfield
                    type="text"
                    class="col-md-12"
                    @keyup="listarProyecto(1,proceso, buscar)"
                    label="Nombre del proyecto"
                    v-model="buscar"
                  ></mdc-textfield>
                </div>
              </div>
              <div class="col-md-7 col-sm-7 text-right">
                <div class="btn-group pull-xs-right">
                  <button
                    class="btn bmd-btn-icon dropdown-toggle"
                    type="button"
                    id="mw2"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i style="color: black;" class="mdi mdi-dots-vertical mdi-dark mdi-24px"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="mw2">
                    <button
                      class="dropdown-item d-block menu"
                      type="button"
                      @click="abrirModalID()"
                    >
                      <i class="mdi mdi-delete-empty"></i>Proyectos Desactivados
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--///////// MODAL PARA ASIGNAR PROYECTOS FUERA DEL SISTEMA /////////-->
      <div
        class="modal fade"
        :class="{'mostrar' : modalP }"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-white" v-text="tituloModalAsignarProy"></h4>
              <button
                type="button"
                @click="cerrarModalP()"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true" class="text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <fieldset>
                <legend class="text-center">Complete los datos requeridos</legend>
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div v-if="estudiantesSelected.length == 0" class="row">
                      <div class="col-md-12">
                        <div
                          class="alert alert-info font-weight-bold text-center"
                          role="alert"
                        >Seleccione de la lista de estudiantes los que serán asignados a este proyecto externo</div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12"></div>
                      <div class="col-md-12">
                        <div class="row">
                          <div :class="[proceso==2 ? 'col-md-12' : 'col-md-7']" class="col-md-7">
                            <label class="font-weight-bold">Seleccione Carrera y Año Academico*</label>
                            <v-select
                              v-model="carrera_selected"
                              :options="arrayCarreras"
                              placeholder="Seleccione una carrera"
                            >
                              <span slot="no-options">No hay datos disponibles</span>
                            </v-select>
                          </div>
                          <div v-if="proceso==1" class="col-md-5">
                            <br>
                            <v-select
                              v-model="nivelSelected"
                              :options="arrayNiveles"
                              placeholder="Nivel academico"
                            >
                              <span slot="no-options">No hay datos disponibles</span>
                            </v-select>
                            <span
                              class="text-danger"
                              v-if="carrera_selected != 0 && nivelSelected == 0"
                            >Seleccione nivel academico</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <br>
                        <pulse-loader class="text-center" :loading="loader"></pulse-loader>
                      </div>
                      <div v-if="proceso==1" class="col-md-10 col-sm-12 col-lg-6">
                        <mdc-textfield
                          v-if="nivelSelected != 0"
                          type="text"
                          style="margin-left: -10px"
                          class="col-md-12"
                          @keyup="getEstudianteByCarrer(1,buscarP)"
                          label="Nombre del estudiante"
                          v-model="buscarP"
                        ></mdc-textfield>
                      </div>
                      <div v-else class="col-md-10 col-sm-12 col-lg-6">
                        <mdc-textfield
                          v-if="carrera_selected != 0"
                          type="text"
                          style="margin-left: -10px"
                          class="col-md-12"
                          @keyup="getEstudianteByCarrer(1,buscarP)"
                          label="Nombre del estudiante"
                          v-model="buscarP"
                        ></mdc-textfield>
                      </div>
                      <div v-if="arrayEstudianteP.length != 0" class="col-md-12">
                        <br>
                        <table class="table table-striped table-bordered table-mc-light-blue">
                          <thead class="thead-primary">
                            <tr>
                              <th>Nombre Estudiante</th>
                              <th class="text-center">Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="item in arrayEstudianteP" :key="item.id">
                              <td v-text="item.nombre +' '+ item.apellido"></td>
                              <td>
                                <checkbox
                                  style="padding-left: 100px;"
                                  v-model="estudiantesSelected"
                                  :value="item.id"
                                >Asignar proyecto</checkbox>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <nav>
                          <ul class="pagination">
                            <li class="page-item" v-if="paginationP.current_page > 1">
                              <a
                                class="page-link font-weight-bold"
                                href="#"
                                @click.prevent="cambiarPaginaP(paginationP.current_page -1,buscarP)"
                              >Ant</a>
                            </li>
                            <li
                              class="page-item"
                              v-for="page in pagesNumberP"
                              :key="page"
                              :class="[page == isActivedP ? 'active' : '']"
                            >
                              <a
                                class="page-link"
                                href="#"
                                @click.prevent="cambiarPaginaP(page,buscarP)"
                                v-text="page"
                              ></a>
                            </li>
                            <li
                              class="page-item"
                              v-if="paginationP.current_page < paginationP.last_page"
                            >
                              <a
                                class="page-link font-weight-bold"
                                href="#"
                                @click.prevent="cambiarPaginaP(paginationP.current_page + 1,buscarP)"
                              >Sig</a>
                            </li>
                            <small
                              v-show="arrayEstudianteP.length != 0"
                              class="text-muted pagination-count"
                              v-text=" '(Mostrando ' + arrayEstudianteP.length + ' de ' + paginationP.total + ' registros)'"
                            ></small>
                          </ul>
                        </nav>
                      </div>
                      <div
                        v-if="arrayEstudianteP.length == 0 && carrera_selected != 0 && nivelSelected != 0"
                        class="col-md-12"
                      >
                        <div class="alert alert-warning" role="alert">
                          <h4 class="font-weight-bold text-center">No hay registros</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-md-12">
                  <button type="button" @click="cerrarModalP()" class="button red">Cerrar</button>
                  <button
                    type="button"
                    :class="[estudiantesSelected.length == 0 ? 'disabled' : '']"
                    :disabled="estudiantesSelected.length == 0"
                    @click="asignarProyecto"
                    class="button blue"
                  >Guardar Datos</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--///////// FIN DE MODAL PARA ASIGNAR PROYECTOS FUERA DEL SISTEMA /////////-->
      <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-mc-light-blue" id="example">
            <thead class="thead-primary">
              <tr>
                <th>Nombre del proyecto</th>
                <th>Institución</th>
                <th class="text-center">Fecha de registro</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="proyecto in arrayProyecto" :key="proyecto.id">
                <td v-text="proyecto.nombre"></td>
                <td v-text="proyecto.institucion.nombre"></td>
                <td class="text-center" v-text="proyecto.fecha"></td>
                <td class="text-center">
                  <button
                    type="button"
                    @click="abrirModal('proyecto','actualizar',proyecto)"
                    class="button secondary"
                    data-toggle="tooltip"
                    title="Editar datos del Proyecto"
                  >
                    <i class="mdi mdi-border-color"></i>
                  </button>
                  <button
                    type="button"
                    @click="desactivarProyecto(proyecto.id)"
                    class="button red"
                    data-toggle="tooltip"
                    title="Desactivar proyecto"
                  >
                    <i class="mdi mdi-delete"></i>
                  </button>
                  <button
                    class="button blue"
                    @click="openModalProy(proyecto)"
                    type="button"
                    ta-toggle="tooltip"
                    title="Asignar Proyecto"
                  >
                    <i class="mdi mdi-bookmark-plus"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a
                  class="page-link font-weight-bold"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page -1,proceso,buscar)"
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page,proceso,buscar)"
                  v-text="page"
                ></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a
                  class="page-link font-weight-bold"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page + 1,proceso,buscar)"
                >Sig</a>
              </li>
              <small
                v-show="arrayProyecto.length != 0"
                class="text-muted pagination-count"
                v-text=" '(Mostrando ' + arrayProyecto.length + ' de ' + pagination.total + ' registros)'"
              ></small>
            </ul>
          </nav>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12">
            <div
              v-show="search == 1"
              class="alert alert-primary h6 font-weight-bold text-center"
              role="alert"
              v-text="'No se encontraron resultados de la búsqueda: '+buscar"
            ></div>
          </div>
        </div>
      </div>
      <!--MODAL PARA PROYECTOS DESACTIVADOS-->
      <div
        class="modal fade"
        :class="{'mostrar' : modalID }"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-white">Proyectos Desactivados</h4>
              <button
                type="button"
                @click="cerrarModalID()"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true" class="text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="bmd-form-group bmd-collapse-inline pull-xs-right">
                <button
                  class="btn bmd-btn-icon"
                  for="search"
                  data-toggle="collapse"
                  data-target="#collapse-search"
                  aria-expanded="false"
                  aria-controls="collapse-search"
                >
                  <i class="mdi mdi-magnify"></i>
                </button>
                <span id="collapse-search" class="collapse">
                  <input
                    v-model="buscarID"
                    @keyup="listarProyectoDes(1,proceso,buscarID)"
                    class="form-control"
                    data-toggle="tooltip"
                    title="Buscar Registros"
                    type="text"
                    id="search"
                    placeholder="Ingrese Nombre del Proyecto"
                  >
                </span>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-mc-light-blue">
                      <thead class="thead-primary">
                        <tr>
                          <th>Nombre del proyecto</th>
                          <th>Fecha de registro</th>
                          <th class="text-center">Estado</th>
                          <th class="text-center">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="proyectosdes in arrayProyectosDes" :key="proyectosdes.id">
                          <td v-text="proyectosdes.nombre"></td>
                          <td v-text="proyectosdes.fecha"></td>
                          <td class="text-center">
                            <template>
                              <h4>
                                <span
                                  v-if="proyectosdes.estado == 0"
                                  class="badge badge-pill badge-danger"
                                >Desactivado</span>
                              </h4>
                            </template>
                          </td>
                          <td class="text-center">
                            <template>
                              <button
                                type="button"
                                @click="activarProyecto(proyectosdes.id)"
                                class="btn btn-primary"
                                data-toggle="tooltip"
                                title="Activar proyecto"
                              >
                                <i class="mdi mdi-check-circle i-crud"></i>
                              </button>
                            </template>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <nav>
                      <ul class="pagination">
                        <li class="page-item" v-if="paginationProyDes.current_page > 1">
                          <a
                            class="page-link font-weight-bold"
                            href="#"
                            @click.prevent="cambiarPaginaPID(paginationProyDes.current_page -1,proceso,buscar)"
                          >Ant</a>
                        </li>
                        <li
                          class="page-item"
                          v-for="page in pagesNumberPID"
                          :key="page"
                          :class="[page == isActivedPID ? 'active' : '']"
                        >
                          <a
                            class="page-link"
                            href="#"
                            @click.prevent="cambiarPaginaPID(page,proceso,buscar)"
                            v-text="page"
                          ></a>
                        </li>
                        <li
                          class="page-item"
                          v-if="paginationProyDes.current_page < paginationProyDes.last_page"
                        >
                          <a
                            class="page-link font-weight-bold"
                            href="#"
                            @click.prevent="cambiarPaginaPID(paginationProyDes.current_page + 1,proceso,buscar)"
                          >Sig</a>
                        </li>
                        <small
                          v-show="arrayProyectosDes.length != 0"
                          class="text-muted pagination-count"
                          v-text=" '(Mostrando ' + arrayProyectosDes.length + ' de ' + paginationProyDes.total + ' registros)'"
                        ></small>
                      </ul>
                    </nav>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <div
                        v-show="searchID == 1"
                        class="alert alert-primary h6 font-weight-bold text-center"
                        role="alert"
                        v-text="'No se encontraron resultados o No hay registros'"
                      ></div>
                      <pulse-loader
                        class="text-center"
                        :loading="loading"
                        :color="color"
                        :size="size"
                      ></pulse-loader>
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
      <!-- FIN DE MODAL PARA VER LOS PROYECTOS DESACTIVADOS-->
      <!-- MODAL PARA ACTUALIZAR DATOS  -->
      <div
        class="modal fade"
        :class="{'mostrar' : modal }"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-white">Actualización de Datos</h4>
              <button
                type="button"
                @click="cerrarModal()"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true" class="text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <pulse-loader class="text-center" :loading="loading" :color="color" :size="size"></pulse-loader>
                </div>
                <div class="col-md-12 col-xs-12 col-lg-12">
                  <br>
                  <label class="font-weight-bold" for="nombre">Nombre del proyecto</label>
                  <input
                    type="text"
                    v-model="nombreP"
                    id="nombre"
                    name="nombre"
                    class="form-control"
                    autocomplete="off"
                  >
                  <span v-if="errors.nombre" class="text-danger" v-text="errors.nombre[0]"></span>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12 col-xs-12 col-lg-12">
                  <br>
                  <label class="font-weight-bold" for="hrsRealizar">Horas a realizar</label>
                  <input
                    type="number"
                    v-model="hrsRealizar"
                    id="hrsRealizar"
                    name="hrsRealizar"
                    class="form-control"
                    autocomplete="off"
                    v-mask="'###'"
                    min="0"
                    :max="proceso==1 ? '300':'160'"
                  >
                </div>
              </div>
              <br>
              <div class="form-group row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                  <label for class="font-weight-bold">Actividades:*</label>
                  <br>
                  <vue-editor v-model="actividadesP" :editorToolbar="toolBars"></vue-editor>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-xs-12 col-lg-12">
                  <br>
                  <label class="font-weight-bold" for>Institución donde se realiza proyecto:*</label>
                  <br>
                  <v-select
                    label="label"
                    v-model="institucionP"
                    placeholder="Seleccione una institución"
                    :options="arrayInstitucion"
                  >
                    <span slot="no-options">No hay datos disponibles</span>
                  </v-select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-md-12">
                  <button type="button" @click="cerrarModal()" class="button red">
                    <i class="mdi mdi-close-box"></i>&nbsp;Cancelar
                  </button>
                  <button type="button" class="button blue" @click="actualizarProyecto">
                    <i class="mdi mdi-content-save"></i>&nbsp;Actualizar Proyecto
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--- FIN MODAL PARA REGISTRAR Y ACTUALIZAR DATOS -->
    </div>
    <div class="row">
      <div class="col-md-12 loading text-center" v-if="loadSpinner == 1"></div>
    </div>
  </div>
</template>
				<script>
import { VueEditor } from "vue2-editor";
import Switches from "vue-switches";
export default {
  data() {
    return {
      loadSpinner: 0,
      arrayProyecto: [],
      arrayInstitucion: [],
      nombreP: "",
      actividadesP: "",
      proyectoId: 0,
      institucionP: 0,
      proceso: 0,
      estado: 0,
      buscar: "",
      search: 0,
      searchID: 0,
      toolBars: [
        [{ header: [false, 1, 2, 3, 4, 5, 6] }],
        [
          { align: "" },
          { align: "center" },
          { align: "right" },
          { align: "justify" }
        ],
        ["bold", "blockquote", "code-block"],
        [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],
        [{ indent: "-1" }, { indent: "+1" }],
        [{ color: [] }]
      ],
      modal: 0,
      modalID: 0,
      arrayProyectosDes: [],
      buscarID: "",
      carreras_id: 0,
      paginationProyDes: {},
      loading: false,
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      paginationP: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      color: "#533fd0",
      size: "20px",
      disabledVE: false,
      hrsRealizar: 0,
      estudiante_id: 0,
      arrayEstudianteP: [],
      buscarP: "",
      offsetP: 3,
      modalP: 0,
      loader: false,
      carrera_selected: 0,
      arrayCarreras: [],
      tituloModalAsignarProy: "",
      proyecto_selected_id: 0,
      nivelSelected: 0,
      arrayNiveles: [
        { value: 1, label: "Primer Año" },
        { value: 2, label: "Segundo Año" }
      ],
      estudiantesSelected: []
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    isActivedPID: function() {
      return this.paginationProyDes.current_page;
    },
    isActivedP: function() {
      return this.paginationP.current_page;
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
      if (!this.paginationProyDes.to) {
        return [];
      }
      var from = this.paginationProyDes.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.paginationProyDes.last_page) {
        to = this.paginationProyDes.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    pagesNumberP: function() {
      if (!this.paginationP.to) {
        return [];
      }
      var from = this.paginationP.current_page - this.offsetP;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offsetP * 2;
      if (to >= this.paginationP.last_page) {
        to = this.paginationP.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  watch: {
    proceso: function() {
      this.listarProyecto(1, this.proceso, "");
    },
    nivelSelected: function() {
      this.getEstudianteByCarrer(1);
    },
    carrera_selected: function() {
      if (this.nivelSelected != 0) {
        this.getEstudianteByCarrer(1);
      }
    }
  },
  methods: {
    listarProyecto(page, proceso, buscar) {
      let me = this;
      me.loadSpinner = 1;
      me.arrayProyecto = [];
      var url =
        "proyectos/externos?page=" +
        page +
        "&proceso=" +
        proceso +
        "&buscar=" +
        buscar;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayProyecto = respuesta.proyectos.data;
          me.pagination = respuesta.pagination;
          me.loadSpinner = 0;
          me.searchEmpty();
        })
        .catch(function(error) {
          me.loadSpinner = 0;
          console.log(error);
        });
    },
    listarProyectoDes(page, proceso, buscar) {
      let me = this;
      var url =
        "proyecto/desactivados/externos?page=" +
        page +
        "&proceso=" +
        proceso +
        "&buscar=" +
        buscar;
      me.loading = true;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayProyectosDes = respuesta.proyectos.data;
          me.paginationProyDes = respuesta.pagination;
          if (me.arrayProyectosDes.length == 0) me.searchID = 1;
          else me.searchID = 0;

          me.loading = false;
        })
        .catch(function(error) {
          me.loading = false;
          console.log(error);
        });
    },
    getInst() {
      let me = this;
      me.loadSpinner = 1;
      var url = "GetInstituciones/" + this.proceso;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayInstitucion = respuesta;
          me.loadSpinner = 0;
        })
        .catch(function(error) {
          me.loadSpinner = 0;
          console.log(error);
        });
    },
    getEstudianteByCarrer(page) {
      let me = this;
      var url =
        "stundentByCarrer?page=" +
        page +
        "&carrera_id=" +
        me.carrera_selected.value +
        "&proceso_id=" +
        me.proceso +
        "&nivelAcad=" +
        me.nivelSelected.value +
        "&buscar=" +
        me.buscarP;
      me.loader = true;
      axios
        .get(url)
        .then(function(response) {
          me.loader = false;
          var respuesta = response.data;
          me.arrayEstudianteP = respuesta.estudiantes.data;
          me.paginationP = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getCarreras() {
      let me = this;
      var url = "carreras/GetCarreras";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayCarreras = respuesta;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarProyecto() {
      let me = this;
      const toast = swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 4000
      });
      me.loading = true;
      axios
        .put("/proyecto/actualizar", {
          id: me.proyectoId,
          nombre: me.nombreP,
          actividades: me.actividadesP,
          institucion_id: me.institucionP.value,
          horasRealizar: me.hrsRealizar,
          tipoProyecto: "E",
          procesoId: me.proceso
        })
        .then(function(response) {
          swal({
            position: "center",
            type: "success",
            title: "Proyecto actualizado correctamente!",
            showConfirmButton: false,
            timer: 1000
          });
          me.cerrarModal();
          me.listarProyecto(1, me.proceso, "");
        })
        .catch(function(error) {
          me.loadSpinner = 0;
          toast({
            type: "danger",
            title: "Error! Intente Nuevamente"
          });
          console.log(error);
        });
    },
    abrirModal(modelo, accion, data = []) {
      this.loadSpinner = 1;
      let me = this;
      const el = document.body;
      el.classList.add("abrirModal");

      var inst = JSON.stringify({
        value: data.institucion.id,
        label: data.institucion.nombre
      });
      //Cargando datos de proyecto en modal
      switch (me.proceso) {
        //El proyecto a actualizar es de Serivio Social
        case "1":
          this.modal = 1;
          me.proyectoId = data.id;
          me.nombreP = data.nombre;
          me.actividadesP = data.actividades;
          me.institucionP = JSON.parse(inst);
          me.hrsRealizar = data.horas_realizar;
          me.getInst();
          this.loadSpinner = 0;
          break;
        case "2":
          this.modal = 1;
          me.proyectoId = data.id;
          me.nombreP = data.nombre;
          me.actividadesP = data.actividades;
          me.institucionP = JSON.parse(inst);
          me.hrsRealizar = data.horas_realizar;
          me.getInst();
          this.loadSpinner = 0;

          break;
      }
    },
    openModalProy(data = []) {
      const el = document.body;
      el.classList.add("abrirModal");
      this.tituloModalAsignarProy = data.nombre;
      this.proyecto_selected_id = data.id;
      if (this.proceso == 2) {
        this.nivelSelected = this.arrayNiveles[1];
      }
      this.getCarreras();
      this.modalP = 1;
    },
    // Modal de proyectos desactivados
    abrirModalID() {
      const el = document.body;
      el.classList.add("abrirModal");
      this.modalID = 1;
      this.listarProyectoDes(1, this.proceso, "");
    },
    cerrarModalID() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modalID = 0;
      this.arrayProyectosDes = [];
      this.paginationProyDes = "";
    },
    cerrarModal() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modal = 0;
      this.tituloModal = "";
      this.nombreP = "";
      this.actividadesP = "";
      this.arrayInstitucion = [];
      this.estado = 0;
      this.errors = [];
      this.loading = false;
    },
    cerrarModalP() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modalP = 0;
      this.carrera_proy_ind = 0;
      this.arrayEstudianteP = [];
      this.tituloModalAsignarProy = "";
      this.proyecto_selected_id = 0;
      this.carrera_selected = 0;
      this.nivelSelected = 0;
      this.estudiantesSelected = [];
    },
    cambiarPaginaPID(page, proceso, buscar) {
      let me = this;
      me.paginationProyDes.current_page = page;
      me.listarProyectoDes(page, this.proceso, "");
    },
    cambiarPagina(page, proceso, buscar) {
      let me = this;
      me.pagination.current_page = page;
      me.listarProyecto(page, proceso, buscar);
    },
    cambiarPaginaP(page, buscar) {
      let me = this;
      me.paginationP.current_page = page;
      if (me.arrayEstudianteP.length > 0) {
        me.getEstudianteByCarrer(page, "");
      }
    },
    asignarProyecto() {
      swal({
        title:
          "Esta seguro de asignar este proyecto a los estudiantes seleccionados",
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
          var url = route("asinarProyectoExterno", {
            estudent_id: me.estudiantesSelected,
            project_id: me.proyecto_selected_id
          });
          axios
            .get(url)
            .then(function(response) {
              swal(
                "Hecho",
                "Las asignaciones a este proyecto fueron realizadas con exito",
                "success"
              );
              me.loadSpinner = 0;
              me.estudiantesSelected = [];
              me.getEstudianteByCarrer(1);
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (result.dismiss === swal.DismissReason.cancel) {
        }
      });
    },
    desactivarProyecto(id) {
      swal({
        title: "Esta seguro de desactivar este Proyecto?",
        type: "warning",
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
          axios
            .put("/proyecto/desactivar", {
              id: id
            })
            .then(function(response) {
              me.listarProyecto(1, me.proceso, "");
              swal(
                "Desactivado!",
                "El Registro ha sido desactivado con exito",
                "success"
              );
              me.loadSpinner = 0;
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },
    activarProyecto(id) {
      swal({
        title: "Esta seguro de activar este Proyecto?",
        type: "warning",
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
          axios
            .put("/proyecto/activar", {
              id: id
            })
            .then(function(response) {
              me.listarProyectoDes(1, me.proceso, "");
              me.listarProyecto(1, me.proceso, "");
              swal(
                "Activada!",
                "El proyecto ha sido activado con exito",
                "success"
              );
              me.loadSpinner = 0;
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },
    searchEmpty() {
      let me = this;
      if (me.arrayProyecto.length == 0) {
        me.search = 1;
      } else {
        me.search = 0;
      }
      return me.search;
    }
  },
  components: {
    VueEditor,
    Switches
  }
};
</script>