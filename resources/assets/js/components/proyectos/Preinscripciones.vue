<template>
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <fieldset>
                  <legend class="text-center">Seleccione un proceso ver Preincripciones</legend>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row md-radio">
                        <div class="col-md-6 text-center">
                          <input id="radioSS" value="1" v-model="proceso" type="radio" name="radioP">
                          <label for="radioSS">Servicio Social</label>
                        </div>
                        <div class="col-md-6 text-center">
                          <input id="radioPP" value="2" v-model="proceso" type="radio" name="radioP">
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
    <div class="row">
      <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
      </div>
    </div>
    <div class="card" v-if="proceso != 0 ">
      <div class="card-body">
        <div class="row">
            <div class="col-md-11">
                <h2 class="text-center" v-if="proceso == 1">Proyectos de Servicio Social</h2>
                <h2 class="text-center" v-if="proceso == 2">Proyectos de Práctica Profesional</h2>
              </div>
        </div>
      </div>
    </div>
    <div class="card" v-if="proceso != 0 ">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-11">
                <!-- <h1 v-if="proceso == 1">Proyectos de Servicio Social</h1>
                <h1 v-if="proceso == 2">Proyectos de Práctica Profesional</h1> -->
              </div>
              <div class="col-md-1 col-sm-1 col-lg-1 text-right">
               <div class="btn-group pull-lg-right">
                <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="mw2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Más opciones">
                  <i class="mdi mdi-dots-vertical"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right"  aria-labelledby="mw2">
                  <button style="cursor: pointer;" class="dropdown-item d-block menu" @click="openModalProy" type="button"><i class="mdi mdi-bookmark-plus"></i> Asignación de proyecto</button>
                  <!-- <button class="dropdown-item d-block menu" type="button"><i class="mdi mdi-delete-empty"></i> Instituciones Desactivadas</button> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--///////// MODAL PARA ASIGNAR PROYECTOS FUERA DEL SISTEMA /////////-->
        <div class="modal fade" :class="{'mostrar' : modalP }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title text-white">Información del estudiante</h4>
                <button type="button" @click="cerrarModalP()" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="text-white">&times;</span>
                </button>
              </div>
              <div class="modal-body">
               <fieldset>
                <legend class="text-center">Complete los datos requeridos</legend>
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label class="font-weight-bold">Seleccione Carrera*</label>
                        <v-select v-model="carrera_proy_ind" :options="arrayCarreras" placeholder="Seleccione una carrera"></v-select>
                      </div>
                      <div class="col-md-12">
                        <br><pulse-loader class="text-center" :loading="loader" ></pulse-loader>
                      </div>
                      <div v-if="arrayEstudianteP.length != 0" class="col-md-10 col-sm-12 col-lg-6">
                        <mdc-textfield type="text" style="margin-left: -10px" class="col-md-12"  @keyup="getEstudianteByCarrer(1,buscarP)"  label="Nombre del estudiante" v-model="buscarP"></mdc-textfield>
                      </div>
                      <div v-if="arrayEstudianteP.length != 0" class="col-md-12">
                       <br><table class="table table-striped table-bordered table-mc-light-blue">
                        <thead class="thead-primary">
                          <tr>
                            <th>Nombre Estudiante</th>
                            <th>Año Academico</th>
                            <th class="text-center">Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in arrayEstudianteP" :key="item.id">
                            <td v-text="item.nombre +' '+ item.apellido"></td>
                            <td>Pendiente...</td>
                            <td class="text-center">
                              <button type="button" class="button secondary" @click="asignarProyecto(item.id)" data-toggle="tooltip" title="Dar Acceso a que el alumno llene el perfil con un proyecto fuera del sistema"><i class="mdi mdi-check"></i>&nbsp;Proyecto externo</button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <nav>
                       <ul class="pagination">
                        <li class="page-item" v-if="paginationP.current_page > 1">
                          <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaP(paginationP.current_page -1,buscarP)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumberP" :key="page" :class="[page == isActivedP ? 'active' : '']">
                          <a class="page-link" href="#" @click.prevent="cambiarPaginaP(page,buscarP)" v-text="page"></a>

                          <li class="page-item" v-if="paginationP.current_page < paginationP.last_page">
                            <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPaginaP(paginationP.current_page + 1,buscarP)">Sig</a>
                          </li>
                          <small v-show="arrayEstudianteP.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayEstudianteP.length + ' de ' + paginationP.total + ' registros)'"></small>
                        </ul>
                      </nav>
                    </div>
                    <div v-if="arrayEstudianteP.length == 0 && carrera_proy_ind != 0 " class="col-md-12">
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
                <button type="button" @click="cerrarModalP()" class="btn btn-danger">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--///////// FIN DE MODAL PARA ASIGNAR PROYECTOS FUERA DEL SISTEMA /////////-->
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
          <v-select v-if="proceso==2" v-model="carrera_selected" :options="arrayCarreras" placeholder="Seleccione una carrera"></v-select>
        </div>
        <div class="col-md-6" :class="[proceso == 1 ? 'col-md-12' : 'col-md-6']">
          <v-select ref="vselectProy" v-model="proyecto_selectd" :options="arrayProyectos" placeholder="Seleccione un Proyecto">
            <i slot="spinner" class="icon icon-spinner"></i>
          </v-select>
          <h6 v-if="contentProy == false" class="text-danger">No Hay Proyectos en esta institución</h6>
        </div>
      </div>
    </div><br>

    <div v-if="proyecto_selectd != 0 && proyecto_selectd != null" class="col-md-10 col-sm-12 col-lg-6">
      <mdc-textfield type="text" style="margin-left: -10px" class="col-md-12"  @keyup="getPreregister(proyecto_selectd.value,1,buscar)"  label="Nombre del estudiante" v-model="buscar"></mdc-textfield>
    </div>
    <div v-if="proyecto_selectd != 0 && proyecto_selectd != null " class="col-md-12 col-lg-12 col-sm-12">
      <br>
      <table class="table table-striped table-bordered table-mc-light-blue">
        <thead class="thead-primary">
          <tr>
            <th>Nombre Estudiante</th>
            <th>Fecha preinscripción</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in arrayPreregister" :key="item.id">
            <td><button type="button" @click="getMoreInfo(item.id)" class="btn btn-link text-capitalize h4" style="font-size: 16px">{{item.nombre +" "+ item.apellido}}</button></td>
            <td v-text="item.pivot.created_at"></td>
            <td class="text-center">
              <button type="button" class="button blue " @click="aprobarProy(item.id,proyecto_selectd.value)" data-toggle="tooltip" title="Aprobar Proyecto"><i class="mdi mdi-check"></i>&nbsp;Aprobar</button>
              <button type="button" class="button red " @click="rechazarProy(item.id,proyecto_selectd.value)" data-toggle="tooltip" title="Rechazar proyecto"><i class="mdi mdi-close"></i>&nbsp;Rechazar</button>
            </td>
          </tr>
        </tbody>
      </table>
      <nav>
       <ul class="pagination">
        <li class="page-item" v-if="pagination.current_page > 1">
          <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page -1,buscar)">Ant</a>
        </li>
        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
          <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>

          <li class="page-item" v-if="pagination.current_page < pagination.last_page">
            <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
          </li>
          <small v-show="arrayPreregister.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayPreregister.length + ' de ' + pagination.total + ' registros)'"></small>
        </ul>
      </nav>
      <div v-if="arrayPreregister.length == 0" class="alert alert-warning" role="alert">
        <h4 class="font-weight-bold text-center">No hay Preincripciones en este proyecto ó la búsqueda no coincide</h4>
      </div>
      <!--///////// MODAL PARA MOSTRAR INFORMACION DEL ALUMNO /////////-->
      <div class="modal fade" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-white">Información del estudiante</h4>
              <button type="button" @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             <fieldset>
              <legend class="text-center">Datos completos del estudiante</legend>
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                   <div class="col-md-8">
                    <h5 class="font-weight-bold">Nombre:</h5><h4>{{estudiante.nombre +" "+ estudiante.apellido}}</h4>
                    <h5 class="font-weight-bold">Carrera:</h5><h4>{{estudiante.carrer}}</h4>
                    <h5 class="font-weight-bold">Fecha Nacimiento: </h5><h4>{{estudiante.fechaNac}}</h4>
                    <h5 class="font-weight-bold">Género: </h5><h4 v-text="estudiante.genero == 'M' ? 'Masculino' : 'Femenino'"></h4>
                    <h5 class="font-weight-bold">Codigo de Carnet: </h5><h4>{{estudiante.codCarnet}}</h4>
                    <h5 class="font-weight-bold">Dirección: </h5><h4>{{estudiante.direccion}}</h4>
                  </div>
                  <div class="col-md-4">
                    <template v-if="estudiante.foto_name == ''">
                      <img v-if="estudiante.genero == 'M'" class="text-center img-fluid" :src="'images/avatarM.png'" alt="">
                      <img v-else class="text-center img-fluid" :src="'images/avatarF.png'" alt="">
                    </template>
                    <template v-else>
                      <img class="text-center img-fluid" :src="rutaIMG" alt="">
                    </template>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="col-md-12">
              <button type="button" @click="cerrarModal()" class="btn btn-danger">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--///////// FIN DE MODAL PARA MOSTRAR INFORMACION DEL ALUMNO /////////-->
