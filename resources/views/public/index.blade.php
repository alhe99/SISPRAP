@extends('public.layout.app') 
@section('contenido')

<div class="row">
  <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
    <h2 align="center" style="font-size: 5vh;">¡Bienvenido al apartado de proyectos disponibles para tu proceso de {{ Auth::user()->estudiante->proceso[0]->nombre}}!</h2>
  </div>
</div>

<br>

@if (Auth::user()->estudiante->supero_limite == 1)

<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Has Llegado el limite de preinscripciones!</h4>
  <p>Porfavor espera la confirmación de alguno de los proyectos por parte de encargado de <strong>{{ Auth::user()->estudiante->proceso[0]->nombre}}</strong>,
    o elimina una de las preinscripciones vigentes que posees!
  </p>
  <hr>
  <p class="mb-0"><a href="{{route('myPreregister',array(Auth::user()->estudiante->id,Auth::user()->estudiante->proceso[0]->id)) }}" class="btn btn-link">Ver mis preinscripciones</a></p>
</div>

@elseif (Auth::user()->estudiante->no_proyectos == 2)
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Ya estas realizando tu segundo proyecto de {{ Auth::user()->estudiante->proceso[0]->nombre}}!</h4>
  <p>El limite de proyecto por proceso es de 2!</p>
</div>

@elseif (Auth::user()->estudiante->estado_pp == 2)
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Has completado exitosamente tus procesos!</h4>
  <p>Puedes retirar tu constancia de finalización cuando el encargado de tu procesos te lo indique</p>
</div>

@elseif(Auth::user()->estudiante->proceso[0]->id == 2 and Auth::user()->estudiante->nivel_academico_id == 1 and Auth::user()->estudiante->articulado
  == false)
  <div class="alert alert-success" role="alert">
    <hr>
    <h4 class="alert-heading">Has completado el proceso de servicio social!</h4>
    <p>Regresa el siguiente año para realizar tu proceso de Práctica Profesional</p>
    <hr>
  </div>


@else
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12 wow animated fadeInUp" data-wow-delay=".3s">
        <article class="single-blog-post col-md-12">
          <div class="featured-image">
            {{ Form::open(['route' => 'public', 'method' => 'GET','class' => 'form-horizontal','role' => 'search','id' => 'form-search'])
            }}
            <div class="row">
              <div class="col-md-10 wow animated fadeInRight">
                <div class="form-group label-floating">
                  <label class="control-label" for="name_supervisor">Buscar Proyectos...</label> {{ Form::text('buscar', isset($data_search)
                  ? $data_search : '', ['class' => 'form-control']) }}
                </div>
              </div>
              <div class="col-md-2 wow animated fadeInRight">
                <div class="form-group label-floating">
                  {!! Form::button('Buscar&nbsp;<i class="mdi mdi-magnify" style="cursor: pointer;"></i>', ['class' => 'animated4
                  btn btn-primary btn-block CursorPoint','type'=>'submit','id' => 'btn-search']) !!}
                </div>
              </div>
            </div>
            {{ Form::close() }}
          </div>
        </article>
      </div>
    </div>

 @if (count($proyectos) > 0)
    <div class="row">
        @foreach ($proyectos as $p)
        <div class="col-md-6 col-lg-3 col-xl-3 card-group wow animated fadeInUp" onclick="redirectToCard('{{ Auth::user()->estudiante->proceso[0]->id}}','{{$p->slug}}')"
          data-wow-delay=".3s">
          <article class="single-blog-post" style="width: 100%; cursor: pointer;">
            <div class="featured-image">
              <a href="#">
                  @if ($p->img == null)
                  @if($p->proceso_id == 1)
                  <img src="{{url("/images/img_projects/SS.png")}}" class="img-fluid img-proy" alt="Servicio Social">
                  @else
                  <img src="{{url("/images/img_projects/PP.png")}}" class="img-fluid img-proy" alt="Práctica Profesional">
                  @endif
                  @else
                  <img src="{{url("/images/img_projects/".$p->img)}}" class="img-fluid img-proy" alt="{{$p->nombre}}" {{--  style="width: 100%; display: block;margin-left: auto;margin-right: auto;height:200px;" --}}>
                  @endif
                  </a>
            </div>
            <div class="meta-tags">
              <h5 class="subtitle">{{strtolower($p->nombre)}}</h5>
              {{--
              <p class="truncate">{!!substr($p->actividades,0,125)!!}</p>
              --}}
            </div>
            <div class="meta-tags">
              <span class="comments"><i class="mdi mdi-calendar-check"></i>Publicación: {{substr($p->created_at,0,10)}}</span>
              <a class="btn btn-round btn-fab" href="{{route('viewProject', array($p->proceso_id,$p->slug))}}">
                    <i style="margin-top: 0% " class="material-icons mdi mdi-arrow-right"></i>
                    <div class="ripple-container"></div>
                  </a>
            </div>
          </article>
        </div>
        @endforeach
        <div class="col-md-12 d-flex justify-content-center">
          {!!$proyectos->links() !!}
        </div>

        @if (count($proyectos)>= 9)
        <div class="col-md-12 d-flex justify-content-center">
          <p class="text-muted" style="font-size: 14px;margin-top: -10px;">(Mostrando del {{$proyectos->firstItem()}} al {{$proyectos->lastItem()}} de {{$proyectos->total()}} registros)</p>
        </div>
        @endif 

        @elseif(count($proyectos) == 0) 
        <div class="alert alert-success" role="alert">
          <hr>
          <h4 class="alert-heading">No Hay Proyectos Publicados!</h4>
          <p>Espera a que el administrador realice nuevas publicaciones pronto</p>
          <hr>
        </div>
  @endif

</div>
@endif
</div>
</div>
@endsection
 
@section('page_script')
<script>
 $(document).ready(function(){
  redirectToCard = function (process_id,project_slug) {
    window.location.href = route('viewProject',[process_id,project_slug])
  }.bind(this);
  $(".img-proy").click(function(e){
    e.preventDefault();
  })
});
</script>
@endsection