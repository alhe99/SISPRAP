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
                    <h4 class="font-weight-bold" >Reporte General de Supervisiones</h4>
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
        <legend class="text-center">Seleccione un proceso para la generación del informe</legend>
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
        <button type="button" id="btnGenerar" :class="[validate == true ? 'disabled' : '']"  :disabled="validate == true" class="button blue" @click="sendParameterToMethod()" data-toggle="tooltip" title="Generar Hoja de Supervisión"><i class="mdi mdi-package-down"></i>&nbsp;Generar Reporte</button>
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
     var url = route('getReporteSupervisiones',{'proceso_id':me.proceso_id})
     window.open(url);
     me.clearData();
   }
 },
 components: {
  Switches,
},
mounted() {

}

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