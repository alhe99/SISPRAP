require('./bootstrap');
window.Vue = require('vue');


Vue.component('chat_public', require('./components/chat/ChatPublic.vue'));

const app = new Vue({
    el: '#public',
    data: {
        notifications: [],
        arrayMessages: [],
        msj_unread: '',
        token: document.head.querySelector('meta[name="csrf-token"]').content
    },
    methods: {
        getMessages() {
            let me = this;
            var url = route('getMessagesStudent');
            axios.get(url).then(function(response) {
                    var respuesta = response.data;
                    me.arrayMessages = respuesta.messages;
                    me.msj_unread = respuesta.messages_unread;
                    me.showSpan();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        showSpan() {
            let me = this;
            if (me.msj_unread > 0) {
                $(".span-chat").css('display', 'inline');
                $(".span-chat").text(me.msj_unread)
            } else {
                $(".span-chat").css('display', 'none');
            }
        },
        sendMessage(message) {
            let me = this;
            var url = route('messages.store', message);
            axios.post(url).then(function(response) {
                    me.bodyMessage = '';
                    var respuesta = response.data;
                    me.arrayMessages.push(respuesta.message);
                    setTimeout(me.scrollToEnd, 0.10);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        scrollToEnd() {
            document.getElementById('chat-box').scrollTo(0, 99999);
        },
        setReadMessages() {
            let me = this;
            var url = route('setReadMessageEstudent', { _token: me.token });
            axios.post(url).then(function(response) {
                me.getMessages();
            }).catch(function(error) {
                console.log(error);
            });
        },
    },
    mounted() {
        let me = this;
        me.getMessages();
        // Echo.private('chat').listen('MessageSentEvent', (e) => {
        // 	alert(e.user.estudiante.nombre);
        // });
        $("#btn-fab").on("mouseenter", function() {
            me.msj_unread > 0 ? $("#span-ppal").css('display', 'none') : '';
        }).on("mouseleave", function() {
            me.msj_unread > 0 ? $("#span-ppal").css('display', 'inline') : '';
        });

        $(".chat-btn").click(function() {
            me.msj_unread > 0 ? me.setReadMessages() : '';
        });
    },
});