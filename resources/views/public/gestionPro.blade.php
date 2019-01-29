@extends('public.layout.app')
@section('contenido')
<div class="row">
  <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
    <h1 class="section-title">PROYECTOS EN MARCHA</h1>
  </div>
</div>
<div class="container" id="table">
  <div class="card wow animated fadeInLeft">
    <div class="card-body">
      <h4 class="text-center">
        Listado de proyectos que has realizado deacuerdo a tus proceso:
      </h4>
    </div>
  </div>
  <br><br>
  @if ($gestionp->count() > 0)
    <div class="card wow animated fadeInLeft">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <h2 class="py-3 text-center font-bold font-up blue-text">Tus Proyectos</h2>
          </div>
        </div>
        <div class="col-md-12">
          <table class="table table-hover table-responsive mb-0 table-striped">
            <thead>
              <tr>
                <th scope="row">No</th>
                <th class="th-lg">Proyecto</th>
                <th class="th-lg text-center">Proceso</th>
                <th class="th-lg text-center">Fecha de Inicio</th>
                <th class="th-lg text-center">Fecha de Finalización</th>
                <th class="th-lg text-center">Estado</th>
                <th class="th-lg text-center">Opciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($gestionp as $item)
              <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td class="text-truncate">{{substr($item->proyecto->nombre,0,25)}}...</td>
                <td class="text-center">{{ $item->tipo_gp == '1' ? 'Servicio Social' : 'Práctica Profesional'  }}</td>
                <td class="text-center">{{$item->fecha_inicio}}</td>
                <td class="text-center">{{ $item->fecha_fin == null ? 'Indefinida...' : $item->fecha_fin }}</td>
                @if ($item->estado == "I")
                <td class="text-center"><h3 class="badge badge-primary">Iniciado</h3></td>
                @elseif($item->estado == "P")
                <td class="text-center"><h3 class="badge badge-secondary">En Proceso</h3></td>
                @elseif($item->estado == "F")
                <td class="text-center"><h3 class="badge badge-success">Finalizado</h3></td>
                @endif
                <td class="text-center">
                  <a href="{{route('getFullDataByGestion', array("gestionId" => $item->id))}}" rel="nofollow" class="animated4 btn btn-primary" title="Ver más Información"><i class="fas fa-plus-circle"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @else
  <div class="alert alert-primary" role="alert">
    <h5 class="font-weight-bold m-2">¡No tienes datos de proyectos que hayas realizado!</h5>
  </div>
  @endif
</div>
@endsection


