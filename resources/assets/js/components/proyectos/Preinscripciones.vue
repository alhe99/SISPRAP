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
            <h2 class="text-center font-weight-bold" v-if="proceso == 1">Proyectos de Servicio Social</h2>
            <h2 class="text-center font-weight-bold" v-if="proceso == 2">Proyectos de Práctica Profesional</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="card" v-if="proceso != 0 ">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <v-select v-if="proceso==2" v-model="carrera_selected" :options="arrayCarreras" placeholder="Seleccione una carrera">
                 <span slot="no-options">
                  No hay datos disponibles
                </span>
              </v-select>
              <!-- <fade-loader :loading="true"></fade-loader> -->
            </div>
            <div class="col-md-6" :class="[proceso == 1 ? 'col-md-12' : 'col-md-6']">
              <v-select ref="vselectProy" v-model="proyecto_selectd" :options="arrayProyectos" placeholder="Seleccione un Proyecto">
               <span slot="no-options">
                No hay datos disponibles
              </span>
            </v-select>
            <h6 v-if="contentProy == false" class="text-danger">No hay proyectos disponibles para esta carrera</h6>
          </div>
        </div>
      </div><br>

      <div v-if="proyecto_selectd != 0 && proyecto_selectd != null" class="col-md-12 col-sm-12 col-lg-12">
       <div class="row">
         <div class="col-md-6">
          <mdc-textfield type="text" style="margin-left: -10px" class="col-md-12"  @keyup="getPreregister(proyecto_selectd.value,1,buscar)"  label="Nombre del estudiante" v-model="buscar"></mdc-textfield>
        </div>
        <div class="col-md-6"><br>
          <div v-if="proyecto_selectd.vacantes - proyecto_selectd.preinscripciones != 0" class="alert alert-primary font-weight-bold h6" role="alert">
            Vacantes disponibles: {{ proyecto_selectd.vacantes - proyecto_selectd.preinscripciones }}
          </div>
          <div v-else class="alert alert-primary font-weight-bold h6" role="alert">
            Número de vacantes completado
          </div>
        </div>
      </div>
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
              <button type="button" class="button secondary " @click="aprobarProy(item.id,proyecto_selectd.value,item.proceso_actual)" data-toggle="tooltip" title="Aprobar Proyecto"><i class="mdi mdi-check"></i>&nbsp;Aprobar</button>
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
        <h4 class="font-weight-bold text-center">No hay Preinscripciones en este proyecto ó la búsqueda no coincide</h4>
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
  props: {
    value: {
      default: null
    },
  },
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
      testObj:
      {value: 1 ,label: "Primer Año"},
      // loadSpinner: true
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
           this.$refs.vselect.toggleLoading(true);
           vselect.disabled = false;
           this.contentProy = true;
         }
       }
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
    testProp: function(){
      return  this.value = this.proyecto_selectd;
    }
  },
  methods: {
      //obtener proyectos que los estudiante se han preinscrito dependiendo por su proceso
      getProyectos() {
        let me = this;
      //
      if(this.proceso == 1){
        var url = "GetProjectsByProcess?process_id=" + this.proceso+"&tipoProyecto=I";
      }else if(this.proceso == 2){
       var url = "GetProjectsByProcess?process_id=" + this.proceso +"&carre_id="+this.carrera_selected.value+"&tipoProyecto=I";
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
  //obtener todas las carreras
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

  //listado de los estudiantes preinscritos a un proyecto en especifico
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

  //abrir el modal de proyectos externos
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

  //obtener informacion del estudiante
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

    //aprobar la preinscripción
    aprobarProy(estudiante_id,proyecto_id,proceso_actual){
      if (proceso_actual == 'I') {
       Swal({
        type: 'error',
        title: 'Alerta...',
        text: "Este estudiante ya posee un proyecto en marcha!, Proceso de aceptar solictud denegado",
      });
     }else {
      swal({
        title: "Seguro de Aceptar Esta Solicitud?",
        type: "info",
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
    }
  },
  //rechazar la preinscripcion del estudiante
  rechazarProy(estudiante_id,proyecto_id){
    // const vselect = this.$refs.vselectProy._props.value;
    // this.$refs.vselectProy._props.value = this.testObj
    //console.log(vselect);
    swal({
      title: "Seguro de Rechazar Preincripcion?",
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
        var url = "/destroyPreregister/"+estudiante_id+"/"+proyecto_id;
        axios.get(url)
        .then(function(response) {
          me.getProyectos();
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
