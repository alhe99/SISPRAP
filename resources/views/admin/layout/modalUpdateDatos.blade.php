 {{-- MODAL PARA ACTUALIZAR LOS DATOS DEL USUARIO LOG --}}
 <div class="modal" id="modalUpdateDatos" tabindex="-4" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 60px;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-center text-white" id="exampleModalLongTitle">Datos de usuario</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('updateUsuario') }}" method="POST">
               {{ csrf_field() }}
               <input type="hidden" name="id" id="id" value="{{ Auth::user()->id }}">
               <div class="modal-body">
                <div class="row ">
                    <div class="col-md-12">
                        <h4 class="font-weight-bold">Nombre(*)</h4>
                        <input type="text" class="form-control" name="nombre" id="nombre" value="{{ Auth::user()->nombre }}">
                    </div>
                </div>
                <br>
                <div class="row ">
                    <div class="col-md-12">
                       <h4 class="font-weight-bold">Usuario(*)</h4>
                       <input type="text" class="form-control" name="usuario" id="usuario" value="{{ Auth::user()->usuario }}">
                   </div>
               </div>
               <br>
               <div class="row">
                <div class="col-md-12">
                    <button id="btnOpenPasswordsFields" class="btn btn-primary font-weight-bold text-dark btn-lg btn-block" data-toggle="collapse" data-target="#divPasswords" aria-expanded="false" aria-controls="divPasswords" type="button"><i class="mdi mdi-key-variant"></i>&nbsp;
                        Cambiar contraseña
                    </button>
                    <div class="collapse" id="divPasswords">
                        <br><div class="row">
                            <div class="col-md-6" data-wow-delay=".1s">
                                <div class="form-group">
                                    <h4 class="font-weight-bold">Nueva contraseña(*)</h4>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <div class="col-md-6" data-wow-delay=".1s">
                                <div class="form-group">
                                   <h4 class="font-weight-bold">Confirmar contraseña(*)</h4>
                                   <input type="password" class="form-control" name="cPassword" id="cPassword">
                               </div>
                           </div>
                           <div class="col-md-12" id="divNoMatchPasswords" style="display: none;">
                               <div class="alert alert-warning font-weight-bold text-center" role="alert">
                                   Las contraseñas no coinciden
                               </div>
                           </div>
                       </div>
                    </div>
             </div>
         </div>
     </div>
     <div class="modal-footer">
        <button type="button" class="button red" data-target="#exampleModal" data-dismiss="modal"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
        <button type="submit"class="button blue  disabled " id="btnUpdateData" disabled><i class="mdi mdi-content-save"></i>&nbsp;Guardar</button>
    </div>
</form>
</div>
</div>
</div>
