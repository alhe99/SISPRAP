<link rel="stylesheet" href="{{asset('css/bmdf.css')}}">

 <body>
 <div>
        <table class="table-bordered container-fluid" cellpadding="0" cellspacing="0"  style="border-collapse: collapse; heigth:auto;" >
            <thead> 
                    <img  src="images/img_reportes/logopequeño.png" style=" width: 50px; height:15px ; margin-top:-50px; position:absolute; " alt="">
                    <tr>
                        
                        {{--  <td style="background:  background-color: #fff;"></td>  --}}
                        <td class="text-center" style="font-size: 0.800em;  padding-top: 0px; " colspan="2"><strong>ASOCIACION AGAPE<br>DE EL SALVADOR</strong></td>
                        <td class="text-center" style="font-size: 0.800em;  padding-top: 0px; "><strong>DOCUMENTO DE LA CALIDAD<br>FORMULARIO</strong></td>
                        <td class="text-center" style="font-size: 0.800em;  padding-top: 0px; "><strong>CODIGO: F.IT.036<br>Verion: 02<br>Página 1 de 1</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: left; font-size: 0.800em;  height:10px;  padding-top: 0px;"><p><strong>Titulo: Constancia de Práctica Profesional</strong></p></td>
                        <td colspan="1" class="text-center" style="font-size: 0.800em; height:10px;  padding-top: 0px; "><p><strong>FECHA DE REVISION:<br> 14/07/2016</strong></p></td>
                    </tr>
                </thead>
        </table>
        </div>
         <br><br><br>
        <div class="row" style="">
       
            <div> 
                <p class="text-center"><strong>INSTITUTO TECNOLOGICO DE CHALATENANGO ITCHA/AGAPE<br><br>CONSTANCIA DE PRÁCTICA PROFESIONAL</strong></p>
            <div>
                <img style="margin-top:-90px; margin-left:630px;"  class="text-center img-fluid " src="images/img_reportes/logoITCHA.png" alt=""></div>
            </div>
        </div>
        <br><br><br><br>
        <div class="row">
            <div> 
                <p class="text-justify" style="margin-left:35px; margin-right:22px;">El (la) infrascrito(a) Supervisor(a) de Práctica Profesional del Instituto Tecnológico de Chalatenango /AGAPE(ITCHA/AGAPE), hace constar que de conformidad al <strong>M.IT.004: "Manual de Regulaciones", Capítulo V, Graduación</strong>, el alumno(a)
                de la carrera {{$gp->estudiante->carrera->nombre}} ha realizado el Práctica Profesional de acuerdo a lo que se detalla a continuación:</p>
        </div>
        <br><br>
        <div class="row">
            <div> 
            <p class="text-justify" style="margin-left:55px; margin-right:22px; margin-top:180px;">Alumno(a): {{$gp->estudiante->nombre}} Total horas: {{$gp->horas_realizadas}}</p>
        </div>
        
        <div>
        <table class="table-bordered container-fluid"   style=" heigth:auto; margin-top:250px; margin-left:15px; margin-right:17px;" >
            <thead> 
                    
                    <tr>
                        <th class="text-center"><strong>Empresa / Institución /<br> Organización</strong></th>
                        <th class="text-center"><strong>Proyecto o actividad<br>realizada</strong></th>
                        <th class="text-center"><strong>Periodo</strong></th>
                        <th class="text-center"><strong>Horas</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$gp->proyecto->institucion->nombre}}</td>
                        <td>{{$gp->proyecto->nombre}}</td>
                        <td>{{$gp->fecha_inicio . " - " . $gp->fecha_fin}}</td>
                        <td>{{$gp->horas_realizadas}}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
        </table>
        </div>
        <div class="row">
            <div> 
                <p class="text-justify" style="margin-left:55px; margin-right:22px; margin-top:450px;">Fecha: {{$fecha}}  &nbsp;&nbsp;&nbsp;&nbsp; F.:________________________</p><p style="margin-left:420px; font-size:x-small; margin-top:-20px; backgroup-color: #DADFDD;">Nombre del Supervisor(a)<br>Supervisor de Práctica Profesional</p>
                <img style="margin-left:650px; margin-top:-65px;" src="images/img_reportes/cuadro.fw.png">
        </div>

        
       
        
        
        
       
        
        {{-- <p style="font-size: 0.500em; margin-top: -25supx;">NOTA: Este documento debe ser presentado a mas tardar 5 días hábiles despúes de su retiro(No es válido si presenta tachadura, enmendedura y/o correciones),   *Campos NO obligatorios</p> --}}
        
        
         <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
         <script src="{{asset('js/popper.js')}}"></script>
         <script src="{{asset('js/bmd3.js')}}"></script>
         <script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>

        
</body>
