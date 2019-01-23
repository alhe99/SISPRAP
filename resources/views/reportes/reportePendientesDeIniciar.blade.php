<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estudiantes Pendientes de Iniciar</title>
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
            <p class="text-center font-weight-bold"><u>INFORME DE ESTUDIANTES PENDIENTES DE INICIO DE {{ $procesoTitulo }} DE {{ $anio}}</u></p><br>
            @if ($tipo == 'M')
            <p class="text-center font-weight-bold"><u>MES(ES): {{ implode(",", $meses) }}</u></strong></p>
            @elseif($tipo == 'T')
            <p class="text-center font-weight-bold"><u>MES(ES): {{ $meses }}</u></strong></p>
            @elseif($tipo == 'A')
            <p class="text-center font-weight-bold"><u>INFORME ANUAL</u></strong></p>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        @if ($tipo == 'T')
    {{-- TABLA PARA DATOS DE LOS MESES INDIVIDUALES --}}
            @for ($i = 0; $i < count($mensuales) ; $i++)
                <h5 class="font-weight-bold text-center"><u>&nbsp;{{ $mensuales[$i][0] }}&nbsp;</u></h5>
                @for ($j = 1; $j < count($mensuales[$i]) ; $j++)
                 <?php $tieneDatos = false ?>
                    @foreach ($mensuales[$i][$j][1] as $key => $item)
                        @if ($item->count() != 0)
                            <?php $tieneDatos = true ?>
                            <table class="mb-1"  border="2px" cellpadding="7">
                                <tr class="bg-header text-center font-weight-bold">
                                    <td>Carrera: {{ $mensuales[$i][$j][0] }} </td>
                                    <td>Nivel Académico: {{ $key }} </td>
                                </tr>
                                <tr class="bg-header text-center font-weight-bold">
                                    <td colspan="2">Nombre del Alumnos</td>
                                </tr>
                                @foreach ($item as $estudiante)
                                <tr>
                                  <td colspan="2">{{ $estudiante["nombre"]." ".$estudiante["apellido"] }}</td>
                              </tr>
                              @endforeach
                              <tr class="font-weight-bold bg-header">
                                <td class="text-right">Total</td>
                                <td>{{ count($item) }}</td>
                            </tr>
                        </table><br>
                        @endif
                    @endforeach
                @endfor
                @if (!$tieneDatos)
                <div class="row">
                    <div class="col-md-12 text-center">
                        <br><span class="text-center">No hay datos para el mes de <strong>{{ strtolower($mensuales[$i][0]) }}</strong></span>
                    </div>
                </div><br>
                @endif
            @endfor

         {{-- TABLAS PARA CONSOLIDADO FINAL POR NIVEL ACADEMICO --}}
        @for ($i = 0; $i < count($consolidadoPorNivel) ; $i++)
         @php $cuentaMined = 0;$cuentaOtros=0; @endphp
          @if (count($consolidadoPorNivel[$i]) != 0)
            <table width="100%" border="1px" cellpadding="6">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{"CONSOLIDADO ".strtoupper($consolidadoPorNivel[$i][0])}}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Carrera</td>
                        <td>Becados Mined</td>
                        <td>Otros</td>
                    </tr>
                </thead>
                <tbody>
                    @for ($j = 1; $j < count($consolidadoPorNivel[$i]); $j++)
                        @php
                            $cuentaMined += $consolidadoPorNivel[$i][$j]['totalBecaMined'];
                            $cuentaOtros += $consolidadoPorNivel[$i][$j]['totalOtraBeca'];
                        @endphp
                    <tr>
                        <th class="font-normal">{{$consolidadoPorNivel[$i][$j]['Carrera']}}</th>
                        <th class="text-center font-normal">{{$consolidadoPorNivel[$i][$j]['totalBecaMined']}}</th>
                        <th class="text-center font-normal">{{$consolidadoPorNivel[$i][$j]['totalOtraBeca']}}</th>
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
                    <td colspan="4">{{"CONSOLIDADO ".$consolidado[0]." (GENERAL)"}}</td>
                </tr>
                <tr class="text-center">
                    <td>Carrera</td>
                    <td>Becados Mined</td>
                    <td>Otros</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $cuentaMined = 0;$cuentaOtros=0;
                @endphp
                @for ($i = 1; $i < count($consolidado); $i++)
                    @php
                        $cuentaMined += $consolidado[$i]['totalBecaMined'];
                        $cuentaOtros += $consolidado[$i]['totalOtraBeca'];
                    @endphp
                <tr>
                    <th class="font-normal">{{$consolidado[$i]['Carrera']}}</th>
                    <th class="text-center font-normal">{{$consolidado[$i]['totalBecaMined']}}</th>
                    <th class="text-center font-normal">{{$consolidado[$i]['totalOtraBeca']}}</th>
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
        {{-- REPORTE MENSUAL --}}
        @elseif($tipo == 'M')
        {{-- TABLA PARA DATOS DE LOS MESES INDIVIDUALES --}}
            @for ($i = 0; $i < count($mensuales) ; $i++)
                <h5 class="font-weight-bold text-center"><u>&nbsp;{{ $mensuales[$i][0] }}&nbsp;</u></h5>
                @for ($j = 1; $j < count($mensuales[$i]) ; $j++)
                 <?php $tieneDatos = false ?>
                    @foreach ($mensuales[$i][$j][1] as $key => $item)
                        @if ($item->count() != 0)
                            <?php $tieneDatos = true ?>
                            <table class="mb-1"  border="2px" cellpadding="7">
                                <tr class="bg-header text-center font-weight-bold">
                                    <td>Carrera: {{ $mensuales[$i][$j][0] }} </td>
                                    <td>Nivel Académico: {{ $key }} </td>
                                </tr>
                                <tr class="bg-header text-center font-weight-bold">
                                    <td colspan="2">Nombre del Alumnos</td>
                                </tr>
                                @foreach ($item as $estudiante)
                                <tr>
                                  <td colspan="2">{{ $estudiante["nombre"]." ".$estudiante["apellido"] }}</td>
                              </tr>
                              @endforeach
                              <tr class="font-weight-bold bg-header">
                                <td class="text-right">Total</td>
                                <td>{{ count($item) }}</td>
                            </tr>
                        </table><br>
                        @endif
                    @endforeach
                @endfor
                @if (!$tieneDatos)
                <div class="row">
                    <div class="col-md-12 text-center">
                        <br><span class="text-center">No hay datos para el mes de <strong>{{ strtolower($mensuales[$i][0]) }}</strong></span>
                    </div>
                </div><br>
                @endif
            @endfor

            {{-- TABLA PARA EL CONSOLIDADO DE LOS MESES SELECCIONADOS --}}
            @for ($i = 0; $i < count($consolidado) ; $i++)
             @php $cuentaMined = 0;$cuentaOtros=0; @endphp
              @if (count($consolidado[$i]) != 0)
                <table width="100%" border="1px" cellpadding="6">
                    <thead class="font-weight-bold text-center bg-header">
                        <tr>
                            <td colspan="4">{{"CONSOLIDADO ".strtoupper($consolidado[$i][0])}}</td>
                        </tr>
                        <tr class="text-center">
                            <td>Carrera</td>
                            <td>Becados Mined</td>
                            <td>Otros</td>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($j = 1; $j < count($consolidado[$i]); $j++)
                            @php
                                $cuentaMined += $consolidado[$i][$j]['totalBecaMined'];
                                $cuentaOtros += $consolidado[$i][$j]['totalOtraBeca'];
                            @endphp
                        <tr>
                            <th class="font-normal">{{$consolidado[$i][$j]['Carrera']}}</th>
                            <th class="text-center font-normal">{{$consolidado[$i][$j]['totalBecaMined']}}</th>
                            <th class="text-center font-normal">{{$consolidado[$i][$j]['totalOtraBeca']}}</th>
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

        {{-- REPORTE ANUAL --}}
        @elseif($tipo == 'A')
        {{-- TABLA PARA MESES INDIVIDUALES --}}

         @for ($i = 0; $i < count($consolidadoMensual) ; $i++)
            <h5 class="font-weight-bold text-center"><u>&nbsp;{{ $consolidadoMensual[$i][0] }}&nbsp;</u></h5>
         @for ($j = 1; $j < count($consolidadoMensual[$i]) ; $j++)
            @php($tieneDatos = false)
            @foreach ($consolidadoMensual[$i][$j][1] as $key => $item)
                @if (count($item) > 0)
                    @php($tieneDatos = true)
                    <table class="mb-1"  border="2px" cellpadding="7">
                        <tr class="bg-header text-center font-weight-bold">
                            <td>Carrera: {{ $consolidadoMensual[$i][$j][0] }} </td>
                            <td>Nivel Académico: {{ $key }} </td>
                        </tr>
                        <tr class="bg-header text-center font-weight-bold">
                            <td colspan="2">Nombre del Alumnos</td>
                        </tr>
                        @foreach ($item as $estudiante)
                            <tr>
                                  <td colspan="2">{{ $estudiante["nombre"]." ".$estudiante["apellido"] }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-weight-bold bg-header">
                            <td class="text-right">Total</td>
                            <td>{{ count($item) }}</td>
                        </tr>
                        </table><br>
                    @endif
                @endforeach
         @endfor
             @if (!$tieneDatos)
             <div class="row">
                <div class="col-md-12 text-center">
                    <span class="text-center">No hay datos para el mes de <strong>{{ strtolower($consolidadoMensual[$i][0]) }}</strong></span>
                </div>
            </div><br>
            @endif
        @endfor
        {{-- TABLA PARA CONSOLIDADO FINAL POR NIVEL ACADEMICO  --}}
        @for ($i = 0; $i < count($consolidadoAnualNiveles) ; $i++)
        <?php $cuentaMined = 0;$cuentaOtros=0; ?>
         @if (count($consolidadoAnualNiveles[$i]) != 0)
            <table width="100%" border="1px" cellpadding="6">
                <thead class="font-weight-bold text-center bg-header">
                    <tr>
                        <td colspan="4">{{"CONSOLIDADO ".strtoupper($consolidadoAnualNiveles[$i][0])}}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Carrera</td>
                        <td>Becados Mined</td>
                        <td>Otros</td>
                    </tr>
                </thead>
                <tbody>
                    @for ($j = 1; $j < count($consolidadoAnualNiveles[$i]); $j++)
                    <?php
                    $cuentaMined += $consolidadoAnualNiveles[$i][$j]['totalBecaMined'];
                    $cuentaOtros += $consolidadoAnualNiveles[$i][$j]['totalOtraBeca'];
                    ?>
                    <tr>
                        <th class="font-normal">{{$consolidadoAnualNiveles[$i][$j]['Carrera']}}</th>
                        <th class="text-center font-normal">{{$consolidadoAnualNiveles[$i][$j]['totalBecaMined']}}</th>
                        <th class="text-center font-normal">{{$consolidadoAnualNiveles[$i][$j]['totalOtraBeca']}}</th>
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
     {{-- TABLA DEL CONSOLIDADO FINAL GENERAL ANUAL --}}
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
    @endif
    </div>
</div>
</body>
</html>