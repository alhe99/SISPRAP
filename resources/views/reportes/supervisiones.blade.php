<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte General de Supervisiones de {{ $proceso }}</title>
    <link rel="stylesheet" href="{{public_path('css/bmd.css')}}">
    <style>
    .bg-header {
        background-color: #F8EFB6
    }

    .font-normal {
        font-weight: normal;
    }
    table{width:100%;page-break-inside: avoid;}
</style>
</head>
<body style="background-color:white">
    <header>
        <div class="row text-center">
            <div class="col-md-8">
                <img class="img-fluid" width="450" src="{{public_path('images/header_reportes.PNG')}}">
            </div>
        </div>
        <div>
            <div class="row">
                <div class="col-md-12"><br>
                    <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
                    <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
                    <h6 class="text-center font-weight-bold"><ins>INFORME DE SUPERVISIONES DE {{ strtoupper($proceso)." ".$anio }}</ins></h6><br>
                </div>
            </div>
            <div class="col-md-12">
                @for ($i = 0; $i < count($supervisiones) ; $i++)
                <table width="100%" border="1px" cellpadding="10">
                    <thead class="bg-header">
                        <tr class="font-weight-bold">
                            @if ($proceso == "Práctica Profesional")
                            <td class="text-center" colspan="4">Empresa / Institución : {{ $supervisiones[$i][0]  }}</td>
                            @else
                            <td class="text-center" colspan="3">Empresa / Institución : {{ $supervisiones[$i][0]  }}</td>
                            @endif

                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="2">Proyectos</td>
                            @if ($proceso == "Práctica Profesional")
                            <td>Carrera del proyecto</td>
                            @endif
                            <td>Fecha de Supervisión</td>

                        </tr>
                    </thead>
                    <tbody>
                        @for ($j = 1; $j < count($supervisiones[$i]) ; $j++)
                            <tr>
                                <td colspan="2" class="text-capitalize">{{ strtolower($supervisiones[$i][$j]["nombreProyecto"]) }}</td>
                                @if ($proceso == "Práctica Profesional")
                                <td>{{ $supervisiones[$i][$j]["carreraProyecto"] }}</td>
                                @endif
                                <td class="text-center">{{ $supervisiones[$i][$j]["fechaSupervision"] }}</td>
                            </tr>
                        @endfor
                        <tr class="bg-header font-weight-bold">
                            <td colspan="{{$proceso == "Práctica Profesional" ? '3':'2' }}">Total de proyectos supervisados: </td>
                            <td>{{ count($supervisiones[$i])-1 }}</td>
                        </tr>
                    </tbody>
                </table><br>
                @endfor

            </div>
        </div>
    </body>
    </html>