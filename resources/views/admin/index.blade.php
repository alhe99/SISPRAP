@extends('admin.layout.app')
@section('contenido')
<template v-if="menu==0">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="images/logo.png" class="img-fluid" alt="logo sisprap" style="width: 700px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card  text-center">
         {{--    <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center"> --}}
                        <img src="images/atajos/GESTIONES.png" class="img-fluid" alt="logo sisprap" style="width: 410px; height: 180px; display: block; margin-left: auto; margin-right: auto;">
            {{--         </div>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card text-center">
            {{-- <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center"> --}}
                        <img src="images/atajos/PEINSCRIPCIONES.png" class="img-fluid" alt="logo sisprap" style="width: 410px; height: 180px; display: block; margin-left: auto; margin-right: auto;">
           {{--          </div>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card text-center">
      {{--       <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center"> --}}
                        <img src="images/atajos/PUBLICACIONES.png" class="img-fluid" alt="logo sisprap" style="width: 410px; height: 180px;display: block; margin-left: auto; margin-right: auto;">
          {{--           </div>
                </div>
            </div> --}}
        </div>
    </div>
</template>
@if (Auth::user()->rol_id == 1)
<template v-if="menu==1">
    <publiproject></publiproject>
</template>
@elseif(Auth::user()->rol_id == 2)
<template v-if="menu==1">
    <pagoarancel></pagoarancel>
</template>
@endif
<template v-if="menu==2">
    <preregister></preregister>
</template>
<template v-if="menu==3">
    <lineaproject></lineaproject>
</template>
<template v-if="menu==4">
    <gestproy></gestproy>
</template>
<template v-if="menu==5">
    <institucion></institucion>
</template>
<template v-if="menu==6">
    <hojasupervgeneral></hojasupervgeneral>
</template>
<template v-if="menu==7">
    <constancias></constancias>
</template>
<template v-if="menu==8">
    <pagoaranceladmin></pagoaranceladmin>
</template>
<template v-if="menu==9">
    <usuarios></usuarios>
</template>
<template v-if="menu==10">
    <h1>Copias de Seguridad</h1>
</template>
<template v-if="menu==11">
    <general></general>
</template>
<template v-if="menu==12">
    <carrinst></carrinst>
</template>
<template v-if="menu==13">
 <supervision></supervision>
</template>
<template v-if="menu==14">
 <regsuperv></regsuperv>
</template>
<template v-if="menu==15">
 <configuracion></configuracion>
</template>
<template v-if="menu==16">
 <inicioproceso></inicioproceso>
</template>
<template v-if="menu==17">
 <pendientesinicio></pendientesinicio>
</template>
<template v-if="menu==18">
 <pendientefin></pendientefin>
</template>
<template v-if="menu==19">
 <culminados></culminados>
</template>
@endsection