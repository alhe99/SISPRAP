<aside class="left-sidebar" style="zoom:100%">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile" style="background: url(images/bg-01.jpg)no-repeat;background-size: cover;">
            <!-- User profile image -->
            <div class="profile-img"> <img src="images/users/1.png" alt="user" /> </div>
            <!-- User profile text-->
            <div class="profile-text">
                <a href="#" class="dropdown-toggle u-dropdown text-truncate" data-toggle="dropdown" role="button"
                    aria-haspopup="true" aria-expanded="true">{{Auth::user()->nombre }}
                </a>
                <div class="dropdown-menu" style="width:80%">
                    <a href="#" class="dropdown-item"><i class="ti-user"></i>
                        &nbsp;Mi Cuenta
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"><i class="ti-settings"></i>
                        &nbsp;Configuraciones
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
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
                        <li><button type="button" @click="menu=1" class="btn btn-link  btn-colors">Publicación de
                                proyectos</button></li>
                        <li><button type="button" @click="menu=2" class="btn btn-link btn-colors">Preinscripciones
                                proyectos</button></li>
                        <li><button type="button" @click="menu=3" class="btn btn-link btn-colors">Proyectos en linea</button></li>
                        <li><button type="button" @click="menu=4" class="btn btn-link btn-colors">Gestión proyectos</button></li>
                    </ul>
                </li>
                <li><a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-city"></i><span
                            class="hide-menu">Instituciones</span></a>
                    <ul aria-expanded="false" class="collapse ">
                        <li><button type="button" @click="menu=5" class="btn btn-link btn-colors">Control instituciones</button></li>
                        <li style="margin-left: -5px;"> <a class="has-arrow waves-effect waves-dark " aria-expanded="false"><i
                                    class="mdi mdi-clipboard-text"></i><span class="hide-menu">Hojas de supervisión
                                    &nbsp;&nbsp;&nbsp;</span></a>
                            <ul aria-expanded="false" class="collapse ">
                                <li><button type="button" @click="menu=6" class="btn btn-link  btn-colors">General</button></li>
                                <li><button type="button" @click="menu=14" class="btn btn-link btn-colors">Por
                                        Institución</button></li>
                            </ul>
                        </li>
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
                                    class="mdi mdi-clipboard-text"></i><span class="hide-menu">Reportes de SS-PP
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
                                <li><button type="button" @click="menu=20" class="btn btn-link btn-colors">Informe
                                        general</button></li>
                            </ul>
                        </li>
                        <li style="margin-left: -5px;"> <a class="has-arrow waves-effect waves-dark " aria-expanded="false"><i
                                    class="mdi mdi-clipboard-text"></i><span class="hide-menu">Reportes por Institucion
                                    &nbsp;&nbsp;&nbsp;</span></a>
                            <ul aria-expanded="false" class="collapse ">
                                <li><button type="button" @click="menu=11" class="btn btn-link  btn-colors">Informe
                                        general</button></li>
                                <li><button type="button" @click="menu=12" class="btn btn-link btn-colors">Por
                                        Institución</button></li>
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
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!--Formulario de cerrar sesion-->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    <!--Fin de formulario de cerrar sesion-->
    <!-- Bottom points-->
    {{-- <div class="sidebar-footer">
        <!-- item--><a href="" class="link" @click="menu=15" onclick="event.preventDefault();" data-toggle="tooltip"
            title="Settings"><i class="ti-settings"></i></a>
        <!-- item--><a href="http://sisprap.test/public" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
        <!-- item--><a href="{{ route('logout') }}"
    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
    class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
    --}}
    <!-- End Bottom points-->
</aside>
