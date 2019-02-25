require('./bootstrap');
window.Vue = require('vue');

//Import Vue Toaster
import VueToastr from '@deveodk/vue-toastr'
import '@deveodk/vue-toastr/dist/@deveodk/vue-toastr.css'
Vue.use(VueToastr)

Vue.component('chat_public', require('./components/chat/ChatPublic.vue'));

const app = new Vue({
    el: '#public',
    data: {
        notifications: [],
        arrayMessages: [],
        msj_unread: '',
        token: document.head.querySelector('meta[name="csrf-token"]').content,
        user_id: document.head.querySelector('meta[name="user-id"]').content
    },
    methods: {
        getMessages() {
            let me = this;
            var url = route('getMessagesStudent');
            axios.get(url).then(function(response) {
                    var respuesta = response.data;
                    me.arrayMessages = respuesta.messages;
                    setTimeout(me.scrollToEnd, 0.05);
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
        showNotification() {
            this.$toastr('add', {
                title: 'Nuevo mensaje',
                msg: 'Tienes un nuevo mensaje del usuario administrador',
                timeout: 8000,
                position: 'toast-bottom-left',
                type: 'info',
                clickClose: true,
                closeOnHover: false
            });
        }
    },
    mounted() {
        let me = this;
        me.getMessages();

        $("#btn-fab").on("mouseenter", function() {
            me.msj_unread > 0 ? $("#span-ppal").css('display', 'none') : '';
        }).on("mouseleave", function() {
            me.msj_unread > 0 ? $("#span-ppal").css('display', 'inline') : '';
        });

        $(".chat-btn").click(function() {
            me.msj_unread > 0 ? me.setReadMessages() : '';
        });
    },
    created() {
        let me = this;
        Echo.private('messages.' + me.user_id).listen('MessageSentEventStudent', (e) => {
            me.getMessages();
            $("#container-chat").hasClass('show') ? me.setReadMessages() : me.showNotification();
        });
    },
});