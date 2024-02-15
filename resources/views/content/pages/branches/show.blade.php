@extends('layouts/layoutMaster')

@section('title', 'Ver sucursal')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
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
<script src="{{asset('assets/js/app-ecommerce-customer-detail.js')}}"></script>
<script src="{{asset('assets/js/app-ecommerce-customer-detail-overview.js')}}"></script>
<script src="{{asset('assets/js/custom-js/branch-products.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Clientes / {{$client->company_name}} / Sucursales /</span> {{$branch->branch_name}}
</h4>

<div class="d-flex flex-column flex-sm-row align-items-center justify-content-sm-between mb-4 text-center text-sm-start gap-2">
  <div class="mb-2 mb-sm-0">
    <h4 class="mb-1">
      ID de la sucursal: #{{$branch->id}}
    </h4>
    <p class="mb-0">
      Fecha de creación: {{$branch->created_at}}
    </p>
  </div>
</div>


<div class="row">
  <!-- Customer-detail Sidebar -->
  <div class="col-xl-12 order-1 order-md-0">
    <!-- Customer-detail Card -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="customer-avatar-section">
          <div class="d-flex align-items-center flex-column">
            <div class="customer-info text-center">
              <h2 class="mb-1">{{ $client->company_name }} - {{ $branch->branch_name }}</h2>
              <small>ID #{{$branch->id}}</small>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-around flex-wrap mt-4 py-3">
          <div class="d-flex align-items-center gap-2">
            <div class="avatar">
              <div class="avatar-initial rounded bg-label-primary"><i class='fa-solid fa-store'></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">{{ $totalDeliveredPackages }}</h5>
                <span>Envíos recibidos</span>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="avatar">
              <div class="avatar-initial rounded bg-label-primary"><i class='fa-solid fa-box-archive'></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">{{ $totalStock }}</h5>
                <span>Productos en stock</span>
              </div>
          </div>
        </div>

        <div class="info-container">
          <small class="d-block pt-4 border-top fw-normal text-uppercase text-muted my-3">DETALLES</small>
          <ul class="list-unstyled">
            <li class="mb-3">
              <span class="fw-medium me-2">Dirección:</span>
              <span>{{ $branch->branch_address }}</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Teléfono:</span>
              <span>{{ $branch->branch_phone }}</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Email:</span>
              <span>{{ $branch->branch_email }}</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">RUT</span>
              <span>{{ $branch->branch_rut }}</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Estado:</span>
              <span class="badge bg-label-success">{{ $branch->branch_status }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- /Customer-detail Card -->
  </div>
  <!--/ Customer Sidebar -->
</div>

<!-- Modal -->
@include('_partials/_modals/modal-edit-user')
<!-- /Modal -->
@endsection
