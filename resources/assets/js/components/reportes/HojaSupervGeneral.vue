<template>
  <div class="col-lg-12 col-md-12">
   <div class="row">
    <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
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
<div class="card">
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
                        No hay resultados
                      </span>
                     </v-select>
                   </div>
                   <div class="col-md-6 text-center"><br>
                    <v-select ref="selectMuni" multiple label="label" v-model="municipio_id"  placeholder="Seleccione un municipio"  :options="arrayMunicipios">
                      <span slot="no-options">
                        No hay resultados
                      </span>
                    </v-select>
                   </div>
                   <div class="col-md-12 text-center"><br>
                    <button type="button" :class="[municipios_selected.length == 0 ? 'disabled' : '']" :disabled="municipios_selected.length == 0" id="btnGenerar" class="button blue" @click="sendParameterToMethod()" data-toggle="tooltip" title="Generar Hoja de Supervisión"><i class="mdi mdi-package-down"></i>&nbsp;Generar Hoja de Supervisión</button>
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
      municipios_selected : []
    }
  },
  watch: {
    departamento_id: function() {
      const vselect = this.$refs.selectMuni;
      if(this.departamento_id != 0){
        vselect.disabled = false;
      }
      if(this.departamento_id == null){
       vselect.disabled = true;
       this.municipio_id = 0;
     }
     this.getMunicipios();
     this.municipio_id = 0;
     if ((this.departamento_id == 1) || (this.departamento_id == null) ) {
      this.municipio_id = [];
    }
  },
  municipio_id: function(){
    let me = this;
    for (var i = 0; i < me.municipio_id.length; i++) {
      me.municipios_selected[i] = me.municipio_id[i].value;
    }
  }
},
methods: {
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
  getDepartamentos() {
    axios.get("GetDepartamentos").then(response => {
      this.arrayDepartamentos = response.data;
    });
  },

  clearData(){
    let me = this;
    const vselect = this.$refs.selectMuni;
    me.municipio_id = [];
    me.departamento_id = 0;
    vselect.disabled = true;
    me.municipios_selected = []
  },
  sendParameterToMethod() {
    let me = this;
    var url = route('hojasupervigen', {"muni_id" : me.municipios_selected});
    window.open(url);
    me.clearData();

  },
},
mounted() {
 const vselect = this.$refs.selectMuni;
 this.getDepartamentos();
 vselect.disabled = true;
}
};
</script>
