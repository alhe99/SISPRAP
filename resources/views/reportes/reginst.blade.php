<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte General de Instituciones de {{$proceso == "Servicio Social" ? 'Servicio Social' : 'Práctica Profesional'}}{{" en ".$anio}}</title>
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
                <div class="col-md-12">
                    <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
                    <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
                    <p class="text-center" style="font-size:small;"><strong><ins>INFORME DE EMPRESAS/INTITUCIONES RELACIONADAS AL PROCESO DE
                    {{$proceso == "Servicio Social" ? 'SERVICIO SOCIAL' : 'PRÁCTICA PROFESIONAL'}}</ins></strong></p><br>
                    <p class="text-center" style="font-size:small;" ><strong>AÑO {{$anio}}</strong></p>
                </div>
            </div>
            <div class="col-md-12">
                <table width="100%" border="1.5px" cellpadding="10">
                    <thead class="bg-header">
                        <tr class="font-weight-bold">
                            <th>Nº</th>
                            <th>Empresa / Institución</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($instituciones as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->nombre}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table><br>
                <table width="100%" border="1.5px" cellpadding="10">
                    <tr class=" font-weight-bold">
                        <td>Total Instituciones:</td>
                        <td>
                        {{$total}}</h4>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>
</html>