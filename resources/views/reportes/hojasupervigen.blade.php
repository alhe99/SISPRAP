<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hoja De Supervisión Por Municipio</title>
    <link rel="stylesheet" href="{{asset('css/bmd.css')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-favicon.png') }}">
    <style>
    .bg-header{background-color:#F8EFB6}
    .font-normal{font-weight: normal;}
    table{width:100%;page-break-inside: avoid;}
</style>
</head>
<body style="background-color:white">
    <header>
        <div class="row text-center">
            <div class="col-md-8">
               <img class="img-fluid" width="450" src="{{asset('images/header_reportes.PNG')}}">
           </div>
       </div>
   </header>
   <div>
    <div class="row">
        <div class="col-md-12">
            <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
            <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
            <p class="text-center" style="font-size:small;" ><strong><ins>SUPERVISIONES A INSTITUCIONES POR MUNICIPIO</ins></strong></p><br>
            <p class="text-center" style="font-size:small;" ><strong>AÑO {{ date('Y') }}</strong></p>
        </div>
    </div>
    <div class="col-md-12">
        <table border="1px" cellpadding="10" width="100%">
            <thead class="bg-header font-weight-bold">
                <tr>
                    <th>Nombre de institución / empresa</th>
                    <th>Municipio / Departamento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($instituciones as $item)
                <tr>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->municipio->nombre." / ".$item->municipio->departamento->nombre }}</td>
                </tr>
                @endforeach
                <tr class="bg-header font-weight-bold">
                    <td>Total de instituciones:</td>
                    <td>{{ count($instituciones) }}</td>
                </tr>
            </tbody>
        </table>
        <h4></h4>
    </div>
</div>
</body>
</html>