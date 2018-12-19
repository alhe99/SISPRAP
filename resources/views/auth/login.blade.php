@extends('auth.contenido') 
@section('login')
<div class="wrap-login100">
  <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <span class="login100-form-title p-b-26">
    						Bienvenido/a
              </span>
    <span class="login100-form-title p-b-48 text-center">
    <img src="{{ asset('images/logoITCHAL.png') }}" alt="ITCHA-AGAPE" height="150" width="150"></span>
    <div class="wrap-input100" data-validate="Ingrese Codigo de Carnet">
      <input class="input100 {{ $errors->has('usuario') == true ? 'has-val' : '' || $errors->has('password') == true ? 'has-val' : '' }}"
        style="text-transform: uppercase;" type="text" name="usuario" value="{{old('usuario')}}" id="usuario" autocomplete="off">
      <span class="focus-input100" data-placeholder="Código de Carnet"></span>
    </div>
    <div class="row">
      <div class="col-md-12 text-center" style="margin-top: -25px">
        {!!$errors->first('usuario','<small class="text-danger text-center">:message</small>')!!}
      </div>
    </div>
    <div class="wrap-input100 " data-validate="Ingrese Contraseña" {{$errors->has('password' ? 'is-invalid' : '')}}">
      <span class="btn-show-pass"><i class="zmdi zmdi-eye"></i></span>
      <input class="input100 {{ $errors->has('password') == true ? 'has-val' : ''}}" type="password" name="password" id="password">
      <span class="focus-input100" data-placeholder="Contraseña">
    </div>
    <div class="row">
      <div class="col-md-12 text-center" style="margin-top: -25px">
        {!!$errors->first('password','<small class="text-danger text-center">:message</small>')!!}
      </div>
    </div>
    <div class="container-login100-form-btn">
      <div class="wrap-login100-form-btn">
        <div class="login100-form-bgbtn"></div>
        <button type="submit" class="login100-form-btn">Ingresar</button>
      </div>
    </div>
  </form>
</div>
@endsection