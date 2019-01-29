<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supervisión {{ $proceso }}</title>
    <link rel="stylesheet" href="{{public_path('css/bmd.css')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ public_path('images/logo-favicon.png') }}">
    <style>
    .bg-header{background-color:#F8EFB6}
    .font-normal{font-weight: normal;}
    table{width:100%;page-break-inside: avoid;}
    thead { display : table-row-group ;}
    tfoot { display : table-row-group }
</style>

</head>
<body style="background-color:white">
    <div>
        <div class="col-md-12">
            @if (count($institucionesGeneral) > 0)
            <br><table border cellpadding="10" width="100%">
                <thead class="font-weight-bold">
                    <tr class="text-center">
                        <th>Empresa / Institución</th>
                        <th>Dirección</th>
                        <th>Supervisor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($institucionesGeneral as $item)
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
        {{-- TABLA PARA DETALLE DE LA EMPRESA --}}
        {{-- <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br> --}}
        {{-- <br><br> --}}
        <table style='page-break-after:always;'></table>
        {{-- @include('reportes.header_reportes') --}}
        <div class="col-md-12">
            <table border="2px" cellpadding="10" cellspacing="10" width="100%" >
                <thead class="font-weight-bold">
                    <tr class="text-center">
                        <th>Empresa / Institución</th>
                        <th>Alumno(a)</th>
                        <th>Observaciones</th>
                        <th>Firma y Sello Supervisor</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < count($institucionDetalle) ; $i++)
                    <tr>
                        <td width="22%" height="{{ count($institucionDetalle[$i][1]) <= 2 ? '110' : '' }}" rowspan="{{ count($institucionDetalle[$i][1]) }}">{{ $institucionDetalle[$i][0] }}</td>
                        <td width="26%">{{ $institucionDetalle[$i][1][0]->nombre." ".$institucionDetalle[$i][1][0]->apellido }}</td>
                        <td width="15%">{{-- observaciones --}}</td>
                        <td width="20%" rowspan="{{ count($institucionDetalle[$i][1]) }}">{{-- Firma y sello de supervisior --}}</td>
                        <td rowspan="{{ count($institucionDetalle[$i][1]) }}">{{-- Fecha --}}</td>
                    </tr>
                    @for ($j = 1; $j < count( $institucionDetalle[$i][1]) ; $j++)
                    <tr>
                        <td>{{ $institucionDetalle[$i][1][$j]->nombre." ".$institucionDetalle[$i][1][$j]->apellido }}</td>
                        <td></td>
                    </tr>
                    @endfor
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>