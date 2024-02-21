@extends('layouts/layoutMaster')

@section('title', 'Clientes')

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
<script src="{{asset('assets/js/custom-js/clients.js')}}"></script>
@endsection


@section('content')

@can('view clients')


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


<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Total de Clientes</span>
            <div class="d-flex align-items-end mt-2">
              <h4 class="mb-0 me-2">{{ $totalClients }}</h4>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="bx bx-user bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Clientes Activos</span>
            <div class="d-flex align-items-end mt-2">
              <h4 class="mb-0 me-2">{{ $activeClients }}</h4>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="bx bx-group bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Clientes Inctivos</span>
            <div class="d-flex align-items-end mt-2">
              <h4 class="mb-0 me-2">{{ $inactiveClients }}</h4>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="bx bx-user-check bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Sucursales</span>
            <div class="d-flex align-items-end mt-2">
              <h4 class="mb-0 me-2">{{ $totalBranches }}</h4>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="bx bx-user-voice bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Formulario Normal -->

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Crear Cliente</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0">
    <!-- Ajusta la acción a la ruta correcta de Laravel -->
    <form class="add-new-user pt-0" id="addNewUserForm" action="create-client" method="POST">
      @csrf <!-- Token CSRF para seguridad en Laravel -->
      <div class="mb-3">
        <label class="form-label" for="companyName">Nombre de la Empresa</label>
        <input type="text" class="form-control" id="companyName" placeholder="Introduce el nombre de la empresa" name="companyName" aria-label="Introduce el nombre de la empresa" required/>
      </div>

      <div class="mb-3">
        <label class="switch">
            <span class="switch-label">Sub-Cliente</span>
            <input type="checkbox" class="switch-input" id="subCliente" />
            <span class="switch-toggle-slider">
                <span class="switch-on"></span>
                <span class="switch-off"></span>
            </span>
        </label>
      </div>

      <div class="mb-3" id="listaClientes" style="display: none;">
        <label for="clienteSelect" class="form-label">¿A quién pertenece?</label>
        <select class="form-select" id="clienteSelect" name="owner">
            <!-- Iterar sobre la colección de clientes y mostrar cada cliente como una opción en el select -->
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->company_name }}</option>
            @endforeach
        </select>
      </div>



      <div class="mb-3">
        <label class="form-label" for="companyAddress">Dirección</label>
        <input type="text" class="form-control" id="companyAddress" placeholder="Introduce la dirección" name="companyAddress" aria-label="Introduce la dirección" required/>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyPhone">Teléfono</label>
        <input type="text" id="companyPhone" class="form-control" placeholder="Ej: 099123456" name="companyPhone" required/>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyEmail">Email</label>
        <input type="text" class="form-control" id="companyEmail" placeholder="Ej: empresa@empresa.com" name="companyEmail" aria-label="Introduce la dirección de correo electrónico" required/>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyRut">RUT</label>
        <input type="text" class="form-control" id="companyRut" placeholder="Introduce el RUT" name="companyRut" aria-label="Introduce el RUT" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyState">Departamento</label>
        <input type="text" class="form-control" id="companyState" placeholder="Ej: Montevideo" name="companyState" aria-label="Introduce el departamento" required/>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyCity">Localidad</label>
        <input type="text" class="form-control" id="companyCity" placeholder="Ej: Centro" name="companyCity" aria-label="John Doe" required/>
      </div>
      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Crear Cliente</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
    </form>
  </div>
</div>

<!-- Offcanvas para editar cliente -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" aria-labelledby="offcanvasEditUserLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasEditUserLabel" class="offcanvas-title">Editar Cliente</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0">

    <form class="edit-user pt-0" id="editUserForm" action="{{ route('clients.update', ['id' => '__id__']) }}" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" id="editClientId" name="id">

      <div class="mb-3">
        <label class="form-label" for="editCompanyName">Nombre de la Empresa</label>
        <input type="text" class="form-control" id="editCompanyName" name="company_name" placeholder="Introduce el nombre de la empresa" aria-label="Introduce el nombre de la empresa" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyAddress">Dirección</label>
        <input type="text" class="form-control" id="editCompanyAddress" name="company_address" placeholder="Introduce la dirección" aria-label="Introduce la dirección" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyPhone">Teléfono</label>
        <input type="text" id="editCompanyPhone" class="form-control" name="company_phone" placeholder="Ej: 099123456" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyEmail">Email</label>
        <input type="text" class="form-control" id="editCompanyEmail" name="company_email" placeholder="Ej: empresa@empresa.com" aria-label="Introduce la dirección de correo electrónico" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyRut">RUT</label>
        <input type="text" class="form-control" id="editCompanyRut" name="company_rut" placeholder="Introduce el RUT" aria-label="Introduce el RUT" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyState">Departamento</label>
        <input type="text" class="form-control" id="editCompanyState" name="company_state" placeholder="Ej: Montevideo" aria-label="Introduce el departamento" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyCity">Localidad</label>
        <input type="text" class="form-control" id="editCompanyCity" name="company_city" placeholder="Ej: Centro" aria-label="Introduce la localidad" />
      </div>

      <div class="mb-3">
        <label for="editCompanyStatus" class="form-label">Estado</label>
        <div class="form-check form-switch">
          <input type="hidden" name="client_status" value="inactive">
          <input class="form-check-input" type="checkbox" role="switch"
                 id="editCompanyStatus" name="client_status"
                 value="active">
        </div>
      </div>



      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Actualizar Cliente</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
    </form>
  </div>
</div>

<!-- Users List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-clients table border-top">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Sucursales</th>
          <th>Dirección</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Cliente con sub clientes -->



@elseif('view own clients')


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


