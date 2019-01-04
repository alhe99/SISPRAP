<template>
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <fieldset>
                  <legend class="text-center">Seleccione un proceso para la publicación del proyecto</legend>
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
    <div class="card" v-if="proceso != 0 ">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <h3
              class="text-center font-weight-bold"
              v-if="proceso==1"
            >Formulario de publicación para proyectos de Servicio Social</h3>
            <h3
              class="text-center font-weight-bold"
              v-if="proceso==2"
            >Formulario de publicación para proyectos de Práctica Profesional</h3>
            <form>
              <div class="form-group row">
                <mdc-textfield
                  type="text"
                  class="col-md-12"
                  label="Nombre del proyecto"
                  helptext="(Ingrese el nombre del proyecto a publicar)"
                  v-model="nombre"
                ></mdc-textfield>
              </div>
              <div class="form-group row" v-if="proceso==2">
                <div class="col-md-10 col-sm-10 col-lg-10">
                  <v-select
                    multiple
                    ref="selectCarreras"
                    v-model="carrerasProy"
                    :options="arrayCarreras"
                    placeholder="Seleccione una o mas carreras"
                  ></v-select>
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 text-primary"></div>
              </div>
              <div class="form-group row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                  <label for="vEditorSS">Describa Actividades a Realizar:</label>
                  <form-wizard
                    v-if="proceso == 2 && carrerasProy.length != 0 "
                    title="Actividades"
                    subtitle="Detalle Actividades para cada carrera seleccionada"
                    color="#6200ee"
                  >
                    <wizard-step
                      slot-scope="props"
                      slot="step"
                      :tab="props.tab"
                      :transition="props.transition"
                      :index="props.index"
                    ></wizard-step>
                    <tab-content
                      v-for="carreras in carrerasProy"
                      :key="carreras.id"
                      :title="carreras['label']"
                      ref="titleC"
                    >
                      <vue-editor
                        ref="editorPP"
                        :disabled="disabledVE"
                        v-model="actividadesCarre"
                        :editorToolbar="toolBars"
                      ></vue-editor>
                    </tab-content>
                    <mdc-button
                      type="button"
                      ref="myBtn"
                      id="btnNextActi"
                      raised
                      slot="next"
                      @click="agregarActivi"
                      :disabled="actividadesCarre == ''"
                    >Siguiente</mdc-button>
                    <mdc-button
                      type="button"
                      ref="btnEnd"
                      raised
                      slot="finish"
                      @click="agregarActivi"
                      :disabled="actividadesCarre == ''"
                    >Aceptar</mdc-button>
                    
                  </form-wizard>
                  <vue-editor
                    v-else-if="proceso == 1"
                    v-model="actividadesCarre"
                    :editorToolbar="toolBars"
                    id="vEditorSS"
                  ></vue-editor>
                </div>
                
              </div>
              <div class="form-group row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                  <v-select
                    v-model="institucion"
                    :options="arrayInstituciones"
                    placeholder="Seleccione una Institución"
                  ></v-select>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-12 col-lg-12  col-sm-12">
                  <div class="row">
                    <div class="col-md-6 col-sm-12 col-lg-6">
                      <button
                        :disabled="switchImg ==true"
                        ref="btntest"
                        v-on:click="clearGallery()"
                        class="btn btn-primary font-weight-bold text-dark"
                        type="button"
                        data-toggle="collapse"
                        data-target="#collapseExample"
                        aria-expanded="false"
                        aria-controls="collapseExample"
                      >
                        <i class="mdi mdi-folder-multiple-image h4"></i> Imágenes Predeterminadas
                      </button>
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-6">
                      <div class="form-group text-right">
                        <switches
                          class="switch-md"
                          v-model="switchImg "
                          theme="bootstrap"
                          color="primary"
                        ></switches>
                        <label>Seleccionar Imagen De PC</label>
                      </div>
                    </div>
                  </div>
                  <div
                    v-show="switchImg==false"
                    class="collapse"
                    ref="divCollapse"
                    id="collapseExample"
                  >
                    <div class="card card-body">
                      <div class="row" id="seccion">
                        <div
                          class="col-md-2 col-sm-12 col-lg-2"
                          v-for="image in arrayImages"
                          :key="image.id"
                        >
                          <input
                            type="radio"
                            :id="image"
                            name="select"
                            :value="image"
                            v-model="imgGallery"
                          >
                          <label :for="image">
                            <img
                              class="text-center img-fluid"
                              :src="'images/img_projects/' + image"
                              alt=""
                            >
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="imageP" v-show="switchImg==true">
                    <img-inputer
                      class="col-md-12 col-sm-12 col-lg-12"
                      ref="imgUpload"
                      icon="img"
                      bottom-text="Seleccionar nueva imagen"
                      theme="material"
                      accept="image/*"
                      @onChange="changeImg"
                      size="large"
                      placeholder="Selecione una imagen de su computadora!"
                    />
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <br>
                      <button type="button" :disabled="validate == true" id="btnGuardar" class="button blue" @click="saveProyect"><i class="mdi mdi-content-save"></i>&nbsp;Guardar Proyecto</button>
                      <!-- <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 loading text-center" v-if="loadSpinner == 1"></div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import { VueEditor } from "vue2-editor";
  import Switches from "vue-switches";
  export default {
    data() {
      return {
        loadSpinner: 0,
        disabledVE: false,
        actividadesCarre: "",
        indiceCarre: 0,
        actividadesProy: [],
        proceso: 0,
        switchImg: false,
        imgGallery: "",
        nombre: "",
        arrayImages: [
          "test.jpg",
          "turismo.jpg",
          "computacion.jpg",
          "focos.jpg",
          "herramienta.jpg",
          "mercadeo.jpg"
        ],
        image: "",
        exist: false,
        carrerasProy: [],
        arrayCarreras: [],
        arrayInstituciones: [],
        arrayActividades: [],
        institucion: "",
        toolBars: [
          [{ header: [false, 1, 2, 3, 4, 5, 6] }],
          [
            { align: "" },
            { align: "center" },
            { align: "right" },
            { align: "justify" }
          ],
          ["bold", "blockquote", "code-block"],
          [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],
          [{ indent: "-1" }, { indent: "+1" }],
          [{ color: [] }]
        ]
      };
    },
    watch: {
      switchImg: function() {
        if (this.switchImg == true) {
          this.imgGallery = "";
        }
      },
      proceso: function() {
        if (this.proceso != 0) {
          this.validateIfExist("");
          this.getInstituciones();
          this.clearData();
        }
      },
      indiceCarre: function() {
        const tituloStep = this.$refs.titleC;
        if (this.indiceCarre > tituloStep.length) {
          this.indiceCarre = tituloStep.length;
        }
      },
      actividadesProy: function() {
        if (this.carrerasProy.length > 0) {
          var ultimoObj = this.actividadesProy[this.actividadesProy.length - 1];
          if (ultimoObj.actividades == "") {
            this.actividadesProy.splice(this.actividadesProy.length - 1, 1);
            this.indiceCarre = this.indiceCarre - 1;
          }
        }
      },
      carrerasProy: function() {
        this.disabledVE = false;
        if (this.carrerasProy.length > 1) {
        if (this.carrerasProy.length -1 == this.actividadesProy.length) {
               this.clickBtn();
        }
        }
      },
      nombre: function(){
        this.validateIfExist(this.nombre);
      }
    },
    computed:{
      validate: function(){
        //if((this.nombre == "") || (this.actividadesCarre == "") || (this.institucion == ""))
        if((this.nombre == "") || (this.institucion == ""))
        //if((this.nombre == "") || (this.actividadesCarre == "") || (this.institucion == ""))
        {
          return true;
        }else{
          return false;
        }
      },
    },
    methods: {
      changeImg(file) {
        this.image = "";
        this.addImage(file);
      },
      addImage(file) {
        const img = new Image(),
        reader = new FileReader();
        reader.onload = e => (this.image = e.target.result);
        reader.readAsDataURL(file);
      },
      clearGallery() {
        let me = this;
        const elem = me.$refs.imgUpload;
        me.imgGallery = "";
        me.image = "";
        elem.reset();
      },
      validateIfExist(project){
        let me = this;
        var url = "/proyecto/validatess/" + project;
        axios.get(url).then(function(response) {
             var respuesta = response.data;
             console.log(respuesta);
             if(respuesta == true){
                me.exist = true;
             }else {
               me.exist = false;
             }
        });
      },
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
      getInstituciones() {
        let me = this;
        var url = "GetInstituciones/" + this.proceso;
        axios
          .get(url)
          .then(function(response) {
            var respuesta = response.data;
            me.arrayInstituciones = respuesta;
          })
          .catch(function(error) {
            console.log(error);
          });
      },
      saveProyect() {
        let me = this;
        this.loadSpinner = 1;
        if(me.exist == false){
            axios
            .post("/proyecto/registrar", {
              proceso_id: this.proceso,
              nombre: this.nombre,
              actividades: this.actividadesProy,
              institucion_id: this.institucion.value,
              imageG: this.imgGallery,
              imagen: this.image,
              actividadSS: this.actividadesCarre
            })
            .then(function(response) {
              swal({
                position: "center",
                type: "success",
                title: "¡Proyecto publicado correctamente!",
                showConfirmButton: false,
                timer: 1000
              });
              me.clearData();
            })
            .catch(error => {
              me.loadSpinner = 0;
              console.log(error.response.data.errors);
            });
        }else{
           swal({
                  position: "center",
                  type: "warning",
                  title: "Proyecto existente! Ingrese otro nombre",
                  showConfirmButton: true,
                  timer: 5000
                });
                me.nombre = "";
                me.loadSpinner = 0;
                me.exist = false;
        }
      
      },
      agregarActivi() {
        this.indiceCarre = this.indiceCarre + 1;
        const tituloStep = this.$refs.titleC;
        this.actividadesProy.push({
          actividades: this.actividadesCarre,
          carrera_id: this.carrerasProy[this.indiceCarre - 1].value
        });
        this.actividadesCarre = "";
        const btnEnd = this.$refs.btnEnd;
        if (btnEnd._events.click) {
          this.disabledVE = true;
        }
      },
      clickBtn() {
        $(function() {
          $("#btnNextActi").click();
        });
      },
      clearData() {
        let me = this;
        me.nombre = "";
        me.actividadesCarre = "";
        me.indiceCarre = 0;
        me.carrerasProy = "";
        me.actividadesProy.pop();
        me.institucion = "";
        me.carrerasProy = [];
        me.imgGallery = "";
        me.image = "";
        me.loadSpinner = 0;
        me.clearGallery();
        if (me.switchImg == true) {
          me.switchImg = false;
        }
        const elem = me.$refs.divCollapse;
        if (elem.classList.contains("collapse")) {
          elem.classList.remove("show");
        }
      }
    },
    components: {
      VueEditor,
      Switches
    },
    mounted() {
     
      this.getCarreras();
      this.getInstituciones();
      //$("#btnGuardar").prop('disabled',true);
    }
  };
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
