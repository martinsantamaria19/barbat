@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Not Authorized - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-misc.css')}}">
@endsection


@section('content')
<!-- Not Authorized -->
<div class="container-xxl container-p-y">
  <div class="misc-wrapper">
    <h2 class="mb-2 mx-2">¡No estás autorizado!</h2>
    <p class="mb-4 mx-2">No tienes permiso para acceder aquí con el usuario que has iniciado sesión <br> Por favor, contactate con el administrador de la plataforma</p>
    <a href="{{url('/')}}" class="btn btn-primary">Regresar al inicio</a>
    <div class="mt-5">
      <img src="{{asset('assets/img/illustrations/girl-with-laptop-'.$configData['style'].'.png')}}" alt="page-misc-not-authorized-light" width="450" class="img-fluid" data-app-light-img="illustrations/girl-with-laptop-light.png" data-app-dark-img="illustrations/girl-with-laptop-dark.png">
    </div>
  </div>
</div>
<!-- /Not Authorized -->
@endsection
