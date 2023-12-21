@extends('layouts/blankLayout')

@section('title', 'Rastreo de Envío')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/mapbox-gl/mapbox-gl.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-logistics-fleet.css')}}" />

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/mapbox-gl/mapbox-gl.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-logistics-fleet.js')}}"></script>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="card p-3 col-10 col-md-4">
    <div class="app-brand justify-content-start">
      <a href="{{url('/')}}" class="app-brand-link gap-2">
        <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
        <span class="app-brand-text demo text-body fw-bold">{{config('variables.templateName')}}</span>
      </a>
    </div>
    <h3 class="text-center pt-2 pb-4">Rastreo de Envío</h3>
    <h5 class="text-center mb-0"><strong>{{$package->client->company_name}} - {{$package->branch->branch_name}}</strong></h5>
    <p class="p-0 m-0 text-center mb-4"><strong>{{$package->tracking_code}}</strong></p>
    <p class="p-0 m-0 text-center">Dirección: <strong>{{$package->branch->branch_address}}</strong></p>
    <p class="p-0 m-0 text-center">Teléfono: <strong>{{$package->branch->branch_phone}}</strong></p>
    <p class="p-0 m-0 text-center">Envío creado: <strong>{{ $package->created_at->format('d/m/Y - H:i') }}hs.</strong></p>
    <p class="p-0 m-0 text-center">
      @switch($activity->status)
        @case('delivered')
        @break
        @default
          Entrega estimada: <strong>{{ Carbon\Carbon::parse($package->delivery_date)->format('d/m/Y') }}</strong>
        @endswitch
    </p>
    @if(isset($products) && $products->count() > 0)
    <div class="product-list mt-4">
      <div class="table-responsive">
        <table class="table table-striped table-hover text-center">
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
  @else
    <p class="text-center">No hay productos asociados a este envío.</p>
  @endif


    <div class="pt-4 text-center">

        @switch($activity->status)
            @case('delivered')
            <h3 class="mb-0"><strong class="text-success">Paquete Entregado</strong></h3>
              <p>{{ $activity->created_at->format('d/m/Y H:i') }} hs.</p>
            @break
            @case('shipped')
            <h3><strong class="text-success">Paquete En Camino</strong></h3>
                @break
            @case('processing')
            <h3><strong class="text-success">Paquete En Proceso</strong></h3>
                @break
            @default
                {{ $activity->status }}
        @endswitch
      </h3>
      <button onclick="window.location.href='{{ url('/rastreo') }}';" class="btn btn-primary mt-5">Rastrear otro envío</button>
    </div>
  </div>
</div>

@endsection
