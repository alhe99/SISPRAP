<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>SISPRAP || Public</title>
	<link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo-favicon.png')}}">
	<link rel="stylesheet" href="{{asset('other/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/materialdesignicons.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/fontawesome-all.min.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/material.min.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/ripples.min.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/owl.carousel.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/magnific-popup.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/slicknav.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/animate.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/responsive.css')}}">
	<link rel="stylesheet" href="{{asset('css/publicTemplate.css')}}">
	<link rel="stylesheet" href="{{asset('other/css/normalize.min.css')}}">
	<link rel="stylesheet" type="text/css" media="screen" href="{{asset('other/css/indigo.css')}}">
	<link rel='stylesheet' href='{{ asset('other/css/gijgo.min.css') }}'>

	<style>
</style>

</head>

<body>
	<header id="header">
		<nav class="navbar navbar-expand-lg fixed-top scrolling-navbar nav-bg">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="{{route('public')}}"><img src="{{asset('images/LogoItch.png')}}" alt=""></a>
				</div>
				<div class="collapse navbar-collapse" id="main-navbar">
					<ul class="navbar-nav mr-auto w-100 justify-content-end">
						<li class="nav-item active">
							<a class="nav-link" href="{{route('public')}}">
								INICIO
							</a>
						</li>
						<li class="nav-item active">
							<a class="nav-link" href="{{route('myPreregister',array(Auth::user()->estudiante->id,session('process_id'))) }}">
								PREINSCRIPCIONES
							</a>
						</li>
						<li class="nav-item active">
							<a class="nav-link" href="{{route('proyects_now',array(session('student_id'))) }}">
								MIS PROYECTOS
							</a>
						</li>
						<li class="nav-item active">
							<a class="nav-link" target="_black" href="{{asset('manual/MANUAL.pdf')}}">
								<ins>MANUAL</ins>
							</a>
						</li>
						<li class="nav-item dropdown active">
							<a class="nav-link" href="#">
								Bienvenido(a): {{Auth::user()->estudiante->nombre." ".Auth::user()->estudiante->apellido }}
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesion </a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<ul class="wpb-mobile-menu">
				<li>
					<a class="active" href="{{route('public')}}">
						INICIO
					</a>
				</li>
				<li>
					<a href="{{route('myPreregister',array(Auth::user()->estudiante->id,session('process_id'))) }}">
						PREINSCRIPCIONES
					</a>
				</li>
				<li>
					<a href="{{route('proyects_now',array(session('student_id'))) }}">
						MIS PROYECTOS
					</a>
				</li>
				<li>
					<a href="{{asset('manual/MANUAL.pdf')}}" target="_black">
						<ins>MANUAL</ins>
					</a>
				</li>
				<li>
					<a href="#">
						Bienvenido(a): {{Auth::user()->estudiante->nombre}}
					</a>
					<ul class="dropdown">
						<li>
							<a href="{{ route('logout') }}"  data-target="#exampleModal">Cerrar Sesion</a>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
	</header>
	<br><br>
	<div class="Notificaciones">
		<div class="contenedorBF">
			<button class="btn BF1 tex-center" style="border-radius: 50px; cursor: pointer;">&nbsp;<i class="fas fa-plus fa">&nbsp;
				@if (count(Auth::user()->estudiante->preinscripciones) != 0)
				@if (Auth::user()->estudiante->preinscripciones[0]->pivot->estado == 'F')
				<span class="badge badge-primary">1</span>
				@endif
				@endif
			</i>
		</button>
		<button class="btn BF2 hint--left" data-hint="Documentos de procesos" data-toggle="modal" data-target="#exampleModal2" style="border-radius: 50px; cursor: pointer;">
			<i class="far fa-folder-open fa-sm"></i>
		</button>
		<button class="btn BF3 hint--left" data-hint="Chat con el administrador" style="border-radius: 50px; cursor: pointer;">
			<i class="far fa-comments fa-sm"></i>
		</button>
		<button class="btn BF4 hint--left" data-hint="Notificaciones" data-toggle="modal" data-target=".docs-example-modal-lg" style="border-radius: 50px; cursor: pointer;"><i class="far fa-bell fa">
			@if (count(Auth::user()->estudiante->preinscripciones) != 0)
			@if (Auth::user()->estudiante->preinscripciones[0]->pivot->estado == 'F')
			<span class="badge badge-danger">1</span>
			@endif
			@endif
		</i>
	</button>
	{{-- <button class="btn BF5 hint--left" data-hint="Documentos de procesos" data-toggle="modal" data-target="#exampleModal2" style="border-radius: 50px;cursor: pointer;">
		<i class="far fa-folder-open fa-sm"></i>
	</button> --}}
