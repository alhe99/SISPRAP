<template>
  <div class="col-lg-12 col-md-12">
    <div class="row">
      <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
      </div>
    </div>
    <div class="card" v-if="showANotherCard == false">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <fieldset>
                  <legend class="text-center">Seleccione un proceso para ver listado de estudiantes</legend>
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
    <div class="card" v-if="proceso != 0 && showANotherCard == false">
      <div class="card-body">
       <div class="row">
         <div class="col-md-12">
           <v-select v-model="carrera_selected" :options="arrayCarreras" placeholder="Seleccione Una Carrera Para ver el listado de estudiantes"></v-select>
         </div>
       </div>
       <div class="row" v-if="carrera_selected != 0 && carrera_selected != null">
        <div class="col-md-12"><br>
          <h3 class="font-weight-bold" v-if="proceso == 1">Listado de estudiantes de Servicio Social</h3>
          <h3 class="font-weight-bold" v-if="proceso == 2">Listado de estudiantes Práctica Profesional</h3>
        </div>
        <div class="col-md-10 col-sm-12 col-lg-6">
          <mdc-textfield type="text" style="margin-left: -10px" class="col-md-12"  @keyup="getGestionProy(carrera_selected.value,proceso,1,buscar);"  label="Buscar estudiante por nombre" v-model="buscar"></mdc-textfield>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
          <br>
          <table class="table table-striped table-bordered table-mc-light-blue">
            <thead class="thead-primary">
              <tr>
                <th>Nombre Estudiante</th>
                <th class="text-center">Proyecto</th>
                <th class="text-center">Carrera</th>
                <th class="text-center">Estado del proceso</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
             <tr v-for="item in arrayStudents" :key="item.id">
              <td v-text="item.estudiante.nombre +' '+ item.estudiante.apellido"></td>
              <td>{{item.proyecto.nombre | truncate(30)}}</td>
              <td class="text-center" v-text="item.estudiante.carrera.nombre"></td>
              <td class="text-center">
                <template>
                 <h4>
                   <span v-if="item.estado == 'I'" class="badge h1 badge-pill badge-primary">Iniciado</span>
                   <span v-else-if="item.estado == 'P'" class="badge badge-pill badge-danger">En proceso</span>
                   <span v-else-if="item.estado == 'F'" class="badge badge-pill badge-danger">Finalizado</span>
                 </h4>
               </template>
             </td>
             <td class="text-center">
              <button type="button" style="cursor:pointer;" @click="showCompleteInfoGp(item.id)" class="button blue" data-toggle="tooltip" title="Ver más información"><i class="mdi mdi-playlist-plus i-crud"></i></button>
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
      </div>
    </div>
  </div>
