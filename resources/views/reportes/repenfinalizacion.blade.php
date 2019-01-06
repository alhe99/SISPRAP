<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estudiantes Pendientes de Finalizar {{ strtolower($procesoTitulo) }}</title>
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
   <div class="row m-5 ">
    <div class="row">
        <div class="col-md-12">
            <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
            <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
            <p class="text-center font-weight-bold"><u>INFORME DE PENDIENTES DE FINALIZACIÓN DE {{ $procesoTitulo }} DE {{ date('Y')}}</u></p><br>
            @if ($tipo == 'M')
            <p class="text-center font-weight-bold"><u>MESES: {{ implode(",", $meses) }}</u></strong></p>
            @elseif($tipo == 'T')
            <p class="text-center font-weight-bold"><u>MESES: {{ $meses }}</u></strong></p>
            @elseif($tipo == 'A')
            <p class="text-center font-weight-bold"><u>INFORME ANUAL</u></strong></p>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        @if ($tipo == 'T')
        {{-- TABLA PARA MESES INDIVIDUALES --}}
        @for ($i = 0; $i < count($mensuales) ; $i++)
        <h6 class="font-weight-bold text-center"><u>&nbsp;{{ $mensuales[$i][0] }}&nbsp;</u></h6>
        @for ($j = 1; $j < count($mensuales[$i]) ; $j++)
        <table class="mb-1"  border="2px" cellpadding="7">
            <tr class="bg-header text-center font-weight-bold">
                <td colspan="2">Carrera: {{ $mensuales[$i][$j][0] }}</td>
            </tr>
            <tr class="bg-header text-center font-weight-bold">
                <td>Nombre del Alumnos</td>
                <td>Documentos Pendientes</td>
            </tr>
            @for ($k = 1; $k < count($mensuales[$i][$j]);$k++)
            @if (count($mensuales[$i][$j][$k]) > 0)
            @foreach ($mensuales[$i][$j][$k] as $item)
            <tr>

                <td>{{ $item["nombre"]." ".$item["apellido"] }}</td>
                <td>
                    @for ($m=0;$m < count($item["documentosEntregados"]);$m++)
                            &#126;{{ $item["documentosEntregados"][$m]->nombre }}<br>
                    @endfor
                </td>
            </tr>
            @endforeach
            <tr class="font-weight-bold bg-header">
                <td class="text-right">Total De Estudiantes Pendientes</td>
                <td>{{ count($mensuales[$i][$j][$k]) }}</td>
            </tr>
            @else
            <tr>
                <td colspan="2" class="text-center">No hay estudiantes de finalizar proceso en esta carrera</td>
            </tr>
            @endif
            @endfor
        </table><br>
        @endfor
        @endfor
        {{-- TABLA PARA CONSOLIDADO FINAL --}}
        <table class="col-md-12" border cellpadding="7">
            <thead class="font-weight-bold text-center bg-header">
                <tr>
                    <td colspan="4">{{"CONSOLIDADO ".$consolidado[0]}}</td>
                </tr>
                <tr class="text-center">
                    <td>Carrera</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                @for ($i = 2; $i < count($consolidado); $i++)
                <tr>
                    <th class="font-normal">{{$consolidado[$i]['Carrera']}}</th>
                    <th class="text-center font-normal">{{$consolidado[$i]['total']}}</th>
                </tr>
                @endfor
                <tr class="text-center bg-header">
                    <th class="text-right">Total</th>
                    <th>{{$consolidado[1]}}</th>
                </tr>
            </tbody>
        </table>

        @elseif($tipo == 'M')
        {{-- TABLA PARA MESES INDIVIDUALES --}}
        @for ($i = 0; $i < count($mensuales) ; $i++)
        <h6 class="font-weight-bold text-center"><u>&nbsp;{{ $mensuales[$i][0] }}&nbsp;</u></h6>
        @for ($j = 2; $j < count($mensuales[$i]) ; $j++)
        <table class="mb-1"  border="2px" cellpadding="7">
            <tr class="bg-header text-center font-weight-bold">
                <td colspan="2">Carrera: {{ $mensuales[$i][$j][0] }}</td>
            </tr>
            <tr class="bg-header text-center font-weight-bold">
                <td colspan="2">Nombre del Alumnos</td>
            </tr>
            @for ($k = 1; $k < count($mensuales[$i][$j]);$k++)
            @if (count($mensuales[$i][$j][$k]) > 0)

            @foreach ($mensuales[$i][$j][$k] as $item)
            <tr>
                <td colspan="2">{{ $item["nombre"]." ".$item["apellido"] }}</td>
            </tr>
            @endforeach
            <tr class="font-weight-bold bg-header">
                <td class="text-right">Total</td>
                <td>{{ count($mensuales[$i][$j][$k]) }}</td>
            </tr>
            @else
            <tr>
                <td colspan="2" class="text-center">No hay estudiantes pendientes de iniciar proceso en esta carrera</td>
            </tr>
            @endif
            @endfor
        </table><br>
        @endfor
        @endfor
        @elseif($tipo == 'A')
        {{-- TABLA PARA MESES INDIVIDUALES --}}
        @for ($i = 0; $i < count($consolidadoMensual) ; $i++)
        <h6 class="font-weight-bold text-center"><u>&nbsp;{{ $consolidadoMensual[$i][0] }}&nbsp;</u></h6>
        @for ($j = 2; $j < count($consolidadoMensual[$i]) ; $j++)
        <table class="mb-1"  border="2px" cellpadding="7">
            <tr class="bg-header text-center font-weight-bold">
                <td colspan="2">Carrera: {{ $consolidadoMensual[$i][$j][0] }}</td>
            </tr>
            <tr class="bg-header text-center font-weight-bold">
                <td colspan="2">Nombre del Alumnos</td>
            </tr>
            @for ($k = 1; $k < count($consolidadoMensual[$i][$j]);$k++)
            @if (count($consolidadoMensual[$i][$j][$k]) > 0)

            @foreach ($consolidadoMensual[$i][$j][$k] as $item)
            <tr>
                <td colspan="2">{{ $item["nombre"]." ".$item["apellido"] }}</td>
            </tr>
            @endforeach
            <tr class="font-weight-bold bg-header">
                <td class="text-right">Total</td>
                <td>{{ count($consolidadoMensual[$i][$j][$k]) }}</td>
            </tr>
            @else
            <tr>
                <td colspan="2" class="text-center">No hay estudiantes pendientes de iniciar proceso en esta carrera</td>
            </tr>
            @endif
            @endfor
        </table><br>
        @endfor
        @endfor
        {{-- TABLA PARA CONSOLIDADO FINAL ANUAL  --}}
        <table class="col-md-12" border cellpadding="7">
            <thead class="font-weight-bold text-center bg-header">
                <tr>
                    <td colspan="4">{{"CONSOLIDADO ANUAL ".$consolidadoAnual[0]}}</td>
                </tr>
                <tr class="text-center">
                    <td>Carrera</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                @for ($i = 2; $i < count($consolidadoAnual); $i++)
                <tr>
                    <th class="font-normal">{{$consolidadoAnual[$i]['Carrera']}}</th>
                    <th class="text-center font-normal">{{$consolidadoAnual[$i]['total']}}</th>
                </tr>
                @endfor
                <tr class="text-center bg-header">
                    <th class="text-right">Total</th>
                    <th>{{$consolidadoAnual[1]}}</th>
                </tr>
            </tbody>
        </table>
        @endif
    </div>
</div>
</body>
</html>