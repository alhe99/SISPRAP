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
                         <fieldset >
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
                        </fieldset><br>
                        <fieldset v-if="proceso_id != 0">
                            <legend class="text-center">Seleccione el tipo de informe</legend>
                            <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group row">
                                     <div class="col-md-12 col-lg-12  col-sm-12">
                                    <div class="row">
                                    <div class="col-md-6 col-sm-12 col-lg-6 text-center">
                                    <button
                                        ref="btntest"
                                        class="btn btn-primary font-weight-bold text-dark"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseExample"
                                        aria-expanded="false"
                                        aria-controls="collapseExample">
                                    
                                        <i class="mdi mdi-folder-multiple-image h4"></i> Trimestral
                                    </button>
                                    </div>
                                     <div class="col-md-6 col-sm-12 col-lg-6 text-center">
                                    <button
                                        ref="btntest"
                                        class="btn btn-primary font-weight-bold text-dark"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseExample2"
                                        aria-expanded="false"
                                        aria-controls="collapseExample">
                                    
                                        <i class="mdi mdi-folder-multiple-image h4"></i> Mensual
                                    </button>
                                    </div>
                                </div>
                                 <div
                                        class="collapse"
                                        ref="divCollapse"
                                        id="collapseExample"
                                    > <v-select
                                        v-model="trimestre"
                                        :options="arrayTrimestral"
                                        placeholder="Seleccione un trimestre"
                                    ></v-select></div>

                                    <div
                                        class="collapse"
                                        ref="divCollapse"
                                        id="collapseExample2"
                                    > <v-select
                                        multiple
                                        v-model="mes"
                                        :options="arrayMeses"
                                        placeholder="Seleccione mes"
                                    ></v-select></div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </fieldset><br>
                         <div class="col-md-12 text-center"><br>
                            <button type="button" id="btnGenerar" class="button blue" @click="sendParameterToMethod()" data-toggle="tooltip" title="Generar Hoja de Supervisión">Generar Reporte</button>
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
                    "Primer Trimestre",
                    "Segundo Trimestre",
                    "Tercer Trimestre",
                    "Cuarto Trimestre"
                ],
                arrayMeses: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                 arrayCarreras: [],
            }
        },
        watch:{
            
        },
        methods: {
            getCarreras() {
                let me = this;
                var url = "carreras/GetCarreras";
                axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    //console.log(respuesta);
                    me.arrayCarreras = respuesta;
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            clearData(){
                let me = this;
                me.proceso_id = 0;
                $("#btnGenerar").prop('disabled',true);
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
                var name = "Institucion por proceso ";
                a.href = "data:application/octet-stream;base64,"+base64;
                a.download = name+".pdf"
                a.click();
            },
            sendParameterToMethod(){
                let me = this;
                me.loadSpinner = 1;
                var url = "institucion/getInstituciones/" +this.proceso_id;
                axios.post(url).then(function(response) {
                var respuesta = response.data;
                me.loadSpinner = 0;
                me.viewPdfFromBase64(respuesta,'Instituciones generales');
                me.downloadPdfFromBase64(respuesta,name);
                me.clearData();
                })
                .catch(function(error) {
                console.log(error);
                });
            }
        },
        components: {
            Switches,
        },
        mounted() {
                this.getCarreras();
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