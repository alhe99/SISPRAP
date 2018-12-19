<template>
    <div class="col-lg-12 col-md-12">
            <div class="card">
            <div class="card-body">
                 <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-body">
                              <fieldset>
                <legend class="text-center">Configuraciones</legend>
                <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group row">
                          <div class="col-md-12 col-lg-12  col-sm-12">
                        <div class="row">
                        <div class="col-md-6 col-sm-12 col-lg-6 text-center">
                        <button
                            ref="btntest"
                            
                            type="button"
                            class="button blue"
                            data-toggle="collapse"
                            data-target="#collapseExample"
                            aria-expanded="false"
                            aria-controls="collapseExample">
                        
                            <i></i> Sector Institucion
                        </button>
                        </div>
                          <div class="col-md-6 col-sm-12 col-lg-6 text-center">
                        <button
                            ref="btntest"
                            
                            type="button"
                            class="button blue"
                            data-toggle="collapse"
                            data-target="#collapseExample2"
                            aria-expanded="false"
                            aria-controls="collapseExample">
                        
                             Estudiante
                        </button>
                        </div>
                    </div>
                      <div
                            class="collapse"
                            ref="divCollapse"
                            id="collapseExample"
                        ><br> 
                        <form>
                        
                                     <h2 class="text-center">Registro de Sector de Instituciones</h2>
                                     <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="form-group row">
                                                <mdc-textfield type="text" name="nombre" class="col-md-12" label="Nombre del sector de Institucion" helptext="(Ingrese el nombre del sector)" v-model="sector" v-validate="'required'"></mdc-textfield>
                                                    <div class="help-block alert-danger" v-show="errors.has('nombre')">{{errors.first('nombre')}}</div>

                                            </div>
                                            <mdc-button  @click="saveSector">Registrar Sector</mdc-button>
                                        </div>
                                    </div>
                      </form>
                        </div>

                        <div
                            class="collapse"
                            ref="divCollapse"
                            id="collapseExample2"
                        > 
                        

   <form >
                                     <h2 class="text-center">Registro de Estudiantes</h2>
                                     <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="form-group row">
                                                <mdc-textfield type="text" name="nombre" class="col-md-12" label="Nombre del Estudiante" helptext="(Ingrese el nombre del estudiante)" v-model="nombre" v-validate="'required'"></mdc-textfield>
                                                     <div class="help-block alert-danger" v-show="errors.has('nombre')">{{errors.first('nombre')}}</div>
                                            </div>
                                            <div class="form-group row">
                                                <mdc-textfield type="text" name="apellido" class="col-md-12" label="Apellido del Estudiante" helptext="(Ingrese el apellido del estudiante)" v-model="apellido" v-validate="'required'"></mdc-textfield>
                                                     <div class="help-block alert-danger" v-show="errors.has('apellido')">{{errors.first('apellido')}}</div>
                                            </div>
                                            <div class="form-group row">
                                                &nbsp;&nbsp;<label>Fecha de Nacimiento</label>
                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <datetime type="date" :max-datetime="maxDatetime" v-model="date" value-zone="America/El_Salvador" input-class="form-control" ></datetime>
                                                    </div>
                                            </div>
                                             <div class="row md-radio">
                                                <div class="col-md-6 text-center">
                                                    <input id="radioSS" value="F" v-model="genero" type="radio" name="radioP" >
                                                    &nbsp;&nbsp;<label for="radioSS">Femenino</label>
                                                </div>
                                                <div class="col-md-6 text-center">
                                                    <input id="radioPP" value="M" v-model="genero" type="radio" name="radioP" >
                                                    <label for="radioPP">Masculino</label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <mdc-textfield type="text" name="telefono" class="col-md-12" label="Telefono" helptext="(Ingrese número de telefono)" v-model="telefono" v-validate="'required'"></mdc-textfield>
                                                 <div class="help-block alert-danger" v-show="errors.has('telefono')">{{errors.first('telefono')}}</div>
                                            </div>
                                            <div class="form-group row">
                                                <mdc-textfield type="text" name="carnet" class="col-md-12" label="Carnet" helptext="(Ingrese el número de carnet)" v-model="carnet" v-validate="'required'"></mdc-textfield>
                                                 <div class="help-block alert-danger" v-show="errors.has('carnet')">{{errors.first('carnet')}}</div>
                                            </div>
                                            <div class="form-group row">
                                                <mdc-textfield type="password" name="contraseña" class="col-md-12" label="Password" helptext="(Ingrese el password)" v-model="password" v-validate="'required'"></mdc-textfield>
                                                 <div class="help-block alert-danger" v-show="errors.has('contraseña')">{{errors.first('contraseña')}}</div>
                                            </div>
                                            <div class="form-group row">
                                                <mdc-textfield type="text" name="correo" class="col-md-12" label="email" helptext="(Ingrese su correo electronico)" v-model="email" v-validate="'required'"></mdc-textfield>
                                                <div class="help-block alert-danger" v-show="errors.has('correo')">{{errors.first('correo')}}</div>
                                            </div>
                                            <div class="form-group row">
                                                <mdc-textfield type="text" name="direccion" class="col-md-12" label="Direccion" helptext="(Ingrese la dirección)" v-model="direccion" v-validate="'required'"></mdc-textfield>
                                                <div class="help-block alert-danger" v-show="errors.has('direccion')">{{errors.first('direccion')}}</div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                <v-select
                                                    ref="selectCarreras"
                                                    v-model="beca_id"
                                                    :options="arrayBecas"
                                                    placeholder="Seleccione un tipo de beca"
                                                ></v-select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                <br><label for="carrera">Seleccione una carrera </label>
                                                <v-select
                                                    ref="selectCarreras"
                                                    v-model="carrerasProy"
                                                    :options="arrayCarreras"
                                                    placeholder="Seleccione una carrera"
                                                ></v-select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12 col-lg-12">
                                                    <br><label for="departamento">Seleccione un Departamento </label>
                                                <br><v-select label="label" v-model="departamento_id" :onChange="watchDepa" placeholder="Seleccione un departamento" :options="arrayDepartamentos"></v-select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12 col-lg-12" v-if="departamento_id !== null">
                                                <br><label for="email">Seleccione un Municipio</label>
                                                <br><v-select label="label" v-model="municipio_id"  placeholder="Seleccione un municipio"  :options="arrayMunicipios"></v-select>
                                            </div>
                                            </div>
                                            <br>
                                            <mdc-button  @click="saveEstudiante">Registrar Estudiante</mdc-button>
                                        </div>
                                        
                                    </div>
                                 </form>
                        </div>
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
    
