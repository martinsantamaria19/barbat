@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Rastrear Envío')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection



@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
              <img src="./assets/img/branding/logo.png" class="app-brand-logo demo" alt="">
          </div>
          <!-- /Logo -->
          <h4 class="mb-2 text-center">Rastrear Envío</h4>
          <p class="mb-4 text-center">Por favor, ingrese su código de rastreo</p>

          <form id="track" action="{{ route('track-package') }}" method="GET">
            <div class="mb-3">
              <label for="tracking_code" class="form-label">Código de rastreo</label>
              <input type="text" class="form-control" id="tracking_code" name="tracking_code" placeholder="Ingrese su código de rastreo" autofocus>
            </div>
            <div class="d-flex justify-content-end">
              <button class="btn btn-primary text-end">Rastrear</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
