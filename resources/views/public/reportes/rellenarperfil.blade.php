<link rel="stylesheet" href="{{asset('css/bmdf.css')}}">

<body>
 <div>
        <table class="table-bordered container-fluid" cellpadding="0" cellspacing="0"  style="border-collapse: collapse; heigth:auto;" >
            <thead>
            <img  src="{{asset('images/img_reportes/logopequeño.png')}}" style=" width: 50px; height:15px ; margin-top:-50px; position:absolute; " alt="">
                    <tr>

                        {{--  <td style="background:  background-color: #fff;"></td>  --}}
                        <td class="text-center" style="font-size: 0.800em;  padding-top: 0px; " colspan="2"><strong>ASOCIACION AGAPE<br>DE EL SALVADOR</strong></td>
                        <td class="text-center" style="font-size: 0.800em;  padding-top: 0px; "><strong>DOCUMENTO DE LA CALIDAD<br>FORMULARIO</strong></td>
                        <td class="text-center" style="font-size: 0.800em;  padding-top: 0px; "><strong>CODIGO: F.IT.036<br>Verion: 02<br>Página 1 de 1</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: left; font-size: 0.800em;  height:10px;  padding-top: 0px;"><p><strong>Titulo: Perfil del proyecto de Servicio Social Estudiantil o Práctica Profesional</strong></p></td>
                        <td colspan="1" class="text-center" style="font-size: 0.800em; height:10px;  padding-top: 0px; "><p><strong>FECHA DE REVISION:<br> 14/07/2016</strong></p></td>
                    </tr>
                </thead>
        </table>
        </div>
        <div class="input-group" >
            {{--  <input type="radio" class="radiobox" name="style-0a2">  --}}
                <span class="radio" style="margin ">
                @if ($data['proceso'] == 1)
                <input type="radio" class="radiobox" name="style-0a2" style="margin-top:-110px; margin-left:350px;" checked>
                @else
                  <input type="radio" class="radiobox" name="style-0a2" style="margin-top:-110px; margin-left:350px;">
                @endif

                <label style="margin-top:-180px; margin-left:240px;  font-size:x-small; "><span> Servicio Social</span></label>
                <span class="radio" style="margin ">
                @if ($data['proceso'] == 2)
                <input type="radio" class="radiobox" name="style-0a2" style="margin-left:530px; margin-top:-110px;" checked>
                @else
                <input type="radio" class="radiobox" name="style-0a2" style="margin-left:530px; margin-top:-110px;">
                @endif
                <label style="margin-top:3px; margin-left:390px;  font-size:x-small;"><span> Practica Profesional</span></label>

            </span>

        </div>

        <div class="row">
        <label  style="margin-left: 115px; margin-top: -510px; font-size:small;"><strong>Datos del Estudiante<strong></label>
            {{--  <h6 style="margin-left: 70px; margin-top: -500px;">Datos del Estudiante</h6>  --}}
        </div>
            {{ $data }}
        <div class="row" style="margin-top: -460px;">
            <div> <br>
            <img style="margin-top:-530px;" class="text-center img-fluid " src="{{asset('images/img_reportes/logoITCHA.png')}}" alt=""></div>
            <div>
                <table style="margin-left: 60px; margin-right:7px; margin-top:-510px; " cellpadding="4" cellspacing="4" class="table-bordered container-fluid" style="border-collapse: collapse; heigth:auto;">
                    <tr style="width:35px;">
                        <td style=" font-size:x-small; width:20px;">Nombres</td>
                        <td style=" font-size:x-small;" colspan="2">{{$data['nombreE']}}</td>
                        <td style=" font-size:x-small;">#Carnét: {{$data['carnetE']}}</td>
                    </tr>
                    <tr>
                        <td style=" font-size:x-small;">Apellidos</td>
                        <td style=" font-size:x-small;" colspan="2">{{$data['apellidoE']}}</td>
                        <td style=" font-size:x-small;">Teléfono(s):  {{$data['telefonoE']}} </td>
                    </tr>
                    <tr>
                        <td style=" font-size:x-small;">Carrera</td>
                        <td style=" font-size:x-small;" colspan="2"> {{$data['carreraE']}}</td>
                        <td style=" font-size:x-small;"ss>Correo electrónico: {{$data['emailE']}}</td>
                    </tr>
                </table>
                <br>
            </div>
        </div>
        <div class="row">
        <label   style="margin-top: -400px; margin-left:45px; font-size:small;"><strong>Datos de la institución / Organización donde realizará el proyecto<strong></label>

        </div>
       <div>
            <table width="575"  cellpadding="2" cellspacing="2" class="table table-bordered container-fluid" style="border: solid 1px #; margin-top:-400px; table-layout:fixed ">
                <tr  valign="middle">
                    <td class="text-left" text-align="left" style="font-size:x-small; padding-top: 0px;" rowspan="2" colspan="2">Nombre de la institución/organizacion/empresa: {{$data['nombreI']}}<br><br></td>
                    <td class="text-center" style="font-size:x-small; padding-top: 0px;" colspan="2" ><strong>Sector principal de la institución/<br>Organización(A que se dedica)</strong><br>  </td>
                </tr>
                <tr text-align="center" valign="middle">

                     <td class="text-left"  colspan="2" rowspan="2" style="font-size:x-small; padding-top: 0px;" >Sector: <br> {{$data['sectorI']}}</td>
                </tr>
                <tr text-align="left" valign="middle">
                    <td class="text-left" colspan="2" style="font-size:x-small; padding-top: 0px;" >Dirección: <br>{{$data['direccionI']}}</td>
                </tr>
                <tr text-align="left" valign="middle">
                    <td class="text-left" style="font-size:x-small; padding-top: 0px;">Departamento: {{$data['departamentoI']}}</td>
                    <td class="text-left" style="font-size:x-small; padding-top: 0px;">Municipio: {{$data['municipioI']}}</td>
                    <td class="text-left" style="font-size:x-small; padding-top: 0px;">Correo Electrónico: <br> {{$data['emailI']}}</td>
                    <td class="text-left" style="font-size:x-small; padding-top: 0px;">Teléfono(s): {{$data['telefonoI']}}</td>
                </tr>
             </table>
       </div>

        <div>
        <label style="margin-top: -220px; font-size:small; margin-left:30px;"><strong>Datos sobre las actividades o proyectos a realizar</strong></label>
        </div>
        <div>
             <table width="580"  cellpadding="0" cellspacing="0" class="table-bordered container-fluid"  style="border: solid 1px #; margin-top:-220px; ">
            <tr  valign="middle">
                <td text-align="left" style="font-size:x-small;" rowspan="2" colspan="3">Nombre de acuerdo a la actividad principal que realiza:<br> {{$data['nombreP']}}<br></td>
                <td class="text-center" style="font-size:x-small;" colspan="1">No. de Horas a realizar</td>
            </tr>
            <tr text-align="center" valign="middle">
                <td colspan="1"  style="font-size:small;" > {{$data['hrasRealizar']}} </td>
            </tr>

            <tr text-align="left" valign="middle">
                <td colspan="4" style="font-size:x-small;"  >Descripción del Proyecto(describa todas las actividades a desarrollar):<br> {!!$data['actividadesP']!!}<br><br><br></td>
            </tr>
            <tr text-align="left" valign="middle">
                <td style="font-size:x-small;">Fecha inicio: <br> {{$data['fechaInicio']}}<br></td>
                <td style="font-size:x-small;">Fecha de finalización<br> {{$data['fechaFin']}}<br></td>
                <td style="font-size:x-small; " cellspacing="3">Nombre del supervisor de la institución o empresa<br> {{$data['nombreS']}}<br></td>
                <td style="font-size:x-small;">Tel del Supervisor<br> {{$data['telefonoS']}}<br></td>
            </tr>
        </table>
        </div>

        <table width="575" style="margin-top: 15px;"   cellpadding="0" cellspacing="0" class="table table-bordered container-fluid" style="border: solid 1px #; ">
            <tr text-align="left" valign="middle">
                <td style="font-size:xx-small;">Firma del estudiante que desarrolla el proyecto:<br><br><br>F.___________________________</td>
        <td class="text-center" style="font-size:xx-small;">Firma y sello de aprobacion de la supervision de practica profesional ITCHA: <br>F.___________________ <img style="width:70px; heigth:70; margin-left:40px; margin-top:10px;" src="{{asset('images/img_reportes/circulo.png')}}" alt=""></td>
                <td style="font-size:xx-small;">Fecha presentación:___________________<br><br>Factura #:___________________</td>
            </tr>
        </table>
        {{-- <p style="font-size: 0.500em; margin-top: -25supx;">NOTA: Este documento debe ser presentado a mas tardar 5 días hábiles despúes de su retiro(No es válido si presenta tachadura, enmendedura y/o correciones),   *Campos NO obligatorios</p> --}}
        <p style="font-size: xx-small; margin-top: 10px;">NOTA: Este documento debe ser presentado a mas tardar 5 días hábiles despúes de su retiro(No es válido si presenta tachadura, enmendedura y/o correciones),   *Campos NO obligatorios</p>

        <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
        <script src="{{asset('js/popper.js')}}"></script>
        <script src="{{asset('js/bootstrap-material-design.js')}}"></script>
        <script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>


    </body>
