<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SISPRAP || Public</title>
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo-favicon.png')}}">
  <link rel="stylesheet" href="{{asset('other/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/fontawesome-all.min.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/material.min.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/ripples.min.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/owl.carousel.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/magnific-popup.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/slicknav.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/animate.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/responsive.css')}}">
  <link rel="stylesheet" href="{{asset('css/publicTemplate.css')}}">
  <link rel="stylesheet" href="{{asset('other/css/normalize.min.css')}}">
  <link rel="stylesheet" type="text/css" media="screen" href="{{asset('other/css/indigo.css')}}">
  <link rel='stylesheet' href='{{ asset('other/css/gijgo.min.css') }}'>
</head>
<body>

  <!-- error section -->
  <section class="Material-error-section section-padding">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h1 class="wow animated fadeInRight" data-wow-delay=".2s">404</h1>
          <h2 class="wow animated fadeInRight" data-wow-delay=".4s">Pagina no encontrada! :(</h2>
          <a href="javascript:void(0)" class="wow animated fadeInUp btn btn-common mt-5" data-wow-delay=".6s"><i class="material-icons mdi mdi-home"></i>Regresar a Inicio<div class="ripple-container"></div></a>
        </div>
      </div>
    </div>
  </section>
  <!-- error section end -->

  <!--Footer-->
  <footer class="page-footer center-on-small-only  pt-0 footer-widget-container">
    <div class="container pt-3 mb-3">
     <div class="row">
      <div class="col-md-6 col-lg-6 col-xl-6 footer-contact-widget">
       <h3 class="footer-title">MISION</h3>
       <p class=" text-justify">Formar profesionales técnicos con responsabilidad ciudadana, pensamiento crítico, con sensibilidad a la investigación
        y al desarrollo tecnológico, a través de un proceso educativo que integra aspectos académicos, procedimentales y actitudinales,
      fortalecidos con un sistema de gestión de calidad, para promover el desarrollo social sostenible de nuestro país.</p>
    </div>
    <div class="col-md-5 col-lg-5 col-xl-5 recent-widget">
     <h3 class="footer-title">VISION</h3>
     <p class="text-justify">Ser una institución educativa referente en la formación de profesionales en áreas tecnológicas con competencias para
      la vida y el trabajo, con sensibilidad humana; que se incorporen responsablemente al desarrollo productivo sostenible
    del país.</p>
  </div>
</div>
</div>
<div class="footer-copyright">
 <div class="container">
  <div class="row">
   <div class="col-md-12 text-center">
    <p>Instituto Tecnologico de Chalatenago &copy; {{date("Y")}}</p>
  </div>
</div>
</div>
</div>
<a href="#" class="back-to-top">
 <div class="ripple-container" style="float:left;"></div>
 <i class="mdi mdi-arrow-up">
 </i>
</a>
<div id="preloader" >
 <div class="loader"  id="loader-1"></div>
</div>

</footer>

<script src="{{asset('other/js/jquery-min.js')}}"></script>
<script src="{{asset('other/js/popper.min.js')}}"></script>
<script src="{{asset('other/js/bootstrap.min.js')}}"></script>
<script src="{{asset('other/js/jquery.mixitup.min.js')}}"></script>
<script src="{{asset('other/js/jquery.inview.js')}}"></script>
<script src="{{asset('other/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('other/js/scroll-top.js')}}"></script>
<script src="{{asset('other/js/smoothscroll.js')}}"></script>
<script src="{{asset('other/js/material.min.js')}}"></script>
<script src="{{asset('other/js/ripples.min.js')}}"></script>
<script src="{{asset('other/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('other/js/form-validator.min.js')}}"></script>
<script src="{{asset('other/js/contact-form-script.min.js')}}"></script>
<script src="{{asset('other/js/wow.js')}}"></script>
<script src="{{asset('other/js/jquery.vide.js')}}"></script>
<script src="{{asset('other/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('other/js/jquery.slicknav.js')}}"></script>
<script src="{{asset('other/js/main.js')}}"></script>
<script src="{{asset('other/js/scripts.js')}}"></script>
<script src="{{asset('js/publicTemplate.js')}}"></script>
<script src="{{asset('other/js/jquery.mask.min.js')}}"></script>
<script src='{{ asset('other/js/gijgo.min.js') }}'></script>
<script src='{{ asset('other/js/messages.es-es.js') }}'></script>
@routes @yield('page_script')

</body>
</html>