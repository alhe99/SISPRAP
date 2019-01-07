<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Control de Proyecto</title>
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
                        <td colspan="3" height="50" class="text-left ">
                            Título:Control del Proyecto Práctica Profesional
                        </td>
                        <td class="text-center ">
                           FECHA DE REVISIÓN: <br> 14/07/2016

                       </td>
                   </tr>
               </table><br>
               <div class="row">
                    <label class="text-center" style="margin-left:200px; "></strong>INSTITUTO TECNOLOGICO DE CHALATENANGO ITCHA/AGAPE<br>CONTROL DE PROYECTO</strong></label>
               </div>

               <div class="row">
                    <div class="col-md-12">
                       <div class="row ">
						<div class="col-md-6 md-radio text-center" style="margin-top:10px;">
                            <label>Servicio Social</label>
							<input type="radio"  {{ $data["proceso"] == 1 ? 'checked' : '' }} >
                            <label >Práctica Profesional</label>
							<input type="radio" {{ $data["proceso"] == 2 ? 'checked' : '' }} >
						</div>
					</div>
                 </div>
               </div>
               <div class="row">
                    <label  style="  margin-top: -110px;  margin-left:15px;" ><strong>Datos del Estudiante<strong></label>
                </div>
               <div width="8">
                        <img style="height:90px; margin-left:700px; margin-top:-185px;" src="{{ asset('images/img_reportes/logoITCHA.png') }}">
                </div>







                 <div>
                <table   cellpadding="7" border="1" class="col-md-12" class="col-md-12" cellspacing="2" style=" margin-top:-30px;"  >
                    <tr class="col-md-12">
                        <td  colspan="5" style=" font-size:small; width:150px;" >Nombre: {{ $data["nombreE"]." ".$data["apellidoE"] }}</td>
                        <td  colspan="1" style=" font-size:small; "  >Carnet Nº: {{ $data["carnetE"] }} </td>
                    </tr>
                     <tr>
                        <td  colspan="6" style=" font-size:small;">Carrera:  {{ $data["carreraE"] }}</td>
                    </tr>
               </table>
               </div>
                <div class="row">
                    <label style="margin-left:18px; margin-top:50px;" ><strong>Nombre de la Empresa / Institución / Organización<strong></label>
                </div>

                <div>
                <table cellpadding="7" cellspacing="0" class="col-md-12 " border="1" style=" margin-left: 5px; margin-right:-5px; ">
                    <tr>
                        <td   style="font-size:small; padding-top: 0px;">Nombre:  {{ $data["nombreI"] }}<br><br></td>
                    </tr>
                </table>
            </div>
                {{--  <table width="575"  cellpadding="2" cellspacing="2" border="1" class="col-md-12">
                <tr  valign="middle">
                    <td class="text-left" text-align="left" style="font-size:small; padding-top: 0px;"  colspan="2">Nombre de la institución / organización / empresa:<br><br><br></td>
                    <td  style="font-size:small; padding-top: 0px; " colspan="2"  >Sector principal de la institución / Organización<br>(A qué se dedica):</td>
                </tr>

                <tr text-align="left" valign="middle">
                    <td class="text-left " rowspan="2" colspan="4" style="font-size:small; padding-top: 0px;" >Dirección:<br><br></td>
                </tr>
                <tr text-align="left" valign="middle">

                </tr>
                <tr text-align="left" valign="middle">
                    <td class="text-left" style="font-size:small; padding-top: 0px;">Municipio:<br>Aguilares</td>
                    <td class="text-left" style="font-size:small; padding-top: 0px;">Departamento:<br>San Salvador</td>
                    <td class="text-left" style="font-size:small; padding-top: 0px;">E-mail:<br>nuevo@gmail.com</td>
                    <td class="text-left" style="font-size:small; padding-top: 0px;">Teléfono(s):<br>78569034</td>
                </tr>
             </table>  --}}
              <div>
                <label style="margin-top: 30px;  margin-left:5px;"><strong>Datos del proyecto </strong></label>
              </div>
                    <div>
                <table cellpadding="7" cellspacing="5" class="col-md-12 " border="1" style="padding-top:-340px; margin-left: 5px; margin-right:-5px;" >
                    <tr>
                        <td style="font-size:small; padding-top: 0px;  width:400px; " colspan="4">Nombre <label style="font-size: x-small;">(segun perfil):  {{ $data["nombreP"] }}</label><br><br></td>
                        <td style="font-size:small; padding-top: 0px; " class="text-center" colspan="2">Horas Realizadas:<br><br></td>
                    </tr>
                    <tr>
                        <td style="font-size:small; padding-top: 0px; " colspan="6">Descripción de actividades realizadas:{!! $data["actividadesP"] !!}<br><br></td>

                    </tr>
                    <tr>
                        <td style="font-size:small; padding-top: 0px; " colspan="6">Nombre del supervisor: {{ $data["nombreS"] }}<br></td>

                    </tr>
                </table>
            </div>
               {{--  <table width="580"  cellpadding="0" cellspacing="0" border="1" class="col-md-12" style=" margin-top:-3px; ">
                <tr  valign="middle">
                    <td text-align="left" style="font-size:small;"  rowspan="2" colspan="3">Nombre de acuerdo a la actividad principal que realiza:<br><br><br></td>
                    <td class="text-center" style="font-size:small;" colspan="1">No. De Horas a realizar</td>
                </tr>
                <tr text-align="center" valign="middle">
                    <td colspan="1"  style="font-size:small;" ></td>
                </tr>

                <tr text-align="left" valign="middle">
                    <td colspan="4" class="td2Table1" style="font-size:small;"  >Descripción del Proyecto(escriba todas las actividades a desarrollar):<br><br><br><br><br><br></td>

                </tr>
                <tr text-align="left" valign="middle">
                    <td  style="font-size:small; width:150px;">Fecha inicio<br><br></td>
                    <td style="font-size:small; width:150px;">Fecha de finalización<br><br></td>
                    <td cellspacing="3" style="font-size:small;">Nombre del Supervisor de la institución / empresa<br><br></td>
                    <td style="font-size:small;">Tel. del Supervisor<br><br></td>
                </tr>
        </table>  --}}
         {{--  <table  cellpadding="0" cellspacing="0" class="col-md-12" border="1" style="margin-top:50px;">
            <tr text-align="left" valign="middle">
                <td style="font-size:x-small;">Firma del Estudiante que desarrolla el proyecto:<br><br><br>F.___________________________</td>
                <td style="font-size:x-small;" class="text-center">Firma y sello de aprobación del encargado de <br>Práctica Profesional ITCHA <br><br><br><br><br>F.____________________________ <br><br><img style="height:90px; margin-left:90px; margin-top:-85px;" src="{{ asset('images/img_reportes/circulo.fw.png') }}"></td>
                <td style="font-size:x-small;">Fecha presentación:___________________<br><br>Factura #:___________________<br></td>
            </tr>
        </table>  --}}
        <table  cellpadding="7" cellspacing="0" class="col-md-12 " border="1" style="margin-top: 100px; ">
            <tr text-align="left" valign="middle">
              <td  style="text-align:left; font-size:x-small; width:400px;">Firma y Sello de Supervisor / Coordinador<br>Contraparte del proyecto (Institución / Organización): <br>F._____________________ <img style="width: 90px;  height:90px; margin-left:120px; margin-top:-20px; " src="{{ asset('images/img_reportes/circulo2.png') }}" alt=""></td>
              <td  style="text-align:left; font-size:x-small;" >Firma y Sello de la Unidad de <br>Practica Profesional ITCHA: <br>F.________________________ <img style="height:90px; margin-left:90px; margin-top:-20px;" src="{{ asset('images/img_reportes/circulo.fw.png') }}"></td>

            </tr>
        </table>
        <p class="text-center" style="font-size:small; margin-top:30px;">NOTA: El Llenado de esta hoja solo aplica cuando se realiza por proyecto el Servicio Social / Práctica Profesional</p>
            </div>
        </div>
    </div>
</body>
