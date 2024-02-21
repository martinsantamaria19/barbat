@extends('layouts/layoutMaster')

@section('title', 'Ver cliente')

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
<script src="{{asset('assets/js/custom-js/client-branches.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Clientes / Detalle del cliente /</span> {{$client->company_name}}
</h4>

<div class="d-flex flex-column flex-sm-row align-items-center justify-content-sm-between mb-4 text-center text-sm-start gap-2">
  <div class="mb-2 mb-sm-0">
    <h4 class="mb-1">
      ID del cliente: #{{$client->id}}
    </h4>
    <p class="mb-0">
      Fecha de creación: {{$client->created_at}}
    </p>
  </div>
</div>


<div class="row">
  <!-- Customer-detail Sidebar -->
  <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
    <!-- Customer-detail Card -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="customer-avatar-section">
          <div class="d-flex align-items-center flex-column">
            <div class="customer-info text-center">
              <h2 class="mb-1">{{$client->company_name}}</h2>
              @if($client->owner)
              <small>ID #{{$client->id}} - Sub-Cliente de: {{ $ownerName }}</small>
          @else
              <small>ID #{{$client->id}}</small>
          @endif



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
              <h5 class="mb-0">{{$totalBranches}}</h5>
              @if($totalBranches == 1)
                <span>Sucursal</span>
              @else
                <span>Sucursales</span>
              @endif
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="avatar">
              <div class="avatar-initial rounded bg-label-primary"><i class='fa-solid fa-box-archive'></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">{{$totalPackages}}</h5>
              @if($totalPackages == 1)
                <span>Envío recibido</span>
              @else
                <span>Envíos recibidos</span>
              @endif
              </div>
          </div>
        </div>

        <div class="info-container">
          <small class="d-block pt-4 border-top fw-normal text-uppercase text-muted my-3">DETALLES</small>
          <ul class="list-unstyled">
            <li class="mb-3">
              <span class="fw-medium me-2">Dirección:</span>
              <span>{{$client->company_address}}, {{$client->company_city}}, {{$client->company_state}}</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Teléfono:</span>
              <span>{{$client->company_phone}}</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Email:</span>
              <span>{{$client->company_email}}</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">RUT</span>
              <span>{{$client->company_rut}}</span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Estado:</span>
              <span class="badge bg-label-success">{{$client->client_status}}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- /Customer-detail Card -->
  </div>
  <!--/ Customer Sidebar -->


  <!-- Customer Content -->
  <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
    <!-- Customer Pills -->
    <ul class="nav nav-pills flex-column flex-md-row mb-4">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>Resumen</a></li>
    </ul>
    <!--/ Customer Pills -->


    <!-- Invoice table -->
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-title">Sucursales</h5>
      </div>
      <div class="card-datatable table-responsive clientBranchesTable mb-3" data-client-id="{{$client->id}}">
        <table class="table datatables-client-branches border-top">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Dirección</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Ciudad</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <!-- /Invoice table -->
  </div>
  <!--/ Customer Content -->
</div>

<!-- Modal -->
@include('_partials/_modals/modal-edit-user')
<!-- /Modal -->
@endsection
