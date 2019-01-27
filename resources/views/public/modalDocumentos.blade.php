<div id="modalDocumentos">
	<div class="modal" id="modal" tabindex="-4" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document" style="margin-top: 60px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-center" id="exampleModalLongTitle" style="display: block; margin-left: auto; margin-right: auto;">Descargar documentos asociados a tu proceso</h5>
					<button type="button" class="close" style="cursor: pointer;" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 col-lg-12 col-xs-12">
							<div class="Material-tab">
								<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
									<li class="nav-item" >
										<a class="nav-link active" id="PP" data-toggle="tab" href="#business" role="tab"><i class="far fa-user"></i></br>Perfil del proyecto</a>
									</li>
									<li class="nav-item" >
										<a class="nav-link" id="CP" data-toggle="tab" href="#startup" role="tab"><i class="far fa-list-alt"></i></br>Control de proyecto</a>
									</li>
									<li class="nav-item" >
										<a class="nav-link" id="CH" data-toggle="tab" href="#agency" role="tab"><i class="fa fa-calendar-alt"></i></br>Control de asistencia</a>
									</li>
								</ul>
								<div class="tab-content">
									@if (Auth::user()->estudiante->no_proyectos == 2)
									<div class="row">
										<div class="col-md-12">
											<select v-model="proyectoSelected" class="custom-select show-tick col-md-6" name="select_proyectos" id="select_proyectos">
												<option value="" selected disabled>Seleccione Proyecto</option>
												<option v-for="item in arrayProyectos" :key="item.gestionId" v-text="item.proyecto.nombre" :value="item.gestionId"></option>
											</select>
										</div>
									</div><br>
									@endif
									<div class="tab-pane fade show active" id="business" role="tabpanel" >
										<div class="card text-center">
											<div class="card-header">
												<h6 class="card-title">Presiona en el botón para descargar</h6>
											</div>
											@if (Auth::user()->estudiante->proceso_actual == 'I')
											<div class="card-body">
												@if (Auth::user()->estudiante->no_proyectos == 2)
													<button type="button" @click.prevent="downloadDocs('P')"
														class="btn btn-primary" :disabled="proyectoSelected == ''" style="cursor: pointer;"><i class="mdi mdi-cloud-download" ></i>&nbsp;Descargar</button>
													</div>
												@else
													<button type="button" @click.prevent="downloadDocs('P')"
														class="btn btn-primary" style="cursor: pointer;"><i class="mdi mdi-cloud-download" ></i>&nbsp;Descargar</button>
													</div>
												@endif
												@elseif (session('downloadDocs') == false)
												<div class="card-body">
													<h5 class="text-center text-danger">No has iniciado tu proceso, la descarga estará disponible hasta que inicies {{ session('process_name') }}!</h5>
												</div>
												@endif
											</div>
										</div>
										<div class="tab-pane fade" id="startup" role="tabpanel" >
											<div class="card text-center">
												<div class="card-header">
													<h6 class="card-title">Presiona en el botón para descargar</h6>
												</div>
												@if (Auth::user()->estudiante->proceso_actual == 'I')
												<div class="card-body">
													@if (Auth::user()->estudiante->no_proyectos == 2)
														<button type="button" @click.prevent="downloadDocs('CP')"
															class="btn btn-primary" :disabled="proyectoSelected == ''" style="cursor: pointer;"><i class="mdi mdi-cloud-download" ></i>&nbsp;Descargar</button>
														</div>
													@else
														<button type="button" @click.prevent="downloadDocs('CP')"
															class="btn btn-primary" style="cursor: pointer;"><i class="mdi mdi-cloud-download" ></i>&nbsp;Descargar</button>
														</div>
													@endif
													@elseif (session('downloadDocs') == false)
													<div class="card-body">
														<h5 class="text-center text-danger">No has iniciado tu proceso, la descarga estará disponible hasta que inicies {{ session('process_name') }}!</h5>
													</div>
													@endif
												</div>
											</div>
											<div  class="tab-pane fade" id="agency" role="tabpanel">
												<div class="card text-center">
													<div class="card-header">
														<h6 class="card-title">Presiona en el botón para descargar</h6>
													</div>
													@if (Auth::user()->estudiante->proceso_actual == 'I')
													<div class="card-body">
														@if (Auth::user()->estudiante->no_proyectos == 2)
															<button type="button" @click.prevent="downloadDocs('CH')"
																class="btn btn-primary" :disabled="proyectoSelected == ''" style="cursor: pointer;"><i class="mdi mdi-cloud-download" ></i>&nbsp;Descargar</button>
															</div>
														@else
															<button type="button" @click.prevent="downloadDocs('CH')"
																class="btn btn-primary" style="cursor: pointer;"><i class="mdi mdi-cloud-download" ></i>&nbsp;Descargar</button>
															</div>
														@endif
														@elseif (session('downloadDocs') == false)
														<div class="card-body">
															<h5 class="text-center text-danger">No has iniciado tu proceso, la descarga estará disponible hasta que inicies {{ session('process_name') }}!</h5>
														</div>
														@endif
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
			</div>
			<script src="{{asset('js/publicTemplate.js')}}"></script>
			<script src="{{asset('other/js/jquery-min.js')}}"></script>
			@if (Request::path() != 'public')
			@routes @yield('page_script')
			@endif
			<script type="text/javascript">
				var app = new Vue({
					el : '#modalDocumentos',
					data : {
						arrayProyectos: [],
						proyectoSelected: '',
					},
					watch: {
						proyectoSelected: function(){
							// alert("Cambio");
						},
					},
					methods: {
						getProyectosRealizados() {
							let me = this;
							var url;
							if(window.location.href == 'http://sisprap.test/public'){
								url = "getActualGestionProyectos";
							}else{
								url = route("getActualGestionProyectos");
							}
							axios.get(url).then(function(response) {
								var respuesta = response.data;
								me.arrayProyectos = respuesta;
							})
							.catch(function(error) {
								console.log(error);
							});
						},
						downloadDocs(tipoDocumento){
							$('#preloader').fadeIn();
							var url = route('downloadDocs',{'gestionId':this.proyectoSelected,'tipoDoc':tipoDocumento})
							window.open(url,"_self");
							this.proyectoSelected = '';
							$('#preloader').fadeOut();
						}
					},
					mounted(){
						this.getProyectosRealizados();
					}
				});
			</script>