</div>
<!--Inicio de seccion para mostra informacion completa de la gestion de el proyecto-->
<template v-if="showANotherCard == true">
 <div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
           <div class="panel panel-default text-center" style="background-color: #f5f5f5;">
            <div class="panel-body">
              <br>
              <h4 v-if="proceso == 1"><strong>Proceso:</strong> Servicio Social</h4>
              <h4 v-if="proceso == 2"><strong>Proceso:</strong> Práctica Profesional</h4>
              <br>
            </div>
          </div><br>
          <div class="row">
            <div class="col-md-4 text-center">
              <template v-if="gpObj.estudiante.foto_name == ''">
                <img v-if="gpObj.estudiante.genero == 'M'" class="text-center img-fluid" :src="'images/avatarM.png'" alt="">
                <img v-else class="text-center img-fluid" :src="'images/avatarF.png'" alt="">
              </template>
              <template v-else>
                <img class="text-center img-fluid" :src="rutaIMG" style="width: 50%;">
              </template>
              <br><br><h5><strong>Num Carnet: </strong> {{gpObj.estudiante.codCarnet}}</h5><br>
              <div v-show="gpObj.estado != 'F'" class="row">
                <div class="col-md-12">
                  <button type="button" :disabled="gpObj.estado == 'F'" @click="abrirModalEnd()" class="button secondary">{{textoBtn}}</button>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="panel panel-default">
                <div class="panel-body">
                  <fieldset class="col-md-12">
                    <legend class="text-center">Datos del estudiante</legend>
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <label><strong>Nombre:</strong> {{gpObj.estudiante.nombre +" "+gpObj.estudiante.apellido }}</label><br>
                        <label><strong>Carrera:</strong> {{gpObj.estudiante.carrera.nombre}}</label><br>
                        <label><strong>Fecha de Nacimiento: </strong> {{gpObj.estudiante.fechaNac}}</label><br>
                        <label v-if="gpObj.estudiante.genero == 'M'"><strong>Género: </strong> Masculino</label><br>
                        <label v-if="gpObj.estudiante.genero == 'F'" ><strong>Género: </strong> Femenino</label>
                        <label><strong>Telefono: </strong> {{gpObj.estudiante.telefono}}</label><br>
                        <label v-if="gpObj.estudiante.tipo_beca_id == '1'"><strong>Becado: </strong> MINED</label><br>
                        <label v-if="gpObj.estudiante.tipo_beca_id == '2'" ><strong>Becado: </strong> Otro</label><br>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div><br>
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">

                    <div class="panel-body">
                      <fieldset class="col-md-12">
                        <legend class="text-center">Datos del proyecto en realización</legend>
                        <div class="panel panel-default">
                          <div class="panel-body">
                            <label ><strong>Nombre: </strong> {{gpObj.proyecto.nombre}}</label><br>
                            <div class="row">
                              <div class="col-md-6 "><label><strong>Institución:</strong> {{gpObj.proyecto.institucion.nombre}}</label></div>
                            </div>
                            <div class="row">
                              <div class="col-md-6" style="margin-top: 2px;"><label><strong>Fecha de Inicio:</strong> {{gpObj.fecha_inicio}}</label></div>
                              <div class="col-md-6">
                                <template>
                                  <h5>
                                    <strong>Estado del Proceso: </strong><span v-if="gpObj.estado == 'I'" class="badge h1 badge-pill badge-primary">Iniciado</span>
                                    <span v-else-if="gpObj.estado == 'P'" class="badge badge-pill badge-warning">En proceso</span>
                                    <span v-else-if="gpObj.estado == 'F'" class="badge badge-pill badge-info">Finalizado</span>
                                  </h5>
                                </template>
                              </div>
                            </div>
                            <label v-if="gpObj.fecha_fin == null"><strong>Fecha Finalización:</strong> Pendiente...</label>
                            <label v-if="gpObj.fecha_fin != null"><strong>Fecha Finalización:</strong> {{gpObj.fecha_fin}}</label><br>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><br>
          <!--Secion para documentos-->
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  <fieldset class="col-md-12">
                    <legend class="text-center">Control de documentos del proceso</legend>
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <!-- <div class="table-responsive"> -->
                          <table class="table table-bordered" style="margin-left: 5px;">
                            <tr>
                              <td v-for="item in gpObj.documentos_entrega" :key="item.id">
                                <h6 class="text-center" v-if="item.pivot.estado == 1">Entregado<i class="mdi mdi-check"></i></h6>
                                <h6 class="text-center" v-if="item.pivot.estado == 0">Pendiente<i class="mdi mdi-share"></i></h6>
                                <p class="text-center"><em>{{item.nombre}}</em></p>
                                <div class="panel-body" style="background-color: #fff">
                                  <p class="text-center">{{item.pivot.observacion}}</p>
                                </div>
                                <div class="col-md-12">
                                  <p class="text-center"><small>{{item.pivot.created_at}}</small></p>
                                </div>
                              </td>
                              <td>
                               <button type="button" :disabled="gpObj.documentos_entrega.length == 4" class="button blue" @click="abrirModalDoc()">Administrar Documentos</button>
                             </td>
                           </tr>
                         </table>
                         <!-- </div> -->
                       </div>
                     </div>
                   </fieldset>
                 </div>
               </div>
             </div>
           </div>

           <!--///////// MODAL PARA MOSTRAR INFORMACION DEL ALUMNO /////////-->
           <div class="modal fade" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title text-white">Administración de documentos</h4>
                  <button type="button" @click="cerrarModalDoc()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">

                    <div class="col-md-12">
                      <label for="">Seleccione un documento</label>
                      <div class="row">
                        <div v-for="(item,index) in arrayDocumentos" :key="item.id" v-if="item.value != arrayDocEntreg[index]" class="col-md-3">
                         <checkbox  :value="item.value" v-model="valuesDoc">{{ item.label }}</checkbox>
                       </div>
                     </div>
                     <!--  <v-select :options="arrayDocumentos" v-model="documento_selected" placeholder="Seleccione que tipo le ha sido entregado"></v-select> -->
                   </div><br>
                   <div class="col-md-12"><br>
                    <label for="obs">Observación</label>
                    <textarea name="obs" class="form-control" id="obs" v-model="obsDoc" rows="5"></textarea>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" @click="cerrarModalDoc()" class="button red">Cerrar</button>
                    <button type="button" @click="saveDoc()" class="button secondary">Guardar Datos</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--///////// FIN DE MODAL PARA MOSTRAR INFORMACION DEL ALUMNO /////////-->

        <!--///////// MODAL PARA FINALIZAR PROYECTO INFORMACION DEL ALUMNO /////////-->
        <div class="modal fade" :class="{'mostrar' : modalEnd }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title text-white">Finalizar Proyecto</h4>
                <button type="button" @click="cerrarModalEnd()" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="text-white">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <label for="obs">Seleccione Fecha de Finalización de Proyecto: </label>
                    <input placeholder="aaaa-mm-dd" id="fechaFin" name="fechaFin" class="form-control" >
                  </div><br>
                  <div class="col-md-12"><br>
                   <label for="obs">Total de Horas Realizadas en el proyecto: </label>
                   <input type="number" min="0" max="300" class="form-control" v-model="hrsFinal">
                 </div>
                 <div class="col-md-12"><br>
                   <label for="obs">Observación Final: </label>
                   <textarea name="obs" class="form-control"  v-model="obsFinal" rows="5"></textarea>
                 </div>
               </div>
             </div>
             <div class="modal-footer">
              <div class="row">
                <div class="col-md-12">
                  <button type="button" @click="cerrarModalEnd()" class="button red">Cerrar</button>
                  <button type="button" @click="deleteProy(idGP)" class="button info">Guardar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--///////// FIN DE MODAL PARA MOSTRAR INFORMACION DEL ALUMNO /////////-->


      <!--Fin de seccion para documentos-->

    </div>
  </div>
