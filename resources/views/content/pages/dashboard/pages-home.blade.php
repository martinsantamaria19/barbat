@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('custom-style')
<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/charts-apex.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection




@section('content')

<!-- First Row -->
<div class="row">

  <div class="col-12 col-md-12 col-lg-12 order-3 order-md-2">
    <div class="row">
      <!--/ Envíos Entregados -->
      <div class="col-12 col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/custom/check-regular-24.png')}}" alt="Check" class="rounded">
              </div>
            </div>
            <span class="d-block mb-1">Envíos Entregados</span>
            <h3 class="card-title text-nowrap mb-2 card-number">{{ $deliveredCount }}</h3>
          </div>
        </div>
      </div>
      <!--/ Envíos En Proceso -->
      <div class="col-12 col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/custom/warning-clock.png')}}" alt="Clock" class="rounded">
              </div>
            </div>
            <span class="d-block mb-1">Envíos En Proceso</span>
            <h3 class="card-title text-nowrap mb-2 card-number">{{ $processingCount }}</h3>
          </div>
        </div>
      </div>
      <!--/ Envíos En Camino -->
      <div class="col-12 col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/custom/success-truck.png')}}" alt="Truck" class="rounded">
              </div>
            </div>
            <span class="d-block mb-1">Envíos En Camino</span>
            <h3 class="card-title text-nowrap mb-2 card-number">{{ $shippedCount }}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-12">
  {{-- <!-- Line Area Chart -->
  <div class="col-12 mb-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <div>
          <h5 class="card-title mb-0">Reporte</h5>
        </div>
        <div class="dropdown">
          <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-calendar"></i></button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Hoy</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Ayer</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Últimos 7 Días</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Últimos 30 Días</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Este  Mes</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Mes Anterior</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div id="lineAreaChart"></div>
      </div>
    </div>
  </div>
  <!-- /Line Area Chart --> --}}
</div>

<!-- Third Row -->
  <!-- Últimos movimientos -->
  <div class="col-12 mb-4 card-left">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Últimos Movimientos</h5>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
        </div>
        <ul class="p-0 m-0">
          @foreach($latestActivities as $activity)
          <li class="d-flex mb-4 pb-1 border-bottom">
            <div class="avatar flex-shrink-0 me-3">
              @switch($activity->status)
                @case('delivered')
                  <img src="{{asset('assets/img/icons/custom/check-regular-24.png')}}" alt="Check" class="rounded">
                  @break
                @case('shipped')
                  <img src="{{asset('assets/img/icons/custom/success-truck.png')}}" alt="Truck" class="rounded">
                  @break
                @case('processing')
                  <img src="{{asset('assets/img/icons/custom/warning-clock.png')}}" alt="Clock" class="rounded">
                  @break
                @default
                  <img src="{{asset('assets/img/icons/custom/warning-clock.png')}}" alt="Clock" class="rounded">
                @endswitch
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <small class="text-muted">{{ $activity->package->client->company_name }} - {{ $activity->package->branch->branch_name }}</small>
                <h6 class="mb-0">
                  @switch($activity->status)
                      @case('delivered')
                          Entregado
                          @break
                      @case('shipped')
                          En Camino
                          @break
                      @case('processing')
                          En Proceso
                          @break
                      @default
                          {{ $activity->status }}
                  @endswitch
                </h6>

              </div>
              <div class="row text-end user-progress">
                <small class="fw-medium activity-user">{{ $activity->created_at->format('d/m/Y')}} - {{ $activity->created_at->format('H:i')}}hs.</small>
                <small class="fw-medium activity-user">
                  @switch($activity->status)
                    @case('processing')
                      Creado por <strong>{{ $activity->user->name }} {{ $activity->user->lastname }}</strong>
                      @break
                    @case('shipped')
                      Actualizado por <strong>{{ $activity->user->name }} {{ $activity->user->lastname }}</strong>
                      @break
                    @default
                      Entregado por <strong>{{ $activity->user->name }} {{ $activity->user->lastname }}</strong>
                    @endswitch
                </small>

              </div>
            </div>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <!--/ Últimos movimientos -->
  <!-- Clientes más activos -->
  <div class="col-md-12 col-12 order-0 mb-4">
  <div class="card">
    <h5 class="card-header">Clientes más activos</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Cliente</th>
            <th class="text-center">Suc. Asignadas</th>
            <th class="text-center">Envíos este mes</th>
            <th class="text-center">Envíos totales</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($activeClients as $client)
        <tr>
            <td>{{ $client->company_name }}</td>
            <td class="text-center">{{ $client->branches_count }}</td>
            <td class="text-center">{{ $client->monthly_packages }}</td>
            <td class="text-center">{{ $client->total_packages }}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!--/ End Clientes más activos -->
</div>

<!-- End Third Row -->


@endsection
