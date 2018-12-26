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
          <h5 class="alert-heading text-center">Porfavor completa los campos que esten vacios y sean de tipo obligatorio!</h5>
          <p class="text-center">Los campos de tipo OBLIGATORIO tienen un <strong>*</strong></p>
          <hr>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".1s">
        <h2 class="subtitle">Tu Información</h2><br>
      </div>
    </div>
    <form class="shake" role="form" method="POST" id="perfilproy" name="perfilproy" data-toggle="validator" >
      <input type="hidden" name="student_id" id="student_id" value="{{Auth::user()->estudiante->id}}" >
      <input type="hidden" name="project_id" id="project_id" value="{{Auth::user()->estudiante->preinscripciones[0]->id}}" >
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
            <label class="control-label" for="estudiante_telefono">Teléfono*</label>
            <input class="form-control" id="estudiante_telefono" type="text" name="estudiante_telefono" value="{{Auth::user()->estudiante->telefono}}" disabled >

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
            @if (Auth::user()->estudiante->preinscripciones[0]->institucion->telefono == null)
            <input class="form-control" id="institucion_tel" type="text" name="institucion_tel">
            @else
            <input class="form-control" id="institucion_tel" type="text" name="institucion_tel" value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->telefono}}" disabled  >
            @endif

          </div>
        </div>
        <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
          <div class="form-group label-floating">
            <label class="control-label" for="institucion_email">Correo electrónico</label>
            @if (Auth::user()->estudiante->preinscripciones[0]->institucion->email == null)
            <input class="form-control" id="institucion_email" type="text" name="institucion_email">
            @else
            <input class="form-control" id="institucion_email" type="text" name="institucion_email" value="{{Auth::user()->estudiante->preinscripciones[0]->institucion->email}}" disabled >
            @endif

          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".1s">
          <h2 class="subtitle">Información de Proyecto</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 wow animated fadeInRight" data-wow-delay=".1s">
          <div class="form-group label-floating">
            <label class="control-label" for="proyecto_name">Nombre del proyecto*</label>
            <input class="form-control" id="proyecto_name" type="text" name="proyecto_name" value="{{Auth::user()->estudiante->preinscripciones[0]->nombre}}" disabled >

          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 wow animated fadeInRight" data-wow-delay=".1s">
         <div class="form-group label-floating">
          <label for="proyecto_acti" class="control-label">Actividades a desarrollar*</label>
          <textarea class="form-control" rows="12" id="message" name="proyecto_acti" id="proyecto_acti" disabled required data-error="Write your message">
            {{strip_tags(Auth::user()->estudiante->preinscripciones[0]->actividades)}}
          </textarea>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 wow animated fadeInRight" data-wow-delay=".1s">
        <div class="form-group control-label">
          <label class="control-label" for="fecha_fin">Fecha Inicio*</label>
          <input class="form-control" id="fecha_ini" disabled  name="fecha_ini" />
        </div>
      </div>
      <div class="col-md-4 wow animated fadeInRight" data-wow-delay=".1s">
        <div class="form-group control-label">
          <label class="control-label" for="fecha_fin">Fecha Finalización*</label>
          <input class="form-control" id="fecha_fin"  name="fecha_fin" >
        </div>
      </div>
      <div class="col-md-4 wow animated fadeInRight" data-wow-delay=".1s">
        <div class="form-group label-floating">
          <label class="control-label" for="total_horas">Total de Horas*</label>
          <input class="form-control" id="total_horas" maxlength="3" type="text" name="total_horas" >
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
        <div class="form-group label-floating">
          <label class="control-label" for="name_supervisor">Nombre de Supervisor de la Institución/Empresa*</label>
          <input class="form-control" id="name_supervisor" type="text" name="name_supervisor" >
        </div>
      </div>
      <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
       <div class="form-group label-floating">
        <label class="control-label" for="tel_supervisor">Teléfono del supervisor*</label>
        <input class="form-control" id="tel_supervisor" maxlength="9" type="text" name="tel_supervisor" >
      </div>
    </div>
  </div>
  <br>
  <div class="row text-center">
    <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
      <button type="button" @click.prevent="saveData({{session('student_id')}})" class="btn btn-round text-capitalize btn-lg font-weight-bold">Guardar Datos</button>
    </div>
    <br>
    <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".1s">
      <a  href="{{ url()->previous() }}" class="btn btn-info text-capitalize btn-lg font-weight-bold">Cancelar</a>
    </div>
  </div>
</form>
</div>
</section>
@endsection
@section('page_script')
<script type="text/javascript">
  var app = new Vue({
    el : '#form',
    data : {
      fechaI: 0,
      fechaFin: null,
      hrsRea: 0,
      projectId: 0,
      studentId: 0,
      nameSuper: "",
      telSuper: 0
    },
    methods : {
      downloadPdfFromBase64(base64){
        let a = document.createElement("a");
        var name = "Perfil de proyecto " + new Date(Date.now()).toLocaleString();
        a.href = "data:application/octet-stream;base64,"+base64;
        a.download = name+".pdf"
        a.click();
      },
      saveData: function (studen_id){
       const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: true, timer: 1500 });
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
          //$('#preloader').fadeIn();
          me.fechaI = $("#fecha_ini").val().trim();
          me.fechaFin = $("#fecha_fin").val().trim();
          me.hrsRea =  $("#total_horas").val().trim();
          me.projectId = $("#project_id").val().trim();
          me.studentId = $("#student_id").val().trim();
          me.nameSuper = $("#name_supervisor").val().trim();
          me.telSuper = $("#tel_supervisor").val().trim();

          var url = route('save_perfil', [this.fechaI, this.fechaFin,this.hrsRea,this.studentId,this.projectId,this.nameSuper,this.telSuper]);
          axios.get(url).then(function(response) {
                        //if(response.data == true){
                          var respuesta = response.data;
                          me.downloadPdfFromBase64(respuesta);
                          //$('#preloader').fadeOut();
                          swal({
                            position: "center",
                            type: "success",
                            title: "Datos Guardados Correctamente",
                            showConfirmButton: true,
                            width: '350px',
                          }).then(function(result){
                            //window.location.href = route('proyects_now',[studen_id]);
                          });
                       // }

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
  },
  mounted(){
   $('#tel_supervisor').mask('########', {reverse: true},{maxlength:false});
   $('#total_horas').mask('###', {reverse: true});
   $("#fecha_ini").datepicker({
    locale: 'es-es',
    minDate: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()),
  });
   $("#fecha_fin").datepicker({
    locale: 'es-es',
    minDate: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate())
  });
 }
})

</script>
@endsection