</div>
</div>
</div>
<button type="button" @click="getBack()" class="btn btn-primary text-capitalize  font-weight-bold" data-toggle="tooltip" id="back" title="Regresar"><i class="mdi mdi-chevron-double-left" ></i>Regresar</button>
</div>
</template>
<!--Fin de seccion para mostra informacion completa de la gestion de el proyecto-->
</div>
</template>
<script>
export default {
  data(){
    return{
      buscar: "",
      loadSpinner: 0,
      proceso: 0,
      arrayCarreras: [],
      arrayStudents: [],
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
      gpObj: 0,
      showANotherCard: false,
      modal: 0,
      arrayDocumentos: [],
      obsDoc: "",
      idGP: 0,
      modalEnd: 0,
      textoBtn: "",
      obsFinal: '',
      hrsFinal: 0,
      rutaIMG:'',
      valuesDoc: [],
      arrayDocEntreg: [],
    }
  },
  watch:{
    proceso: function() {
      this.getCarreras();
      this.carrera_selected = 0;
    },
    carrera_selected: function(){
      this.getGestionProy(this.carrera_selected.value,this.proceso,1,"")
    },
    gpObj:function(){
      if(this.gpObj.documentos_entrega.length == 4){
        this.textoBtn = "Cerrar Proyecto"
      }else if(this.gpObj.documentos_entrega.length < 4){
        this.textoBtn = "Cancelar Proyecto"
      }
      if(this.gpObj.estudiante.codCarnet.length > 7)
        this.rutaIMG =  "http://portal.itcha.edu.sv/fotos/alumnos/"+ this.gpObj.estudiante.foto_name;
      else
        this.rutaIMG =  "http://registro.itcha.edu.sv/matricula/public/images/alumnos/"+ this.gpObj.estudiante.foto_name;
      //Obteniendo en array individual los coumentos entregados de un estudiante
      if (this.gpObj.documentos_entrega.length > 0) {
        for (var i = 0; i < this.gpObj.documentos_entrega.length; i++) {
          this.arrayDocEntreg[i] = this.gpObj.documentos_entrega[i].pivot.documento_id;
        }
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
    me.loadSpinner = 1;
    var url = "carreras/GetCarreras";
    axios
    .get(url)
    .then(function(response) {
      var respuesta = response.data;
      me.arrayCarreras = respuesta;
      me.loadSpinner = 0;
    })
    .catch(function(error) {
      console.log(error);
      me.loadSpinner = 0;
    });
  },
  getDocuments() {
    let me = this;
    var url = "getDocuments";
    axios
    .get(url)
    .then(function(response) {
      var respuesta = response.data;
      me.arrayDocumentos = respuesta;
    })
    .catch(function(error) {
      console.log(error);
    });
  },
  deleteProy(idGp){
    const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000});
    swal({
      title: "¿Desea Guardar los cambios?",
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
        var fechaFin = $("#fechaFin").val().trim();
        var url = route('close_proyect', {"gestionId": idGp,"fechaFin": fechaFin,"horasRea":me.hrsFinal,"obsFinal" : me.obsFinal});
        axios.get(url)
        .then(function(response) {
          me.getMoreInfoGp(me.idGP);
          swal(
            "Hecho!",
            "Proyecto Finalizado Correctamente",
            "success"
            );
          me.loadSpinner = 0;
          me.cerrarModalEnd();
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
  abrirModalDoc() {
    const el = document.body;
    el.classList.add("abrirModal");
    this.modal = 1;
    this.getDocuments();
  },
  cerrarModalDoc() {
    const el = document.body;
    el.classList.remove("abrirModal");
    this.modal = 0;
    this.arrayDocumentos = [];
    this.valuesDoc = [];
    this.obsDoc = "";
  },
  getBack(){
    this.showANotherCard = false;
    this.arrayDocEntreg = [];
  },
  abrirModalEnd() {
    const el = document.body;
    el.classList.add("abrirModal");
    this.modalEnd = 1;

     $("#fechaFin").datepicker({
      locale: 'es-es',
      maxDate: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()),
      format: 'yyyy-mm-dd',

    });

  },
  cerrarModalEnd() {
    const el = document.body;
    el.classList.remove("abrirModal");
    this.modalEnd = 0;
    this.fechaFin= '';
    this.obsFinal= '';
    this.hrsFinal= 0;
    //$(".gj-icon").click();

  },
  getGestionProy(carrera_id,proceso_id,page,buscar) {
    let me = this;
    me.loadSpinner = 1;
    var url = "/gestionproyectos?carre_id="+ carrera_id +"&proceso_id=" + proceso_id + "&page=" + page +"&buscar=" + buscar;
    axios.get(url).then(function(response) {
      var respuesta = response.data;
      me.arrayStudents = respuesta.gp.data;
      me.pagination = respuesta.pagination;
      me.loadSpinner = 0;

    })
    .catch(function(error) {
      console.log(error);
      me.loadSpinner = 0;
    });
  },
  cambiarPagina(page,buscar) {
    let me = this;
    me.pagination.current_page = page;
    if (me.arrayStudents.length > 0) {
      me.getAllStudensHasPayArancel(this.carrera_selected.value,this.proceso,page,"");
    }
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
  getMoreInfoGp(id) {
    let me = this;
    me.loadSpinner = 1;
    var url = "/getMoreInfoGP/"+id;
    axios.get(url).then(function(response) {
      var respuesta = response.data;
      me.gpObj = respuesta;
      me.loadSpinner = 0;
    }).catch(function(error) {
      console.log(error);
      me.loadSpinner = 0;
    });
  },
  showCompleteInfoGp(id){
    let me = this;
    me.getMoreInfoGp(id);
    me.idGP = id;
    me.showANotherCard = true;
  },
  saveDoc() {
   const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000});
   swal({
    title: "¿Desea Guardar los datos?",
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
      var url = route('savedoc', {"gestionId": me.gpObj.id,"objDoc": me.valuesDoc,"observacion" : me.obsDoc});
      axios.get(url)
      .then(function(response) {
        me.getMoreInfoGp(me.idGP);
        swal(
          "Hecho!",
          "Documento Añadido Correctamente",
          "success"
          );
        me.loadSpinner = 0;
        me.cerrarModalDoc();
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
mounted(){}
},
}
</script>
