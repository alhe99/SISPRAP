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
            <p class="text-center" style="font-size:small;" ><strong>AÑO {{ $anio }}</strong></p>
        </div>
    </div>
    <div class="col-md-12">
        @if (count($instituciones) > 0)
        <table border cellpadding="10" width="100%">
            <thead class="bg-header font-weight-bold">
                <tr class="text-center">
                    <th>Empresa / Institución</th>
                    <th>Dirección</th>
                    <th>Supervisor</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i <count($instituciones) ; $i++)
                    @foreach ($instituciones[$i] as $item)
                    <tr>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->direccion.", ".$item->municipio->nombre }}</td>
                        <td>
                            @foreach ($item->supervisores as $supervisor)
                            - {{ $supervisor->nombre}}
                            @if ($supervisor->no_telefono != '')
                                {{ substr($supervisor->no_telefono,0,4)."-".substr($supervisor->no_telefono,4,4) }}
                            @endif
                            <br>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                @endfor
            </tbody>
        </table>
        @else
        <div class="row">
            <div class="col-md-12 text-center">
                <span class="font-weight-bold">No hay datos disponibles con los parámetros de busquéda</span>
            </div>
        </div>
        @endif
        <h4></h4>
    </div>
</div>
</body>
</html>