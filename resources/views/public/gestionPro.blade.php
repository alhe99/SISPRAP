@extends('public.layout.app')
@section('contenido')
<div class="row">
  <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
    <h1 class="section-title">PROYECTOS EN MARCHA</h1>
  </div>
</div>
<div class="container">
  <div class="card wow animated fadeInLeft">
    <div class="card-body">
      <h5 class="text-center">
        Proyecto que actualmente estas realizando de tu proceso de:
        <strong style="font-weight:bold">
          {{session('process_name')}}
        </strong>
      </h5>
    </div>
  </div>
  <br><br>
  <div class="card wow animated fadeInLeft">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <h2 class="py-3 text-center font-bold font-up blue-text">Tus Proyectos</h2>
        </div>
      </div>
      <div class="col-md-12">
        <table class="table table-hover table-responsive mb-0">
          <thead>
            <tr>
              <th scope="row">#</th>
              <th class="th-lg"><a>Proyecto</a></th>
              <th class="th-lg">Fecha de Inicio</a></th>
              <th class="th-lg">Fecha de Finalización</a></th>
              <th class="th-lg">Estado</a></th>
              <th class="th-lg text-center">Opciones</a></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($gestionp as $i)
            <tr>
              <th scope="row">{{$loop->iteration}}</th>
              <td>{{$i->proyecto["nombre"]}}</td>
              <td>{{$i->fecha_inicio}}</td>
              @if ($i->fecha_fin == null)
              <td>Indefinida</td>
              @else
              <td>{{$i->fecha_fin}}</td>
              @endif
              @if ($i->estado == "I")
              <td>Iniciado</td>
              @elseif($i->estado == "P")
              <td>En Proceso</td>
              @elseif($i->estado == "F")
              <td>Finalizado</td>
              @endif
              <td class="text-center">
                <a href="{{route('viewProject', array(session('process_id'),$i->proyecto["slug"]))}}" rel="nofollow" class="animated4 btn btn-primary" title="Ver más Información"><i class="fas fa-plus-circle"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
@section('page_script')
<script type="text/javascript">
  var app = new Vue({
    el : '#projects',
    data : {

    },
    methods : {
      resetPreRegistration: function (studen_id,project_id,process_id){
        const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500});
        swal({
          title: 'Esta seguro de Eliminar esta preinscripcion?',
          text: "Tu solicitud al proyecto sera cancelada totalmente",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            var url = route('deletePreRegister', [studen_id, project_id]);
            axios.get(url).then(function(response) {
                        //if(response.data == true){
                          toast({
                            type: 'success',
                            title: 'Preinscripción Eliminada Exitosamente'
                          });
                          setTimeout(function () { window.location.href = route('myPreregister',[studen_id,process_id]) }.bind(this), 1500);
                        //}

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
  })
</script>
@endsection


