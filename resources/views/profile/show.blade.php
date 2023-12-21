@extends('layouts/layoutMaster')

@section('title', 'User View - Pages')

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
<script src="{{asset('assets/js/app-user-view.js')}}"></script>
<script src="{{asset('assets/js/app-user-view-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Usuarios > </span> Perfil
</h4>
<div class="row">
  <!-- User Sidebar -->
  <div class="col-12 order-1 order-md-0">
    <!-- User Card -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="user-avatar-section">
          <div class=" d-flex align-items-center flex-column">
            <img class="img-fluid rounded my-4" src="{{asset('assets/img/avatars/10.png')}}" height="110" width="110" alt="User avatar" />
            <div class="user-info text-center">
              @auth
                <h4 class="mb-2">{{ Auth::user()->name }}</h4>
                <span class="badge bg-label-secondary">{{ Auth::user()->role->name }}</span>
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
                <span>{{ Auth::user()->name }}</span>
              @endauth
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Email:</span>
              @auth
                <span>{{ Auth::user()->email }}</span>
              @endauth
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Estado:</span>
              <span class="badge bg-label-success">Active</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Rol:</span>
              @auth
                <span>{{ Auth::user()->role->name }}</span>
              @endauth
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Empresa:</span>
              @auth
                <span>{{ Auth::user()->company }}</span>
              @endauth
            </li>

          </ul>
          <div class="d-flex justify-content-center pt-3">
            <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#editUser" data-bs-toggle="modal">Editar</a>
            <a href="javascript:;" class="btn btn-label-danger suspend-user">Suspender</a>
          </div>
        </div>
      </div>
    </div>
    <!-- /User Card -->
  </div>
  <!--/ User Sidebar -->

<!-- Modal -->
@include('_partials/_modals/modal-edit-user')
<!-- /Modal -->
@endsection
