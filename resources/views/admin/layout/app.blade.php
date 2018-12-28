<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/logo-favicon.png">
    @if (Auth::user()->rol_id == 1)
    <title>SISPRAP || Admin</title>
    @elseif(Auth::user()->rol_id == 2)
    <title>SISPRAP || Recepción</title>
    @endif
    <!-- Bootstrap Core CSS -->
    <link href="css/admintemplate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    <link href="css/radioBtn.css" rel="stylesheet">
    <link href="css/colors/purple-dark.css" id="theme" rel="stylesheet">f
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Id for Channel Notification -->
    <meta name="userId" content="0">
</head>

<body class="fix-header fix-sidebar card-no-border ">
    @routes
    <div id="app">
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
            </div>
            <div id="main-wrapper">
                <!-- ============================================================== -->
                <!-- Topbar header - style you can find in pages.scss -->
                <!-- ============================================================== -->
                <header class="topbar">
                    <nav class="navbar top-navbar navbar-expand-md navbar-light">
                        <!-- ============================================================== -->
                        <!-- Logo -->
                        <!-- ============================================================== -->
                        <div class="navbar-header">
                            <a class="navbar-brand" href="/">
                                <!-- Logo icon --><b>
                                    <img src="images/logoITCHAL.png" height="50" alt="homepage" class="dark-logo" />
                                    <!-- Light Logo icon -->
                                    <img src="images/logoITCHAL.png" height="50" alt="homepage" class="light-logo" />
                                </b>
                                <!-- Logo text --><span>
                                 <!-- dark Logo text -->
                                 <img src="images/ITCHA.png" height="25" alt="homepage" class="dark-logo" />
                                 <!-- Light Logo text -->
                                 <img src="images/ITCHA.png" height="25" class="light-logo" alt="homepage" /></span> </a>
                             </div>
                             <!-- ============================================================== -->
                             <!-- End Logo -->
                             <!-- ============================================================== -->
                             <div class="navbar-collapse">
                                <!-- ============================================================== -->
                                <!-- toggle and nav items -->
                                <!-- ============================================================== -->
                                <ul class="navbar-nav mr-auto mt-md-0">
                                    <!-- This is  -->
                                    <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                                    <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                                    <!-- ============================================================== -->
                                </ul>
                                <!-- ============================================================== -->
                                <!-- User profile and search -->
                                <!-- ============================================================== -->
                                <ul class="navbar-nav my-lg-0">
                                    <!-- ============================================================== -->
                                    <!-- Comment -->
                                    @if (Auth::user()->rol_id == 1)
                                    <!-- ============================================================== -->
                                    <notification :notifications="notifications"></notification>
                                    <!-- ============================================================== -->
                                    <!-- End Comment -->
                                    <!-- ============================================================== -->
                                    <!-- ============================================================== -->
                                    <!-- Messages -->
                        <!-- ==============================================================
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-email"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-right scale-up" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/profile.png" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span> </div>
                                            </a>

                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/profile.png" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span> </div>
                                            </a>

                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/profile.png" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span> </div>
                                            </a>

                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/profile.png" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}
                        @endif

                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            @if (Auth::user()->rol_id == 1)
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/users/1.png" alt="user" class="profile-pic" /></a>
                            @elseif(Auth::user()->rol_id == 2)
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/users/recep.png" alt="user" class="profile-pic" /></a>
                            @endif
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box text-center">
                                           @if (Auth::user()->rol_id == 1)
                                           <div class="u-img text-center"><img class="text-center" src="images/users/1.png" alt="user"></div>
                                           @elseif(Auth::user()->rol_id == 2)
                                           <div class="u-img text-center"><img src="images/users/recep.png" class="text-center" alt="user"></div>
                                           @endif
                                           <div class="u-text text-center ">
                                             <h4 class="text-center text-primary"><em>{{Auth::user()->nombre}}</em></h4>
                                         </div>
                                     </li>
                                     <li role="separator" class="divider"></li>
                                   {{-- <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                                   <li><a href="#"><i class="ti-email"></i> Inbox</a></li> --}}
                                   <li role="separator" class="divider"></li>
                                   <li><a href="#"><i class="ti-settings"></i> Configuraciones de Cuenta</a></li>
                                   <li role="separator" class="divider"></li>
                                   <li><a href="{{ route('logout') }}" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-power-off">
                                   </i> Cerrar sesion</a>
                                   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>

                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="modal" id="exampleModal" tabindex="-4" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top: 60px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                {{Auth::user()->nombre}}, ¿Esta seguro de cerrar Sesion?
            </div>
            <div class="contenido text-center">
                <i class="mdi mdi-help fa-4x"></i>
            </div>
            <div class="modal-body text-center">
                <button type="button" class="button red" data-dismiss="modal">Cancelar</button>
                <button type="button" class="button blue"  onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar session</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
@if (Auth::user()->rol_id == 1)
@include('admin.layout.sidebar')
@elseif(Auth::user()->rol_id == 2)
@include('recepcion.layout.sidebar')
@endif
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Home</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">
                    <div class="">
                        <button class="right-side-toggle waves-effect waves-light btn-float rounded-circle  btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Column -->

            @yield('contenido')

        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <div class="right-sidebar">
            <div class="slimscrollright">
                <div class="rpanel-title"> Personalización <span><i class="ti-close right-side-toggle"></i></span> </div>
                <div class="r-panel-body">
                    <ul id="themecolors" class="m-t-20">
                        <li><b>Colores disponibles</b></li>
                        <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                        <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                        <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                        <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                        <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                        <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                        <li class="d-block m-t-30"><b>Colores al menu lateral</b></li>
                        <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                        <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                        <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                        <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                        <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                        <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer"> © {{date("Y")}} Intituto Técnologico de Chalatenango - ITCHA-APAGE </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
</div>
<script src="js/app.js"></script>
<script src="js/admintemplate.js"></script>
</body>

</html>