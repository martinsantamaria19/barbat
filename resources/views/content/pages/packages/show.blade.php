@extends('layouts/layoutMaster')

@section('title', 'Ver Paquete')


@section('content')

@if(session('success'))
<div class="alert alert-primary d-flex col-12" role="alert">
  <span class="badge badge-center rounded-pill bg-primary border-label-primary p-3 me-2"><i class="bx bx-user fs-6"></i></span>
  <div class="d-flex flex-column ps-1">
    <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Estado actualizado!</h6>
    <span>El estado del envío se ha actualizado correctamente.</span>
  </div>
</div>
@endif

<div class="row">
  <div class="col-md-4 col-12 pb-4">
    <div class="card">
      <div class="card-body">
        <h4>Cliente</h4>
        <span class="d-block"><strong>Cliente:</strong> {{ $package->client->company_name }}</span>
        <span class="d-block"><strong>Sucursal:</strong> {{ $package->branch->branch_name }}</span>
        <span class="d-block"><strong>Dirección:</strong> {{ $package->branch->branch_address }}</span>
        <span class="d-block"><strong>Teléfono:</strong> {{ $package->branch->branch_phone }}</span>
        <div class="product-list mt-4">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Cantidad</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                  <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->cantidad ?? 'N/A' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @can('edit status')
          @if ($activity->status == 'delivered')

          @elseif($activity->status == 'shipped')
            <form action="{{ url('/packages/' . $package->id . '/change-status') }}" method="POST">
              @csrf
              <input type="hidden" name="status" value="delivered">
              <div class="text-center">
                <button type="submit" class="btn btn-primary mt-5">Marcar como Entregado</button>
              </div>
            </form>
          @else
            <form action="{{ url('/packages/' . $package->id . '/change-status') }}" method="POST">
              @csrf
              <input type="hidden" name="status" value="shipped">
              <div class="text-center">
                <button type="submit" class="btn btn-primary mt-5">Marcar como En Camino</button>
              </div>
            </form>
          @endif
        @endcan
      </div>
    </div>
  </div>


  <div class="col-12 col-md-8">
    <div class="card">
      <div class="card-body">
        @if ($activity->status == 'delivered')
          <h3 class="mb-0"><strong>Envío Entregado</strong></h3>
          <p>{{ $activity->created_at->format('d/m/Y H:i') }} hs.</p>
        @elseif($activity->status == 'shipped')
          <h3><strong>Envío En Camino</strong></h3>
        @else
          <h3><strong>Envío En Proceso</strong></h3>
        @endif
        <div>
          <ul class="timeline ps-3 mt-4">
            {{-- Envío Ingresado (siempre visible) --}}
            <li class="timeline-item ps-4 border-left-dashed">
              <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none mt-1">
                <i class='bx bx-check-circle'></i>
              </span>
              <div class="timeline-event ps-0 pb-0">
                <div class="timeline-header">
                  <h7 class="text-success text-uppercase fw-medium"><strong>Envío Creado</strong></h7>
                </div>
                @php
                  $processingActivity = $package->activities->firstWhere('status', 'processing');
                  @endphp

                  @if ($processingActivity)
                      <h6 class="mb-1">Creado por <strong>{{ $processingActivity->user->name }} {{ $processingActivity->user->lastname }}</strong></h6>
                      <p class="text-muted mb-0">{{ $processingActivity->created_at->format('d/m/Y')}} - {{ $processingActivity->created_at->format('H:i')}}hs.</p>
                  @else
                      <p class="text-muted mb-0">Información no disponible</p>
                  @endif
              </div>
            </li>

            @php
            $shippedActivity = $package->activities->firstWhere('status', 'shipped');
            @endphp

            {{-- En Camino --}}
            <li class="timeline-item ps-4 border-left-dashed">
                <span class="timeline-indicator-advanced {{ $shippedActivity ? 'timeline-indicator-success' : '' }} border-0 shadow-none">
                    <i class='bx {{ $shippedActivity ? 'bx-check-circle' : 'bx-time-five' }} mt-1'></i>
                </span>
                <div class="timeline-event ps-0 pb-0">
                    <div class="timeline-header">
                        @if($shippedActivity)
                            <h7 class="text-success text-uppercase fw-medium"><strong>En Camino</strong></h7>
                        @else
                            <small class="text-muted text-uppercase fw-medium">Envío</small>
                        @endif
                    </div>
                    @if($shippedActivity)
                        <h6 class="mb-1">Marcado por <strong>{{ $shippedActivity->user->name }} {{ $shippedActivity->user->lastname }}</strong></h6>
                        <p class="text-muted mb-0">{{ $shippedActivity->created_at->format('d/m/Y')}} - {{ $shippedActivity->created_at->format('H:i')}}hs.</p>
                    @else
                        <p class="text-muted mb-0">Aún no enviado</p>
                    @endif
                </div>
            </li>

            @php
            $deliveredActivity = $package->activities->firstWhere('status', 'delivered');
            @endphp

            {{-- Entregado --}}
            <li class="timeline-item ps-4 border-transparent">
                <span class="timeline-indicator-advanced {{ $deliveredActivity ? 'timeline-indicator-success' : '' }} border-0 shadow-none">
                    <i class='bx {{ $deliveredActivity ? 'bx-check-circle' : 'bx-time-five' }} mt-1'></i>
                </span>
                <div class="timeline-event ps-0 pb-0">
                    <div class="timeline-header">
                        @if($deliveredActivity)
                            <h7 class="text-success text-uppercase fw-medium"><strong>Entregado</strong></h7>
                        @else
                            <small class="text-muted text-uppercase fw-medium">Entrega</small>
                        @endif
                    </div>
                    @if($deliveredActivity)
                        <h6 class="mb-1">Entregado por <strong>{{ $deliveredActivity->user->name }} {{ $deliveredActivity->user->lastname }}</strong></h6>
                        <p class="text-muted mb-0">{{ $deliveredActivity->created_at->format('d/m/Y')}} - {{ $deliveredActivity->created_at->format('H:i')}}hs.</p>
                        <br>
                        <h6 class="mb-1">Recibido por <strong>{{ $receiver->name }} {{ $receiver->lastname }}</strong></h6>
                        <p class="text-muted mb-0">CI: {{ $receiver->cedula }}</p>
                        <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#enableOTP"> Ver imagen </button>
                        @else
                        <p class="text-muted mb-0">Aún no entregado - Entrega estimada: {{ Carbon\Carbon::parse($package->delivery_date)->format('d/m/Y') }}</p>
                    @endif
                </div>
            </li>

          </ul>
        </div>

      </div>
    </div>
  </div>
  <div class="d-flex justify-content-center text-center mt-5">
    <button class="btn btn-primary" id="backButton">Volver atrás</button>
  </div>
</div>

@include('_partials/_modals/modal-image')

<script>
  document.getElementById('backButton').addEventListener('click', function() {
    history.back();
});

</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
      $('a[data-toggle="modal"]').click(function() {
          var imageSrc = $(this).attr('data-image');
          $('#imageInModal').attr('src', imageSrc);
          $('#imageModal').modal('show');
      });
  });
  </script>


@endsection
