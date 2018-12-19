<!DOCTYPE html>
<html lang="es">

<head>
  <title>SISPRAP || Ingresar</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" sizes="16x16" href="images/logo-favicon.png">
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
  <link rel="stylesheet" type="text/css" href="css/loginTemplate.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

  <div class="limiter">
    <div class="container-login100" style="background-image:url('{{ asset('images/bg-01.JPG') }}');background{position:absolute; z-index:1; width:100%; height:100%;no-repeat};background-size: cover">
      @yield('login')
    </div>
  </div>
  <script src="js/loginTemplate.js"></script>
</body>

</html>