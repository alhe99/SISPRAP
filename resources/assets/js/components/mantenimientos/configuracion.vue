<template>
  <div class="col-lg-12 col-md-12">
    <!-- <div class="card"> -->
      <div id="tabs" class="container col-md-12">

        <div class="tabs">
          <a v-on:click="activetab=1" v-bind:class="[activetab===1?'active':'']"><i class="mdi mdi-format-list-bulleted mdi-18px"></i> Carreras</a>
          <a v-on:click="activetab=2" v-bind:class="[activetab===2?'active':'']"><i class="mdi mdi-face mdi-18px"></i> Estudiantes</a>
          <a v-on:click="activetab=3" v-bind:class="[activetab===3?'active':'']"><i class="mdi mdi-settings mdi-18px"></i> Configuraciones</a>
        </div>
        
         <div class="content">
          <div v-if="activetab === 1" class="tabcontent">
            <br>
              <h2 class="text-center">Listado de carreras</h2>
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="form-group row">
                    <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-11 ">
                            <div class="form-group row">
                                <mdc-textfield type="text" class="col-md-12" @keyup="listarCarreras(1,  buscar)"  label="Nombre de la carrera" v-model="buscar"></mdc-textfield>
                            </div>
                        </div>
                         <div class="col-md-1">
                                <button type="button" @click="abrirModalID()" class="button secondary " data-toggle="tooltip" title="Carreras Desactivadas"><i class="mdi mdi-playlist-plus"></i></button>
                         </div>
                    </div>
                   </div>
                   <div class="row">
                    <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
                    </div>
                </div>
                <!--tabla de carreras-->
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="table-responsive">
                      <table id="myTable" class="table table-striped table-bordered table-mc-light-blue">
                        <thead class="thead-primary">
                            <tr>
                                <th>Carrera</th>
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
                  </div><br>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="table-responsive">
                      <table id="myTable" class="table table-striped table-bordered table-mc-light-blue">
                        <thead class="thead-primary">
                            <tr>
                                <th >Carrera</th>
                                <th>Estado</th>
                                <th class="text-right" style="padding-right: 35px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="carrera in arrayCarDes" :key="carrera.id">
                                <td v-text="carrera.nombre"></td>
                                <td>
                                  <template>
                                    <h4>
                                    <span v-if="carrera.estado == 0" class="badge badge-pill badge-danger">Desactivada</span>
                                  </h4>
                                </template>
                                <td class="text-right">
                                  <template v-if="carrera.estado == 0">
                                    <button type="button" @click="activarCarrera(carrera.id)" class="button red" data-toggle="tooltip" title="Desactivar carrera"><i class="mdi mdi-delete-variant"></i></button>
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
    <div v-if="activetab === 2" class="tabcontent">
         <h2 class="text-center">Registro de Estudiantes</h2>
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="form-group row">
                    <mdc-textfield
                    type="text"
                    name="estudiante"
                    class="col-md-12"
                    label="Nombre del Estudiante"
                    helptext="(Ingrese el nombre del estudiante)"
                    v-model="nombre"
                    v-validate="'required'"
                    ></mdc-textfield>
                    <span
                    class="help-block alert-danger"
                    v-show="errors.has('estudiante')"
                    style="margin-left: 16px;"
                    >{{errors.first('estudiante')}}</span>
                  </div>

                  <div class="form-group row">
                    <mdc-textfield
                    type="text"
                    name="apellido"
                    class="col-md-12"
                    label="Apellido del Estudiante"
                    helptext="(Ingrese el apellido del estudiante)"
                    v-model="apellido"
                    v-validate="'required'"
                    ></mdc-textfield>
                    <span
                    class="help-block alert-danger"
                    v-show="errors.has('apellido')"
                    style="margin-left: 16px;"
                    >{{errors.first('apellido')}}</span>
                  </div>
                  <div class="form-group row">
                    &nbsp;&nbsp;
                    <label style="margin-left: 5px;">Fecha de Nacimiento</label>
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <datetime
                      type="date"
                      name
                      :max-datetime="maxDatetime"
                      v-model="date"
                      value-zone="America/El_Salvador"
                      input-class="form-control"
                      ></datetime>
                    </div>
                  </div>
                  <div class="row md-radio">
                    <div class="col-md-6 text-center">
                      <input id="radioSS" value="F" v-model="genero" type="radio" name="radioP">
                      &nbsp;&nbsp;
                      <label for="radioSS">Femenino</label>
                    </div>
                    <div class="col-md-6 text-center">
                      <input id="radioPP" value="M" v-model="genero" type="radio" name="radioP">
                      <label for="radioPP">Masculino</label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <mdc-textfield
                    type="text"
                    name="telefono"
                    class="col-md-6"
                    label="Telefono"
                    helptext="(Ingrese número de telefono)"
                    v-model="telefono"
                    v-validate="'digits:8'"
                    ></mdc-textfield>
                    <mdc-textfield
                    type="text"
                    name="carnet"
                    class="col-md-6"
                    label="Carnet"
                    helptext="(Ingrese el número de carnet)"
                    v-model="carnet"
                    v-validate="'required'"
                    ></mdc-textfield>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <span
                      class="help-block alert-danger"
                      v-show="errors.has('telefono')"
                      >{{errors.first('telefono')}}</span>
                    </div>
                    <div class="col-md-6">
                      <span
                      class="help-block alert-danger"
                      v-show="errors.has('carnet')"
                      >{{errors.first('carnet')}}</span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <mdc-textfield
                    type="password"
                    name="contraseña"
                    class="col-md-12"
                    label="Password"
                    helptext="(Ingrese el password)"
                    v-model="password"
                    v-validate="'required'"
                    ></mdc-textfield>
                    <span
                    class="help-block alert-danger"
                    v-show="errors.has('contraseña')"
                    style="margin-left: 16px;"
                    >{{errors.first('contraseña')}}</span>
                  </div>
                  <div class="form-group row">
                    <mdc-textfield
                    type="text"
                    name="correo"
                    class="col-md-12"
                    label="Email"
                    helptext="(Ingrese su correo electronico)"
                    v-model="email"
                    v-validate="'required'"
                    ></mdc-textfield>
                    <span
                    class="help-block alert-danger"
                    v-show="errors.has('correo')"
                    style="margin-left: 16px;"
                    >{{errors.first('correo')}}</span>
                  </div>
                  <div class="form-group row">
                    <mdc-textfield
                    type="text"
                    name="direccion"
                    class="col-md-12"
                    label="Direccion"
                    helptext="(Ingrese la dirección)"
                    v-model="direccion"
                    v-validate="'required'"
                    ></mdc-textfield>
                    <span
                    class="help-block alert-danger"
                    v-show="errors.has('direccion')"
                    style="margin-left: 16px;"
                    >{{errors.first('direccion')}}</span>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <v-select
                      ref="selectCarreras"
                      v-model="beca_id"
                      :options="arrayBecas"
                      placeholder="Seleccione un tipo de beca"
                      >
                      <span slot="no-options">No hay datos disponibles</span>
                    </v-select>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <br>
                    <label for="carrera">Seleccione una carrera</label>
                    <v-select
                    ref="selectCarreras"
                    v-model="carrerasProy"
                    :options="arrayCarreras"
                    placeholder="Seleccione una carrera"
                    >
                    <span slot="no-options">No hay datos disponibles</span>
                  </v-select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-xs-12 col-lg-12">
                  <br>
                  <label for="departamento">Seleccione un Departamento</label>
                  <br>
                  <v-select
                  label="label"
                  v-model="departamento_id"
                  :onChange="watchDepa"
                  placeholder="Seleccione un departamento"
                  :options="arrayDepartamentos"
                  >
                  <span slot="no-options">No hay datos disponibles</span>
                </v-select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 col-xs-12 col-lg-12" v-if="departamento_id !== null">
                <br>
                <label for="email">Seleccione un Municipio</label>
                <br>
                <v-select
                label="label"
                v-model="municipio_id"
                placeholder="Seleccione un municipio"
                :options="arrayMunicipios"
                >
                <span slot="no-options">No hay datos disponibles</span>
              </v-select>
            </div>
          </div>
          <br>
          <button class="btn btn blue" @click="saveEstudiante">
            <i class="mdi mdi-account-plus"></i>&nbsp;Registrar Estudiante
          </button>
        </div>
      </div>     
    </div>
    <div v-if="activetab === 3" class="tabcontent">
        <br><h2 class="text-center">Año Actual del sistema</h2>
      <div class="panel panel-default">
       <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12">
          <br><label for="email">Seleccione año</label>
          <br><v-select label="label" v-model="departamento_id" :onChange="watchDepa" placeholder="Seleccione año" :options="arrayDepartamentos"></v-select>
        </div>
      </div>
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
      car: "",
      tituloModal: "",
      carUpd: "",
      nombre: "",
      buscar: "",
      buscarDes: "",
      sector: "",
      genero: "",
      date: "",
      carnet: "",
      password: "",
      email: "",
      telefono: "",
      direccion: "",
      pass: "",
      usuario: "",
      apellido: "",
      departamento_id: 0,
      municipio_id: 0,
      beca_id: 0,
      admin_id: 0,
      carrerasProy: 0,
      tipoAccion: 0,
      arrayCarreras: [],
      arrayBecas: [],
      arrayDepartamentos: [],
      arrayMunicipios: [],
      arrayAdmin: [],
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
      offset: 3,
      maxDatetime: new Date().toISOString().substring(0, 10)
    };
  },
  computed: {
    watchDepa: function() {
      if (this.departamento_id == null) {
        this.municipio_id = 0;
      }
    },
    isActived: function() {
            return this.pagination.current_page;
      },
      isActivedID: function() {
            return this.paginationID.current_page;
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
    pagesNumberID: function() {
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
    departamento_id: function() {
      this.getMunicipios();

      if (this.tipoAccion == 1) {
        this.municipio_id = 0;
      }
    }
  },
  methods: {
    //carrera
    listarCarreras(page, buscar) {
      let me = this;
      var urlCarreraList = route('carreraList', {"page":page, "buscar": buscar});
      me.loadSpinner = 1;
      axios
      .get(urlCarreraList)
      .then(function(response) {
          var respuesta = response.data;
          me.arrayCar = respuesta.carrera.data;
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
   listarCarrerasDes(page, buscar) {
      let me = this;
      var urlDesactivadasCar = route('carrerasDesactivadas', {"page":page, "buscar":buscar});
      me.loadSpinner = 1;
      axios
      .get(urlDesactivadasCar)
      .then(function(response) {
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
      .catch(function(error) {
          me.loadSpinner = 0;
          console.log(error);
      });
   },
   cambiarPagina(page, buscar) {
        let me = this;
        me.pagination.current_page = page;
        me.listarCarreras(page, buscar);
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
                case "car": {
                    switch (accion) {
                    case "actualizar": {
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
    abrirModalID(){
      const el = document.body;
      el.classList.add("abrirModal");
      this.modalId2 = 2;
      this.tituloModal = "Carreras Desactivadas";
      this.listarCarrerasDes(1, "");
    },
    cerrarModal() {
        const el = document.body;
        el.classList.remove("abrirModal");
        this.modalId = 0;
        this.car_id = 0;
        this.car = "";
        this.carUpd = "";
    },
    cerrarModalID(){
        const el = document.body;
        el.classList.remove("abrirModal");
        this.modalId2 = 0;
    },
    desactivarCarrera(id) {
          var urlDesactivarCarrera = route('desactivarCarrera', {"id":id});
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
              .then(function(response) {
                me.listarCarreras(1,"");
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
        activarCarrera(id) {
          var urlActivarcarrera = route('activarCarrera', {"id":id});
          swal({
            title: "Esta seguro de activar esta Carrera?",
            type: "question",
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
              axios.put(urlActivarcarrera)
              .then(function(response) {
                me.listarCarrerasDes(1,"");
                me.listarCarreras(1, "");
                swal(
                  "Activada!",
                  "La Carrera ha sido activada con exito",
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
    actualizarCarrera(){
        let me = this;
        var urlActualizar = route('actualizarCarrera', {"id":me.car_id, "nombre": me.car});
        var url = route('validateCarrera',{"nombre": me.car});
        me.loadSpinner = 1;
        axios.get(url).then(function(response) {
            var respuesta = response.data;
            console.log(respuesta);
            if((me.car != me.carUpd) && (respuesta == 'existe')){
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
            }else {
                axios
                .put(urlActualizar)
                .then(function(response) {
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
                .catch(function(error) {
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
    //registra los datos del estudiante
    saveEstudiante() {
      this.$validator.validateAll().then(() => {
        let me = this;
        this.loadSpinner = 1;
        axios
        .post("/admin/registrar", {
          nombre: this.nombre,
          apellido: this.apellido,
          fecha: this.date.substring(0, 10),
          genero: this.genero,
          telefono: this.telefono,
          codcarnet: this.carnet,
          password: this.password,
          email: this.email,
          direccion: this.direccion,
          tipo_beca_id: this.beca_id.value,
          carrera_id: this.carrerasProy.value,
          municipio_id: this.municipio_id.value
        })
        .then(function(response) {
          swal({
            position: "center",
            type: "success",
            title: "¡Estudiante agregada correctamente!",
            showConfirmButton: false,
            timer: 1000
          });
          me.clearData();
        })
        .catch(error => {
          me.loadSpinner = 0;
          console.log(error);
        });
      });
    },
    //limpia los componentes una vez registrados
    clearData() {
      let me = this;
      me.nombre = "";
      me.apellido = "";
      me.date = "";
      me.genero = "";
      me.telefono = "";
      me.carnet = "";
      me.password = "";
      me.email = "";
      me.direccion = "";
      me.departamento_id = 0;
      me.municipio_id = 0;
      me.beca_id = 0;
      me.carrerasProy = 0;
      me.sector = "";
    },
    //devuelve todas las carreras
    getCarreras() {
      let me = this;
      var urlCarreras = route('GetCarreras');
      axios
      .get(urlCarreras)
      .then(function(response) {
        var respuesta = response.data;
          //console.log(respuesta);
          me.arrayCarreras = respuesta;
        })
      .catch(function(error) {
        console.log(error);
      });
    },
    //devuelve todo tipo de becas existentes
    getBecas() {
      let me = this;
      var url = "/becas/getAll";
      axios
      .get(url)
      .then(function(response) {
        var respuesta = response.data;
          //console.log(respuesta);
          me.arrayBecas = respuesta;
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
    //devuelve todos los municipios
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
  },
  mounted() {
    this.getCarreras();
    this.getBecas();
    this.getMunicipios();
    this.getDepartamentos();
    let me = this;
    me.listarCarreras(1,"");
    this.maxDatetime;
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
  margin: 60px auto;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 0.9em;
  color: #888;
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
  color: #fff;
}

/* Styling for active tab */
.tabs a.active {
  background-color: #fff;
  color: #484848;
  border-bottom: 2px solid #fff;
  cursor: default;
}

/* Style the tab content */
.tabcontent {
  padding: 30px;
  border: 1px solid #ccc;
  border-radius: 10px;
  box-shadow: 3px 3px 6px #e1e1e1;
}
</style>


