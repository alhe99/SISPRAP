<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile" style="background: url(images/background/user-info.jpg) no-repeat;">
            <!-- User profile image -->
            <div class="profile-img"> <img src="images/users/recep.png" alt="user" /> </div>
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
                <li>
                <button type="button" @click="menu=1"  aria-expanded="false" class="btn btn-link btn-field"><i class="mdi mdi-square-inc-cash btn-i"></i><span class="hide-menu"> Pago de Arancel</span></button>
                </li>
                <li class="nav-devider"></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</aside>

@include('admin.layout.modalUpdateDatos')