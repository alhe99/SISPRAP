<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estudiantes Pendientes de Finalizar {{ ucfirst(strtolower($procesoTitulo)) }}</title>
    <link rel="stylesheet" href="{{asset('css/bmd.css')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-favicon.png') }}">
    <style>
    .bg-header{background-color:#F8EFB6}
    .font-normal{font-weight: normal;}
    table{width:100%;page-break-inside: avoid;}
</style>
</head>
<body style="background-color:white">
    <header>
        <div class="row text-center">
            <div class="col-md-8">
               <img class="img-fluid" width="450" src="{{asset('images/header_reportes.PNG')}}">
           </div>
       </div>
   </header>
   <div class="row m-5 ">
    <div class="row">
        <div class="col-md-12">
            <h6 class="text-center"><strong>INSTITUTO TECNOLÓGICO DE CHALATENANGO</strong></h6>
            <h6 class="text-center"><strong>ASOCIACION AGAPE DE EL SALVADOR</strong></h6><br>
            <p class="text-center font-weight-bold"><u>INFORME DE ESTUDIANTES PENDIENTES DE FINALIZAR {{ $procesoTitulo }} DE {{ $anio }}</u></p><br>
            @if ($tipo == 'M')
            <p class="text-center font-weight-bold"><u>MES(ES): {{ implode(", ", $meses) }}</u></strong></p>
            @elseif($tipo == 'T')
            <p class="text-center font-weight-bold"><u>MES(ES): {{ $meses }}</u></strong></p>
            @elseif($tipo == 'A')
            <p class="text-center font-weight-bold"><u>INFORME ANUAL</u></strong></p>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        @if ($tipo == 'T')
        {{-- TABLA PARA MESES INDIVIDUALES --}}
        @for ($i = 0; $i < count($mensuales) ; $i++)
         <h6 class="font-weight-bold text-center"><u>&nbsp;{{ $mensuales[$i][0] }}&nbsp;</u></h6>
         @php($tieneDatos = false)
            @for ($j = 1; $j < count($mensuales[$i]) ; $j++)
             @foreach ($mensuales[$i][$j][1] as $key => $item)
               @if ($item->count() > 0)
                @php($tieneDatos = true)
                <table class="mb-1"  border="1px" cellpadding="10">
                    <tr class="bg-header text-center font-weight-bold">
                        <td class="text-left font-weight-bold" width="50%">Carrera: {{ substr($mensuales[$i][$j][0],11) }}</td>
                        <td>Nivel Académico: {{ $key }}</td>
                    </tr>
                    <tr class="bg-header text-center font-weight-bold">
                        <td>Nombre del Alumnos</td>
                        <td>Documentos Pendientes</td>
                    </tr>

                    @foreach ($item as $estudiante)
                        <tr>
                            <td>{{ $estudiante["nombre"]." ".$estudiante["apellido"] }}</td>
                            <td>
                                @if (count($estudiante["documentosEntregados"]) == 0)
                                    <small style="font-size: 15px" class="text-center font-weight-bold">Documentos completados, cierre de proyecto pendiente</small>
                                @else
                                    @for ($k=0;$k < count($estudiante["documentosEntregados"]);$k++)
                                          &#126;{{ $estudiante["documentosEntregados"][$k]->nombre }}<br>
                                    @endfor
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-weight-bold bg-header">
                        <td class="text-right">Total De Estudiantes Pendientes</td>
                        <td>{{ $item->count() }}</td>
                    </tr>
                </table><br>
              @endif
            @endforeach
          @endfor
          @if (!$tieneDatos)
          <div class="row m-4">
             <div class="col-md-12 text-center">
                <span class="text-center h6">No hay datos para el mes de <strong>{{ $mensuales[$i][0] }}</strong>.</span>
             </div>
           </div>
         @endif
        @endfor
         {{-- TABLAS PARA CONSOLIDADO FINAL POR NIVEL ACADEMICO --}}
        @for ($i = 0; $i < count($consolidadoByNivel) ; $i++)
         <?php $cuentaMined = 0;$cuentaOtros=0; ?>
          @if (count($consolidadoByNivel[$i]) != 0)
            <table width="100%" border="1px" cellpadding="6">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{"CONSOLIDADO ".strtoupper($consolidadoByNivel[$i][0])}}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Carrera</td>
                        <td>Becados Mined</td>
                        <td>Otros</td>
                    </tr>
                </thead>
                <tbody>
                    @for ($j = 1; $j < count($consolidadoByNivel[$i]); $j++)
                       <?php
                            $cuentaMined += $consolidadoByNivel[$i][$j]['totalBecaMined'];
                            $cuentaOtros += $consolidadoByNivel[$i][$j]['totalOtraBeca'];
                        ?>
                    <tr>
                        <th class="font-normal">{{$consolidadoByNivel[$i][$j]['Carrera']}}</th>
                        <th class="text-center font-normal">{{$consolidadoByNivel[$i][$j]['totalBecaMined']}}</th>
                        <th class="text-center font-normal">{{$consolidadoByNivel[$i][$j]['totalOtraBeca']}}</th>
                    </tr>
                    @endfor
                    <tr class="bg-header font-weight-bold">
                        <td class="text-right" rowspan="2">Total:</td>
                        <td class="text-center">{{ $cuentaMined }}</td>
                        <td class="text-center">{{ $cuentaOtros }}</td>
                    </tr>
                    <tr class="bg-header font-weight-bold text-center">
                       <td colspan="2">{{ $cuentaMined + $cuentaOtros }}</td>
                   </tr>
                </tbody>
            </table><br>
           @endif
        @endfor
        {{-- FIN DE TABLAS PARA CONSOLIDADO FINAL POR NIVEL ACADEMICO --}}
         {{-- TABLA PARA CONSOLIDADO FINAL GENERAL --}}
        <table width="100%" border="1px" cellpadding="6">
            <thead class="font-weight-bold text-center bg-header">
                <tr>
                    <td colspan="4">{{"CONSOLIDADO ".$consolidadoGeneral[0]." (GENERAL)"}}</td>
                </tr>
                <tr class="text-center">
                    <td>Carrera</td>
                    <td>Becados Mined</td>
                    <td>Otros</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $cuentaMined = 0;$cuentaOtros=0;
                ?>
                @for ($i = 1; $i < count($consolidadoGeneral); $i++)
                    <?php
                        $cuentaMined += $consolidadoGeneral[$i]['totalBecaMined'];
                        $cuentaOtros += $consolidadoGeneral[$i]['totalOtraBeca'];
                    ?>
                <tr>
                    <th class="font-normal">{{$consolidadoGeneral[$i]['Carrera']}}</th>
                    <th class="text-center font-normal">{{$consolidadoGeneral[$i]['totalBecaMined']}}</th>
                    <th class="text-center font-normal">{{$consolidadoGeneral[$i]['totalOtraBeca']}}</th>
                </tr>
                @endfor
                <tr class="bg-header font-weight-bold">
                    <td class="text-right" rowspan="2">Total:</td>
                    <td class="text-center">{{ $cuentaMined }}</td>
                    <td class="text-center">{{ $cuentaOtros }}</td>
                </tr>
                <tr class="bg-header font-weight-bold text-center">
                   <td  colspan="2">{{ $cuentaMined + $cuentaOtros }}</td>
               </tr>
            </tbody>
        </table>
        {{-- FIN DE TABLA PARA CONSOLIDADO FINAL GENERAL --}}
        @elseif($tipo == 'M')

         {{-- TABLA PARA MESES INDIVIDUALES --}}
        @for ($i = 0; $i < count($mensuales) ; $i++)
         <h6 class="font-weight-bold text-center"><u>&nbsp;{{ $mensuales[$i][0] }}&nbsp;</u></h6>
         @php($tieneDatos = false)
            @for ($j = 1; $j < count($mensuales[$i]) ; $j++)
             @foreach ($mensuales[$i][$j][1] as $key => $item)
               @if ($item->count() > 0)
                @php($tieneDatos = true)
                <table class="mb-1"  border="1px" cellpadding="7">
                    <tr class="bg-header text-center font-weight-bold">
                        <td class="text-left font-weight-bold" width="50%">Carrera: {{ substr($mensuales[$i][$j][0],11) }}</td>
                        <td>Nivel Académico: {{ $key }}</td>
                    </tr>
                    <tr class="bg-header text-center font-weight-bold">
                        <td>Nombre del Alumnos</td>
                        <td>Documentos Pendientes</td>
                    </tr>

                    @foreach ($item as $estudiante)
                        <tr>
                            <td>{{ $estudiante["nombre"]." ".$estudiante["apellido"] }}</td>
                            <td>
                                @if (count($estudiante["documentosRestantes"]) == 0)
                                    <small style="font-size: 15px" class="text-center font-weight-bold">Documentos completados, cierre de proyecto pendiente</small>
                                @else
                                    @for ($k=0;$k < count($estudiante["documentosRestantes"]);$k++)
                                          &#126;{{ $estudiante["documentosRestantes"][$k]->nombre }}<br>
                                    @endfor
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-weight-bold bg-header">
                        <td class="text-right">Total De Estudiantes Pendientes</td>
                        <td>{{ $item->count() }}</td>
                    </tr>
                </table><br>
              @endif
            @endforeach
          @endfor
          @if (!$tieneDatos)
          <div class="row m-4">
             <div class="col-md-12 text-center">
                <span class="text-center h6">No hay datos para el mes de <strong>{{ $mensuales[$i][0] }}</strong>.</span>
             </div>
           </div>
         @endif
        @endfor

        {{-- TABLAS PARA CONSOLIDADO FINAL POR NIVEL ACADEMICO --}}
        @for ($i = 0; $i < count($consolidadoByNivel) ; $i++)
         <?php $cuentaMined = 0;$cuentaOtros=0; ?>
          @if (count($consolidadoByNivel[$i]) != 0)
            <table width="100%" border="1px" cellpadding="6">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{"CONSOLIDADO ".strtoupper($consolidadoByNivel[$i][0])}}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Carrera</td>
                        <td>Becados Mined</td>
                        <td>Otros</td>
                    </tr>
                </thead>
                <tbody>
                    @for ($j = 1; $j < count($consolidadoByNivel[$i]); $j++)
                       <?php
                            $cuentaMined += $consolidadoByNivel[$i][$j]['totalBecaMined'];
                            $cuentaOtros += $consolidadoByNivel[$i][$j]['totalOtraBeca'];
                        ?>
                    <tr>
                        <th class="font-normal">{{$consolidadoByNivel[$i][$j]['Carrera']}}</th>
                        <th class="text-center font-normal">{{$consolidadoByNivel[$i][$j]['totalBecaMined']}}</th>
                        <th class="text-center font-normal">{{$consolidadoByNivel[$i][$j]['totalOtraBeca']}}</th>
                    </tr>
                    @endfor
                    <tr class="bg-header font-weight-bold">
                        <td class="text-right" rowspan="2">Total:</td>
                        <td class="text-center">{{ $cuentaMined }}</td>
                        <td class="text-center">{{ $cuentaOtros }}</td>
                    </tr>
                    <tr class="bg-header font-weight-bold text-center">
                       <td colspan="2">{{ $cuentaMined + $cuentaOtros }}</td>
                   </tr>
                </tbody>
            </table><br>
           @endif
        @endfor
        {{-- FIN DE TABLAS PARA CONSOLIDADO FINAL POR NIVEL ACADEMICO --}}
        @elseif($tipo == 'A')

         {{-- TABLA PARA MESES INDIVIDUALES --}}
        @for ($i = 0; $i < count($mensuales) ; $i++)
         <h6 class="font-weight-bold text-center"><u>&nbsp;{{ $mensuales[$i][0] }}&nbsp;</u></h6>
         @php($tieneDatos = false)
            @for ($j = 1; $j < count($mensuales[$i]) ; $j++)
             @foreach ($mensuales[$i][$j][1] as $key => $item)
               @if ($item->count() > 0)
               @php($tieneDatos = true)
                <table class="mb-1"  border="1px" cellpadding="7">
                    <tr class="bg-header text-center font-weight-bold">
                        <td class="text-left font-weight-bold" width="50%">Carrera: {{ substr($mensuales[$i][$j][0],11) }}</td>
                        <td>Nivel Académico: {{ $key }}</td>
                    </tr>
                    <tr class="bg-header text-center font-weight-bold">
                        <td>Nombre del Alumnos</td>
                        <td>Documentos Pendientes</td>
                    </tr>

                    @foreach ($item as $estudiante)
                        <tr>
                            <td>{{ $estudiante["nombre"]." ".$estudiante["apellido"] }}</td>
                            <td>
                                @if (count($estudiante["documentosRestantes"]) == 0)
                                    <small style="font-size: 15px" class="text-center font-weight-bold">Documentos completados, cierre de proyecto pendiente</small>
                                @else
                                    @for ($k=0;$k < count($estudiante["documentosRestantes"]);$k++)
                                          &#126;{{ $estudiante["documentosRestantes"][$k]->nombre }}<br>
                                    @endfor
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-weight-bold bg-header">
                        <td class="text-right">Total De Estudiantes Pendientes</td>
                        <td>{{ $item->count() }}</td>
                    </tr>
                </table><br>
              @endif
            @endforeach
          @endfor
          @if (!$tieneDatos)
          <div class="row m-4">
             <div class="col-md-12 text-center">
                <span class="text-center h6">No hay datos para el mes de <strong>{{ $mensuales[$i][0] }}</strong>.</span>
             </div>
           </div>
         @endif
        @endfor
        {{-- TABLAS PARA CONSOLIDADO FINAL ANUAL POR NIVEL ACADEMICO --}}
        @for ($i = 0; $i < count($consolidadoAnualByNivel) ; $i++)
         <?php $cuentaMined = 0;$cuentaOtros=0; ?>
          @if (count($consolidadoAnualByNivel[$i]) != 0)
            <table width="100%" border="1px" cellpadding="6">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{"CONSOLIDADO ".strtoupper($consolidadoAnualByNivel[$i][0])}}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Carrera</td>
                        <td>Becados Mined</td>
                        <td>Otros</td>
                    </tr>
                </thead>
                <tbody>
                    @for ($j = 1; $j < count($consolidadoAnualByNivel[$i]); $j++)
                       <?php
                            $cuentaMined += $consolidadoAnualByNivel[$i][$j]['totalBecaMined'];
                            $cuentaOtros += $consolidadoAnualByNivel[$i][$j]['totalOtraBeca'];
                        ?>
                    <tr>
                        <th class="font-normal">{{$consolidadoAnualByNivel[$i][$j]['Carrera']}}</th>
                        <th class="text-center font-normal">{{$consolidadoAnualByNivel[$i][$j]['totalBecaMined']}}</th>
                        <th class="text-center font-normal">{{$consolidadoAnualByNivel[$i][$j]['totalOtraBeca']}}</th>
                    </tr>
                    @endfor
                    <tr class="bg-header font-weight-bold">
                        <td class="text-right" rowspan="2">Total:</td>
                        <td class="text-center">{{ $cuentaMined }}</td>
                        <td class="text-center">{{ $cuentaOtros }}</td>
                    </tr>
                    <tr class="bg-header font-weight-bold text-center">
                       <td colspan="2">{{ $cuentaMined + $cuentaOtros }}</td>
                   </tr>
                </tbody>
            </table><br>
           @endif
        @endfor
        {{-- FIN DE TABLAS PARA CONSOLIDADO FINAL ANUAL POR NIVEL ACADEMICO --}}
        {{-- TABLA PARA CONSOLIDADO FINAL GENERAL ANUAL --}}
        <table width="100%" border="1px" cellpadding="6">
            <thead class="font-weight-bold text-center bg-header">
                <tr>
                    <td colspan="4">{{"CONSOLIDADO ".$consolidadoAnualGeneral[0]." (GENERAL)"}}</td>
                </tr>
                <tr class="text-center">
                    <td>Carrera</td>
                    <td>Becados Mined</td>
                    <td>Otros</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $cuentaMined = 0;$cuentaOtros=0;
                ?>
                @for ($i = 1; $i < count($consolidadoAnualGeneral); $i++)
                    <?php
                        $cuentaMined += $consolidadoAnualGeneral[$i]['totalBecaMined'];
                        $cuentaOtros += $consolidadoAnualGeneral[$i]['totalOtraBeca'];
                    ?>
                <tr>
                    <th class="font-normal">{{$consolidadoAnualGeneral[$i]['Carrera']}}</th>
                    <th class="text-center font-normal">{{$consolidadoAnualGeneral[$i]['totalBecaMined']}}</th>
                    <th class="text-center font-normal">{{$consolidadoAnualGeneral[$i]['totalOtraBeca']}}</th>
                </tr>
                @endfor
                <tr class="bg-header font-weight-bold">
                    <td class="text-right" rowspan="2">Total:</td>
                    <td class="text-center">{{ $cuentaMined }}</td>
                    <td class="text-center">{{ $cuentaOtros }}</td>
                </tr>
                <tr class="bg-header font-weight-bold text-center">
                   <td  colspan="2">{{ $cuentaMined + $cuentaOtros }}</td>
               </tr>
            </tbody>
        </table>
        {{-- FIN DE TABLA PARA CONSOLIDADO FINAL GENERAL ANUAL --}}
        @endif
    </div>
</div>
</body>
</html>