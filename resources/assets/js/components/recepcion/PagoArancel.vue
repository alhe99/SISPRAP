<template>
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <fieldset>
                  <legend class="text-center">Seleccione una carrera para ver listado de estudiantes</legend>
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
         <div class="col-md-12">
           <v-select v-model="carrera_selected" :options="arrayCarreras" placeholder="Seleccione Una Carrera Para ver el listado de estudiantes"></v-select>
         </div>
       </div>
       <div class="row" v-if="carrera_selected != 0 && carrera_selected != null">
        <div class="col-md-12"><br>
          <h2 class="font-weight-bold">Listado de estudiantes de {{carrera_selected.label}}</h2>
        </div>
        <div class="col-md-10 col-sm-12 col-lg-6">
          <mdc-textfield type="text" style="margin-left: -10px" class="col-md-12"  @keyup="getAllStudens(carrera_selected.value,proceso,1,buscar);"  label="Buscar estudiante por nombre" v-model="buscar"></mdc-textfield>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
          <br>
          <table class="table table-striped table-bordered table-mc-light-blue">
            <thead class="thead-primary">
              <tr>
                <th>Nombre Estudiante</th>
                <th class="text-center">Carrera</th>
                <th class="text-center">Pago Arancel</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
             <tr v-for="item in arrayStudents" :key="item.id">
              <td v-text="item.nombre +' '+ item.apellido"></td>
              <th class="text-center" v-text="item.carrera.nombre"></th>
              <th class="text-center">
               <template v-if="proceso==1">
                <h4>
                  <span v-if="item.pago_arancel.length != 0" class="badge badge-pill badge-primary">Cancelado</span>
                  <span  v-else class="badge badge-pill badge-danger">Pendiente</span>
                </h4>
              </template>
              <template v-if="proceso==2">
                <h4>
                  <span v-if="item.pago_arancel[1] != undefined" class="badge badge-pill badge-primary">Cancelado</span>
                  <span  v-else class="badge badge-pill badge-danger">Pendiente</span>
                </h4>
              </template>
            </th>
            <td class="text-center">
              <template v-if="proceso==1">
                <button type="button"
                :class="[item.pago_arancel.length != 0 ? 'disabled' : '']"
                :disabled="item.pago_arancel.length != 0" class="button secondary " @click="abrirModal(item)" data-toggle="tooltip" title="Registrar Pago"><i class="mdi mdi-square-inc-cash"></i> Registrar Pago</button>
              </template>
              <template v-if="proceso==2">
                <button type="button"
                :class="[item.pago_arancel[1] != undefined ? 'disabled' : '']"
                :disabled="item.pago_arancel[1] != undefined" class="button secondary " @click="abrirModal(item)" data-toggle="tooltip" title="Registrar Pago"><i class="mdi mdi-square-inc-cash"></i> Registrar Pago</button>
              </template>
            </td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination" >
          <li class="page-item" v-if="pagination.current_page > 1">
            <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page -1,buscar)">Ant</a>
          </li>
          <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>

            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
              <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
            </li>
            <small v-show="arrayStudents.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayStudents.length + ' de ' + pagination.total + ' registros)'"></small>
          </ul>
        </nav>
        <div v-if="arrayStudents.length == 0" class="alert alert-warning" role="alert">
          <h4 class="font-weight-bold text-center">No hay registros disponibles</h4>
        </div>

        <div class="modal fade" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 v-if="proceso == 1" class="modal-title text-white">Aperturar expediente de <strong>Servicio Social</strong></h4>
                <h4 v-if="proceso == 2" class="modal-title text-white">Aperturar expediente de <strong>Práctica Profesional</strong></h4>
                <button type="button" @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="text-white">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4>Registrar Apertura de expediente para <strong>{{estudiante.nombre}}</strong></h4><br>
                <fieldset>
                 <legend class="text-center">Seleccione e ingrese los datos correspondientes</legend>
                 <div class="panel panel-default">
                  <div class="panel-body">
                           <!-- <div class="row m-1">
                                <div class="col-md-3">
                                    <label for="arancelSwitch" class="font-weight-bold" style="margin-top: 5px;">Tiene Beca:  &nbsp;</label>
                                    <switches class="switch-md" v-model="payArancel " id="arancelSwitch" theme="bootstrap" color="primary"></switches>
                                </div>
                                <div class="col-md-9 mt-2">
                                  <v-select v-if="payArancel==true" :options="arrayBecas" v-model="beca_selected" placeholder="Seleccione que tipo de beca posee"></v-select>
                                </div>
                              </div>-->
                              <div class="row"><br>
                                <div class="col-md-12">
                                  <mdc-textfield type="text"
                                  class="col-md-12"
                                  id="txtNoFact"
                                  label="Ingrese Número de Factura"
                                  v-model="no_fact"
                                  helptext="(Dato deacuerdo si es becado o no)" ></mdc-textfield>
                                </div>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                      </div>
                      <div class="modal-footer">
                        <div class="row">
                          <div class="col-md-12">
                            <button type="button" @click="cerrarModal()" class="button red"><i class="mdi mdi-close-box"></i>Cerrar</button>
                            <button type="button" id="btnSave" @click="savePay()" class="button blue"><i class="mdi mdi-content-save"></i>Guardar Datos</button>
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
    import Switches from "vue-switches";
    export default {
      data(){
        return{
          buscar: "",
          loadSpinner: 0,
          proceso: 0,
          arrayCarreras: [],
          arrayStudents: [],
          arrayBecas: [],
          estudiante: 0,
          carrera_selected:0,
          pagination: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0
          },
          offset: 3,
          modal: 0,
          tituloModal: "",
          payArancel: false,
          beca_selected: 0,
          no_fact: ""
        }
      },
      watch:{
        proceso: function() {
          this.getCarreras();
          this.carrera_selected = 0;
          this.buscar = "";
        },
        carrera_selected: function(){
          this.getAllStudens(this.carrera_selected.value,this.proceso,1,"")
        },
        estudiante: function(){
          if(this.estudiante.tipo_beca_id == 1)
          {
            this.no_fact = "MINED";
            $("#txtNoFact").addClass("mdc-text-field--disabled");
          }else{
            $("#txtNoFact").removeClass("mdc-text-field--disabled");
            this.no_fact = "";
          }
        },
        no_fact:function(){
          if(this.no_fact == ""){
            $("#btnSave").prop('disabled',true);
          }else{
            $("#btnSave").prop('disabled',false);
          }
        }
      },
      computed:{
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
    },
    methods:{
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
    getAllStudens(carrera_id,proceso_id,page,buscar) {
      let me = this;
      me.loadSpinner = 1;
      var url = "/recepcion/getAllStudents?carre_id="+ carrera_id +"&proceso_id=" + proceso_id + "&page=" + page +"&buscar=" + buscar;
      axios.get(url).then(function(response) {
        var respuesta = response.data;
        me.arrayStudents = respuesta.estudiante.data;
        me.pagination = respuesta.pagination;
        me.loadSpinner = 0;

      })
      .catch(function(error) {
        console.log(error);
      });
    },
    cambiarPagina(page,buscar) {
      let me = this;
      me.pagination.current_page = page;
      if (me.arrayStudents.length > 0) {
        me.getAllStudens(this.carrera_selected.value,this.proceso,page,"");
      }
    },
    savePayArancel(no_fac,estudiante_id,tipobeca_id,proceso_id) {
      const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500});
      swal({
        title: "Segura que desea guardar los datos?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "button secondary",
        cancelButtonClass: "button red",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          me.loadSpinner = 1;
          var url = "/recepcion/payArancel?noFac="+no_fac+"&estudiante_id="+estudiante_id+"&tipobeca_id="+tipobeca_id+"&proceso_id="+proceso_id;
          axios.post(url)
          .then(function(response) {
            me.getAllStudens(me.carrera_selected.value,me.proceso,1,"");
            swal(
              "Hecho!",
              "Apertura de expediente guardada con exito",
              "success"
              );
            me.loadSpinner = 0;
            me.cerrarModal();
          })
          .catch(function(error) {
            me.loadSpinner = 0;
            console.log(error);
            toast({
              type: 'danger',
              title: 'Error! Intente Nuevamente'
            });
          });
        } else if (

          result.dismiss === swal.DismissReason.cancel
          ) {
        }
      });
    },
    getBecas() {
      let me = this;
      var url = "/becas/getAll";
      axios
      .get(url)
      .then(function(response) {
        var respuesta = response.data;
        me.arrayBecas = respuesta;
      })
      .catch(function(error) {
        console.log(error);
      });
    },
    savePay(){
      var nofact = this.no_fact;
      var estudiante_id = this.estudiante.id;
      var tipobeca_id = this.beca_selected.value;
      var tipoproceso_id = this.estudiante.proceso[0].id;
      this.savePayArancel(nofact,estudiante_id,tipobeca_id,tipoproceso_id);
    },
    abrirModal(item = []) {
      this.estudiante = item;
      const el = document.body;
      el.classList.add("abrirModal");
      this.modal = 1;
    },
    cerrarModal() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modal = 0;
      this.estudiante = "";
      this.payArancel = false;
      this.beca_selected = 0;
      this.no_fact = "";
    },
  },
  components: {
    Switches
  },
}
</script>
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
.secondary {
  background-color: #6c757d;
  color: white;
}
.info {
  background-color: #03a9f4;
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

