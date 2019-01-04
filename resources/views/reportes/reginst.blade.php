<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte General de Instituciones</title>
    <link rel="stylesheet" href="{{asset('css/bmd.css')}}">
    <style>
    .bg-header {
        background-color: #F8EFB6
    }

    .font-normal {
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
    <div>
    <div class="row">
        <div class="col-md-12">
            <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
            <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
             <p class="text-center" style="font-size:small;"><strong><ins>INFORME DE EMPRESAS AFILIADAS AL PROCESO DE
            {{strtoupper($proceso)}}</ins></strong></p><br>
             <p class="text-center" style="font-size:small;" ><strong>AÑO {{date('Y')}}</strong></p>
        </div>
    </div>
        <div class="col-md-12">
            <table class="table table-striped table-bordered" style="border: solid 1px #000000; ">
                <thead> 
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
            <table class="col-md-12" border cellpadding="7">
            <tr class="bg-header font-weight-bold">
                    <td>Total:</td>
                    <td>
                      {{$total}}</h4>
                    </td>
                </tr>
            </table>
           
        </div>
    </div>
</body>
</html>