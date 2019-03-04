@extends('public.layout.app') 
@section('contenido')
<section class="Material-blog-section section-padding section-dark" id="contenido">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
                <h1 class="section-title">INFORMACION DE PROYECTO EN MARCHA</h1>
            </div>
        </div>
        <section class="welcome-section section-padding section-dark">
            <div class="container wow animated fadeInLeft">
                <div class="row">
                    <div class="col-md-12 col-lg-4 col-xs-12 welcome-column text-center">
                        @if ($data->proyecto->img == null)
                        @if($data->tipo_gp == 1)
                        <img class="img-fluid img-rounded" style="background-repeat: no-repeat; height: 250px; width: 250px; background-position: 100%;border-radius: 50%; background-size: 100% auto;"
                            src="{{url("/images/img_projects/SS.png") }}" alt="Servicio Social">
                        @else
                        <img class="img-fluid img-rounded" style="background-repeat: no-repeat; height: 250px; width: 250px; background-position: 100%;border-radius: 50%; background-size: 100% auto;"
                            src="{{url("/images/img_projects/PP.png") }}" alt="Práctiva Profesional">
                        @endif
                        @else
                        <img class="img-fluid img-rounded" style="background-repeat: no-repeat; height: 250px; width: 250px; background-position: 100%;border-radius: 50%; background-size: 100% auto;"
                            src="{{url("/images/img_projects/".$data->proyecto->img)}}" alt="{{ $data->proyecto->img }}">
                        @endif
                        <br>
                    </div>
                    <div class="col-md-12 col-lg-8 col-xs-12">
                        <br>
                        <div class="Material-tab">
                            <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <h2 class="nav-link active" data-toggle="tab" href="#busines" role="tab"><strong>Proceso
                                            de:</strong>
                                        <strong>
                                            {{ $data->tipo_gp == 1 ? 'Servicio Social' : 'Práctica Profesional' }}
                                        </strong>
                                    </h2>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" role="tabpanel">
                                    <h6 style="padding: 5px;"><strong>Nombre:</strong> {{ $data->proyecto->nombre }}</h6>
                                    <h6 style="padding: 5px;"><strong>Institucion:</strong> {{ $data->proyecto->institucion->nombre }}</h6>
                                    <h6 style="padding: 5px;"><strong>Direccion:</strong> {{ $data->proyecto->institucion->direccion }}</h6>
                                    @if ($data->estado == 'I')
                                    <h6 style="padding: 5px;"><strong>Horas a realizar:</strong> {{ $data->horas_a_realizar }}</h6>
                                    @else
                                    <h6 style="padding: 5px;"><strong>Horas realizadas:</strong> {{ $data->horas_realizadas }}</h6>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 style="padding: 5px;"><strong>Fecha Inicio:</strong> {{ $data->fecha_inicio }}</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 style="padding: 5px;"><strong>Fecha Finalizacion:</strong> {{ $data->fecha_fin == null ? 'Pendiente...' : $data->fecha_fin }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                        <div class="Material-tab">
                            <div class="tab-content">
                                <legend class="text-center subtitle">Control de documentos del Proceso</legend>
                                <hr>
                                <div class="row">
                                    @foreach ($data->documentos_entrega as $documentos)
                                    <div class="col-md-3">
                                        <div class="tab-pane fade show active" id="business" role="tabpanel">
                                            <br>
                                            <div class="card text-center">
                                                @if(isset($documentos->pivot->gestion_proyecto_id))
                                                <div class="card-header">
                                                    <h5 class="card-title" style="font-size: 1.2em;">Entregado &nbsp;<i
                                                            class="fas fa-check-circle"></i></h5>
                                                </div>
                                                <div>
                                                    <p class="card-text text-center"><strong>{{$documentos->nombre}}</strong></p>
                                                </div>
                                                <hr>
                                                <div>
                                                    <p class="card-text text-center">{{$documentos->pivot->observacion != null ? $documentos->pivot->observacion : 'Entregado sin observaciones' }}</p>
                                                </div>
                                                <br>
                                                <div class="card-footer text-muted">
                                                    {{substr($documentos->pivot->created_at,0,4) != substr($data->fecha_inicio,0,4) ? substr($data->fecha_inicio,0,4)."-".substr($documentos->pivot->created_at,5,5) : substr($documentos->pivot->created_at,0,10)}}
                                                </div>
                                                @else
                                                <div class="card-header">
                                                    <h5 class="card-title">Pendiente &nbsp;<i class="fas fa-exclamation-circle"></i></h5>
                                                </div>
                                                <div>
                                                    <p class="card-text text-center"><strong>{{$documentos->nombre}}</strong></p>
                                                </div>
                                                <hr>
                                                <div>
                                                    <p class="card-text text-center">...</p>
                                                </div>
                                                <br>
                                                <div class="card-footer text-muted">
                                                    ...
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <br>
                            <a href="{{ url()->previous() }}" class="btn btn-dark text-capitalize  font-weight-bold"
                                data-toggle="tooltip" id="#" title="Regresar"><i class="mdi mdi-chevron-double-left"></i>Regresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
@endsection