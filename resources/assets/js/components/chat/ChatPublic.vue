<template>
	<div>
		<div class="chat-box">
			<div class="chat-box-header">
				<i class="mdi mdi-message"></i>&nbsp;Chat con el usuario administrador
				<span class="chat-box-toggle"><i class="mdi mdi-close"></i></span>
			</div>
			<div class="chat-box-body">
				<div class="chat-box-overlay">
				</div>
				<div class="chat-logs" id="chat-box">
					<div v-for="message in messages" :key="message.id" :id="'cm-msg-'+message.id" :class="[message.usuario_id == user.id ? 'self' : 'user']" class="chat-msg">
						<div class="cm-msg-text">
							<div  v-text="message.mensaje"></div>
							<small class="text-muted text-center" v-text="message.created_at" :class="[message.usuario_id == user.id ? 'dateSelf' : 'dateUser']" ></small>
						</div>
					</div>
				</div>
			</div>
			<div class="chat-input">
				<input type="text" @keyup.enter="send" v-model="bodyMessage" id="chat-input" placeholder="Escribe tu msj"/>
				<button type="button" @click="send" style="cursor: pointer" :disabled="bodyMessage == ''" :class="[bodyMessage == '' ? 'disabled' : '']" class="chat-submit" id="chat-submit"><i class="mdi mdi-send"></i></button>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props:['user','messages'],
	data() {
		return {
			bodyMessage: '',
			token:document.head.querySelector('meta[name="csrf-token"]').content
		}
	},
	watch:{

	},
	methods:{
		send(){
			let me = this;
			me.$emit('sendmessage',{'mensaje': me.bodyMessage,'receiver_id': 0 , _token: me.token});
			me.bodyMessage = '';
		},
	},
};
</script>
<style>
	.dateSelf{
		position: absolute;
		margin-top: 9px;
		width: 125%;
		left: -33.30px;
	}
	.dateUser{
		left: 5px;
		position: absolute;
		margin-top: 9px;
	}
</style>