@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles de Usuario')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>

<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>

@endsection

@section('page-script')

<script src="{{asset('assets/js/modal-add-role.js')}}"></script>
<script src="{{asset('assets/js/custom-js/modal-edit-role.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-2">Roles de Usuario</h4>

<p>Los roles manejan los permisos de diferentes grupos de usuarios, sus capacidades y accesos.</p>
<!-- Role cards -->
<div class="row g-4">
  @foreach ($roles as $role)
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
          <h6 class="fw-normal">
            {{ $count = $users->where('role_id', $role->id)->count() }}

            {{ $count == 1 ? 'usuario' : 'usuarios' }}
          </h6>
        </div>
        <div class="d-flex justify-content-between align-items-end">
          <div class="role-heading">
            <h4 class="mb-1">{{Str::ucfirst($role->name) }}</h4>
            <a href="javascript:void(0);" onclick="editRole({{ $role->id }})" class="role-edit-modal"><small>Editar rol</small></a>

          </div>
          <a href="javascript:void(0);" class="text-muted"><i class="bx bx-copy"></i></a>
        </div>
      </div>
    </div>
  </div>
  @endforeach
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card h-100">
      <div class="row h-100">
        <div class="col-sm-5">
          <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
            <img src="{{asset('assets/img/illustrations/sitting-girl-with-laptop-'.$configData['style'].'.png')}}" class="img-fluid" alt="Image" width="120" data-app-light-img="illustrations/sitting-girl-with-laptop-light.png" data-app-dark-img="illustrations/sitting-girl-with-laptop-dark.png">
          </div>
        </div>
        <div class="col-sm-7">
          <div class="card-body text-sm-end text-center ps-sm-0">
            <button data-bs-target="#addRoleModal" data-bs-toggle="modal" class="btn btn-primary mb-3 text-nowrap add-new-role">Crear rol</button>
            <p class="mb-0">Crea un rol si no existe</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Role cards -->

<!-- Add Role Modal -->
@include('_partials/_modals/modal-add-role')
@include('_partials/_modals/modal-edit-role')
<!-- / Add Role Modal -->
@endsection
