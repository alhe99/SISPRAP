@extends('public.layout.app')
@section('contenido')
<section class="Material-blog-section section-padding section-dark"  id="contenido">
	<div class="container">
		<div class="row">
			<div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
				<h1 class="section-title">INFORMACION DE PROYECTO EN MARCHA</h1>
			</div>
		</div>
		<section class="welcome-section section-padding section-dark">
			<div class="container wow animated fadeInLeft">
				<div class="row">
					<div class="col-md-12 col-lg-4 col-xs-12 welcome-column">
						@if ($data->proyecto->img == null)
							<img class="img-fluid img-rounded" src="/images/img_projects/{{ $data->tipo_gp == 1 ? 'SS.png' : 'PP.png' }}" alt="{{ $data->proyecto->img }}">
						@else
							<img class="img-fluid img-rounded" src="/images/img_projects/{{ $data->proyecto->img }}" alt="{{ $data->proyecto->img }}">
						@endif
						<br>
					</div>
					<div class="col-md-12 col-lg-8 col-xs-12">
						<div class="Material-tab">
							<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
								<div class="card-header">
									<h5 class="text-center">
										Proceso de:
										<strong>
											{{ $data->tipo_gp == 1 ? 'Servicio Social' : 'Práctica Profesional' }}
										</strong>
									</h5>
								</div>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade show active" role="tabpanel">
									<h6 style="padding: 5px;"><strong>Nombre:</strong> {{ $data->proyecto->nombre }}</h6>
									<h6 style="padding: 5px;"><strong>Institucion:</strong> {{ $data->proyecto->institucion->nombre }}</h6>
									<h6 style="padding: 5px;"><strong>Direccion:</strong> {{ $data->proyecto->institucion->direccion }}</h6>
							{{-- 		<h6 style="padding: 5px;"><strong>Estado de proyecto:</strong>{{ $data->estado == 'I' ? 'Iniciado' : $data->estado == 'F' : 'Finalizado'  }}</h6> --}}
									<div class="row">
										<div class="col-md-6"><h6 style="padding: 5px;"><strong>Fecha Inicio:</strong> {{ $data->fecha_inicio }}</h6></div>
										<div class="col-md-6"><h6 style="padding: 5px;"><strong>Fecha Finalizacion:</strong> {{ $data->fecha_fin == null ? 'Pendiente...' : $data->fecha_fin  }}</h6></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="welcome-section section-padding section-dark">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-12 col-xs-12">
						<div class="Material-tab">
							<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#business" role="tab"><i class="far fa-user"></i></br>Perfil del proyecto</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#startup" role="tab"><i class="far fa-clock"></i></br>Control de Horas</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#agency" role="tab"><i class="far fa-list-alt"></i></br>Control de asistencia</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#saas" role="tab"><i class="far fa-file-alt"></i></br>Constancia de Finalizacion</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade show active" id="business" role="tabpanel" >
									<div class="card text-center">
										<div class="card-header">
											<h5 class="card-title">Entregrado &nbsp;<i class="fas fa-check-circle"></i></h5>
										</div>
										<div class="card-body">
											<p class="card-text text-center">Entregrado sin observaciones.</p>
										</div>
										<div class="card-footer text-muted">
											2 days ago
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="startup" role="tabpanel" >
									<div class="card text-center">
										<div class="card-header">
											<h5 class="card-title">Entregrado &nbsp;<i class="fas fa-check-circle"></i></h5>
										</div>
										<div class="card-body">
											<p class="card-text text-center">Entregrado sin observaciones.</p>
										</div>
										<div class="card-footer text-muted">
											2 days ago
										</div>
									</div>
								</div>
								<div  class="tab-pane fade" id="agency" role="tabpanel">
									<div class="card text-center">
										<div class="card-header">
											<h5 class="card-title">Entregrado &nbsp;<i class="fas fa-check-circle"></i></h5>
										</div>
										<div class="card-body">
											<p class="card-text text-center">Entregrado sin observaciones.</p>
										</div>
										<div class="card-footer text-muted">
											2 days ago
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="saas" role="tabpanel">
									<div class="card text-center">
										<div class="card-header">
											<h5 class="card-title">Pendiente &nbsp;<i class="fas fa-exclamation-circle"></i></h5>
										</div>
										<div class="card-body">
											<p class="card-text text-center">Documento restante.</p>
										</div>
										<div class="card-footer text-muted">
											2 days ago
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>
@endsection
