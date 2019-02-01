@extends('public.layout.app')
@section('contenido')
{{-- Inicio --}}
<section class="Material-contact-section section-padding section-dark" id="form">
  <div class="container">
    <div class="row">
      <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".1s">
        <h1 class="section-title text-center">Formulario de inscripción de proyecto de {{ Auth::user()->estudiante->proceso[0]->nombre }}</h1>
      </div>
      <div class="col-md-12">
        <div class="alert alert-info" role="alert">
          <h5 class="alert-heading text-center font-weight-bold text-danger">Porfavor completa los campos que esten vacios y sean de tipo obligatorio!</h5>
          <p class="text-center text-primary">Los campos de tipo OBLIGATORIO tienen un <strong>*</strong></p>
          <hr>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".1s">
        <h2 class="subtitle">Tu Información</h2><br>
      </div>
    </div>
    <div class="single-blog-post col-md-12">
      <form class="shake" role="form" method="POST" id="perfilproy" name="perfilproy" data-toggle="validator" >
        @csrf
        <input type="hidden" name="student_id" id="student_id" value="{{Auth::user()->estudiante->id}}" >
        <input type="hidden" id="validateFecha" value="">
        <input type="hidden" name="project_id" id="project_id" value="{{Auth::user()->estudiante->preinscripciones[0]->id == 0 ? '' : Auth::user()->estudiante->preinscripciones[0]->id  }}" >
        <div class="row">
          <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="estudiante_name">Nombre de alumno*</label>
              <input class="form-control" id="estudiante_name" value="{{Auth::user()->estudiante->nombre}}" disabled type="text" name="estudiante_name" >
            </div>
          </div>
          <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="estudiante_codcarnet">Código de Carnet*</label>
              <input class="form-control" id="estudiante_codcarnet" value="{{Auth::user()->estudiante->codCarnet}}" disabled type="text" name="estudiante_codcarnet" >

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="estudiante_telefono" >Teléfono*</label>
              <input class="form-control" id="estudiante_telefono" type="text" name="estudiante_telefono" disabled value="{{Auth::user()->estudiante->telefono}}" disabled >

            </div>
          </div>
          <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="estudiante_carrera">Carrera*</label>
              <input class="form-control" id="estudiante_carrera" type="text"  name="estudiante_carrera" value="{{Auth::user()->estudiante->carrera->nombre}}" disabled >

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="estudiante_email">Correo electrónico</label>
              <input class="form-control" id="estudiante_email" type="text" name="estudiante_email" value="{{Auth::user()->estudiante->email}}" disabled >
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".1s">
            <h2 class="subtitle">Información de Institución/Empresa</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="institucion_name">Nombre de institución*</label>
              <input class="form-control" id="institucion_name" type="text" name="institucion_name" value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->nombre}}" disabled >

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="institucion_nombre">Dirección*</label>
              <input class="form-control" id="institucion_nombre" type="text" name="institucion_nombre" value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->direccion}}" disabled >

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="institucion_depa">Departamento*</label>
              <input class="form-control" id="institucion_depa" type="text" name="institucion_nombre" value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->municipio->departamento->nombre}}" disabled >

            </div>
          </div>
          <div class="col-md-4 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="institucion_muni">Municipio*</label>
              <input class="form-control" id="institucion_muni" type="text" name="institucion_muni" value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->municipio->nombre}}" disabled>
            </div>
          </div>
          <div class="col-md-4 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="sector_name">Sector de Institución*</label>
              <input class="form-control" id="sector_name" type="text" name="sector_name" value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->sectorInstitucion->sector}}" disabled >

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="institucion_tel">Teléfono*</label>
              <input class="form-control" id="institucion_tel" type="text" name="institucion_tel" disabled value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->telefono}}" disabled  >
            </div>
          </div>
          <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="institucion_email">Correo electrónico</label>
              <input class="form-control" id="institucion_email" type="text" name="institucion_email" value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->email}}" disabled >
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".1s">
            <h2 class="subtitle">Información de Proyecto</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="proyecto_name">Nombre del proyecto*</label>
              <input class="form-control" id="proyecto_name" type="text" name="proyecto_name" value="{{Auth::user()->estudiante->preinscripciones[0]->nombre}}" disabled >

            </div>
          </div>
          <div class="col-md-2 wow animated fadeInRight" data-wow-delay=".1s">
            <div class="form-group label-floating">
              <label class="control-label" for="total_horas">Total de Horas*</label>
              <input class="form-control" value="{{ Auth::user()->estudiante->no_proyectos == 1 ? Auth::user()->estudiante->proceso[0]->pivot->num_horas : Auth::user()->estudiante->preinscripciones[0]->horas_realizar }}"
              id="total_horas" maxlength="3" {{  Auth::user()->estudiante->no_proyectos == 1 ? 'disabled' : '' }} type="text" name="total_horas">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" data-wow-delay=".2s">
           <div class="form-group">
            <label for="proyecto_acti" class="control-label">Actividades a desarrollar*</label>
            <textarea class="form-control-sm col-md-12" rows="5" name="proyecto_acti" id="proyecto_acti" disabled>{{\Html2Text\Html2Text::convert(Auth::user()->estudiante->preinscripciones[0]->actividades)}}</textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
          <div class="form-group control-floating">
            <label class="control-label" for="fecha_fin">Fecha Inicio*</label>
            <input class="form-control" @click="openDateIPicker" readonly placeholder="aaaa-mm-dd" id="fecha_ini"   name="fecha_ini" />
            <small v-show="showMessage" class="text-center text-danger font-weight-bold">*Campo Obligatorio</small>
          </div>
        </div>
        <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
          <div class="form-group control-floating">
            <label class="control-label" for="tel_supervisor">Teléfono del supervisor</label>
            <input class="form-control" v-model="telSuper" disabled id="tel_supervisor" maxlength="9" disabled type="text" name="tel_supervisor" >
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 wow animated fadeInRight" data-wow-delay=".1s">
          <div class="form-group mdl-selectfield">
           <label class="control-label"  for="name_supervisor">Nombre de Supervisor de la Institución/Empresa*</label>
           <select v-model="selectSuper" id="selectSupervisores" class="custom-select show-tick col-md-6" data-style="btn-primary">
            <option value="" selected disabled>Seleccione el nombre del supervisor</option>
            @foreach (Auth::user()->estudiante->preinscripciones[0]->institucion->supervisores()->get() as $item)
            <option value="{{ $item->nombre.";".$item->no_telefono }}">{{ $loop->iteration." - ".$item->nombre }}</option>
            @endforeach
          </select>
          <small class="text-center text-danger font-weight-bold">*Selecciona del listado de supervisores el que estara a cargo de proceso</small>
        </div>
      </div>
    </div>
    <br>
    <div class="row text-center">
      <div class="col-md-6 col-sm-6 wow animated fadeInRight" data-wow-delay=".1s">
        <button type="button" :disabled="validate"  @click.prevent="saveData({{session('student_id')}})" id="btnSave" class="animated4 btn btn-round text-capitalize  font-weight-bold" style="cursor: pointer;"><i class="far fa-save"></i>&nbsp;Guardar Datos</button>
      </div>
      <div class="col-md-6 col-sm-6 wow animated fadeInRight" data-wow-delay=".1s">
        <a  class="btn btn-danger text-capitalize text-white font-weight-bold" data-toggle="modal" data-target="#ModalCancelarPreins"><i class="fas fa-ban"></i>&nbsp;Cancelar</a>
      </div>
    </div><br>
  </form>
