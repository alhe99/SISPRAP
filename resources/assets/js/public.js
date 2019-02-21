require('./bootstrap');
window.Vue = require('vue');


Vue.component('chat_public', require('./components/chat/ChatPublic.vue'));

const app = new Vue({
    el: '#public',
    data: {
        menu: 0,
        notifications: [],
    },
    created() {
      //
    },
});