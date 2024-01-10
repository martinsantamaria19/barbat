@php
 $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Contraseña')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-account-settings.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
@endsection

@section('page-script')
{{-- <script src="{{asset('assets/js/pages-account-settings-security.js')}}"></script> --}}
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Configuración /</span> Seguridad
</h4>

@if(session('success'))
  <div class="alert alert-primary d-flex" role="alert">
    <span class="badge badge-center rounded-pill bg-primary border-label-primary p-3 me-2"><i class="bx bx-user fs-6"></i></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Correcto!</h6>
      <span>{{ session('success') }}</span>
    </div>
  </div>
@elseif(session('error'))
  <div class="alert alert-danger d-flex" role="alert">
    <span class="badge badge-center rounded-pill bg-danger border-label-danger p-3 me-2"><i class="bx bx-user fs-6"></i></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Error!</h6>
      <span>{{ session('error') }}</span>
  </div>
@endif

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link" href="{{ route('settings.my-account')}}"><i class="bx bx-user me-1"></i> Mi Cuenta</a></li>
      <li class="nav-item"><a class="nav-link active" href="security"><i class="bx bx-lock-alt me-1"></i> Contraseña</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('settings.notifications')}}"><i class="bx bx-bell me-1"></i> Notificaciones</a></li>
    </ul>
    <!-- Change Password -->
    <div class="card mb-4">
      <h5 class="card-header">Cambiar contraseña</h5>
      <div class="card-body">
        <form id="formAccountSettings" method="POST" action="{{ route('user.password.update') }}">
          @csrf
          <div class="row">
            <div class="mb-3 col-md-6 form-password-toggle">
              <label class="form-label" for="newPassword">Nueva contraseña</label>
              <div class="input-group input-group-merge">
                <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>

            {{-- <div class="mb-3 col-md-6 form-password-toggle">
              <label class="form-label" for="confirmPassword">Confirmar nueva contraseña</label>
              <div class="input-group input-group-merge">
                <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div> --}}
            <div class="col-12 mt-1">
              <button type="submit" class="btn btn-primary me-2">Cambiar contraseña</button>
              <button type="reset" class="btn btn-label-secondary">Cancelar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!--/ Change Password -->
  </div>
</div>
@endsection
