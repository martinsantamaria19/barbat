@extends('layouts/layoutMaster')

@section('title', 'Usuarios')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/custom-js/users.js')}}"></script>
@endsection


@section('content')

@can ('view users')

<h4 class="py-3 mb-2">Usuarios</h4>

<!-- Formulario Normal -->

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Crear Usuario</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0">
    <form class="add-new-user pt-0" id="addNewUserForm" action="create-user" method="POST" autocomplete="off">
      @csrf
      <div class="d-flex">
        <div class="mb-3 me-2">
          <label class="form-label" for="name">Nombre</label>
          <input type="text" class="form-control" id="name" placeholder="Introduce el nombre y apellido" name="name" aria-label="Introduce el nombre y apellido" />
        </div>
        <div class="mb-3">
          <label class="form-label" for="name">Apellido</label>
          <input type="text" class="form-control" id="lastname" placeholder="Introduce el apellido" name="lastname" aria-label="Introduce el apellido" />
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="company">Empresa</label>
        <input type="text" class="form-control" id="company" placeholder="Introduce el nombre de la empresa" name="company" aria-label="Introduce el nombre de la empresa" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="role">Rol</label>
        <select class="form-select" id="role" name="role" aria-label="Selecciona un rol">
            <option value="" selected>Seleccione un rol</option> <!-- Opción por defecto -->
            @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option> <!-- Usar el nombre en lugar del ID -->
            @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input type="email" class="form-control" id="email" placeholder="Ej: empresa@empresa.com" name="email" autocomplete="new-email" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="phone">Teléfono</label>
        <input type="text" class="form-control" id="phone" placeholder="Ej: 099123456" value="" autocomplete="off" name="phone" aria-label="Introduce el teléfono" />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" name="password" autocomplete="off" value="">
      </div>
      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Crear Usuario</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
    </form>
  </div>
</div>


<!-- Offcanvas para editar usuario -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" aria-labelledby="offcanvasEditUserLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasEditUserLabel" class="offcanvas-title">Editar Usuario</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0">

    <form class="edit-user pt-0" id="editUserForm" action="{{ route('users.update', ['id' => '__id__']) }}" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" id="editUserId" name="id">

      <div class="d-flex">
        <div class="mb-3">
          <label class="form-label" for="editUserName">Nombre</label>
          <input type="text" class="form-control" id="editUserName" name="name" placeholder="Introduce el nombre" aria-label="Introduce el nombre" />
        </div>
        <div class="mb-3">
          <label class="form-label" for="editUserLastname">Apellido</label>
          <input type="text" class="form-control" id="editUserLastname" name="lastname" placeholder="Introduce el apellido" aria-label="Introduce el nombre apellido" />
        </div>
      </div>


      <div class="mb-3">
        <label class="form-label" for="editUserCompany">Empresa</label>
        <input type="text" class="form-control" id="editUserCompany" name="company" placeholder="Introduce el nombre de la empresa" aria-label="Introduce el nombre de la empresa"/>
      </div>

      <div class="mb-3">
        <label class="form-label" for="editUserRole">Rol</label>
        <select class="form-select" id="editUserRole" name="role" aria-label="Selecciona un rol">
            <option value="" selected>Seleccione un rol</option> <!-- Opción por defecto -->
            @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label" for="editUserEmail">Email</label>
        <input type="text" class="form-control" id="editUserEmail" name="email" placeholder="Ej: usuario@ejemplo.com" aria-label="Introduce la dirección de correo electrónico" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editUserPhone">Teléfono</label>
        <input type="text" class="form-control" id="editUserPhone" name="phone" placeholder="Ej: 099123456" aria-label="Introduce el teléfono" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="editUserStatus">Estado</label>
        <select class="form-select" id="editUserStatus" name="status" aria-label="Selecciona un estado">
            <option value="active">Activo</option>
            <option value="inactive">Inactivo</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Actualizar Usuario</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
    </form>
  </div>
</div>

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

<!-- Users List Table -->
<div class="card p-4">
  <div class="card-datatable table-responsive">
    <table class="datatables-users table border-top">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Correo Electrónico</th>
          <th>Rol</th>
          <th>Empresa</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
</div>


<button type="button" class="btn btn-primary fixed-bottom-right" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">
  <i class="bx bx-plus"></i>
  <span class="d-none d-sm-inline-block">Nuevo Usuario</span>
</button>

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
