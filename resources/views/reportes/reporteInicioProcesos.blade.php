<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estudiantes que Iniciarion Procesos</title>
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
   <div class="row m-5">
    <div class="row">
        <div class="col-md-12">
            <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
            <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
            {{-- TRAER EL PROCESO PARA TERMINAR ESTE TITULO --}}
            <p class="text-center font-weight-bold"><u>INFORME DE ALUMNOS QUE HAN INICIADO {{ $procesoTitulo }} DE {{ $anio}}</u></p><br>
            @if ($tipo == 'M')
            <p class="text-center font-weight-bold"><u>MES(ES): {{ implode(", ", $meses) }}</u></strong></p>
            @elseif($tipo == 'T')
            <p class="text-center font-weight-bold"><u>MES(ES): {{ $meses }}</u></strong></p>
            @elseif($tipo == 'A')
            <p class="text-center font-weight-bold"><u>INFORME ANUAL</u></strong></p>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        @if ($tipo == 'T')
        {{-- TABLA PARA MESES INDIVIDUALES --}}
        @foreach ($mensuales[0] as $item)
         @if ($item[1]['totalMined'] + $item[1]['totalOtros'] != 0)
            <table border="1px" cellpadding="5">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{$item[0]}}</td>
                    </tr>
                    <tr>
                        <th>Carrera</th>
                        <th>Becados MINED</th>
                        <th>Otros</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 2; $i < count($item); $i++)
                    <tr>
                        <th class="font-normal">{{ $item[$i]['Carrera']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['BecadosMined']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['Otros']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['BecadosMined']+$item[$i]['Otros']}}</th>
                    </tr>
                    @endfor
                    <tr class="bg-header">
                        <th class="text-right">Total</th>
                        <th class="text-center">{{ $item[1]['totalMined'] }}</th>
                        <th class="text-center">{{ $item[1]['totalOtros'] }}</th>
                        <th class="text-center">{{ $item[1]['totalMined'] + $item[1]['totalOtros'] }}</th>
                    </tr>
                </tbody>
            </table><br>
         @else
             <div class="row m-4">
                 <div class="col-md-12 text-center">
                   <span class="text-center h6">No hay datos para el mes de <strong>{{ $item[0] }}</strong>.</span>
                 </div>
             </div>
         @endif
        @endforeach
        {{-- TABLA PARA CONSOLIDADO FINAL --}}
        <table border="1px" cellpadding="5">
            <thead class="font-weight-bold text-center bg-header">
                <tr>
                    <td colspan="4">{{"CONSOLIDADO ".$consolidado[0]}}</td>
                </tr>
                <tr>
                    <th>Carrera</th>
                    <th>Becados <br> MINED</th>
                    <th>Otros</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 2; $i < count($consolidado); $i++)
                <tr>
                    <th class="font-normal">{{$consolidado[$i]['Carrera']}}</th>
                    <th class="text-center font-normal">{{$consolidado[$i]['BecadosMined']}}</th>
                    <th class="text-center font-normal">{{$consolidado[$i]['Otros']}}</th>
                    <th class="text-center font-normal">{{$consolidado[$i]['BecadosMined']+$consolidado[$i]['Otros']}}</th>
                </tr>
                @endfor
                <tr class="text-center bg-header">
                    <th class="text-right">Total</th>
                    <th>{{$consolidado[1]['totalMined']}}</th>
                    <th>{{$consolidado[1]['totalOtros']}}</th>
                    <th>{{$consolidado[1]['totalMined']+$consolidado[1]['totalOtros']}}</th>
                </tr>
            </tbody>
        </table>
        @elseif($tipo == 'M')
        @foreach ($mensuales as $item)
        @if ($item[1]['totalMined'] + $item[1]['totalOtros'] != 0)
            <table border="1px" cellpadding="5">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{ $item[0] }}</td>
                    </tr>
                    <tr>
                        <th>Carrera</th>
                        <th>Becados MINED</th>
                        <th>Otros</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 2; $i < count($item); $i++)
                    <tr>
                        <th class="font-normal">{{ $item[$i]['Carrera']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['BecadosMined']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['Otros']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['BecadosMined']+$item[$i]['Otros']}}</th>
                    </tr>
                    @endfor
                    <tr class="bg-header">
                        <th class="text-right">Total</th>
                        <th class="text-center">{{ $item[1]['totalMined'] }}</th>
                        <th class="text-center">{{ $item[1]['totalOtros'] }}</th>
                        <th class="text-center">{{ $item[1]['totalMined'] + $item[1]['totalOtros'] }}</th>
                    </tr>
                </tbody>
            </table><br><br>
            @else
            <div class="row m-4">
               <div class="col-md-12 text-center">
                 <span class="text-center h6">No hay datos para el mes de <strong>{{ $item[0] }}</strong>.</span>
             </div>
         </div>
         @endif
        @endforeach
        @elseif($tipo == 'A')
        @foreach ($mensuales as $item)
         @if ($item[1]['totalMined'] + $item[1]['totalOtros'] != 0)
            <table border="1px""4" cellpadding="5">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{ $item[0] }}</td>
                    </tr>
                    <tr>
                        <th>Carrera</th>
                        <th>Becados MINED</th>
                        <th>Otros</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 2; $i < count($item); $i++)
                    <tr>
                        <th class="font-normal">{{ $item[$i]['Carrera']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['BecadosMined']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['Otros']}}</th>
                        <th class="text-center font-normal">{{ $item[$i]['BecadosMined']+$item[$i]['Otros']}}</th>
                    </tr>
                    @endfor
                    <tr class="bg-header">
                        <th class="text-right">Total</th>
                        <th class="text-center">{{ $item[1]['totalMined'] }}</th>
                        <th class="text-center">{{ $item[1]['totalOtros'] }}</th>
                        <th class="text-center">{{ $item[1]['totalMined'] + $item[1]['totalOtros'] }}</th>
                    </tr>
                </tbody>
            </table><br><br>
                @else
                <div class="row m-4">
                 <div class="col-md-12 text-center">
                   <span class="text-center h6">No hay datos para el mes de <strong>{{ $item[0] }}</strong>.</span>
               </div>
           </div>
           @endif
        @endforeach
        {{-- TABLA PARA CONSOLIDADO FINAL ANUAL  --}}
        <table border="1px" cellpadding="7">
            <thead class="font-weight-bold text-center bg-header">
                <tr>
                    <td colspan="4">{{"CONSOLIDADO DEL AÑO ".$consolidadoAnual[0]}}</td>
                </tr>
                <tr>
                    <th>Carrera</th>
                    <th>Becados <br> MINED</th>
                    <th>Otros</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 2; $i < count($consolidadoAnual); $i++)
                <tr>
                    <th class="font-normal">{{$consolidadoAnual[$i]['Carrera']}}</th>
                    <th class="text-center font-normal">{{$consolidadoAnual[$i]['BecadosMined']}}</th>
                    <th class="text-center font-normal">{{$consolidadoAnual[$i]['Otros']}}</th>
                    <th class="text-center font-normal">{{$consolidadoAnual[$i]['BecadosMined']+$consolidadoAnual[$i]['Otros']}}</th>
                </tr>
                @endfor
                <tr class="text-center bg-header">
                    <th class="text-right">Total</th>
                    <th>{{$consolidadoAnual[1]['totalMined']}}</th>
                    <th>{{$consolidadoAnual[1]['totalOtros']}}</th>
                    <th>{{$consolidadoAnual[1]['totalMined']+$consolidadoAnual[1]['totalOtros']}}</th>
                </tr>
            </tbody>
        </table>
        @endif
    </div>
</div>
</body>
</html>