@extends('layouts/layoutMaster')

@section('title', 'Productos')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/custom-js/activity.js')}}"></script>
@endsection

@section('content')

<!-- Últimos movimientos -->
<div class="row justify-content-center">
  <div class="col-6 mb-4 card-left">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Toda la Actividad</h5>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
        </div>
        <ul class="p-0 m-0">
          @foreach($latestActivities as $activity)
          <li class="d-flex mb-4 pb-1">
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
                      Creado por <strong>{{ $activity->user->name }}</strong>
                      @break
                    @case('shipped')
                      Actualizado por <strong>{{ $activity->user->name }}</strong>
                      @break
                    @default
                      Entregado por <strong>{{ $activity->user->name }}</strong>
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
</div>
<!--/ Últimos movimientos -->

@endsection
