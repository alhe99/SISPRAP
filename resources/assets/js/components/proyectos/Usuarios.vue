<template>
    <div class="col-lg-12 col-md-12">
          <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                <div class="panel panel-default">
                <div class="panel-body">
                    <h1 class="text-center">Gestion de Usuarios</h1>
                    <fieldset >
                        <legend class="text-center">Opciones:</legend>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" @click="permisos()" class="btn btn-primary " data-toggle="tooltip" title="Permisos de Usuarios">Permisos de Usuarios</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" @click="rol()" class="btn btn-primary" data-toggle="tooltip" title="Añadir nuevo rol">Añadir nuevo rol</button>
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
    
    <!-- MODAL PARA PERMISOS  -->
<div class="modal fade" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-white"></h4>
        <button type="button" @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <div class="col-md-12 col-sm-12 col-lg-12">
              <v-select v-model="roles" :options="arrayRol" placeholder="Seleccione una Rol">
                <span slot="no-options">
               No hay datos disponibles
             </span>
              </v-select>
          </div>
          </div>
        <div class="row">
          <div class="form-group row">
          <div class="col-md-4" v-for="permiso in arrayPermiso" :key="permiso.id">
            <switches v-model="enabled"></switches>
						<label for="id-name" class="switch-label" v-text="permiso.nombre"></label>
          </div>
          </div>
        </div>
  </div>
  <div class="modal-footer">
    <div class="row"> 
      <div class="col-md-12">
        <button type="button" @click="cerrarModal()" class="btn red"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
        <button  @click="permisos" class="btn blue" dense>Permisos</button>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<!--- FIN MODAL PARA PERMISOS -->

<!--MODAL PARA ROL-->
<div class="modal fade" :class="{'mostrar' : modal2 }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-white"></h4>
        <button type="button" @click="cerrarModal2()" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <fieldset >
                <legend class="text-center">Registrar un nuevo rol</legend>
                   <div class="form-group row">
                      <mdc-textfield type="text" name="roles" class="col-md-12" label="Nombre del rol" helptext="(Ingrese el nombre del rol a registrar)" v-model="nombre" v-validate="'required'"></mdc-textfield>
                      <div class="error" v-if="errors.has('roles')">{{errors.first('roles')}}</div>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
  </div>
  <div class="modal-footer">
    <div class="row"> 
      <div class="col-md-12">
        <button type="button" @click="cerrarModal2()" class="btn red"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
        <button  @click="saveRol" class="btn blue">Registrar Rol</button>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<!--FIN DEL MODAL ROL--> 
</div>
</template>
<script>
import Switches from 'vue-switches';
export default {
    data() {
        return{
            modal: 0,
            modal2: 0,
            modal3: 0,
            arrayPermiso: [],
            enabled: false,
            nombre: "",
            nombreUser: "",
            password: "",
            roles: "",
            arrayRol: [],

        }
        
    },
methods: {
    permisos(){
       let me = this;
       this.modal = 1;
       var url ="/permiso";
        axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayPermiso = respuesta;  

        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cerrarModal(){
        this.modal = 0;
    },
    rol(){
        this.modal2 = 1;
    },
    cerrarModal2(){
        this.modal2 = 0;
    },
    usuario(){
        this.modal3 = 1;
    },
    cerrarModal3(){
        this.modal3 = 0;
    },
    saveRol(){
      this.$validator.validateAll().then(result => {
      if(result){
       let me = this;
       axios
          .post("/rol/registrar", {
            nombre: this.nombre
          })
          .then(function(response) {
            swal({
              position: "center",
              type: "success",
              title: "¡Rol reistrado correctamente!",
              showConfirmButton: false,
              timer: 1000
            });
            me.clearData();
          })
          .catch(error => {
            console.log(error.response.data.errors);
          });

      }
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
    getRoles() {
      let me = this;
         var url ="/rol";
        axios
          .get(url)
          .then(function(response) {
            var respuesta = response.data;
            me.arrayRol = respuesta;
          })
          .catch(function(error) {
            console.log(error);
          });
    },
    saveUser(){
      this.$validator.validateAll().then(result => {
      if(result){
      let me = this;
       axios
          .post("/usuario/registrar", {
            usuario: this.nombreUser,
            password: this.password,
            rol_id: this.roles.value

          })
          .then(function(response) {
            swal({
              position: "center",
              type: "success",
              title: "¡Usuario reistrado correctamente!",
              showConfirmButton: false,
              timer: 1000
            });
            me.clearData();
          })
          .catch(error => {
            console.log(error.response.data.errors);
          });

          }
        });
    },

  },
  components: {
     Switches,
  },
   mounted() {
      this.getRoles();
    },
}
</script>