</div>
<div class="modal fade docs-example-modal-lg col-md-12"  id="app" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLongTitle">Panel de Notificaciones</h5>
			<button type="button" class="close" style="cursor: pointer;" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="row ">
				<div class="col-md-12 ">
					&nbsp;
					@if (count(Auth::user()->estudiante->preinscripciones) != 0)
					@if (Auth::user()->estudiante->preinscripciones[0]->pivot->estado == 'F')
					<h6>
						<i class="fas fa-check">
						</i> Tu solicitud al proyecto  Ha sido aceptada!
						<a href="{{route('show_perfil')}}" onclick="perfilProy()">
							<strong>
								Puedes iniciar con tu proceso ahora!!
							</strong>
						</a>
					</a>
				</h6>
				@endif
				@endif
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" style="cursor: pointer;"  data-target="#exampleModal" data-dismiss="modal">Cerrar</button>
	</div>
</div>
</div>
</div>
<div class="modal" id="exampleModal2" tabindex="-4" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="margin-top: 60px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-center" id="exampleModalLongTitle" style="display: block; margin-left: auto; margin-right: auto;">Desacarga tus documentos</h5>
				<button type="button" class="close" style="cursor: pointer;" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-lg-12 col-xs-12">
						<div class="Material-tab">
							<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#business" role="tab"><i class="far fa-user"></i></br>Perfil del proyecto</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#startup" role="tab"><i class="far fa-list-alt"></i></br>Control de proyecto</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#agency" role="tab"><i class="fa fa-calendar-alt"></i></br>Control de asistencia</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade show active" id="business" role="tabpanel" >
									<div class="card text-center">
										<div class="card-header">
											<h6 class="card-title">Presiona en el botón para descargar</h6>
										</div>
										<div class="card-body">
											<button type="button" class="btn btn-primary btn-sm text-white btn-lg" style="cursor: pointer;"><i class="mdi mdi-check-all" ></i>&nbsp;Descargar</button>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="startup" role="tabpanel" >
									<div class="card text-center">
										<div class="card-header">
											<h6 class="card-title">Presiona en el botón para descargar</h6>
										</div>
										<div class="card-body">
											<button class="btn btn-primary btn-sm text-white btn-lg" style="cursor: pointer;"><i class="mdi mdi-check-all" ></i>&nbsp;Descargar</button>
										</div>
									</div>
								</div>
								<div  class="tab-pane fade" id="agency" role="tabpanel">
									<div class="card text-center">
										<div class="card-header">
											<h6 class="card-title">Presiona en el botón para descargar</h6>
										</div>
										<div class="card-body">
											<button type="button" class="btn btn-primary btn-sm text-white btn-lg" style="cursor: pointer;"><i class="mdi mdi-check-all " ></i>&nbsp;Descargar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<button type="button" class="btn btn-danger btn-sm ml-auto d-flex text-white" style="cursor: pointer;" data-target="#exampleModal" data-dismiss="modal"><i class="mdi mdi-close-box" ></i>&nbsp;Cancelar</button>
			</div>
		</div>
	</div>
