<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-favicon.png') }}">
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
  <link href="css/colors/purple-dark.css" id="theme" rel="stylesheet">
  <link rel='stylesheet' href='{{ asset('css/gijgo.min.css') }}'>
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
        <header class="topbar">
          <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-header">
              <a class="navbar-brand">
                <!-- Logo icon --><b>
                  <img src="images/logoITCHAL.png" style="cursor:pointer" @click="menu=0" height="50" alt="homepage" class="dark-logo" />
                  <!-- Light Logo icon -->
                  <img src="images/logoITCHAL.png" style="cursor:pointer" @click="menu=0" height="50" alt="homepage" class="light-logo" />
                </b>
                <!-- Logo text --><span>
                 <!-- dark Logo text -->
                 <img src="images/ITCHA.png" style="cursor:pointer" @click="menu=0" height="25" alt="homepage" class="dark-logo" />
                 <!-- Light Logo text -->
                 <img src="images/ITCHA.png" style="cursor:pointer" @click="menu=0" height="25" class="light-logo" alt="homepage" /></span>
               </a>
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

              <ul class="navbar-nav my-lg-0">
                @if (Auth::user()->rol_id == 1 and Auth::user()->id == 0)
                <notification v-on:getnotificactions="getNotifications" :notifications="notifications"></notification>
                @endif
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

                       @if (Auth::user()->rol_id == 1 and Auth::user()->id == 0)
                        <li><a style="cursor: pointer;" @click="menu=21"><i class="mdi mdi-square-inc-cash"></i> Recepción</a></li>
                       @endif
                       @if (Auth::user()->rol_id == 1 and Auth::user()->id == 0)
                       <li role="separator" class="divider"></li>
                       <li><a style="cursor: pointer;" @click="menu=15" ><i class="mdi mdi-plus-circle mdi-18px"></i> Más opciones</a></li>
                       @endif
                       <li role="separator" class="divider"></li>
                       <li><a style="cursor: pointer;" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-power-off">
                       </i> Cerrar sesion</a>
                     </li>
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
                {{Auth::user()->nombre}}, ¿Esta seguro(a) de cerrar Sesión?
              </div>
              <div class="contenido text-center">
                <i class="mdi mdi-help fa-4x"></i>
              </div>
              <div class="modal-body text-center">
                <button type="button" class="button red" data-dismiss="modal"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
                <button type="button" class="button blue"  onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="mdi mdi-login"></i>&nbsp;Cerrar sesión</button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form>
              </div>
            </div>
          </div>
        </div>

        @if (Auth::user()->rol_id == 1)
          @include('admin.layout.sidebar')
        @elseif(Auth::user()->rol_id == 2)
          @include('recepcion.layout.sidebar')
        @endif

        <div class="page-wrapper">
          <div class="container-fluid">
            <br>


            <div class="row">

              @yield('contenido')

            </div>

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
          </div>
          <footer class="footer"> © {{date("Y")}} Intituto Técnologico de Chalatenango - ITCHA-AGAPE </footer>
          <button id="btnFAB" class="right-side-toggle waves-effect waves-light btn-float rounded-circle  btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
        </div>

       </div>
     </div>
     <script src="js/app.js"></script>
     <script src="js/admintemplate.js"></script>
     <script src='{{ asset('js/gijgo.min.js') }}'></script>
     <script src='{{ asset('js/messages.es-es.js') }}'></script>
     <script src='{{ asset('js/year-select.js') }}'></script>
     {{-- Mensaje cuando un usuario ha actualizados sus datos personales --}}
     @if (session()->has('updateDataUser'))
     <script>
      $(function(){
       const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: true, timer: 5000});
       toast({
        type: 'success',
        title: 'Datos Actualizados Correctamente'
      });
     })
   </script>
   @endif
   <script>
     $(function(){

      $("#nombre").keyup(function(){
        if(($(this).val().trim() != '') && ($("#usuario").val().trim() != '')){
          $("#btnUpdateData").prop('disabled', false);
          $("#btnUpdateData").removeClass('disabled');
        }else{
          $("#btnUpdateData").prop('disabled', true);
          $("#btnUpdateData").addClass('disabled');
        }
      })
      $("#usuario").keyup(function(){
        if(($(this).val().trim() != '') && ($("#nombre").val().trim() != '')){
          $("#btnUpdateData").prop('disabled', false)
          $("#btnUpdateData").removeClass('disabled');
        }else{
          $("#btnUpdateData").prop('disabled', true);
          $("#btnUpdateData").addClass('disabled');
        }
      });

      $("#btnOpenPasswordsFields").on('click',function(){
        if(!$("#divPasswords").hasClass('show')){
             //Desabilitando el boton de guardar
             $("#btnUpdateData").prop('disabled', true);
             $("#btnUpdateData").addClass('disabled');

             $("#cPassword").keyup(function(){
              if($(this).val().trim() != $("#password").val().trim()){
                $("#divNoMatchPasswords").css('display','block');
                $("#btnUpdateData").prop('disabled', true);
                $("#btnUpdateData").addClass('disabled');
              }else if($(this).val().trim() == $("#password").val().trim()){
                $("#divNoMatchPasswords").css('display','none');
                $("#btnUpdateData").prop('disabled', false)
                $("#btnUpdateData").removeClass('disabled');
              }

              if($(this).val().trim() == ''){
               $("#btnUpdateData").prop('disabled', true);
               $("#btnUpdateData").addClass('disabled');
             }
           });

           }else {
            // Limpiando datos de password
            $("#password").val('');$("#cPassword").val('');
            if($("#btnUpdateData").hasClass('disabled')){
              if(($("#nombre").val().trim() != '') && ($("#usuario").val().trim() != '')){
               $("#btnUpdateData").prop('disabled', false)
               $("#btnUpdateData").removeClass('disabled');
             }
           }
         }
       });
    })
  </script>
</body>
</html>