@extends('public.layout.app')
@section('contenido')
<div class="row">
    <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
        <h1 class="section-title">INFORMACION DE PROYECTO {{$proyecto->nombre}}</h1>
    </div>
</div>
<div class="row">
    <div class="container" id="cards">
        <section class="welcome-section section-padding section-dark">
            <div class="container wow animated fadeInLeft">
                <div class="row">
                    <div class="col-md-12 col-lg-8 col-xs-12">
                        <div class="Material-tab">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#business" role="tab"><i class="fas fa-list-ol"></i></br>Detalles del Proyecto</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#startup" role="tab"><i class="fas fa-list-ul"></i></br>Datos de la institución a realizar el proyecto</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="business" role="tabpanel">
                                    <p class="text-capitalize"><strong style="font-weight:bold;">Nombre del Proyecto:</strong> {{$proyecto->nombre}}</p>
                                    <p><strong style="font-weight:bold;">Actividades a realizar:</strong> {!! $proyecto->actividades
                                        !!}
                                    </p>
                                    <p><strong style="font-weight:bold;">Horas a realizar:</strong> {{$proyecto->tipoProceso->horas}}</p>
                                </div>
                                <div class="tab-pane fade" id="startup" role="tabpanel">
                                    <p><strong style="font-weight:bold;">Nombre:</strong> {{$proyecto->institucion->nombre}}</p>
                                    <p><strong style="font-weight:bold;">Dirección exacta:</strong> {{$proyecto->institucion->direccion}}</p>
                                    <p><strong style="font-weight:bold;">Sector de la empresa/institución:</strong> {{$proyecto->institucion->sectorInstitucion->sector}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4 col-xs-12 welcome-column">
                            @if ($proyecto->img == null)
                                @if (session('process_id') == 1)
                                  <img src="/images/img_projects/SS.png" alt="{{$proyecto->nombre}}" style="background-repeat: no-repeat; height: 250px; width: 250px; background-position: 100%;border-radius: 50%; background-size: 100% auto;">
                                @elseif(session('process_id') == 2)
                                  <img src="/images/img_projects/PP.png" alt="{{$proyecto->nombre}}" style="background-repeat: no-repeat; height: 250px; width: 250px; background-position: 100%;border-radius: 50%; background-size: 100% auto;">
                                @endif
                           @else
                           <img class="img-fluid"  style="background-repeat: no-repeat; height: 250px; width: 250px; background-position: 100%;border-radius: 50%; background-size: 100% auto;" src="/images/img_projects/{{$proyecto->img}}" alt="{{$proyecto->nombre}}">
                           @endif
                    </div>
                    <div class="row">
                        @if (in_array($proyecto->id,Auth::user()->estudiante->preinscripciones->pluck('id')->toArray()) == 1)
                        <div class="col-md-6 text-center">
                            <br><button type="button" class="animated4 btn btn-dark" disabled>Preinscribirme &nbsp;<i class="fas fa-plus-circle MisProyFon"></i></button>
                        </div>
                        @else
                        <div class="col-md-6 text-center">
                            <br><button style="cursor: pointer;" type="button" class="animated4 btn btn-common" @click.prevent="loadPreRegistration('{{Auth::user()->estudiante->id}}','{{$proyecto->id}}','{{session('process_id')}}')"
                                id="btnPreinscribir">Preinscribirme&nbsp;<i class="fas fa-plus-circle MisProyFon"></i></button>
                            </div>

                            @endif
                            <div class="col-md-6 text-center">
                                <br><a href="#" rel="nofollow" class="animated4 btn btn-info">Dudas sobre proyecto &nbsp;<i class="fas fa-question-circle MisProyFon"></i></a>
                            </div>
                        </div>
                    </div>
                    <br>
                    <a href="{{ url()->previous() }}"  class="btn btn-dark text-capitalize  font-weight-bold" data-toggle="tooltip" id="#" title="Regresar"><i class="mdi mdi-chevron-double-left" ></i>Regresar</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('page_script')
<script type="text/javascript">
    var app = new Vue({
        el : '#cards',
        data : {

        },
        methods : {
            loadPreRegistration: function (studen_id,project_id,process_id){
               const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: true, timer: 1500 });
               swal({
                title: 'Esta seguro de Preinscribirte al este proyecto?',
                text: "Una vez hecho espera la respuesta del admistrador!",
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
                    var url = route('preRegister', [studen_id, project_id]);
                    axios.get(url).then(function(response) {
                        if(response.data == true){
                            //$('#preloader').fadeOut();
                            swal({
                                position: "center",
                                type: "warning",
                                title: "Preinscripción realizada con exito",
                                showConfirmButton: true,
                                width: '350px',
                            }).then(function(result){
                                 window.location.href = route('myPreregister',[studen_id,process_id]);
                                //setTimeout(function () { window.location.href = route('myPreregister',[studen_id,process_id]) }.bind(this), 1500);
                             })

                            //setTimeout(function () { window.location.href = route('myPreregister',[studen_id,process_id]) }.bind(this), 1500);
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