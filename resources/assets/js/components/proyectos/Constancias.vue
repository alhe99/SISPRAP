<template>
  <div class="col-lg-12 col-md-12">
    <div class="row">
      <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
      </div>
    </div>
    <div class="card">
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
    <div class="card" v-if="proceso != 0">
      <div class="card-body">
       <div class="row">
         <div class="col-md-12">
           <v-select v-model="carrera_selected" :options="arrayCarreras" placeholder="Seleccione Una Carrera Para ver el listado de estudiantes"></v-select>
         </div>
       </div>
       <div class="row" v-if="carrera_selected != 0 && carrera_selected != null">
        <div class="col-md-12"><br>
          <h3 class="font-weight-bold" v-if="proceso == 1">Listado de estudiantes con Servicio Social Finalizado</h3>
          <h3 class="font-weight-bold" v-if="proceso == 2">Listado de estudiantes con Práctica Profesional Finalizado</h3>
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
                <th class="text-center">Carrera</th>
                <th class="text-center">Entregada</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
             <tr v-for="item in arrayStudents" :key="item.id">
              <td v-text="item.nombre +' '+ item.apellido"></td>
              <td class="text-center" v-text="item.carrera.nombre"></td>
              <td class="text-center">
                <template>
                 <h4 v-if="proceso==1">
                   <span v-if="item.gestion_proyecto[0].constancia_entreg.length != 0 && item.gestion_proyecto[0].constancia_entreg.length != undefined " class="badge badge-pill badge-primary">{{"Entregada: " + item.gestion_proyecto[0].constancia_entreg[0].created_at}}</span>
                   <span v-else class="badge badge-pill badge-danger">No Entregada</span>
                 </h4>
                 <h4 v-if="proceso==2">
                  <span v-if="item.gestion_proyecto[1].constancia_entreg.length > 0" class="badge badge-pill badge-primary">{{"Entregada: " + item.gestion_proyecto[1].constancia_entreg[0].created_at}}</span>
                   <span v-else class="badge badge-pill badge-danger">No Entregada</span>
                 </h4>
               </template>
             </td>
             <td class="text-center">
              <button type="button" style="cursor:pointer;" @click="saveDoc(item.id,proceso)" class="button blue" data-toggle="tooltip" title="Generar Constancia"><i class="mdi mdi-file-document-box i-crud"></i></button>
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
      documento_selected: 0,
      obsDoc: "",
      idGP: 0,
      modalEnd: 0,
      textoBtn: "",
      fechaFin: '',
      obsFinal: '',
      hrsFinal: 0
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

  //obtener todas las carreras registradas
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

  //listado de los estudiantes por carrera que han finalizado un proceso en especifico
  getGestionProy(carrera_id,proceso_id,page,buscar) {
    let me = this;
    me.loadSpinner = 1;
    var url = "/gestionproyectos/constancias?carre_id="+ carrera_id +"&proceso_id=" + proceso_id + "&page=" + page +"&buscar=" + buscar;
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
      me.getGestionProy(me.carrera_selected.value,me.proceso,page,"");
    }
  },

  //descargar el pdf de la constancia
  downloadPdfFromBase64(base64){
   let a = document.createElement("a");
   var name = "Constancia " + new Date(Date.now()).toLocaleString();
   a.href = "data:application/octet-stream;base64,"+base64;
   a.download = name+".pdf"
   a.click();
 },
 saveDoc(gp_id,procesoId) {
  let me = this;
  const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000});
  swal({
    title: "¿ Desea Generar Constancia?",
    type: "warning",
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
     var url = route('getConstancia', {"estudianteId": gp_id,"procesoId": procesoId});
     window.open(url);
     me.getGestionProy(me.carrera_selected.value,me.proceso,1,"");
   } else if (
    result.dismiss === swal.DismissReason.cancel

    ) {
     me.getGestionProy(me.carrera_selected.value,me.proceso,1,"");
   }
   me.getGestionProy(me.carrera_selected.value,me.proceso,1,"");
 });
},
mounted(){}
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

