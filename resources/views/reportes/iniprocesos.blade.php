<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Pdf</title>
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
    <div>
    <div class="row">
        <div class="col-md-12">
            <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
            <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
            <p class="text-center font-weight-bold"><u>INFORME SERVICIO SOCIAL Y PRÁCTICA PROFESIONAL DE ENERO, FEBRERO Y MARZO DE 2018</u></p><br>
            <p class="text-center font-weight-bold"><u>MESES: ENERO, FEBRERO, MARZO</u></strong></p>
        </div>
    </div>
        <div class="col-md-12">
            {{-- TABLA PARA MESES INDIVIDUALES --}}
        {{--     <table class="col-md-12" border cellpadding="10">
                <thead class="font-weight-bold text-center">
                    <tr>
                        <td colspan="4">MES</td>
                    </tr>
                    <tr>
                        <th>Carrera</th>
                        <th>Becados MINED</th>
                        <th>Otros</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                        @for ($i = 1; $i < count($inicioproceso); $i++)
                            @foreach ($inicioproceso[$i] as $item)
                                <tr>
                                <th>{{$item}}</th>

                                </tr>
                            @endforeach
                        @endfor
                </tbody>
            </table> --}}
            {{-- TABLA PARA CONSOLIDADO FINAL --}}
            <table class="col-md-12" border cellpadding="5">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{$consolidado[0]}}</td>
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
        </div>
    </div>
</body>
</html>