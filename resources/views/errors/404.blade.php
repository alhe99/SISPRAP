<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-favicon.png') }}">
    <title>Error 404</title>
   <link href="css/admintemplate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
</head>
<body class="fix-header card-no-border">
    <section id="wrapper" class="error-page">
        <div class="error-box" style="background-image:url('{{ asset('images/errors/error-bg.jpg') }}');background{no-repeat center center #fff;} ;!important">
            <div class="error-body text-center">
                <h1>404</h1>
                <h3 class="text-uppercase font-weight-bold">Página No Encontrada</h3>

                @if(Auth::check())
                    @if (Auth::user()->rol_id > 2)
                        <a href="{{route('public')}}" class="button secondary">Regresar a Inicio</a> </div>
                    @else
                        <a href="{{route('main')}}" class="button secondary">Regresar a Inicio</a> </div>
                    @endif
                @else
                    <a href="{{route('showLogin')}}" class="button secondary">Regresar a Inicio</a> </div>
                @endif
              
            <footer class="footer text-center">© 2017 Material Pro.</footer>
        </div>
    </section>
    <script src="js/admintemplate.js"></script>
</body>