<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Total de Clientes</span>
            <div class="d-flex align-items-end mt-2">
              <h4 class="mb-0 me-2">{{ $totalOwnClients }}</h4>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="bx bx-user bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Clientes Activos</span>
            <div class="d-flex align-items-end mt-2">
              <h4 class="mb-0 me-2">{{ $ownActiveClients }}</h4>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="bx bx-group bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Clientes Inctivos</span>
            <div class="d-flex align-items-end mt-2">
              <h4 class="mb-0 me-2">{{ $ownInactiveClients }}</h4>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="bx bx-user-check bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Sucursales</span>
            <div class="d-flex align-items-end mt-2">
              <h4 class="mb-0 me-2">{{ $ownTotalBranches }}</h4>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="bx bx-user-voice bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Formulario Normal -->

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Crear Cliente</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0">
    <!-- Ajusta la acción a la ruta correcta de Laravel -->
    <form class="add-new-user pt-0" id="addNewUserForm" action="create-client" method="POST">
      @csrf <!-- Token CSRF para seguridad en Laravel -->
      <div class="mb-3">
        <label class="form-label" for="companyName">Nombre de la Empresa</label>
        <input type="text" class="form-control" id="companyName" placeholder="Introduce el nombre de la empresa" name="companyName" aria-label="Introduce el nombre de la empresa" required />
      </div>
      <div class="mb-3">
        <label class="switch">
            <span class="switch-label">Sub-Cliente</span>
            <input type="checkbox" class="switch-input" id="subCliente" />
            <span class="switch-toggle-slider">
                <span class="switch-on"></span>
                <span class="switch-off"></span>
            </span>
        </label>
      </div>

      <div class="mb-3" id="listaClientes" style="display: none;">
        <label for="clienteSelect" class="form-label">¿A quién pertenece?</label>
        <select class="form-select" id="clienteSelect" name="owner">
            <!-- Iterar sobre la colección de clientes y mostrar cada cliente como una opción en el select -->
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->company_name }}</option>
            @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyAddress">Dirección</label>
        <input type="text" class="form-control" id="companyAddress" placeholder="Introduce la dirección" name="companyAddress" aria-label="Introduce la dirección" required/>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyPhone">Teléfono</label>
        <input type="text" id="companyPhone" class="form-control" placeholder="Ej: 099123456" name="companyPhone" required/>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyEmail">Email</label>
        <input type="text" class="form-control" id="companyEmail" placeholder="Ej: empresa@empresa.com" name="companyEmail" aria-label="Introduce la dirección de correo electrónico" required/>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyRut">RUT</label>
        <input type="text" class="form-control" id="companyRut" placeholder="Introduce el RUT" name="companyRut" aria-label="Introduce el RUT" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyState">Departamento</label>
        <input type="text" class="form-control" id="companyState" placeholder="Ej: Montevideo" name="companyState" aria-label="Introduce el departamento" required/>
      </div>
      <div class="mb-3">
        <label class="form-label" for="companyCity">Localidad</label>
        <input type="text" class="form-control" id="companyCity" placeholder="Ej: Centro" name="companyCity" aria-label="John Doe" required/>
      </div>
      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Crear Cliente</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
    </form>
  </div>
</div>

<!-- Offcanvas para editar cliente -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" aria-labelledby="offcanvasEditUserLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasEditUserLabel" class="offcanvas-title">Editar Cliente</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0">

    <form class="edit-user pt-0" id="editUserForm" action="{{ route('clients.update', ['id' => '__id__']) }}" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" id="editClientId" name="id">

      <div class="mb-3">
        <label class="form-label" for="editCompanyName">Nombre de la Empresa</label>
        <input type="text" class="form-control" id="editCompanyName" name="company_name" placeholder="Introduce el nombre de la empresa" aria-label="Introduce el nombre de la empresa" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyAddress">Dirección</label>
        <input type="text" class="form-control" id="editCompanyAddress" name="company_address" placeholder="Introduce la dirección" aria-label="Introduce la dirección" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyPhone">Teléfono</label>
        <input type="text" id="editCompanyPhone" class="form-control" name="company_phone" placeholder="Ej: 099123456" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyEmail">Email</label>
        <input type="text" class="form-control" id="editCompanyEmail" name="company_email" placeholder="Ej: empresa@empresa.com" aria-label="Introduce la dirección de correo electrónico" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyRut">RUT</label>
        <input type="text" class="form-control" id="editCompanyRut" name="company_rut" placeholder="Introduce el RUT" aria-label="Introduce el RUT" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyState">Departamento</label>
        <input type="text" class="form-control" id="editCompanyState" name="company_state" placeholder="Ej: Montevideo" aria-label="Introduce el departamento" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="editCompanyCity">Localidad</label>
        <input type="text" class="form-control" id="editCompanyCity" name="company_city" placeholder="Ej: Centro" aria-label="Introduce la localidad" />
      </div>

      <div class="mb-3">
        <label for="editCompanyStatus" class="form-label">Estado</label>
        <div class="form-check form-switch">
          <input type="hidden" name="client_status" value="inactive">
          <input class="form-check-input" type="checkbox" role="switch"
                 id="editCompanyStatus" name="client_status"
                 value="active">
        </div>
      </div>



      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Actualizar Cliente</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
    </form>
  </div>
</div>

<!-- Users List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-clients table border-top">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Sucursales</th>
          <th>Dirección</th>
          <th>Teléfono</th>
          <th>Email</th>
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


<script>
  document.addEventListener('DOMContentLoaded', function() {
      const subClienteSwitch = document.getElementById('subCliente');
      const listaClientes = document.getElementById('listaClientes');

      subClienteSwitch.addEventListener('change', function() {
          if (this.checked) {
              listaClientes.style.display = 'block';
          } else {
              listaClientes.style.display = 'none';
          }
      });
  });
</script>
