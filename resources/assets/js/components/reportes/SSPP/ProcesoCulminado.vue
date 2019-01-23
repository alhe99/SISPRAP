|<template>
  <div class="col-lg-12 col-md-12">
    <div class="row">
      <div class="col-md-12 loading text-center" v-if="loadSpinner == 1"></div>
    </div>
    <div class="card">
      <div class="card-body">
       <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
           <fieldset>
            <legend class="text-center">Seleccione un proceso para el informe</legend>
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="row md-radio">
                  <div class="col-md-6 text-center">
                    <input id="radioSS" value="1" v-model="proceso_id" type="radio" name="radioP" >
                    <label for="radioSS">Servicio Social</label>
                  </div>
                  <div class="col-md-6 text-center">
                    <input id="radioPP" value="2" v-model="proceso_id" type="radio" name="radioP" >
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
<div class="card"  v-if="proceso_id != 0">
  <div class="card-body">
   <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
       <fieldset>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row md-radio">
              <div class="col-md-12 text-center">
                <h4 class="font-weight-bold" v-if="proceso_id == 1">Reporte De Estudiantes Que Culminaron Servicio Social</h4>
                <h4 class="font-weight-bold" v-if="proceso_id == 2">Reporte De Estudiantes Que Culminaron Práctica Profesional</h4>
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

<div class="card"  v-if="proceso_id != 0">
  <div class="card-body">
   <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <fieldset>
          <legend class="text-center">Seleccione el tipo de informe</legend>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="form-group row">
               <div class="col-md-12 col-lg-12  col-sm-12">
                <div class="row">
                  <div class="col-md-6 col-sm-12 col-lg-6 text-center">&nbsp;
                    <switches :disabled="mensual == true" class="switch-md" v-model="trimestral " theme="bootstrap" color="primary"></switches>
                    <i class="mdi mdi-calendar-range h4"></i> Trimestral
                  </div>
                  <div class="col-md-6 col-sm-12 col-lg-6 text-center">
                    <switches :disabled="trimestral == true" class="switch-md" v-model="mensual " theme="bootstrap" color="primary"></switches>&nbsp;
                    <i class="mdi mdi-calendar-check h4"></i>  Mensual
                  </div>
                </div>
                <div v-if="trimestral == true" class="row">
                  <div class="col-md-12">
                    <br><v-select v-model="trimestre" :options="arrayTrimestral" placeholder="Seleccione un trimestre"></v-select>
                  </div>
                </div>
                <div v-if="mensual == true" class="row">
                  <div class="col-md-10">
                    <br><v-select multiple :disabled="anual==true" v-model="mes" :options="arrayMeses" placeholder="Seleccione mes"></v-select>
                  </div>
                  <div class="col-md-2">
                    <br><checkbox  v-model="anual">Reporte Anual</checkbox>
                  </div>
                </div>
                <div class="col-md-12 text-center"><br>
                  <button type="button" id="btnGenerar" :class="[mes == '' && trimestre == '' && anual == false ? 'disabled' : '']" :disabled="mes == '' && trimestre == '' && anual == false" class="button secondary" @click="sendParameterToMethod()" data-toggle="tooltip" title="Generar Reporte"><i class="mdi mdi-package-down"></i>&nbsp;Generar Reporte</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </fieldset><br>
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</template>
<script>
import Switches from 'vue-switches';
export default {
  data(){
    return {
      loadSpinner: 0,
      proceso_id: 0,
      mes: "",
      carreras: "",
      trimestre: "",
      arrayTrimestral: [
      {value: [1,2,3] ,label: "Primer Trimestre"},
      {value: [4,5,6] ,label: "Segundo Trimestre"},
      {value: [7,8,9] ,label: "Tercer Trimestre"},
      {value: [10,11,12] ,label: "Cuarto Trimestre"},
      ],
      arrayMeses: [
      {value: 1 ,label: "Enero"},
      {value: 2 ,label: "Febrero"},
      {value: 3 ,label: "Marzo"},
      {value: 4 ,label: "Abril"},
      {value: 5 ,label: "Mayo"},
      {value: 6 ,label: "Junio"},
      {value: 7 ,label: "Julio"},
      {value: 8 ,label: "Agosto"},
      {value: 9 ,label: "Septiembre"},
      {value: 10 ,label: "Octubre"},
      {value: 11 ,label: "Noviembre"},
      {value: 12 ,label: "Diciembre"},
      ],
      arrayCarreras: [],
      tipoRepo: '',
      trimestral: false,
      mensual: false,
      valuesMonth: [],
      anual: false
    }
  },
  watch:{
    proceso_id:function(){

      this.trimestral = false;
      this.mensual = false;
      this.trimestre = "";
      this.anual=false;

    },
    trimestral:function(){
      if (this.trimestral == true) {this.tipoRepo = 'T';}
      else {this.tipoRepo = '';}
      this.trimestre = "";
      this.anual=false;
    },
    mensual:function(){
      if (this.mensual == true) {this.tipoRepo = 'M';}
      else {this.tipoRepo = '';}
      this.mes = [];
      this.valuesMonth = [];
      this.anual=false;
    },
    mes:function(){
      for (var i = 0; i < this.mes.length; i++) {
        this.valuesMonth[i] = this.mes[i].value;
      }
    },
    anual: function(){
      if (this.anual) {
        this.tipoRepo = 'A';
        this.mes = [];
        this.valuesMonth = [];
      }
      else{
       this.tipoRepo = 'M';
     }
   }
 },
 methods: {
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
  clearData(){
    this.trimestre = "";
    this.mes = [];
    this.valuesMonth = [];
    this.anual=false;
  },
  sendParameterToMethod() {
    let me = this;
    if(me.trimestral == true)
     var url = route('reporteProcesosCulminados',{'proceso_id':me.proceso_id,'meses': [me.trimestre.value],'tipoRepo':me.tipoRepo})
    else if(me.mensual == true)
    var url = route('reporteProcesosCulminados',{'proceso_id':me.proceso_id,'meses': [me.valuesMonth],'tipoRepo':me.tipoRepo})
   else if(me.anual == true)
    var url = route('reporteProcesosCulminados',{'proceso_id':me.proceso_id,'tipoRepo':me.tipoRepo})
    window.open(url);
    me.clearData();
  },
},
components: {
  Switches,
},
mounted() {
  this.getCarreras();
}

}
</script>
