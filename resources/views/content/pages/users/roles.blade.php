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
<script src="{{asset('assets/js/custom-js/modal-edit-role.js')}}"></script>
@endsection

@section('content')

@can('view roles')

@if(session('success'))
<div class="col-12">
  <div class="alert alert-primary d-flex" role="alert">
    <span class="badge badge-center rounded-pill bg-primary border-label-primary p-3 me-2"><i class="bx bx-user fs-6"></i></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Correcto!</h6>
      <span>{{ session('success') }}</span>
    </div>
  </div>
</div>
@elseif(session('error'))
<div class="col-12">
  <div class="alert alert-danger d-flex" role="alert">
    <span class="badge badge-center rounded-pill bg-danger border-label-danger p-3 me-2"><i class="bx bx-user fs-6"></i></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Error!</h6>
      <span>{{ session('error') }}</span>
  </div>
</div>
@endif

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
                      {{ $role->users_count }} {{ $role->users_count == 1 ? 'usuario' : 'usuarios' }}
                  </h6>
              </div>
              <div class="d-flex justify-content-between align-items-end">
                  <div class="role-heading">
                      <h4 class="mb-1">{{ Str::ucfirst($role->name) }}</h4>
                      <a href="javascript:void(0);" onclick="editRole({{ $role->id }})" class="role-edit-modal"><small>Editar rol</small></a>
                  </div>
                  <form action="{{ route('roles.destroy', ['id' => $role->id]) }}" method="post" class="delete-form" id="deleteForm_{{ $role->id }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-icon item-delete"><i class="bx bxs-trash"></i></button>
                  </form>
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
      const deleteForms = document.querySelectorAll('.delete-form');

      deleteForms.forEach(form => {
          form.addEventListener('submit', function(event) {
              event.preventDefault(); // Previene el envío predeterminado del formulario

              const formId = this.id;

              Swal.fire({
                  title: '¿Estás seguro?',
                  text: 'Esta acción no se puede deshacer.',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Sí, eliminar',
                  cancelButtonText: 'Cancelar'
              }).then((result) => {
                  if (result.isConfirmed) {
                      // Si el usuario confirma, envía el formulario específico
                      document.getElementById(formId).submit();
                  }
              });
          });
      });
  });
  </script>




@else

<!-- Mensaje de no autorizado -->

<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-misc.css')}}">

<div class="container-xxl container-p-y">
  <div class="misc-wrapper">
    <h2 class="mb-2 ">¡No estás autorizado!</h2>
    <p class="mb-4 ">No tienes permiso para acceder aquí con el usuario que has iniciado sesión</p>
    <a href="{{url('/')}}" class="btn btn-primary">Regresar al inicio</a>
  </div>
</div>

@endcan

@endsection
