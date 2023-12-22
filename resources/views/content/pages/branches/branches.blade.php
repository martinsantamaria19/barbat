@extends('layouts/layoutMaster')

@section('title', 'Sucursales')

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
<script src="{{asset('assets/js/custom-js/datatable-branches.js')}}"></script>
@endsection


@section('content')

@can('view branches')


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


<!-- Formulario Normal -->

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddBranch" aria-labelledby="offcanvasAddBranchLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasAddBranchLabel" class="offcanvas-title">Crear Sucursal</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0">
    <!-- Ajusta la acción a la ruta correcta de Laravel -->
    <form class="add-new-branch pt-0" id="addNewBranchForm" action="create-branch" method="POST">
      @csrf <!-- Token CSRF para seguridad en Laravel -->

      <div class="mb-3">
        <label for="branchClient" class="form-label">Cliente</label>
        <select id="branchClient" name="branchClient" class="selectpicker w-100" data-style="btn-default" data-live-search="true">
            @foreach ($clients as $client)
                <option value="{{ $client->id }}">{{ $client->company_name }}</option>
            @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="branchName">Nombre de la Sucursal</label>
        <input type="text" class="form-control" id="branchName" placeholder="Introduce el nombre de la empresa" name="branchName" aria-label="Introduce el nombre de la empresa" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="branchAddress">Dirección</label>
        <input type="text" class="form-control" id="branchAddress" placeholder="Introduce la dirección" name="branchAddress" aria-label="Introduce la dirección" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="branchPhone">Teléfono</label>
        <input type="text" id="branchPhone" class="form-control" placeholder="Ej: 099123456" name="branchPhone" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="branchEmail">Email</label>
        <input type="text" class="form-control" id="branchEmail" placeholder="Ej: empresa@empresa.com" name="branchEmail" aria-label="Introduce la dirección de correo electrónico" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="branchRut">RUT</label>
        <input type="text" class="form-control" id="branchRut" placeholder="Introduce el RUT" name="branchRut" aria-label="Introduce el RUT" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="branchState">Departamento</label>
        <input type="text" class="form-control" id="branchState" placeholder="Ej: Montevideo" name="branchState" aria-label="Introduce el departamento" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="branchCity">Localidad</label>
        <input type="text" class="form-control" id="branchCity" placeholder="Ej: Centro" name="branchCity" aria-label="John Doe" />
      </div>
      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Crear Sucursal</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
    </form>
  </div>
</div>

<!-- Offcanvas para editar sucursal -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditBranch" aria-labelledby="offcanvasEditBranchLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasEditBranchLabel" class="offcanvas-title">Editar Sucursal</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0">

    <form class="edit-branch pt-0" id="editBranchForm" action="{{ route('branches.update', ['id' => '__id__']) }}" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" id="editBranchId" name="id">

      <div class="mb-3">
        <label for="editBranchClient" class="form-label">Cliente</label>
        <select id="editBranchClient" name="branch_client" class="selectpicker w-100" data-style="btn-default" data-live-search="true">
            @foreach ($clients as $client)
                <option value="{{ $client->id }}">{{ $client->company_name }}</option>
            @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label" for="editBranchName">Nombre de la Sucursal</label>
        <input type="text" class="form-control" id="editBranchName" name="branch_name" placeholder="Introduce el nombre de la empresa" aria-label="Introduce el nombre de la empresa" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editBranchAddress">Dirección</label>
        <input type="text" class="form-control" id="editBranchAddress" name="branch_address" placeholder="Introduce la dirección" aria-label="Introduce la dirección" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editBranchPhone">Teléfono</label>
        <input type="text" id="editBranchPhone" class="form-control" name="branch_phone" placeholder="Ej: 099123456" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editBranchEmail">Email</label>
        <input type="text" class="form-control" id="editBranchEmail" name="branch_email" placeholder="Ej: empresa@empresa.com" aria-label="Introduce la dirección de correo electrónico" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editBranchRut">RUT</label>
        <input type="text" class="form-control" id="editBranchRut" name="branch_rut" placeholder="Introduce el RUT" aria-label="Introduce el RUT" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editBranchState">Departamento</label>
        <input type="text" class="form-control" id="editBranchState" name="branch_state" placeholder="Ej: Montevideo" aria-label="Introduce el departamento" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editBranchCity">Localidad</label>
        <input type="text" class="form-control" id="editBranchCity" name="branch_city" placeholder="Ej: Centro" aria-label="Introduce la localidad" />
      </div>

      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Actualizar Sucursal</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
    </form>
  </div>
</div>


<!-- Users List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-branches table border-top">
      <thead>
        <tr>
          <th>Sucursal</th>
          <th>Cliente</th>
          <th>Dirección</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th>RUT</th>
          <th>Departamento</th>
          <th>Localidad</th>
          <th>Estado</th>
          <th>Acciones</th>


        </tr>
      </thead>
    </table>
  </div>
</div>

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