</template>
<script>
export default {
    data() {
        return{
            loadSpinner: 0,
             modal: 0,
            nombre: "",
            sector: "",
            genero: "",
            date: "",
            carnet: "",
            password: "",
            email: "",
            telefono: "",
            direccion: "",
            pass: "",
            usuario: "",
            apellido: "",
            departamento_id: 0,
            municipio_id: 0,
            beca_id: 0,
            admin_id :0,
            carrerasProy: 0,
            tipoAccion: 0,
            arrayCarreras: [],
            arrayBecas: [],
            arrayDepartamentos: [],
            arrayMunicipios: [],
            arrayAdmin: [],
            maxDatetime:  new Date().toISOString().substring(0, 10),

        } 
    },
     computed: {
         watchDepa: function() {
            if (this.departamento_id == null) {
                this.municipio_id = 0;
            }
        },
     },
     watch: {
    departamento_id: function() {
      this.getMunicipios();

      if (this.tipoAccion == 1) {
        this.municipio_id = 0;
      }
    },
},
    methods: {
    saveSector(){
      
      let me = this;
      this.loadSpinner = 1;
      axios
        .post("sector/registrar", {
          nombre: this.sector,
        })
        .then(function(response) {
          swal({
            position: "center",
            type: "success",
            title: "¡Sector agregado correctamente!",
            showConfirmButton: false,
            timer: 1000
          });
        })
        .catch(error => {
          console.log(error);
        });
       
    },
    saveEstudiante() {
      this.$validator.validateAll().then(() => {
      let me = this;
      this.loadSpinner = 1;
      axios
        .post("/admin/registrar", {
          nombre: this.nombre,
          apellido: this.apellido,
          fecha: this.date.substring(0, 10),
          genero: this.genero,
          telefono: this.telefono,
          codcarnet: this.carnet,
          password: this.password,
          email: this.email,
          direccion: this.direccion,
          tipo_beca_id: this.beca_id.value,
          carrera_id: this.carrerasProy.value,
          municipio_id: this.municipio_id.value
        })
        .then(function(response) {
          swal({
            position: "center",
            type: "success",
            title: "¡Estudiante agregada correctamente!",
            showConfirmButton: false,
            timer: 1000
          });
        })
        .catch(error => {
          me.loadSpinner = 0;
          console.log(error);
        });
        });
  },
    clearData() {
        let me = this;
        me.nombre = "";
        const elem = me.$refs.divCollapse;
        if (elem.classList.contains("collapse")) {
          elem.classList.remove("show");
        }
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
      getBecas() {
        let me = this;
       var url = "/becas/getAll";
        axios
          .get(url)
          .then(function(response) {
            var respuesta = response.data;
            //console.log(respuesta);
            me.arrayBecas = respuesta;
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
    //administrador
    getadmin(){
         let me = this;
        var url =
        "/getAdmin";
         me.loadSpinner = 1;
        axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayAdmin = respuesta;
          me.searchEmpty();
           me.loadSpinner = 0;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
     cerrarModal() {
      const el = document.body;
      el.classList.remove("abrirModal");
      this.modal = 0;
      this.errors = [];
    },
    updateAdmin(){
        let me = this;
      axios
        .put("/usuario/actualizar", {
          id: this.admin_id,
          usuario: this.usuario
          
        })
        .then(function(response) {
          swal({
            position: "center",
            type: "success",
            title: "Usuario actualizado correctamente!",
            showConfirmButton: false,
            timer: 1000
          });
          me.cerrarModal();
          me.getadmin();
        })
        .catch(function(error) {
          swal({
            position: "center",
            type: "warning",
            title: "Ocurrio un error al actualizar el usuario",
            showConfirmButton: false,
            timer: 1000
          });
          console.log(error);
        });
    },
   abrirModal(modelo, accion, data = []) {
      const el = document.body;
      el.classList.add("abrirModal");
      //no al switch
      switch (modelo) {
        case "usuario": {
          switch (accion) {
            case "actualizar": {
              //Asignando los datos traidos a los controles del formulario
              this.modal = 1;
              this.admin = data["id"];
              this.admin_id = data["id"];
              this.usuario = data["nombre"];
              break;
            }
          }
        }
      }
    },

    
},
mounted() {
      this.getCarreras();
      this.getBecas();
      this.getMunicipios();
      this.getDepartamentos();
      this.getadmin();
      this.maxDatetime;
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
