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
  <div class="card" >
    <div class="card-body">
     <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-body">
         <fieldset>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row md-radio">
                <div class="col-md-12 text-center">
                  <h4 class="font-weight-bold" >Hoja de Supervisión General</h4>
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
<div class="card" v-show="proceso != 0">
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <fieldset>
              <legend class="text-center">Seleccione Municipios para la generación de la hoja de supervisión:</legend>
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6 text-center"><br>
                     <v-select label="label" v-model="departamento_id" placeholder="Seleccione un departamento" :options="arrayDepartamentos">
                      <span slot="no-options">
                         No hay datos disponibles
                      </span>
                    </v-select>
                  </div>
                  <div class="col-md-6 text-center"><br>
                    <v-select :disabled="departamento_id == 0 || departamento_id == undefined" ref="selectMuni" multiple label="label" v-model="municipio_id"  placeholder="Seleccione un municipio"  :options="arrayMunicipios">
                      <span slot="no-options">
                         No hay datos disponibles
                      </span>
                    </v-select>
                  </div>
                  <div class="col-md-12 text-center"><br>
                    <v-select ref="selectInstitucion" :disabled="municipios_selected.length == 0" multiple label="label" v-model="institucion_id"  placeholder="Seleccione una institución"  :options="arrayInstituciones">
                      <span slot="no-options">
                         No hay datos disponibles
                      </span>
                    </v-select>
                  </div>
                  <div class="col-md-12 text-center"><br>
                    <button type="button" :class="[instituciones_selected.length == 0 ? 'disabled' : '']" :disabled="instituciones_selected.length == 0" id="btnGenerar" class="button blue" @click="sendParameterToMethod()" data-toggle="tooltip" title="Generar Hoja de Supervisión"><i class="mdi mdi-package-down"></i>&nbsp;Generar Hoja de Supervisión</button>
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
</div>
</template>
<script>
export default {
  data() {
    return {
      arrayDepartamentos: [],
      departamento_id: 0,
      arrayMunicipios: [],
      municipio_id: [],
      loadSpinner: 0,
      municipios_selected : [],
      proceso: 0,
      arrayInstituciones: [],
      carrera_id: 0,
      institucion_id: [],
      instituciones_selected : [],

    }
  },
  watch: {
    proceso:function(){
      this.clearData();
    },
    departamento_id: function() {
      if((this.departamento_id == null) || (this.departamento_id == 0)){
       this.municipio_id = 0;
     }
     this.municipio_id = 0;
     if ((this.departamento_id == 1) || (this.departamento_id == null) ) {
      this.municipio_id = [];
     }else{
      this.getMunicipios();
     }
  },
  municipio_id: function(){
    let me = this;
    const vselectInsti = me.$refs.selectInstitucion;
    for (var i = 0; i < me.municipio_id.length; i++) {
      me.municipios_selected[i] = me.municipio_id[i].value;
    }
    me.getInstituciones();
    if(me.municipio_id.length == 0){
      me.arrayInstituciones = [];
      me.institucion_id = [];
      me.instituciones_selected = [];
      me.municipios_selected = [];
    }
  },
  institucion_id: function(){
    let me = this;
   for (var i = 0; i < me.institucion_id.length; i++) {
    me.instituciones_selected[i] = me.institucion_id[i].value;
  }
}
},
methods: {
  getMunicipios() {
    let me = this;
    me.loadSpinner = 1;
    var url = route('getMunicipios',me.departamento_id["value"])
    axios.get(url).then(function(response) {
      var respuesta = response.data;
      me.arrayMunicipios = respuesta;
      me.loadSpinner = 0;
    })
    .catch(function(error) {
      me.loadSpinner = 0;
      console.log(error);
    });
  },
  getInstituciones() {
    let me = this;
    me.loadSpinner = 1;
    var url = route('getInstitucionesByProcess', {"proceso_id" : me.proceso,"municipio_id": me.municipios_selected});
    axios
    .get(url)
    .then(function(response) {
      var respuesta = response.data;
      me.arrayInstituciones = respuesta;
      me.loadSpinner = 0;
    })
    .catch(function(error) {
      me.loadSpinner = 0;
      console.log(error);
    });
  },
  getDepartamentos() {
    axios.get("GetDepartamentos").then(response => {
      this.arrayDepartamentos = response.data;
    });
  },
  clearData(){
    let me = this;
    me.municipio_id = [];
    me.departamento_id = 0;
    me.municipios_selected = [];
    me.instituciones_selected = [];
    me.institucion_id = [];

  },
  sendParameterToMethod() {
    let me = this;
    var url = route('getHojaSupervision', {"instituciones_id" : me.instituciones_selected,"proceso_id": me.proceso});
    window.open(url);
    me.clearData();

  },
},
mounted() {
 this.getDepartamentos();
}
};
</script>
