@extends('public.layout.app')
@section('contenido')
<div class="card wow animated fadeInLeft">
    <div class="card-body">
        <h5 class="text-center">
            Proyectos a los que te has preinscrito de proceso de:
            <strong style="font-weight:bold">
                {{session('process_name')}}
            </strong>
        </h5>
    </div>
</div>
<br>
<div class="card wow animated fadeInLeft">
    <div class="card-body">
        @if (count($proyectos) == 0)
        <div class="alert alert-primary" role="alert">
            ¡No tienes preinscripciones a proyectos!
        </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <h2 class="py-3 text-center font-bold font-up blue-text">Tus Preinscripciones Actuales</h2>
            </div>
        </div>
        <div class="col-md-12" id="projects">
            <div class="table-responsive">
                <table class="table table-striped  mb-0">
                    <thead>
                        @if ($proyectos[0]->tipo_proyecto == 'I')
                        <tr>
                            <th scope="row" class="text-center">No</th>
                            <th class="th-lg text-center">Proyecto</th>
                            <th class="th-lg text-center">Fecha de preinscripción</th>
                            <th class="th-lg text-center">Estado</th>
                            <th class="th-lg text-center">Opciones</th>
                        </tr>
                        @else
                        <tr>
                            <th scope="row" class="text-center">No</th>
                            <th class="th-lg text-center">Proyecto</th>
                            <th class="th-lg text-center">Fecha de asignación</th>
                            <th class="th-lg text-center">Estado</th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @foreach ($proyectos as $preinscripcion)
                        <tr>
                          <th scope="row" class="text-center">{{$loop->iteration}}</th>
                          <td class="text-capitalize">{{strtolower($preinscripcion->nombre)}}</td>
                          <td class="text-center">{{substr($preinscripcion->pivot->created_at,0,10)}}</td>
                          @if ($preinscripcion->pivot->estado == "P")
                          <td class="text-center"><h3 class="badge badge-primary">Preinscrito</h3></td>
                          @elseif($preinscripcion->pivot->estado == "A" or $preinscripcion->pivot->estado == "F")
                          <td class="text-center"><h3 class="badge badge-success">Aprobado</h3></td>
                          @elseif($preinscripcion->pivot->estado == "R")
                          <td class="text-center"><h3 class="badge badge-danger">Rechazado</h3></td>
                          @endif
                          @if ($preinscripcion->tipo_proyecto == 'I')
                          <td class="text-center">
                            <a href="{{route('viewProject', array($preinscripcion->proceso_id,$preinscripcion->slug))}}" rel="nofollow" class="animated4 btn btn-primary" title="Ver más Información del proyecto"><i class="fas fa-plus-circle"></i></a>
                            @if ($preinscripcion->pivot->estado != "A" && $preinscripcion->pivot->estado != "R" && $preinscripcion->pivot->estado != "F"  )
                            <a href="#" @click.prevent="resetPreRegistration('{{Auth::user()->estudiante->id}}','{{$preinscripcion->id}}','{{session('process_id')}}')"
                                class="btn btn-success" title="Eliminar"><i class="fas fa-trash"></i>
                            </a>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <br>
    <div class="col-md-12">
        <a href="{{ route('public') }}" class="btn btn-secondary text-capitalize  font-weight-bold" data-toggle="tooltip" id="#" title="Regresar"><i class="mdi mdi-chevron-double-left" ></i> Ir a inicio</a>
    </div>
    <div class="d-flex justify-content-center">
        {!! $proyectos->links() !!}
    </div>
</div>

@endif
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
                        $('#preloader').fadeIn();
                        var url = route('deletePreRegister', [studen_id, project_id]);
                        axios.get(url).then(function(response) {
                            if(response.data == true){
                                $('#preloader').fadeOut();
                                toast({
                                    type: 'success',
                                    title: 'Preinscripción Eliminada Exitosamente'
                                });
                                setTimeout(function () { window.location.href = route('myPreregister',[studen_id,process_id]) }.bind(this), 1500);
                            }

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