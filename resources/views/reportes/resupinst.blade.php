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
            <p class="text-center" style="font-size:small;" ><strong><ins>REGISTRO DE SUPERVISIONES A INSTITUCIONES</ins></strong></p><br>
           
        </div>
    </div>
        <div class="col-md-12">
            <table class="table table-striped table-bordered" style="border: solid 1px #000000; ">
                <thead> 
                    <tr style="background-color:#ffbb98;">
                        <td colspan="4"><strong>Empresa/Institucion: </strong></td>
                        <td colspan="4"><strong>Proceso: </strong></td>
                    </tr>
                    <tr style="background-color:#ffbb98;" colspan="12">
                        <th>Proyecto</th>
                        <th>Carrera</th>
                        <th>Alumnos</th>
                        <th>Supervisor</th>
                        <th>Horarios</th>
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
                
                    
                </tbody>
            </table>
            
            {{-- <h4>Total: {{$total}}</h4> --}}
            <table class="table table-striped table-bordered" style="border: solid 1px #000000; ">
                <thead > 
                    <tr style="background-color:#ffbb98; " >
                        <th >Observaciones: </th> 
                    </tr>
                    <tr>s
                        <th ><br><br><br> </th>
                    </tr>
                </thead>
                
            </table>
            <br>
            <div class="row col-md-12">
                <div class="col-md-5"><br><br><strong>Fecha de supervision: </strong></div>
                <div class="col-md-5"><br><br><strong>Firma: ________________</strong></div>
                <div class="col-md-2">
                    <table class="table" style="border: solid 1px #000000; ">
                        <tr style="width: 10px;">
                            <th class="text-center" ><br><br><br><strong> Sello</strong></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>