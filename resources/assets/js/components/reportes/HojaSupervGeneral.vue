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
                  <legend class="text-center">Seleccione Municipios para la generación de la hoja de supervisión:</legend>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-6 text-center"><br>
                         <v-select label="label" v-model="departamento_id" placeholder="Seleccione un departamento" :options="arrayDepartamentos"></v-select>
                        </div>
                        <div class="col-md-6 text-center"><br>
                         <v-select ref="selectMuni" label="label" v-model="municipio_id"  placeholder="Seleccione un municipio"  :options="arrayMunicipios"></v-select>
                        </div>
                        <div class="col-md-12 text-center"><br>
                            <button type="button" id="btnGenerar" class="button blue" @click="sendParameterToMethod()" data-toggle="tooltip" title="Generar Hoja de Supervisión">Generar Hoja de Supervisión</button>
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
      municipio_id: 0,
      loadSpinner: 0
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
           $("#btnGenerar").prop('disabled',true);
      }
      this.getMunicipios();
      this.municipio_id = 0;
      if ((this.departamento_id == 1) || (this.departamento_id == null) ) {
        this.municipio_id = 0;
      }
    },
    municipio_id: function(){
        if(this.municipio_id != 0){
            $("#btnGenerar").prop('disabled',false);
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
    viewPdfFromBase64(base64,titlepdf){
        var objbuilder = '';
        objbuilder += ('<object width="100%" height="100%"data="data:application/pdf;base64,');
        objbuilder += (base64);
        objbuilder += ('" type="application/pdf" class="internal">');
        objbuilder += ('<embed src="data:application/pdf;base64,');
        objbuilder += (base64);
        objbuilder += ('" type="application/pdf"  />');
        objbuilder += ('</object>');

        var win = window.open("#","_blank");
        win.document.write('<html><title>'+ titlepdf +'</title><body style="margin-top:0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px;">');
        win.document.write(objbuilder);
        win.document.write('</body></html>');
        layer = jQuery(win.document); 

        
    },
    downloadPdfFromBase64(base64){
         let a = document.createElement("a");
         var name = "Supervisión General " + new Date(Date.now()).toLocaleString();
         a.href = "data:application/octet-stream;base64,"+base64;
         a.download = name+".pdf"
         a.click();
    },
    clearData(){
        let me = this;
        const vselect = this.$refs.selectMuni;
        me.municipio_id = 0;
        me.departamento_id = 0;
        vselect.disabled = true;
        $("#btnGenerar").prop('disabled',true);
    },
    sendParameterToMethod() {
      let me = this;
      me.loadSpinner = 1;
      var url = "institucion/getHojaSupervision?muni_id="+this.municipio_id.value;
      axios.post(url).then(function(response) {
          var respuesta = response.data;
          console.log(respuesta);
          me.loadSpinner = 0;
          me.viewPdfFromBase64(respuesta,'Hoja de Supervisión General');
          me.downloadPdfFromBase64(respuesta,name);
          me.clearData();
        })
        .catch(function(error) {
          console.log(error);
        });
        //window.open('http://sisprap.test/institucion/getHojaSupervision?'+this.municipio_id.value + ',' + '_blank')
    },
   },
   mounted() {
       const vselect = this.$refs.selectMuni;
       this.getDepartamentos();
        if(this.municipio_id == 0){
            $("#btnGenerar").prop('disabled',true);
        }
        vselect.disabled = true;
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
