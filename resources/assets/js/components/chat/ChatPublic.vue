<template>
	<div>
		<!-- <div class="row">
			<div class="col-md-12 d-flex text-center justify-content-center">
				<button type="button" class="btn btn-raised chat-btn" id="chat-circle">Chat con el administrador</button>
			</div>
		</div> -->
		<div class="chat-box">
			<div class="chat-box-header">
				<i class="mdi mdi-message"></i>&nbsp;Chat con el usuario administrador
				<span class="chat-box-toggle"><i class="mdi mdi-close"></i></span>
			</div>
			<div class="chat-box-body">
				<div class="chat-box-overlay">
				</div>
				<div class="chat-logs">

				</div>
			</div>
			<div class="chat-input">
				<form>
					<input type="text" id="chat-input" placeholder="Escribe tu msj"/>
					<button type="submit" class="chat-submit" id="chat-submit"><i class="mdi mdi-send"></i></button>
				</form>
			</div>
		</div>
	</div>
</template>