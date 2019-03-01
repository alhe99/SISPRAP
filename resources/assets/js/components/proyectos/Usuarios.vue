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
                <h2 class="text-left font-weight-bold">Listado de usuarios con permisos de administrador</h2><br>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="row">
              <div class="col-md-6">
                <mdc-textfield type="text" style="margin-left: -10px" class="col-md-12"  @keyup="getAllUsers(1,buscar);"  label="Buscar usuarios" v-model="buscar"></mdc-textfield>
              </div>
              <div class="col-md-6 text-right">
                 <button type ="button" class="button secondary" @click="abrirModal"><i class="mdi mdi-account-plus"></i></button>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-lg-12 col-sm-12">
            <br>
            <table class="table table-striped table-bordered table-mc-light-blue">
              <thead class="thead-primary">
                <tr>
                  <th>Nombre Usuario</th>
                  <th class="text-center">Login</th>
                  <th class="text-center">Fecha registro</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
               <tr v-for="item in arrayUsers" :key="item.id">
                <td v-text="item.nombre"></td>
                <th class="text-center" v-text="item.usuario"></th>
                <th class="text-center" v-text="item.fecha_registro"></th>
                <td class="text-center">
                  <button type="button" class="button red" @click="deleteUser(item.id)" ><i class="mdi mdi-delete-empty"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination" >
              <li class="page-item" v-if="pagination.current_page > 1">
                <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page -1,buscar)">Ant</a>
              </li>
              <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                </li>
                <small v-show="arrayUsers.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arrayUsers.length + ' de ' + pagination.total + ' registros)'"></small>
              </ul>
            </nav>
            <div v-if="arrayUsers.length == 0" class="alert alert-warning" role="alert">
              <h4 class="font-weight-bold text-center">No hay registros disponibles</h4>
            </div>
        </div>
      </div>


    <!-- MODAL PARA AGREGAR USUARIOS  -->
    <div class="modal fade" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-white">Agregar Nuevo Usuario</h4>
            <button type="button" @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-primary text-center font-weight-bold h6" role="alert">
                  Campos requeridos poseen un (*)
                </div>
              </div><br>
              <div class="col-md-12">
                <label class="font-weight-bold" for="nombre">Nombre (*)</label>
                <input type="text" v-model="nombre" id="nombre" name="nombre" class="form-control" autocomplete="off">
              </div>
              <div class="col-md-12">
                <br><label class="font-weight-bold" for="nombre">Login (*)</label>
                <input type="text" v-model="usuario" id="usuario" name="usuario" class="form-control" autocomplete="off">
              </div>
              <div class="col-md-12">
                <br><label class="font-weight-bold" for="nombre">Contraseña (*)</label>
                <input type="password" v-model="password" id="password" name="password" class="form-control" autocomplete="off">
              </div>
              <div class="col-md-12">
                <br><label class="font-weight-bold" for="nombre">Confirme contraseña (*)</label>
                <input type="password" v-model="cPass" id="cPass" name="cPass" class="form-control" autocomplete="off">
              </div>
              <div class="col-md-12" id="divNoMatchPasswords" v-if="passNoMatch">
               <br><div class="alert alert-warning font-weight-bold text-center" role="alert">
                 Las contraseñas no coinciden
               </div>
             </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-md-12">
                <button type="button" @click="cerrarModal()" class="button red"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
                <button type="button" @click="saveUser()" :class="[nombre == '' || usuario == '' || password == '' || cPass != password ? 'disabled' : '']" :disabled="nombre == '' || usuario == '' || password == '' || cPass != password" class="button blue"><i class="mdi mdi mdi-content-save"></i>&nbsp;Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
    <!--- FIN MODAL PARA AGREGAR USUARIOS -->
  </div>
</template>
<script>
export default {
  data() {
    return{
      modal: 0,
      buscar: '',
      loadSpinner: 0,
      arrayUsers: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      nombre: '',
      usuario: '',
      password: '',
      cPass: '',
      passNoMatch: false
    }
  },
  watch:{
    cPass: function(){
      if(this.cPass != this.password)
        this.passNoMatch = true;
      else
        this.passNoMatch = false;
    }
  },
   computed:{
     isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  methods: {
    getAllUsers(page,buscar) {
      const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500});
      let me = this;
      me.loadSpinner = 1;
      var url = route('getUsers',{
        'buscar': buscar,
      });
      axios.get(url).then(function(response) {
        var respuesta = response.data;
        me.arrayUsers = respuesta.usuarios.data;
        me.pagination = respuesta.pagination;
        me.loadSpinner = 0;
      })
      .catch(function(error) {
        console.log(error);
        me.loadSpinner = 0;
        toast({
          type: 'danger',
          title: 'Error al cargar los datos! Intente Nuevamente'
        });
      });
    },
  abrirModal(){
   let me = this;
   const el = document.body;
   el.classList.add("abrirModal");
   me.modal = 1;
  },
  cerrarModal(){
    this.modal = 0;
    this.nombre = '';
    this.usuario = '';
    this.password = '';
    this.cPass = '';
    this.passNoMatch = false;
  },
  saveUser() {
    let me = this;
    const toast = swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 4000
    });
    var url = route('createUser',{
      'nombre': me.nombre,
      'usuario': me.usuario,
      'pass': me.password,
    });
    me.loading = 1;
    axios.post(url).then(function(response) {
      swal({
        position: "center",
        type: "success",
        title: "Usuario agregado correctamente",
        showConfirmButton: false,
        timer: 1000
      });
      me.cerrarModal();
      me.getAllUsers(1, "");
    })
    .catch(function(error) {
      me.loadSpinner = 0;
      toast({
        type: "danger",
        title: "Error! Intente Nuevamente"
      });
      console.log(error);
    });
  },
  deleteUser(id){
   const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500});
   swal({
    title: "Esta seguro de eliminar este usuario(a)?",
    type: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
    confirmButtonClass: "button blue",
    cancelButtonClass: "button red",
    buttonsStyling: false,
    reverseButtons: true
  }).then(result => {
    if (result.value) {
      let me = this;
      me.loadSpinner = 1;
      var url = route('deleteUser',id);
      axios.put(url)
      .then(function(response) {
        swal(
          "Eliminado!",
          "El usuario ha sido eliminado con exito",
          "success"
          );
        me.loadSpinner = 0;
        me.getAllUsers(1, "");
      })
      .catch(function(error) {
        me.loadSpinner = 0;
        toast({
          type: 'danger',
          title: 'Error al cargar los datos! Intente Nuevamente'
        });
        console.log(error);
      });
    } else if (
      // Esto lo hace cuando se descativa el registro
      result.dismiss === swal.DismissReason.cancel
      ) {
    }
  });
  },
  cambiarPagina(page,buscar) {
    let me = this;
    me.pagination.current_page = page;
    if (me.arrayUsers.length > 0) {
      me.getAllUsers(page,"");
    }
  },
},
components: {},
mounted() {
  this.getAllUsers(1,"");
},
}
</script>
