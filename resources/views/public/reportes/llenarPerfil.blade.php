<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perfil del Estudiante</title>
    <link rel="stylesheet" href="{{asset('css/bmdf.css')}}">
    <style>
        .font-normal{font-weight: normal;}
    </style>
</head>
<body>
    <div class="container">
        <div class="row m-4">
            <div class="col-md-12"><br>
                <table class="col-md-12" border="1" cellpadding="2" cellspacing="1" style="margin-top:35px;">
                    <td width="8">
                        <img style src="{{ asset('images/img_reportes/AgapeLogo.png') }}">
                    </td>
                    <td class="text-center  ">
                        ASOCIACION AGAPE <br> DE EL SALVADOR
                    </td>
                    <td class="text-center ">
                        DOCUMENTO DE LA CALIDAD <br> FORMULARIO
                    </td>
                    <td class="text-center">
                        CODIGO: F.IT.058 <br>
                        Versión: 01<br>
                        Pagina 1 de 1
                    </td>
                    <tr>
                        <td colspan="3" height="50" class="text-left ">
                            Título: Perfil de Proyecto de Servicio Social Estudiantil o Práctica Profesional
                        </td>
                        <td class="text-center ">
                           FECHA DE REVISIÓN: <br> 14/07/2016

                       </td>
                   </tr>
               </table>

               <div class="row">
                <div class="col-md-12 text-center">
                 <label><input type="radio" class="radio-inline" name="radios"
                    {{ $data['proceso'] == 1 ? 'checked' : ''}}
                    ><span class="outside"><span class="inside"></span></span>Servicio Social</label>
                    <label><input type="radio"class="radio-inline" name="radios"
                     {{ $data['proceso'] == 2 ? 'checked' : ''}}
                     ><span class="outside"><span class="inside"></span></span>Práctica Profesional</label>
                 </div>
             </div>
         </div>
         <div class="row">
            <label style="margin-left:70px; margin-top:10px;" ><strong>Datos del Estudiante<strong></label>
            </div>
            <td width="8">
                <img style="height:90px;" src="{{ asset('images/img_reportes/logoITCHA.png') }}">
            </td>

            <div>
                <table  cellpadding="5" border="1" cellspacing="2" style="margin-left:100px; margin-top:-90px;"  >
                    <tr>
                        <td style="width:90px; font-size:small;" >Nombres</td>
                        <td class="font-normal" style="width:270px; font-size:small;" >{{$data['nombreE']}}</td>
                        <td style="width:100px; font-size:small;"  >#Carné:</td>
                        <td class="font-normal" style="width:250px; font-size:small;">{{ $data['carnetE'] }}</td>
                    </tr>
                    <tr>
                        <td style="width:90px; font-size:small;" >Apellidos</td>
                        <td class="font-normal" style="width:270px; font-size:small;" >{{ $data['apellidoE'] }}</td>
                        <td  style="width:100px;font-size:small;" >Teléfono(s):</td>
                        <td class="font-normal" style="width:250px; font-size:small;">{{ $data['telefonoE'] }}</td>
                    </tr>
                    <tr>
                        <td style="width:90px; font-size:small;">Carrera</td>
                        <td class="font-normal" style="width:270px; font-size:small;" >{{ $data['carreraE'] }}</td>
                        <td style="width:100px; font-size:small;" >E-mail:</td>
                        <td  class="font-normal" style="width:250px; font-size:small;" >{{ $data['emailE'] }}</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <label style="margin-left:15px; margin-top:50px;" ><strong>Datos de la Institución / Organización donde realizará el proyecto<strong></label>
                </div>
                <table width="575"  cellpadding="7" cellspacing="2" border="1" class="col-md-12">
                    <tr  valign="middle">
                        <td class="text-left font-normal" text-align="left" style="font-size:small; padding-top: 0px;"  colspan="2">Nombre de la institución / organización / empresa:<br>{{ $data['nombreI'] }}</td>
                        <td  style="font-size:small; padding-top: 0px; " colspan="2"  >Sector principal de la institución / Organización<br>(A qué se dedica): {{ $data['sectorI'] }}</td>
                    </tr>

                    <tr text-align="left" valign="middle">
                        <td class="text-left font-normal " rowspan="2" colspan="4" style="font-size:small; padding-top: 0px;" >Dirección:<br>{{ $data['direccionI'] }}</td>
                    </tr>
                    <tr text-align="left" valign="middle">

                    </tr>
                    <tr text-align="left" valign="middle">
                        <td class="text-left font-normal" style="font-size:small; padding-top: 0px;">Municipio:<br>{{ $data['municipioI'] }}</td>
                        <td class="text-left font-normal" style="font-size:small; padding-top: 0px;">Departamento:<br>{{ $data['departamentoI'] }}</td>
                        <td class="text-left font-normal" style="font-size:small; padding-top: 0px;">E-mail:<br>{{ $data['emailI'] }}</td>
                        <td class="text-left" style="font-size:small; padding-top: 0px;">Teléfono(s):<br>{{ $data['telefonoI'] }}</td>
                    </tr>
                </table>
                <div>
                    <label style="margin-top: 50px;  margin-left:5px;"><strong>Datos sobre las actividades o proyecto a realizar</strong></label>
                </div>
                <table width="580"  cellpadding="7" cellspacing="0" border="1" class="col-md-12" style=" margin-top:-3px; ">
                    <tr  valign="middle">
                        <td text-align="left" style="font-size:small;"  rowspan="2" colspan="3">Nombre de acuerdo a la actividad principal que realiza:<br>{{ $data['nombreP'] }}</td>
                        <td class="text-center" style="font-size:small;" colspan="1">No. De Horas a realizar</td>
                    </tr>
                    <tr text-align="center" valign="middle">
                        <td colspan="1" class="font-normal"  style="font-size:small;" >{{ $data['hrasRealizar'] }}</td>
                    </tr>

                    <tr text-align="left" valign="middle">
                        <td colspan="4" class="td2Table1" style="font-size:small;"  >Descripción del Proyecto(escriba todas las actividades a desarrollar):<br>{!! $data['actividadesP'] !!}</td>

                    </tr>
                    <tr text-align="left" valign="middle">
                        <td  style="font-size:small; width:120px;">Fecha inicio<br>{{ $data['fechaInicio'] }}</td>
                        <td style="font-size:small; width:180px;">Fecha de finalización<br>{{ $data['fechaFin'] }}</td>
                        <td cellspacing="3" style="font-size:small;">Nombre del Supervisor de la institución / empresa<br>{{ $data['nombreS'] }}</td>
                        <td style="font-size:small;">Tel. del Supervisor<br>{{ $data['telefonoS'] }}</td>
                    </tr>
                </table>
                <table  cellpadding="7" cellspacing="0" class="col-md-12" border="1" style="margin-top:50px;">
                    <tr text-align="left" valign="middle">
                        <td style="font-size:x-small; ">Firma del Estudiante que desarrolla el proyecto:<br><br><br>F.___________________________</td>
                        <td style="font-size:x-small;" >Firma y sello de aprobación del encargado de <br>Práctica Profesional ITCHA <br><br><br><br><br>F.____________________________ <br><br><img class="text-right" style="height:90px; margin-left:200px; margin-top:-130px;" src="{{ asset('images/img_reportes/circulo.fw.png') }}"></td>
                        <td style="font-size:x-small;">Fecha presentación:___________________<br><br>Factura #:___________________<br></td>
                    </tr>
                </table>
                <p style="font-size:x-small; margin-top:30px;">NOTA: Este documento debe ser presentado a más tardar 5 días hábiles despúes de su retiro(No es válido si presenta tachadura, enmendedura y/o correciones),   *Campos NO obligatorios</p>
            </div>
        </div>
    </div>
</body>
