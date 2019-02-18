<template>
  <div class="col-lg-12 col-md-12">
    <div class="row">
      <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
      </div>
    </div>
    <div class="card">
      <div class="card-body">
       <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
           <fieldset>
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="row md-radio">
                  <div class="col-md-12 text-center">
                    <h4 class="font-weight-bold">Reporte General De Instituciones Activas En El Sistema</h4>
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
   <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
       <fieldset >
        <legend class="text-center">Seleccione un proceso para la generacion del reporte</legend>
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
      <div class="col-md-12 text-center"><br>
        <button type="button" id="btnGenerar" :disabled="proceso_id == 0" :class="[proceso_id == 0 ? 'disabled': '']" class="button blue" @click="sendParameterToMethod()" data-toggle="tooltip" title="Generar reporte"><i class="mdi mdi-package-down"></i>&nbsp;Generar Reporte</button>
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
      proceso_id: 0
    }
  },
  watch:{

  },
  computed:{
    validate: function(){
      if(this.proceso_id == 0)
      {
        return true;
      }else{
        return false;
      }
    },
  },
  methods: {
    clearData(){
      let me = this;
      me.proceso_id = 0;
      $("#btnGenerar").prop('disabled',true);
    },
    sendParameterToMethod(){
      let me = this;
      var url = route('generalInstitucion',{'proceso_id':me.proceso_id})
      window.open(url);
      me.clearData();
    }
  },
  components: {
    Switches,
  },
}
</script>