</div>
</div>
</div>
</section>
<div class="modal" id="ModalCancelarPreins" tabindex="-4" role="dialog" aria-labelledby="ModalCancelarPreinsc" aria-hidden="true">
  <div class="modal-dialog" role="document" style="margin-top: 60px;">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title text-center" id="ModalCancelarPreinsc">{{Auth::user()->estudiante->nombre ." ".Auth::user()->estudiante->apellido }}</h5>
        <button type="button" class="close" style="cursor: pointer;" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        ¿Esta seguro de cancelar, se perderán los datos ingresados o cambios realizados?
      </div>

      <div class="modal-body text-center">
        <button type="button" class="btn btn-danger text-capitalize text-white " style="cursor: pointer;" data-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Cancelar</button>
        <a  class="btn btn-primary" style="cursor: pointer;"  href="{{ url()->previous() }}"><i class="mdi mdi-check"></i>&nbsp;Aceptar</a>
      </div>
    </div>
  </div>
</div>
@endsection
@section('page_script')
<script type="text/javascript">
  var app = new Vue({
    el : '#form',
    data : {
      fechaI: "",
      hrsRea: "",
      projectId: "",
      studentId: "",
      selectSuper: "",
      telSuper: "",
      nombreSupervisor: "",
      showMessage: false
    },
    computed:{
      validate: function(){
      if (!(this.telSuper == '') && !(this.nombreSupervisor == '')){return false;}else{return true;}
    }
  },
  watch:{
    selectSuper: function(){
      var data = $("#selectSupervisores").val().trim();
      data = data.split(";");
      this.telSuper = data[1];
      this.nombreSupervisor = data[0];
    },
  },
  methods : {
    openDateIPicker(){
     var datepicker = $('#fecha_ini').datepicker();
     datepicker.open();
   },
   downloadPdfFromBase64(base64){
    let a = document.createElement("a");
    var name = "Perfil de proyecto " + new Date(Date.now()).toLocaleString();
    a.href = "data:application/octet-stream;base64,"+base64;
    a.download = name+".pdf"
    a.click();
  },
  saveData: function (){
   const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: true, timer: 1500 });
   var datepicker = $('#fecha_ini').datepicker();
   if(datepicker.value() == ''){
    this.showMessage = true;
   }else{
         this.showMessage = false;
         swal({
          title: 'Seguro de Guardar los datos?',
          text: "Una vez realizado quedaras registrado a este proyecto!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            let me = this;
            $('#preloader').fadeIn();
            me.fechaI = $("#fecha_ini").val().trim();
            me.studentId = $("#student_id").val().trim();
            me.projectId = $("#project_id").val().trim();
            me.hrsRea = $("#total_horas").val().trim();

            var url = route('save_perfil', {
              "fechaini": this.fechaI,
              "hrsreal":this.hrsRea,
              "student_id":this.studentId,
              "proyecto_id":this.projectId,
              "super_name":this.nombreSupervisor,
              "super_cell":this.telSuper });

            axios.get(url).then(function(response) {
              var respuesta = response.data;
              me.downloadPdfFromBase64(respuesta);
              $('#preloader').fadeOut();
              swal({
                position: "center",
                type: "success",
                title: "Datos Guardados Correctamente",
                showConfirmButton: true,
                width: '350px',
              }).then(function(result){
                window.location.href = route('proyects_now',[me.studentId]);
              });

            }).catch(function(error) {
              console.log(error);
              toast({
                type: 'danger',
                title: 'Error! Intente Nuevamente'
              });
            });
          }
        })
      }
   }
},
mounted(){
  let me = this;
  $('#tel_supervisor').mask('########', {reverse: true},{maxlength:false});
  $('#total_horas').mask('###', {reverse: true});
  $("#fecha_ini").datepicker({
    locale: 'es-es',
    minDate: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()),
    format: 'yyyy-mm-dd',
    change: function (e) {
      var datepicker = $('#fecha_ini').datepicker();
      $("#validateFecha").val(datepicker.value())
      this.fechaI = datepicker.value();
      if ((this.name_supervisor != '') && (this.telSuper != '')){$("btnSave").prop('disabled',false)}
    }
});
}
})

</script>
@endsection