require('./bootstrap');
window.Vue = require('vue');


Vue.component('chat_public', require('./components/chat/ChatPublic.vue'));

const app = new Vue({
	el: '#public',
	data: {
		notifications: [],
		arrayMessages: []
	},
	methods:{
		getMessages(){
			let me = this;
			var url = route('getMessagesStudent');
			axios.get(url).then(function(response) {
				var respuesta = response.data;
				me.arrayMessages = respuesta
			})
			.catch(function(error) {
				console.log(error);
			});
		},
		sendMessage(message){
			let me = this;
			var url = route('messages.store',message);
			axios.post(url).then(function(response) {
				me.bodyMessage = '';
				var respuesta = response.data;
				me.arrayMessages.push(respuesta.message);
				setTimeout(me.scrollToEnd,0.10);
			})
			.catch(function(error) {
				console.log(error);
			});
		},
		scrollToEnd(){
			document.getElementById('chat-box').scrollTo(0,99999);
		},
	},
	mounted(){
		this.getMessages();
		// Echo.private('chat').listen('MessageSentEvent', (e) => {
		// 	alert(e.user.estudiante.nombre);
		// });
	}
});