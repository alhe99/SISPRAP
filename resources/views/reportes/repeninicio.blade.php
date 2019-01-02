<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Pdf</title>
    <link rel="stylesheet" href="{{asset('css/bmd.css')}}">
    
</head>
<body>
    <header>
        <div class="row text-center">
        
            <div class="col-md-6">
                 <img class="text-center img-fluid " src="images/img_reportes/logoITCHA.png" alt="">
                 
                 <img class="text-center img-fluid " src="images/img_reportes/mined.jpg" alt="">
                  
                 <img class="text-center img-fluid " src="images/img_reportes/megatec.jpg" alt="">
                  
                 <img class="text-center img-fluid " src="images/img_reportes/logopequeño.png" alt="">

            </div>    
        </div>
        
    </header>
    <div>
    <div class="row">
        <div class="col-md-12">
            <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
            <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
            <p class="text-center" style="font-size:small;" ><strong><ins>INFORME TRIMESTRAL DE ALUMNOS PENDIENTES DE INICIO DE SERVICIO SOCIAL</ins></strong></p><br>
            <p class="text-center" style="font-size:small;" ><strong><ins>MESES: ENERO, FEBRERO, MARZO </ins></strong></p>

        </div>
    </div>
        <div class="col-md-12">
            <table class="table table-striped table-bordered" style="border: solid 1px #000000; ">
                <thead>
                     <tr style="background-color:#ffbb98;">
                        <td colspan="4"><strong>Carrera: </strong></td>
                        <td colspan="4"><strong>Nivel Académico: </strong></td>
                    </tr> 
                    <tr>
                        <th>Nombre del Alumno</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Total: </td>
                    <td>{{$total}}</td>
                </tr>
                    {{--  @foreach ($instituciones as $i)
                        <tr>
                            <th>{{$i->nombre}}</th>
                            <th>{{$i->municipio->nombre}} {{$i->municipio->departamento->nombre}}</th>
                        </tr>
                    @endforeach  --}}
                </tbody>
            </table>
            
        </div>
    </div>
</body>
</html>