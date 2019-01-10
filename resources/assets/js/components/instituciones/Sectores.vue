<template>
    <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-body">
             <div class="row">
                <div class="col-md-6 col-sm-12 col-lg-6  ">
                    <h2 class=" align-baseline font-weight-bold">Listado de sectores de instituciones</h2><br>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-11 ">
                            <div class="form-group row">
                                <mdc-textfield type="text" class="col-md-12" @keyup="listarSectores(1,  buscar)"  label="Nombre del sector" v-model="buscar"></mdc-textfield>
                            </div>
                        </div>
                        <div class="col-md-1 text-center">
                            <button class="button secondary" type="button" @click="abrirModal('sector','registrar')"><i class="mdi mdi-plus-box"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-12 loading text-center" v-if="loadSpinner == 1">
          </div>
      </div>
      <!--tabla de sectores-->
      <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="table-responsive">
           <table id="myTable" class="table table-striped table-bordered table-mc-light-blue">
            <thead class="thead-primary">
                <tr>
                    <th>Nombre de sector</th>
                    <th class="text-right" style="padding-right: 35px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="sectores in arraySector" :key="sectores.id">
                    <td v-text="sectores.sector"></td>
                    <td class="text-right">
                        <button type="button" @click="abrirModal('sector','actualizar',sectores)" class="button secondary" data-toggle="tooltip" title="Editar datos del sector"><i class="mdi mdi-border-color i-crud"></i></button>
                        <button type="button" @click="deleteSector(sectores.id)" class="button red" data-toggle="tooltip" title="Eliminar Sector"><i class="mdi mdi-delete i-crud"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page -1, buscar)">Ant</a>
                </li>
                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar)" v-text="page"></a>

                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link font-weight-bold" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                    </li>
                    <small v-show="arraySector.length != 0" class="text-muted pagination-count" v-text=" '(Mostrando ' + arraySector.length + ' de ' + pagination.total + ' registros)'"></small>
                </ul>
            </nav>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div v-show="search == 1"  class="alert alert-primary h6 font-weight-bold text-center" role="alert" v-text="'No se encontraron resultados o No hay registros'"></div>
    </div>
</div>

<!-- MODAL PARA REGISTRAR Y ACTUALIZAR DATOS  -->
<div class="modal fade" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-white" v-text="tituloModal"></h4>
                <button type="button" @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12 col-xs-12 col-lg-12">
                        <br><label for="nombre">Nombre del sector institución*</label>
                        <input type="text" v-model="sector" id="sector" name="sector" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button"  @click="cerrarModal()" class="button red"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
                        <button type="button secondary" :class="[sector == '' ? 'disabled' : '']"  :disabled="sector == ''" v-if="tipoAccion==1" class="button blue" @click="registrarSector" dense><i class="mdi mdi-content-save"></i>&nbsp;Guardar Sector</button>
                        <button type="button" :disabled="validate == true" v-if="tipoAccion==2" class="button blue" @click="actualizarSector" dense><i class="mdi mdi-content-save"></i>&nbsp;Actualizar Sector</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--- FIN MODAL PARA REGISTRAR Y ACTUALIZAR DATOS -->

