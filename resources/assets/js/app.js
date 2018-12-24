require('./bootstrap');
window.Vue = require('vue');

//Vue-Select para el manejo de datos en varias formas
import vSelect from 'vue-select';
Vue.component('v-select', vSelect);

import jquery from 'vue-jquery';

Vue.use(jquery);

import ImgInputer from 'vue-img-inputer';
import 'vue-img-inputer/dist/index.css';

Vue.component('ImgInputer', ImgInputer);

import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
Vue.use(VueFormWizard);

import Datetime from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'

Vue.use(Datetime)

var VueTruncate = require('vue-truncate-filter')
Vue.use(VueTruncate)

//Import Tab content
import Tabs from 'vue-tabs-component';
Vue.use(Tabs);

//Import Vue Toaster
import VueToastr from '@deveodk/vue-toastr'
import '@deveodk/vue-toastr/dist/@deveodk/vue-toastr.css'
Vue.use(VueToastr)

import es from 'vee-validate/dist/locale/es';
import VeeValidate, { Validator } from 'vee-validate';
Vue.use(VeeValidate);
Validator.localize('es', es);


Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader.vue'));
Vue.component('institucion', require('./components/instituciones/Institucion.vue'));
Vue.component('publiproject', require('./components/proyectos/Publicacion.vue'));
Vue.component('lineaproject', require('./components/proyectos/Linea.vue'));
Vue.component('usuarios', require('./components/proyectos/Usuarios.vue'));
Vue.component('notification', require('./components/Notification.vue'));
Vue.component('preregister', require('./components/proyectos/Preinscripciones.vue'));
Vue.component('pagoarancel', require('./components/recepcion/PagoArancel.vue'));
Vue.component('pagoaranceladmin', require('./components/arancel/PagoArancelAdmin.vue'));
Vue.component('hojasupervgeneral', require('./components/reportes/HojaSupervGeneral.vue'));
Vue.component('general', require('./components/reportes/InstitucionGeneral.vue'));
Vue.component('supervision', require('./components/reportes/SupervisionesInst.vue'));
Vue.component('carrinst', require('./components/reportes/CarrerasInst.vue'));
Vue.component('regsuperv', require('./components/reportes/RegistrarSupervision.vue'));
Vue.component('configuracion', require('./components/mantenimientos/configuracion.vue'));
Vue.component('inicioproceso', require('./components/reportes/SSPP/InicioProceso.vue'));
Vue.component('pendientesinicio', require('./components/reportes/SSPP/PendienteInicio.vue'));
Vue.component('pendientefin', require('./components/reportes/SSPP/PendienteFin.vue'));
Vue.component('culminados', require('./components/reportes/SSPP/ProcesoCulminado.vue'));
Vue.component('configuracion', require('./components/mantenimientos/configuracion.vue'));
Vue.component('gestproy', require('./components/proyectos/GestionProyectos.vue'));
Vue.component('constancias', require('./components/proyectos/Constancias.vue'));


const app = new Vue({
    el: '#app',
    data: {
        menu: 0,
        notifications: []
    },
    created() {
        let me = this;
        axios.post('notifications/get').then(function(response) {
            me.notifications = response.data;

        }).catch(function(error) {
            console.log(error);
        });


        var userId = 0;

        Echo.private('App.User.' + userId).notification((notification) => {
            me.notifications.unshift(notification);
            this.$toastr('add', {
                title: 'Nueva Notificacion',
                msg: 'Tienes una Nueva Preinscripción',
                timeout: 5000,
                position: 'toast-bottom-right',
                type: 'success',
                clickClose: true,
                closeOnHover: false
            });
        });
    }
});