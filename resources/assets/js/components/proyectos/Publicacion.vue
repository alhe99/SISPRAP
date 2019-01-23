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
                          <input id="radioSS" value="1" v-model="proceso" :onChange="changeHours" type="radio" name="radioP">
                          <label for="radioSS">Servicio Social</label>
                        </div>
                        <div class="col-md-6 text-center">
                          <input id="radioPP" value="2" v-model="proceso" :onChange="changeHours" type="radio" name="radioP">
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
            >Formulario para publicación proyectos  de Servicio Social</h3>
            <h3
            class="text-center font-weight-bold"
            v-if="proceso==2"
            >Formulario para publicación proyectos  de Práctica Profesional</h3>
            <form>
              <div class="form-group" style="margin-left: -12px;">
                <div class="row">
                  <div class="col-md-10">
                    <mdc-textfield
                    type="text"
                    class="col-md-12"
                    label="Nombre del proyecto"
                    helptext="(Ingrese el nombre del proyecto a publicar)"
                    v-model="nombre"
                    ></mdc-textfield>
                  </div>
                  <div class="col-md-2"><br>
                    <checkbox v-model="proyectoExterno">Proyecto Externo</checkbox>
                  </div>
                </div>
              </div>
              <div class="row">
                <mdc-textfield
                type="text"
                v-mask="'###'"
                :min="0"
                :max="300"
                class="col-md-6"
                :class="[proyectoExterno == true ? 'col-md-12' : 'col-md-6']"
                label="Cantidad de horas a realizar:"
                helptext="Detalle el numero de horas del proyecto"
                v-model="catidadHoras"
                ></mdc-textfield>
                <mdc-textfield
                type="text"
                v-mask="'####'"
                :min="0"
                :max="500"
                v-show="proyectoExterno == false"
                class="col-md-6"
                label="Cantidad de alumnos para proyecto:"
                helptext="Digite la cantidad"
                v-model="cantidadVacantes"
                ></mdc-textfield>
              </div>
              <div class="form-group row" v-if="proceso==2 && proyectoExterno == false">
                <div class="col-md-10 col-sm-10 col-lg-10">
                  <v-select
                  multiple
                  ref="selectCarreras"
                  v-model="carrerasProy"
                  :options="arrayCarreras"
                  placeholder="Seleccione una o mas carreras"
                  ><span slot="no-options">
               No hay datos disponibles
             </span></v-select>
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
              v-else-if="proceso == 1 || proyectoExterno == true"
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
              ><span slot="no-options">
               No hay datos disponibles
             </span></v-select>
            </div>
          </div>
          <div class="form-group row" v-if="proyectoExterno == false">
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
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <button type="button" :class="[validate == true ? 'disabled' : '']" :disabled="validate == true" id="btnGuardar" class="button blue" @click="saveProyect"><i class="mdi mdi-content-save"></i>&nbsp;Guardar Proyecto</button>
        <!-- <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader> -->
      </div>
    </div>
  </form>
  <div class="row">
    <div class="col-md-12 loading text-center" v-if="loadSpinner == 1"></div>
  </div>
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
      catidadHoras: 0,
      cantidadVacantes: 1,
      imgGallery: "",
      proyectoExterno: false,
      nombre: "",
      arrayImages: [
      "test.jpg",
      "turismo.jpg",
      "computacion.jpg",
      "focos.jpg",
      "herramienta.png",
      "mercadeo.jpg",
      "agro.jpg"
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
    changeHours: function(){
      let me = this;
      if (me.proceso==1){me.catidadHoras=300}else{me.catidadHoras=160}
    }
},
methods: {

      //cambiar imagen
      changeImg(file) {
        this.image = "";
        this.addImage(file);
      },

      //agregar imagen
      addImage(file) {
        const img = new Image(),
        reader = new FileReader();
        reader.onload = e => (this.image = e.target.result);
        reader.readAsDataURL(file);
      },

      //limpiar galeria de imagenes
      clearGallery() {
        let me = this;
        const elem = me.$refs.imgUpload;
        me.imgGallery = "";
        me.image = "";
        elem.reset();
      },
      //obtener carreras
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

      //obtener todas las instituciones
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

      //registrar proyecto
      saveProyect() {
        const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000});
        let me = this;
        var tipoProyecto = 'I';
        if (me.proyectoExterno) {tipoProyecto = 'E';};
        this.loadSpinner = 1;
        axios
        .post("/proyecto/registrar", {
          proceso_id: me.proceso,
          nombre: me.nombre,
          actividades: me.actividadesProy,
          institucion_id: me.institucion.value,
          imageG: me.imgGallery,
          imagen: me.image,
          actividadSS: me.actividadesCarre,
          horas: me.catidadHoras,
          cantidadAlumnos: me.cantidadVacantes,
          tipoProyecto: tipoProyecto
        })
        .then(function(response) {
          swal({
            position: "center",
            type: "success",
            title: "¡Proyecto publicado correctamente!",
            showConfirmButton: false,
            timer: 1000
          });
          if(tipoProyecto == 'I')
            me.clearData();
          else
            me.clearDataExternos()
        })
        .catch(error => {
          me.loadSpinner = 0;
          toast({
            type: 'danger',
            title: 'Error! Intente Nuevamente'
          });
        });

      },
      //agregar elementos al arreglo de actvidades del proceso de practica profesional
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
        me.cantidadVacantes = 0;
        me.catidadHoras = 0;
        // me.proyectoExterno = false;
        if(me.proceso==1)
          me.catidadHoras = 300;
        else
          me.catidadHoras = 160;
        me.clearGallery();
        if (me.switchImg == true) {
          me.switchImg = false;
        }
        const elem = me.$refs.divCollapse;
        if (elem.classList.contains("collapse")) {
          elem.classList.remove("show");
        }
      },
      clearDataExternos(){
       let me = this;
       me.nombre = "";
       me.actividadesCarre = "";
       me.institucion = "";
       me.loadSpinner = 0;
       me.proyectoExterno = false;
       if(me.proceso==1)
        me.catidadHoras = 300;
      else
        me.catidadHoras = 160;

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
