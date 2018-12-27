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
                        <tr>
                            <th scope="row" class="text-center">No</th>
                            <th class="th-lg text-center"><a>Proyecto</a></th>
                            <th class="th-lg text-center">Descripción</a>
                            </th>
                            <th class="th-lg text-center">Fecha de preinscripción</a>
                            </th>
                            <th class="th-lg text-center">Estado</a>
                            </th>
                            <th class="th-lg text-center">Opciones</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proyectos as $mp)
                        <tr>
                            <th scope="row" class="text-center">{{$loop->iteration}}</th>
                            <td class="text-capitalize">{{strtolower($mp->nombre)}}</td>
                            <td class="text-center truncate">{!!$mp->actividades !!}</td>
                            <td class="text-center">{{substr($mp->preRegistration[0]->pivot->created_at,0,10)}}</td>
                            @if ($mp->preRegistration[0]->pivot->estado == "P")
                            <td class="text-center"><h2 class="badge badge-primary">Preinscrito</h2></td>
                            @elseif($mp->preRegistration[0]->pivot->estado == "A")
                            <td class="text-center"><h2 class="badge badge-success">Aprobado</h2></td>
                            @elseif($mp->preRegistration[0]->pivot->estado == "R")
                            <td class="text-center"><h2 class="badge badge-danger">Rechazado</h2></td>
                            @endif
                            <td class="text-center">
                                <a href="{{route('viewProject', array($mp->proceso_id,$mp->slug))}}" rel="nofollow" class="animated4 btn btn-primary" title="Ver más Información"><i class="fas fa-plus-circle"></i></a>
                                <a href="#" @click.prevent="resetPreRegistration('{{Auth::user()->estudiante->id}}','{{$mp->id}}','{{session('process_id')}}')"
                                    class="btn btn-success" title="Eliminar"><i class="fas fa-trash"></i>
                                </a>
                            </td>
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
                        var url = route('deletePreRegister', [studen_id, project_id]);
                        axios.get(url).then(function(response) {
                            if(response.data == true){
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