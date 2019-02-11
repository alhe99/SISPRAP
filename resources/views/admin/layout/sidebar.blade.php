<div id="modalUpd">
    <aside class="left-sidebar" style="zoom:100%">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- User profile -->
            <div class="user-profile" style="background: url(images/bg-01.jpg)no-repeat;background-size: cover;">
                <!-- User profile image -->
                <div class="profile-img"> <img src="images/users/1.png" alt="user" /> </div>
                <!-- User profile text-->
                <div class="profile-text">
                    <a href="#" class="text-truncate dropdown-toggle u-dropdown" data-toggle="dropdown" role="button"
                    aria-haspopup="true" aria-expanded="true"><span class="" style="color: #B0BEC5;">
                        {{Auth::user()->nombre }}</span>
                    </a>
                    <div class="dropdown-menu" style="width:80%">
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modalUpdateDatos"><i class="ti-user"></i>
                            &nbsp;Mi Cuenta
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" data-toggle="modal" data-target="#modalChangeYear" class="dropdown-item"><i class="mdi mdi-calendar-range"></i>
                            &nbsp;Año del sistema
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" data-toggle="modal" data-target="#exampleModal"
                        class="dropdown-item"><i class="fa fa-power-off"></i>
                        &nbsp;Cerrar Sesion
                    </a>
                </div>
            </div>
        </div>

        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">FUNCIONES</li>
                <li> <a class="has-arrow waves-effect waves-dark " aria-expanded="false"><i class="mdi mdi-file-check"></i><span
                    class="hide-menu">Control Proyectos</span></a>
                    <ul aria-expanded="false" class="collapse ">
                        <li><button type="button" @click="menu=1" class="btn btn-link  btn-colors">Publicación de proyectos</button></li>
                        <li><button type="button" @click="menu=3" class="btn btn-link btn-colors">Proyectos Internos</button></li>
                        <li><button type="button" @click="menu=23" class="btn btn-link  btn-colors">Proyectos Externos</button></li>
                        <li><button type="button" @click="menu=2" class="btn btn-link btn-colors">Preinscripciones proyectos</button></li>
                        <li><button type="button" @click="menu=22" class="btn btn-link btn-colors">Aprobaciones</button></li>
                        <li><button type="button" @click="menu=4" class="btn btn-link btn-colors">Gestión proyectos</button></li>
                    </ul>
                </li>
                <li><a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-city"></i><span
                    class="hide-menu">Instituciones</span></a>
                    <ul aria-expanded="false" class="collapse ">
                        <li><button type="button" @click="menu=5" class="btn btn-link btn-colors">Control instituciones</button></li>
                        <li><button type="button" @click="menu=20" class="btn btn-link btn-colors">Sector Institución</button></li>
                        <li><button type="button" @click="menu=6" class="btn btn-link btn-colors">Hojas de supervisión</button></li>
                    </ul>
                </li>
                <li>
                    <button type="button" @click="menu=7" aria-expanded="false" class="btn btn-link btn-field"><i class="mdi mdi-file-document-box btn-i"></i><span
                        class="hide-menu">Constancias</span></button>
                    </li>
                    <li><button type="button" @click="menu=8" class="btn btn-link btn-field"><i class="mdi mdi-marker-check btn-i"></i><span
                        class="hide-menu">Pago Arancel</span></button>
                    </li>
                    <li> <a class="has-arrow waves-effect waves-dark " aria-expanded="false"><i class="mdi mdi-clipboard-text"></i><span
                        class="hide-menu">Reportes</span></a>
                        <ul aria-expanded="false" class="collapse ">
                            <li style="margin-left: -5px;"> <a class="has-arrow waves-effect waves-dark " aria-expanded="false"><i
                                class="mdi mdi-clipboard-check"></i><span class="hide-menu">Reportes de SS-PP
                                &nbsp;&nbsp;&nbsp;</span></a>
                                <ul aria-expanded="false" class="collapse ">
                                    <li><button type="button" @click="menu=16" class="btn btn-link  btn-colors">Inicio de
                                    Procesos</button></li>
                                    <li><button type="button" @click="menu=17" class="btn btn-link btn-colors">Pendientes
                                    de inicio</button></li>
                                    <li><button type="button" @click="menu=18" class="btn btn-link btn-colors">Pendientes
                                    de finalización</button></li>
                                    <li><button type="button" @click="menu=19" class="btn btn-link btn-colors">Procesos
                                    culminados</button></li>
                                </ul>
                            </li>
                            <li style="margin-left: -5px;"> <a class="has-arrow waves-effect waves-dark " aria-expanded="false"><i
                                class="mdi mdi-clipboard-check"></i><span class="hide-menu">Reportes por Institución
                                </span></a>
                                <ul aria-expanded="false" class="collapse ">
                                    <li><button type="button" @click="menu=11" class="btn btn-link  btn-colors">Informe
                                    general</button></li>
                                    <li><button type="button" @click="menu=13" class="btn btn-link btn-colors">Supervisiones</button></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><button type="button" @click="menu=9" class="btn btn-link btn-field"><i class="mdi mdi-account-multiple btn-i"></i><span
                        class="hide-menu"> Usuarios</span></button>
                    </li>
                    <li><button type="button" @click="menu=10" class="btn btn-link btn-field"><i class="mdi mdi-file-multiple btn-i"></i><span
                        class="hide-menu"> Copias de seguridad</span></button>
                    </li>
                    <li><button type="button" class="btn btn-link btn-field"><i class="mdi mdi-alert-circle btn-i" style="color: #FF0000"></i>
                        <span class="hide-menu" style="color: #B0BEC5;"> Manual de Usuario</span></button>
                    </li>
                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!--Formulario de cerrar sesion-->
        <!--Fin de formulario de cerrar sesion-->
    </aside>
     @include('admin.layout.modalUpdateDatos')
     <modal_year :app_year="{{ env('APP_YEAR') }}"></modal_year>
</div>
