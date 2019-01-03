<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estudiantes que Iniciarion Procesos</title>
    <link rel="stylesheet" href="{{asset('css/bmd.css')}}">
    <style>
    .bg-header{
     background-color:#F8EFB6
 }
 .font-normal{
     font-weight: normal;
 }
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
            <p class="text-center font-weight-bold"><u>INFORME {{ $procesoTitulo }} DE {{ date('Y')}}</u></p><br>
            @if ($tipo == 'M')
                <p class="text-center font-weight-bold"><u>MESES: {{ implode(",", $meses) }}</u></strong></p>
            @else
                <p class="text-center font-weight-bold"><u>MESES: {{ $meses }}</u></strong></p>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        @if ($tipo == 'T')
        {{-- TABLA PARA MESES INDIVIDUALES --}}
        @foreach ($mensuales[0] as $item)
        <table class="col-md-12" border cellpadding="7">
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
        </table><br><br>
        @endforeach
        {{-- TABLA PARA CONSOLIDADO FINAL --}}
        <table class="col-md-12" border cellpadding="7">
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
        <br><br><table class="col-md-12" border cellpadding="4" style="margin-bottom: -17px;">
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
        @endforeach
        @endif
    </div>
</div>
</body>
</html>