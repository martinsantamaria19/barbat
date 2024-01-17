@extends('layouts/layoutMaster')

@section('title', 'Perfil de Usuario')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-user-view.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/modal-edit-user.js')}}"></script>
<script src="{{asset('assets/js/custom-js/user-profile.js')}}"></script>
@endsection

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Usuarios > </span> Perfil
</h4>
<div>
  <ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item"><a class="nav-link active" href="{{ route('settings.my-account')}}"><i class="bx bx-user me-1"></i> Mi Cuenta</a></li>
    <li class="nav-item"><a class="nav-link" href="security"><i class="bx bx-lock-alt me-1"></i> Contraseña</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('settings.notifications')}}"><i class="bx bx-bell me-1"></i> Notificaciones</a></li>
  </ul>
</div>
<div class="row">
  <!-- User Sidebar -->
  <div class="col-12 order-1 order-md-0">
    <!-- User Card -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="user-avatar-section">
          <div class=" d-flex align-items-center flex-column">
            <img class="img-fluid rounded my-4" src="{{asset('assets/img/branding/logo.png')}}" height="60" width="60" alt="User avatar" />
            <div class="user-info text-center">
              @auth
                <h4 class="mb-2">{{ $user->name }} {{ $user->lastname }}</h4>
                <span class="badge bg-label-secondary">{{ Auth::user()->roles->first()->name ?? 'Sin Rol' }}</span>
              @endauth

            </div>
          </div>
        </div>
        <h5 class="pb-2 pt-2 border-bottom mb-4">Detalles</h5>
        <div class="info-container">
          <ul class="list-unstyled">
            <li class="mb-3">
              <span class="fw-medium me-2">Nombre:</span>
              @auth
                <span>{{ Auth::user()->name }} {{Auth::user()->lastname}}</span>
              @endauth
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Email:</span>
              @auth
                <span> {{$user->email }} </span>
              @endauth
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Teléfono:</span>
              @auth
                <span>{{ Auth::user()->phone }}</span>
              @endauth
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Rol:</span>
              @auth
                <span>{{ Auth::user()->roles->first()->name ?? 'Sin Rol' }}</span>
              @endauth
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Empresa:</span>
              @auth
                <span>{{ Auth::user()->company }}</span>
              @endauth
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Estado:</span>
              @auth
                @if(Auth::user()->status == 'active')
                  <span class="badge bg-success">Activo</span>
                @elseif(Auth::user()->status == 'inactive')
                  <span class="badge bg-danger">Inactivo</span>
                @endif
              @endauth
            </li>

          </ul>
          {{-- <div class="d-flex justify-content-center pt-3">
            <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#editUser" data-bs-toggle="modal">Editar</a>
         </div> --}}
      </div>
    </div>
    <!-- /User Card -->
  </div>
  <!--/ User Sidebar -->


<!-- Modal -->
@include('_partials/_modals/modal-edit-user')
<!-- /Modal -->
@endsection
