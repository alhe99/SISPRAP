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
						<img class="img-fluid img-rounded" style="background-repeat: no-repeat; height: 250px; width: 250px; background-position: 100%;border-radius: 50%; background-size: 100% auto;" src="/images/img_projects/{{ $data->tipo_gp == 1 ? 'SS.png' : 'PP.png' }}" alt="{{ $data->proyecto->img }}">
						@else
						<img class="img-fluid img-rounded" style="background-repeat: no-repeat; height: 250px; width: 250px; background-position: 100%;border-radius: 50%; background-size: 100% auto;" src="/images/img_projects/{{ $data->proyecto->img }}" alt="{{ $data->proyecto->img }}">
						@endif
						<br>
					</div>
					<div class="col-md-12 col-lg-8 col-xs-12">
						<div class="Material-tab">
							<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
								<li class="nav-item">
									<h2 class="nav-link active" data-toggle="tab" href="#busines" role="tab"><strong>Proceso de:</strong>
										<strong>
											{{ $data->tipo_gp == 1 ? 'Servicio Social' : 'Práctica Profesional' }}
										</strong></h2>
									</li>
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
								<div class="tab-content">
									<legend class="text-center subtitle">Control de documentos del Proceso</legend>
									<hr>
									<div class="row">
										<div class="col-md-3">
											<div class="tab-pane fade show active" id="business" role="tabpanel" >
												<div class="card text-center">
													<div class="card-header">
														<h5 class="card-title">Entregrado &nbsp;<i class="fas fa-check-circle"></i></h5>
													</div>
													<div>
														<p class="card-text text-center"><strong>Apertura de expediente</strong></p>
													</div>
													<hr>
													<div class="card-body">
														<p class="card-text text-center">Entregrado sin observaciones.</p>
													</div>
													<div class="card-footer text-muted">
														2 days ago
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="tab-pane fade show active" id="business" role="tabpanel" >
												<div class="card text-center">
													<div class="card-header">
														<h5 class="card-title">Entregrado &nbsp;<i class="fas fa-check-circle"></i></h5>
													</div>
													<div>
														<p class="card-text text-center"><strong>Perfil de proyecto</strong></p>
													</div>
													<hr>
													<div class="card-body">
														<p class="card-text text-center">Entregrado sin observaciones.</p>
													</div>
													<div class="card-footer text-muted">
														2 days ago
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="tab-pane fade show active" id="business" role="tabpanel" >
												<div class="card text-center">
													<div class="card-header">
														<h5 class="card-title">Entregrado &nbsp;<i class="fas fa-check-circle"></i></h5>
													</div>
													<div>
														<p class="card-text text-center"><strong>Control de asistencia</strong></p>
													</div>
													<hr>
													<div class="card-body">
														<p class="card-text text-center">Entregrado sin observaciones.</p>
													</div>
													<div class="card-footer text-muted">
														2 days ago
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="tab-pane fade show active" id="#" role="tabpanel">
												<div class="card text-center">
													<div class="card-header">
														<h5 class="card-title">Pendiente &nbsp;<i class="fas fa-exclamation-circle"></i></h5>
													</div>
													<div>
														<p class="card-text text-center"><strong>Carta de finalización</strong></p>
													</div>
													<hr>
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
					</div>
				</div>
			</section>
		</div>
	</section>
	@endsection
