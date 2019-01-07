<template>
    <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i style="font-size: 22px" class="mdi mdi-bell"></i>
                <span class="badge badge-secondary">{{notifications.length}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                <ul>
                    <li>
                        <div class="drop-title">Notificaciones</div>
                    </li>
                    <li >
                        <div v-if="notifications.length" class="message-center">
                            <a  v-for="item in listar" :key="item.id" href="#">
                                <div class="btn btn-danger btn-circle"><span class="badge badge-secondary">{{item.data.cantidad}}</span></div>
                                <div class="mail-contnet">
                                    <h6>{{item.data.msj.substring(0,36)}} <strong>{{ item.data.msj.substring(37,60) }}</strong></h6><span class="time">{{item.data.fecha}}</span> </div>
                            </a>
                        </div>
                         <div v-else class="message-center">
                            <a href="#" class="text-center"><span> No tiene Notificaciones</span></a>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link text-center" @click="menu=2"><strong>Ver todas las preinscripciones</strong> <i class="fa fa-angle-right"></i> </a>
                    </li>
                </ul>
            </div>
        </li>
</template>
<script>
export default {
	props : ['notifications'],
    data (){
        return {
           arrayNotifications: [],
        }
    },
    computed: {
       listar: function()  {
            //return this.notifications[0];
             this.arrayNotifications = Object.values(this.notifications[0]);
            if (this.notifications == '') {
                    return this.arrayNotifications = [];
            } else {
                //Capturo la ultima notificación agregada
                this.arrayNotifications = Object.values(this.notifications[0]);
                //Validación por indice fuera de rango
                if (this.arrayNotifications.length > 3) {
                    //Si el tamaño es > 3 Es cuando las notificaciones son obtenidas desde el mismo servidor, es decir por la consulta con AXIOS
                    return Object.values(this.arrayNotifications[4]);
                } else {
                    //Si el tamaño es < 3 Es cuando las notificaciones son obtenidas desde el canal privado, es decir mediante Laravel Echo y Pusher
                    return Object.values(this.arrayNotifications[0]);
                }
            }
        }

    },
    methods:{
         loadComponent: function(){
            $("body").load( "./proyectos/Preinscripciones.vue");
        },
    }
}
</script>
