<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Constancia {{ $proceso }}</title>
    <link rel="stylesheet" href="{{public_path('css/bmdf.css')}}">
    <style>
    .text-jus {
      text-align: justify;
      text-justify: inter-word;
      line-height: 1.6;
  }
  .bg-header{
     background-color:#F8EFB6
 }
</style>
</head>
<body>
    <div class="container">
        <div class="row m-4">
            <div class="col-md-12">
                <table class="col-md-12" border="1" cellpadding="2" cellspacing="1">
                    <td width="8">
                        <img src="{{ public_path('images/img_reportes/logopequeño.png') }}">
                    </td>
                    <td class="text-center font-weight-bold ">
                        ASOCIACION AGAPE <br> DE EL SALVADOR
                    </td>
                    <td class="text-center font-weight-bold">
                        DOCUMENTO DE LA CALIDAD <br> FORMULARIO
                    </td>
                    <td class="text-center font-weight-bold">
                        CODIGO: F.IT.058 <br>
                        Versión: 01<br>
                        Pagina 1 de 1
                    </td>
                    <tr>
                        <td colspan="3" height="50" class="text-left font-weight-bold">
                            Título: Constancia de {{ $proceso }}
                        </td>
                        <td class="text-center font-weight-bold">
                         FECHA DE REVISIÓN: <br> 14/07/2016

                     </td>
                 </tr>
             </table><br><br><br>

             <h5 class="text-center font-weight-bold">INSTITUTO TECNOLOGICO DE CHALATENANGO ITCHA/AGAPE</h5><br>
             <h5 class="text-center font-weight-bold"> CONSTANCIA DE {{ strtoupper($proceso) }}</h5><br><br>

             <div class="row">
                <div class="col-md-12">
                    <h5 class=" text-jus">
                        El (la) infrascrito(a) Encargado(a) de Práctica Profesional del Instituto Tecnológico de
                        Chalatenango /AGAPE (ITCHA/AGAPE), hace constar que de conformidad a la Ley de
                        Educación Superior, Capítulo I, Art. 19, literal C); el alumno(a) de la carrera:
                        {{ $estudiante->carrera->nombre }} ha realizado el {{ $proceso }} de acuerdo a lo que se detalla a
                        continuación:
                    </h4>
                </div><br>
                <div class="col-md-12">
                    <h5 class="text-left">Alumno(a): &nbsp;&nbsp; <u>{{ $estudiante->nombre." ".$estudiante->apellido }}</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total
                    horas: {{ $totalHoras }}</h5>
                </div><br><br>
                <div class="col-md-12">
                    <table class="col-md-12" border="1" cellpadding="7">
                        <tr>
                            <td class="text-center font-weight-bold">
                                Institución / Organización
                            </td>
                            <td class="text-center font-weight-bold">
                                Proyecto o actividad <br>
                                realizada
                            </td>
                            <td class="text-center font-weight-bold">
                                Período
                            </td>
                            <td class="text-center font-weight-bold">
                                Horas
                            </td>
                        </tr>
                        @foreach ($proyectos as $item)
                        <tr>
                            <td height="50" class="text-center">{{ $item->proyecto->institucion->nombre }}</td>
                            <td height="50">{{ $item->proyecto->nombre }}</td>
                            <td class="text-center" height="50">{{ $item->fecha_inicio." al ".$item->fecha_fin }}</td>
                            <td class="text-center" height="50">{{ $item->horas_realizadas }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div><br><br><br>
                <div class="col-md-12">
                    <table class="col-md-12"  cellpadding="15">
                        <tr>
                            <td colspan="10" style="padding-top: 10px;">
                                Fecha: <u>&nbsp;&nbsp;&nbsp;&nbsp;{{ $fecha }}&nbsp;&nbsp;&nbsp;&nbsp;</u>
                            </td>
                            <td class="text-center">
                             <br>F:_______________________________ <br>
                             <span class="text-muted text-center">
                                Lic. {{ $admin->nombre }}
                            </span>
                        </td>
                        <td>
                            <img  src="{{ public_path('images/img_reportes/cuadro.png') }}">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

</div>

</body>
