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
        <table class="table table-striped table-bordered" style="border: solid 1px #000000; " >
            <thead> 
                    <tr>
                        <th><img class="text-center img-fluid " src="images/img_reportes/logopequeño.png" alt=""></th>
                        <th class="text-center">ASOCIACION AGAPE<br>DE EL SALVADOR</th>
                        <th class="text-center">DOCUMENTO DE LA CALIDAD<br>FORMULARIO</th>
                        <th class="text-center">CODIGO: F.IT.036<br>Verion: 02<br>Página 1 de 1</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-center">Titulo: Perfil del proyecto de Servicio Social Estudiantil o <br> Práctica Profesional</th>
                        <th colspan="1" class="text-center">FECHA DE REVISION: 14/07/2016</th>
                    </tr>
                </thead>
        </table>
        {{--  <div class="row text-center">
        
            <div class="col-md-6">
                 <img class="text-center img-fluid " src="images/img_reportes/logoITCHA.png" alt="">
                 
                 <img class="text-center img-fluid " src="images/img_reportes/mined.jpg" alt="">
                
                 <img class="text-center img-fluid " src="images/img_reportes/megatec.jpg" alt="">
                  
                 <img class="text-center img-fluid " src="images/img_reportes/logopequeño.png" alt="">

            </div>    
        </div>  --}}
        
    </header>
    <div>
    <div class="row">
        <div class="col-md-12">
             <div class="row md-radio">
                <div class="col-md-6 text-center">
                    <input
                        id="radioSS"
                        value="1"
                        v-model="proceso"
                        type="radio"
                        name="radioP"
                    >
                        <label for="radioSS">Servicio Social</label>
                </div>
                <div class="col-md-6 text-center">
                    <input
                        id="radioPP"
                        value="2"
                        v-model="proceso"
                        type="radio"
                        name="radioP"
                    >
                        <label for="radioPP">Práctica Profesional</label>
                </div>
            </div>
        </div>
        <div class="row">
                 <div class="col-md-2">
                    <img class="text-center img-fluid " src="images/img_reportes/logoITCHA.png" alt="">
                 </div>
                 <div class="col-md-10">
                 <h6>Datos del Estudiante</h6><br>
                    <table class="table table-striped table-bordered" style="border: solid 1px #000000; " >
                        <thead> 
                            <tr>
                                <th>Nombre: </th>
                                <th></th>
                                <th>#Carnét: </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Apellidos</td>
                                <td></td>
                                <td>Teléfono(s): </td>
                            </tr>
                             <tr>
                                <td>Carrera</td>
                                <td></td>
                                <td>Corrreo electrónico: </td>
                            </tr>
                        </tbody>
                    </table>
                 </div>
        </div>
        <h6>Datos de la Institución/Organización donde realizará el proyecto </h6><br>
    
    </div>
    </div>
</body>
</html>