</div>
</template>
<script>
export default {
    data(){
        return{
            loadSpinner: 0,
            arraySector: [],
            sector: "",
            sectorUpd: "",
            buscar: "",
            message: 0,
            search: 0,
            modal: 0,
            sector_id: 0,
            tituloModal: "",
            tipoAccion: 0,
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
            offset: 3,
            sectores: 0,
        }

    },
    computed: {
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
    watch:{

    },
    methods: {
        listarSectores(page, buscar) {
            let me = this;
            var url =
            "/sector?page=" +
            page +
            "&buscar=" +
            buscar;
            me.loadSpinner = 1;
            axios
            .get(url)
            .then(function(response) {
                var respuesta = response.data;
                me.arraySector = respuesta.sectores.data;
                me.pagination = respuesta.pagination;
                //Por si no devuelve datos
                me.loadSpinner = 0;
                me.searchEmpty();

            })
            .catch(function(error) {
                me.loadSpinner = 0;
                console.log(error);
            });
        },
        registrarSector(){
         let me = this;
         this.loadSpinner = 1;
         var url = route('validateSector',{"sector": me.sector});
         axios.get(url).then(function(response) {
            var respuesta = response.data;
            console.log(respuesta);
            if(respuesta == 'existe'){
                swal({
                    position: "center",
                    type: "warning",
                    title: "Sector existente! Ingrese otro nombre!",
                    showConfirmButton: true,
                    timer: 5000
                });
                me.nombre = "";
                me.loadSpinner = 0;
                me.exist = false;
            }else {
                axios
                .post("sector/registrar", {
                    sector: me.sector,
                })
                .then(function(response) {
                    swal({
                        position: "center",
                        type: "success",
                        title: "¡Sector agregado correctamente!",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    me.cerrarModal();
                    me.listarSectores(1, "");
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                });
            }
        });
     },
     actualizarSector(){
        let me = this;
        var url = route('validateSector',{"sector": me.sector});
        me.loadSpinner = 1;
        axios.get(url).then(function(response) {
            var respuesta = response.data;
            console.log(respuesta);
             if((me.sector != me.sectorUpd) && (respuesta == 'existe')){
                swal({
                    position: "center",
                    type: "warning",
                    title: "Sector existente! Ingrese otro nombre!",
                    showConfirmButton: true,
                    timer: 5000
                });
                me.sector = "";
                me.loadSpinner = 0;
                me.exist = false;
            }else {
                axios
                .put("/sector/actualizar", {
                    id: me.sector_id,
                    sector: me.sector,
                })
                .then(function(response) {
                    me.loadSpinner = 0;
                    swal({
                        position: "center",
                        type: "success",
                        title: "Sector actualizado correctamente!",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    me.cerrarModal();
                    me.listarSectores(1, "");
                })
                .catch(function(error) {
                    swal({
                        position: "center",
                        type: "warning",
                        title: "Ocurrio un error al actualizar el dato",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    me.loadSpinner = 0;
                    console.log(error);
                });
            }
        });
    },
    abrirModal(modelo, accion, data = []) {
        const el = document.body;
        el.classList.add("abrirModal");
        switch (modelo) {
            case "sector": {
                switch (accion) {
                    case "registrar": {
                        this.modal = 1;
                        this.sector = "";
                        this.tipoAccion = 1;
                        this.tituloModal = "Registrar Sector";

                        break;
                    }
                    case "actualizar": {
                //Asignando los datos traidos a los controles del formulario
                this.modal = 1;
                this.tituloModal = "Actualizar Sector";
                this.tipoAccion = 2;
                this.sector_id = data["id"];
                this.sectores = data["id"];
                this.sector = data["sector"];
                this.sectorUpd = data["sector"];
                break;
            }
        }
    }
}
},
cerrarModal() {
    const el = document.body;
    el.classList.remove("abrirModal");
    this.modal = 0;
    this.tituloModal = "";
    this.tipoAccion = 0;
    this.sector = "";
    this.sector_id = 0;
    this.sectorUpd = "";
},
cambiarPagina(page, buscar) {
    let me = this;
    me.pagination.current_page = page;
    me.listarSectores(page, buscar);
},

searchEmpty() {
    let me = this;
        //Aqui hice la verificacion si hay o no datos para mostrar mensaje
        if (me.arraySector.length == 0) {
            me.search = 1;
        } else {
            me.search = 0;
        }
        return me.search;
    },
    deleteSector(id) {
      swal({
        title: "Esta seguro de eliminar este Sector?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn update",
        cancelButtonClass: "btn edit",
        buttonsStyling: false,
        reverseButtons: true
    }).then(result => {
        if (result.value) {
          let me = this;
          me.loadSpinner = 1;
          var url = "/sector/eliminar/"+ id;
          axios
          .get(url)
          .then(function(response) {
            me.listarSectores(1, "");
            swal(
              "Desactivado!",
              "El Registro ha sido eliminado con exito",
              "success"
              );
            me.loadSpinner = 0;
        })
          .catch(function(error) {
            console.log(error);
        });
      } else if (
          // Esto lo hace cuando se descativa el registro
          result.dismiss === swal.DismissReason.cancel
          ) {
      }
  });
},
},
mounted() {
    let me = this;
    me.listarSectores(1,"");
}

}
</script>
