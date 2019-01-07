<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile" style="background: url(images/background/user-info.jpg) no-repeat;">
            <!-- User profile image -->
            <div class="profile-img"> <img src="images/users/recep.png" alt="user" /> </div>
            <!-- User profile text-->
            <div class="profile-text"> <a href="#" class="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{Auth::user()->nombre}}</a>
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
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
   {{--  <div class="sidebar-footer">
        <!-- item--><a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
        <!-- item--><a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
        <!-- item--><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form> --}}
        <!-- End Bottom points-->
</aside>