@extends('public.layout.app') 
@section('contenido')
<div class="row">
  <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
    <h2 align="center">¡Bienvenido al apartado de proyectos disponibles para tu proceso de {{session('process_name')}}!</h2>
  </div>
</div><br> @if (Auth::user()->estudiante->supero_limite == 1)
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Has Llegado el limite de preinscripciones!</h4>
  <p>Porfavor espera la confirmación de alguno de los proyectos por parte de encargado de <strong>{{session('process_name')}}</strong>,
  o elimina una de las preinscripciones vigentes que posees!</p>
  <hr>
  <p class="mb-0"><a href="{{route('myPreregister',array(Auth::user()->estudiante->id,session('process_id'))) }}" class="btn btn-link">Ver mis preinscripciones</a></p>
</div>
@else
@if (count($proyectos) > 0)
<div class="row">
  <div class="col-md-12 col-lg-12 col-xl-12 wow animated fadeInUp" data-wow-delay=".3s">
    <article class="single-blog-post col-md-12">
      <div class="featured-image">
        <div class="search-icon">
          <span class="open-search col-md-1">
            <i class="mdi mdi-magnify btn btn-common"></i>
          </span>
        </div>
        <br>
        <form role="search" class="navbar-form">
          <div class="container">
            <div class="row">
              <div class="form-group has-feedback">
                <input type="text" placeholder="Buscar Proyectos..." class="form-control">
                <div class="close"><i class="mdi mdi-close"></i></div>
              </div>
            </div>
          </div>
        </form>
        <div class="col-md-1 text-center">
          <a href="#" rel="nofollow"  class="animated4 btn btn-common">Buscar</a>
        </div>
      </div>
    </article>
  </div>
</div>
<div class="row">
  @foreach ($proyectos as $p)
  <div class="col-md-6 col-lg-4 col-xl-4 card-group wow animated fadeInUp"  onclick="redirectToCard('{{session('process_id')}}','{{$p->slug}}')" data-wow-delay=".3s">
    <article class="single-blog-post" >
      <div class="featured-image">
        <a href="#">
          @if ($p->img == null)
           @if (session('process_id') == 1)
            <img src="/images/img_projects/SS.png" alt="{{$p->nombre}}" style="width: 100%;display: block;margin-left: auto;margin-right: auto;">
            @elseif(session('process_id') == 2)
            <img src="/images/img_projects/PP.png" alt="{{$p->nombre}}" style="width: 100%;display: block;margin-left: auto;margin-right: auto;">
            @endif
          @else
           <img src="/images/img_projects/{{$p->img}}" alt="{{$p->nombre}}" style="width: 100%;display: block;margin-left: auto;margin-right: auto;">
          @endif
        </a>
      </div>
      <div class="post-meta">
        <h2 class="subtitle">{{strtolower($p->nombre)}}</h2>
        <p class="truncate">{!!substr($p->actividades,0,125)!!}</p>
      </div>
      <div class="meta-tags">
        <span class="comments"><i class="mdi mdi-comment-outline"></i> {{substr($p->created_at,0,10)}}</span>
        <a class="btn btn-round btn-fab" href="{{route('viewProject', array($p->proceso_id,$p->slug))}}"><i style="margin-top: 0% " class="material-icons mdi mdi-arrow-right"></i><div class="ripple-container"></div></a>
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
  }.bind(this)
});
</script>   
@endsection