</div>
{{-- <div class="modal" id="exampleModal1" tabindex="-4" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="margin-top: 60px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-center" id="exampleModalLongTitle" style="display: block; margin-left: auto; margin-right: auto;">Actualiza tus datos</h5>
				<button type="button" class="close" style="cursor: pointer;" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row ">
					<div class="col-md-12">
						<div class="form-group label-floating">
							<label class="control-label" for="estudiante_telefono">Teléfono</label>
							<input class="form-control" id="estudiante_telefono" type="text" name="estudiante_telefono" value="{{Auth::user()->estudiante->telefono}}">
						</div>
					</div>
				</div>
				<div class="row ">
					<div class="col-md-12">
						<div class="form-group label-floating">
							<label class="control-label" for="estudiante_email">Correo electrónico</label>
							<input class="form-control" id="estudiante_email" type="text" name="estudiante_email" value="{{Auth::user()->estudiante->email}}">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-outline-info text-center btn-lg btn-block" type="button" data-toggle="collapse" style="display: block; margin-left: auto; margin-right: auto; cursor:pointer;" data-target=".multi-collapse" aria-expanded="false" aria-controls="collapseExample1 collapseExample2"><i class="mdi mdi-key-variant"></i>&nbsp;
							Cambiar contraseña
						</button>
						<div class="row">
							<div class="col-md-6" data-wow-delay=".1s">
								<div class="form-group label-floating collapse multi-collapse" id="collapseExample1">
									<label class="control-label" for="estudiante_contraseña">Nueva contraseña</label>
									<input class="form-control" id="estudiante_telefono" type="text" name="estudiante_telefono" value="">
								</div>
							</div>
							<div class="col-md-6" data-wow-delay=".1s">
								<div class="form-group label-floating collapse multi-collapse" id="collapseExample2">
									<label class="control-label" for="estudiante_contraseña_conf">Confirmar contraseña</label>
									<input class="form-control" id="estudiante_telefono" type="text" name="estudiante_telefono" value="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6" data-wow-delay=".1s">
								<div class="form-group">
									<button type="button" class="btn btn-primary btn-block" style="cursor: pointer;" data-target="#exampleModal"><i class="mdi mdi-content-save"></i>&nbsp;Actualizar</button>
								</div>
							</div>
							<div class="col-md-6" data-wow-delay=".1s">
								<div class="form-group">
									<button type="button" class="btn btn-danger btn-block text-white" style="cursor: pointer;" data-target="#exampleModal" data-dismiss="modal"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 text-center">
					<button type="button" class="btn btn-primary btn-block" style="cursor: pointer;" data-target="#exampleModal"><i class="mdi mdi-content-save"></i>&nbsp;Actualizar</button>
				</div>
				<div class="col-md-6 text-center">
					<button type="button" class="btn btn-danger btn-block text-white" style="cursor: pointer;" data-target="#exampleModal" data-dismiss="modal"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div> --}}

