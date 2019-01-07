<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Control de Asistencia</title>
    <link rel="stylesheet" href="{{asset('css/bmdf.css')}}">
</head>
<body>
    <div class="container">
        <div class="row m-4">
            <div class="col-md-12"><br>
                <table class="col-md-12" border="1" cellpadding="2" cellspacing="1" style="margin-top:35px;">
                    <td width="8">
                        <img src="{{ asset('images/img_reportes/AgapeLogo.png') }}">
                    </td>
                    <td class="text-center  ">
                        ASOCIACION AGAPE <br> DE EL SALVADOR
                    </td>
                    <td class="text-center ">
                        DOCUMENTO DE LA CALIDAD <br> FORMULARIO
                    </td>
                    <td class="text-center ">
                        CODIGO: F.IT.058 <br>
                        Versión: 01<br>
                        Pagina 1 de 1
                    </td>
                    <tr>
                        @if ($data["proceso"] == 1)
                        <td colspan="3" height="50" class="text-left ">
                            Título:Control de Asistencia Servicio Social
                        </td>
                        @else
                        <td colspan="3" height="50" class="text-left ">
                            Título:Control de Asistencia Práctica Profesional
                        </td>
                        @endif

                        <td class="text-center ">
                           FECHA DE REVISIÓN: <br> 14/07/2016

                       </td>
                   </tr>
               </table><br>
               <div class="row">
                <label class="text-center" style="margin-left:200px; ">INSTITUTO TECNOLOGICO DE CHALATENANGO ITCHA/AGAPE<br>CONTROL DE ASISTENCIA</label>
            </div>

            <div class="row">
                <div class="col-md-12">
                   <div class="row md-radio">
                      <div class="col-md-6 text-center" style="margin-top:10px;">
                        <label for="1">Servicio Social</label>
                        <input id="1" type="radio" name="g" {{ $data["proceso"] == 1 ? 'checked' : '' }} >

                        <label for="2">Práctica Profesional</label>
                        <input id="2" type="radio" name="g" {{ $data["proceso"] == 2 ? 'checked' : '' }} >

                    </div>
                </div>
            </div>
        </div>

        <div width="8">
            <img style="height:90px; margin-left:700px; margin-top:-175px;" src="{{ asset('images/img_reportes/logoITCHA.png') }}">
        </div>
        <div class="row ">

            <table class="col-md-12" border="1" cellpadding="4" cellspacing="4" style="margin-top:;">
                <tr>
                    <td>Nombre del estudiante: {{ $data["nombreE"]." ".$data["apellidoE"] }} <label style="margin-left:350px;">Carnét Nº:
                    {{ $data["carnetE"] }}</label> </td>
                </tr>
                <tr>
                    <td>Carrera: {{ $data["carreraE"] }} </td>
                </tr>
                <tr>
                    <td >Nombre de la institución donde realizará el proyecto  {{ $data["nombreI"] }} </td>
                </tr>
                <tr>
                    <td>Nombre del proyecto <label style="font-size: xx-small;">(segun perfil)</label> {{ $data["nombreP"] }} </td>
                </tr>
            </table>
            <div class="row">
                <p style=" margin-top: 20px; margin-left:20px; ">Reporte Nº: __________</p>
                <br>
            </div>


            <div>

                <table class="col-md-12" border="1" style="margin-top: -10px;" cellpadding="7" >
                    <tr>
                        <td class="text-center" style="font-size:x-small;">&nbsp;</td>
                        <td colspan="14" class="text-center" style="font-size:x-small;">Registro Diario</td>
                        <td  class="text-center" style="font-size:x-small;">#<br>Hrs.</td>
                        <td class="text-center" style="font-size:x-small; width: 70px;">Firma o<br> sello</td>
                    </tr>
                    <tr>
                        <td class="text-center" rowspan="3" style="font-size:xx-small; ">1</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td  class="text-center" rowspan="3" style="font-size:x-small;"></td>
                        <td class="text-center" rowspan="3" style="font-size:x-small; width: 70px;"></td>
                    </tr>
                    <tr>

                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>

                    </tr>
                    <tr>

                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>

                    </tr>
                    <tr>
                        <td class="text-center" rowspan="3" style="font-size:xx-small; ">2</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td  class="text-center" rowspan="3" style="font-size:x-small;"></td>
                        <td class="text-center" rowspan="3" style="font-size:x-small; width: 70px;"></td>
                    </tr>
                    <tr>

                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>

                    </tr>
                    <tr>

                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>

                    </tr>
                    <tr>
                        <td class="text-center" rowspan="3" style="font-size:xx-small; ">3</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td  class="text-center" rowspan="3" style="font-size:x-small;"></td>
                        <td class="text-center" rowspan="3" style="font-size:x-small; width: 70px;"></td>
                    </tr>
                    <tr>

                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br>S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>

                    </tr>
                    <tr>

                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>

                    </tr>
                    <tr>
                        <td class="text-center" rowspan="3" style="font-size:xx-small; ">4</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Fecha:</td>
                        <td  class="text-center" rowspan="3" style="font-size:x-small;"></td>
                        <td class="text-center" rowspan="3" style="font-size:x-small; width: 70px;"></td>
                    </tr>
                    <tr>

                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">E. <br><br> S.</td>

                    </tr>
                    <tr>

                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                        <td colspan="2"  style="font-size:xx-small; text-align: left;">Horas:</td>
                    </tr>
                    <td colspan="15" style="text-align: right;  margin-top:40px;"><strong><br>Total de horas por hoja: </strong></td>
                    <td colspan="2"></td>

                </table>
            </div>

            <table width="575" cellpadding="7" cellspacing="0" class="col-md-12" border="1" style="margin-top: 25px;">
                <tr text-align="left" valign="middle">
                   <td  style="text-align:left; font-size:small;">Firma y Sello de Supervisor / Coordinador<br>Contraparte del proyecto (Institución / Organización): <br><br>F.___________________ <br><img style="width: 70px;  height:70px; margin-left:350px; margin-top:-80px; " src="{{ asset('images/img_reportes/circulo2.png') }}" alt=""></td>
                   <td  style="text-align:left; font-size:small;">Firma y Sello de la Unidad de <br>Practica Profesional ITCHA: <br><br>F.___________________ <br><img style="width: 70px;  height:70px; margin-left:220px; margin-top:-90px; " src="{{ asset('images/img_reportes/circulo.fw.png') }}" alt=""></td>

               </tr>
           </table>
           <br>
           <label class="text-center" style="margin-left:140px;  margin-top:-10px; "><strong>NOTA: Completar este formulario si el proyecto es realizado por control de horas <strong></label>



















           </div>
       </div>
   </div>
</body>