</div>
</div>
</div>
</div>
</div>
</template>
<script>
export default {
  data() {
    return {
      buscar: "",
      loadSpinner: 0,
      proceso: 0,
      arrayProyectos: [],
      arrayCarreras: [],
      arrayPreregister: [],
      estudiante: 0,
      proyecto_selectd:0,
      carrera_selected:0,
      carrera_proy_ind:0,
      contentProy: true,
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
      offsetP: 3,
      modal: 0,
      modalP: 0,
      tituloModal: "",
      tipoAccion: 0,
      estudiante_id: 0,
      arrayEstudianteP: [],
      buscarP: "",
      loader: false,
      rutaIMG:'',
    };
  },
  watch: {
    proceso: function() {
      const vselect = this.$refs.vselectProy;
      this.getProyectos();
      if(this.proceso == 2){
       this.getCarreras();
     }
     this.proyecto_selectd = 0;
     this.carrera_selected = 0;
     vselect.disabled = false;
     this.contentProy = true;
   },
   carrera_selected: function(){
     this.proyecto_selectd = 0;
     this.getProyectos();
         //vselect.disabled = false;
       },
       proyecto_selectd: function(){
        this.getPreregister(this.proyecto_selectd.value,1,"");
      },
      arrayProyectos: function(){
        const vselect = this.$refs.vselectProy;
        if(this.carrera_selected != 0 && this.carrera_selected != null ){
          if(this.arrayProyectos.length == 1){
            vselect.disabled = true;
            this.contentProy = false;
          }else{
            vselect.disabled = false;
            this.contentProy = true;
          }
        }
      },
      carrera_proy_ind: function(){
        this.getEstudianteByCarrer(1);
      },
      estudiante: function(){
        if(this.estudiante.codCarnet.length > 7)
          this.rutaIMG =  "http://portal.itcha.edu.sv/fotos/alumnos/"+ this.estudiante.foto_name;
        else
          this.rutaIMG =  "http://registro.itcha.edu.sv/matricula/public/images/alumnos/"+ this.estudiante.foto_name;
      }
    },
    computed: {
      isActived: function() {
        return this.pagination.current_page;
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
      },
    },
    methods: {
     getProyectos() {
      let me = this;
      //
      if(this.proceso == 1){
        var url = "GetProjectsByProcess?process_id=" + this.proceso;
      }else if(this.proceso == 2){
       var url = "GetProjectsByProcess?process_id=" + this.proceso +"&carre_id="+this.carrera_selected.value;
     }
     axios.get(url).then(function(response) {
      me.loadSpinner = 1;
      var respuesta = response.data;
      me.arrayProyectos = respuesta;
      me.loadSpinner = 0;
    })
     .catch(function(error) {
      console.log(error);
    });
   },
   getEstudianteByCarrer(page) {
    let me = this;
    var url = "stundentByCarrer?page="+page+"&carrera_id="+me.carrera_proy_ind.value+"&proceso_id="+me.proceso+"&buscar=" + me.buscarP;
    me.loader = true;
    axios.get(url).then(function(response) {
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
  getPreregister(proyecto_id,page,buscar) {
    let me = this;
    me.loadSpinner = 1;
    var url = "getPreregistrationByProject?project_id="+proyecto_id +"&page=" + page +"&buscar=" + buscar;
    axios.get(url).then(function(response) {
      var respuesta = response.data;
      me.arrayPreregister = respuesta.projects.data;
      me.pagination = respuesta.pagination;
      me.loadSpinner = 0;

    })
    .catch(function(error) {
      console.log(error);
    });
  },
  openModalProy(){
    const el = document.body;
    el.classList.add("abrirModal");
    this.modalP = 1;
    this.getCarreras();
  },
  cerrarModalP() {
    const el = document.body;
    el.classList.remove("abrirModal");
    this.modalP = 0;
    this.carrera_proy_ind = 0;
    this.arrayEstudianteP = [];
  },
  getMoreInfo(id) {
    let me = this;
    me.loadSpinner = 1;
    var url = "stundentById/"+id;
    axios.get(url).then(function(response) {
      var respuesta = response.data;
      me.estudiante = respuesta;
      me.loadSpinner = 0;
      me.abrirModal();
    }).catch(function(error) {
      console.log(error);
    });
  },
  abrirModal() {
    const el = document.body;
    el.classList.add("abrirModal");
    this.modal = 1;
  },
  cerrarModal() {
    const el = document.body;
    el.classList.remove("abrirModal");
    this.modal = 0;
    this.estudiante = "";
    this.rutaIMG = '';
  },
  cambiarPagina(page,buscar) {
    let me = this;
      //Actualiza la pagina actual
      me.pagination.current_page = page;
      //Envia la pericion para visualizar los datos
      if (me.arrayPreregister.length > 0) {

        me.getPreregister(this.proyecto_selectd.value, page, "");

      }
    },
    cambiarPaginaP(page,buscar) {
      let me = this;
      //Actualiza la pagina actual
      me.paginationP.current_page = page;
      //Envia la pericion para visualizar los datos
      if (me.arrayEstudianteP.length > 0) {

        me.getEstudianteByCarrer(page,"");

      }
    },
    aprobarProy(estudiante_id,proyecto_id){
      swal({
        title: "Seguro de Aceptar este proyecto?",
        type: "info",
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
          var url = route('preregister', {"estudent_id": estudiante_id,"project_id": proyecto_id});
          axios.get(url)
          .then(function(response) {
           me.getPreregister(me.proyecto_selectd.value, 1, "");
           swal(
            "Aprobado!",
            "Has Probado la solicitud para este proyecto",
            "success"
            );
           me.loadSpinner = 0;
         })
          .catch(function(error) {
            console.log(error);
            me.loadSpinner = 0;
          });
        } else if (

          result.dismiss === swal.DismissReason.cancel
          ) {
        }
      });
    },
    asignarProyecto(dataId){
      swal({
        title: "Dar accesso a que el estudiante(a) ingrese un proyecto externo al sistema",
        type: "info",
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
          var dataP = 0;
          var url = route('preregister', {"estudent_id": dataId,"project_id": dataP});
          axios.get(url)
          .then(function(response) {
           me.getEstudianteByCarrer(1);
           swal(
            "Aprobado!",
            "El Estudiante puede iniciar con su proceso",
            "success"
            );
           me.loadSpinner = 0;
         })
          .catch(function(error) {
            console.log(error);
          });
        } else if (

          result.dismiss === swal.DismissReason.cancel
          ) {
        }
      });
    },
    rechazarProy(estudiante_id,proyecto_id){
     swal({
      title: "Seguro de Rechazar este proyecto?",
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
        var url = "/destroyPreregister/"+estudiante_id+"/"+proyecto_id;
        axios.get(url)
        .then(function(response) {
         me.getPreregister(me.proyecto_selectd.value, 1, "");
         swal(
          "Rechazado!",
          "Se ha eliminado la solicitud para este proyecto",
          "success"
          );
         me.loadSpinner = 0;
       })
        .catch(function(error) {
          console.log(error);
        });
      } else if (

        result.dismiss === swal.DismissReason.cancel
        ) {
      }
    });
  }
},
components: {},
mounted() {
  this.getProyectos();
      //this.contentProy = true;
    }
  };
  </script>
  <style>
  .loading {
    position: fixed;
    z-index: 999;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 50px;
    height: 50px;
  }

  /* Transparent Overlay */
  .loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.3);
  }

  /* :not(:required) hides these rules from IE9 and below */
  .loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
  }

  .loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 50px;
    height: 50px;
    margin-top: -0.5em;
    border: 5px solid #533fd0;
    border-radius: 100%;
    border-bottom-color: transparent;
    -webkit-animation: spinner 1s linear 0s infinite;
    animation: spinner 1s linear 0s infinite;
  }
  @-webkit-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @-moz-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @-o-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }

  </style>