<section>
	<div id="carouselExampleIndicators" class="carousel slide wow animated fadeInUp" data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="7"></li>
		</ol>
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img class="d-block w-100" src="{{asset('images/slider/carru1.jpg')}}" alt="First slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="{{asset('images/slider/carru2.jpg')}}" alt="Second slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="{{asset('images/slider/carru3.jpg')}}" alt="Third slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="{{asset('images/slider/carru4.jpg')}}" alt="Third slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="{{asset('images/slider/carru5.jpg')}}" alt="Third slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="{{asset('images/slider/carru6.jpg')}}" alt="Third slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="{{asset('images/slider/carru7.jpg')}}" alt="Third slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="{{asset('images/slider/carru8.jpg')}}" alt="Third slide">
			</div>
			<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</section>
	<br>
	<section class="Material-blog-section section-padding section-dark" id="contenido">
		<div class="container">
			@yield('contenido')

		</div>
	</section>
	<div class="modal" id="exampleModal" tabindex="-4" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document" style="margin-top: 60px;">
			<div class="modal-content">
				<div class="modal-header text-center">
					<h5 class="modal-title text-center" id="exampleModalLabel">{{Auth::user()->estudiante->nombre ." ".Auth::user()->estudiante->apellido }}</h5>
					<button type="button" class="close" style="cursor: pointer;" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-center">
					¿Esta seguro de cerrar Sesión?
				</div>
				<div class="contenido text-center">
					<i class="fas fa-question fa-3x"></i>
				</div>
				<div class="modal-body text-center">
					<button type="button" class="btn btn-secondary" style="cursor: pointer;" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" style="cursor: pointer;" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar Sesión</button>
				</div>
			</div>
		</div>
	</div>
	<footer class="page-footer center-on-small-only  pt-0 footer-widget-container">
		<div class="container pt-3 mb-3">
			<div class="row">
				<div class="col-md-6 col-lg-6 col-xl-6 footer-contact-widget">
					<h3 class="footer-title">MISION</h3>
					<p class=" text-justify">Formar profesionales técnicos con responsabilidad ciudadana, pensamiento crítico, con sensibilidad a la investigación
						y al desarrollo tecnológico, a través de un proceso educativo que integra aspectos académicos, procedimentales y actitudinales,
					fortalecidos con un sistema de gestión de calidad, para promover el desarrollo social sostenible de nuestro país.</p>
				</div>
				<div class="col-md-5 col-lg-5 col-xl-5 recent-widget">
					<h3 class="footer-title">VISION</h3>
					<p class="text-justify">Ser una institución educativa referente en la formación de profesionales en áreas tecnológicas con competencias para
						la vida y el trabajo, con sensibilidad humana; que se incorporen responsablemente al desarrollo productivo sostenible
					del país.</p>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<p>Instituto Tecnologico de Chalatenago &copy; {{date("Y")}}</p>
					</div>
				</div>
			</div>
		</div>
		<a href="#" class="back-to-top">
			<div class="ripple-container" style="float:left;"></div>
			<i class="mdi mdi-arrow-up">
			</i>
		</a>
		<div id="preloader" >
			<div class="loader"  id="loader-1"></div>
		</div>

	</footer>
	<script src="{{asset('other/js/jquery-min.js')}}"></script>
	<script src="{{asset('other/js/popper.min.js')}}"></script>
	<script src="{{asset('other/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('other/js/jquery.mixitup.min.js')}}"></script>
	<script src="{{asset('other/js/jquery.inview.js')}}"></script>
	<script src="{{asset('other/js/jquery.counterup.min.js')}}"></script>
	<script src="{{asset('other/js/scroll-top.js')}}"></script>
	<script src="{{asset('other/js/smoothscroll.js')}}"></script>
	<script src="{{asset('other/js/material.min.js')}}"></script>
	<script src="{{asset('other/js/ripples.min.js')}}"></script>
	<script src="{{asset('other/js/owl.carousel.min.js')}}"></script>
	<script src="{{asset('other/js/form-validator.min.js')}}"></script>
	<script src="{{asset('other/js/contact-form-script.min.js')}}"></script>
	<script src="{{asset('other/js/wow.js')}}"></script>
	<script src="{{asset('other/js/jquery.vide.js')}}"></script>
	<script src="{{asset('other/js/jquery.magnific-popup.min.js')}}"></script>
	<script src="{{asset('other/js/jquery.slicknav.js')}}"></script>
	<script src="{{asset('other/js/main.js')}}"></script>
	<script src="{{asset('other/js/scripts.js')}}"></script>
	<script src="{{asset('js/publicTemplate.js')}}"></script>
	<script src="{{asset('other/js/jquery.mask.min.js')}}"></script>
	<script src='{{ asset('other/js/gijgo.min.js') }}'></script>
	<script src='{{ asset('other/js/messages.es-es.js') }}'></script>
	@routes @yield('page_script')
</body>

</html>