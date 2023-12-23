@extends('layouts/layoutMaster')

@section('title', 'Envíos')

@section('page-script')
<script src="{{asset('assets/js/custom-js/packages.js')}}"></script>
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
@endsection

@section('content')



<div class="container-fluid">
    <h1>Envíos</h1>
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
    <div class="card">
      <div class="card-datatable table-responsive">
        <table id="packagesTable" class="table">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Cliente</th>
                  <th>Sucursal</th>
                  <th>Productos</th>
                  <th>Estado Actual</th>
                  <th>Prioridad</th>
                  <th>Descargar Etiqueta</th>
                  <th>Acciones</th>
              </tr>
          </thead>
          <tbody>
              {{-- Los datos se llenarán con DataTables --}}
          </tbody>
        </table>
      </div>
    </div>

    {{-- <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Sucursal</th>
                <th>Productos</th>
                <th>Estado Actual</th>
                <th>Descargar Etiqueta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packages as $package)
            <tr>
                <td>{{ $package->id }}</td>
                <td>{{ $package->client->company_name }}</td>
                <td>{{ $package->branch->branch_name }}</td>
                <td>
                    @foreach ($package->products as $product)
                        {{ $product->name }}
                    @endforeach
                </td>
                <td>
                  @switch($package->latestActivity->status)
                    @case('shipped')
                      En Camino
                      @break
                    @case('delivered')
                      Entregado
                      @break
                    @default
                      En Proceso
                  @endswitch
                </td>
                <td>
                  <a href="{{ asset($package->label_path) }}" target="_blank">Imprimir Etiqueta</a>
                </td>
                <td>
                  <div class="d-flex">
                    <div>
                      @if (optional($package->latestActivity)->status == 'processing')
                        <a href="#" class="change-status" data-package-id="{{ $package->id }}" data-new-status="shipped" title="Marcar como enviado">
                          <i class="fas fa-lg fa-truck"></i>
                        </a>
                      @elseif (optional($package->latestActivity)->status == 'shipped')
                        <a href="#" class="change-status" data-package-id="{{ $package->id }}" data-new-status="delivered" title="Marcar como entregado">
                            <i class="fas fa-lg fa-check-circle"></i>
                        </a>
                      @elseif (is_null($package->latestActivity))
                        <a href="#" class="change-status" data-package-id="{{ $package->id }}" data-new-status="shipped" title="Marcar como enviado">
                          <i class="fas fa-lg fa-truck"></i>
                        </a>
                      @endif
                    </div>
                  </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table> --}}
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


{{-- <script>
  $(document).ready(function() {
    $('.change-status').change(function() {
        var packageId = $(this).data('package-id');
        var newStatus = $(this).val();

        $.ajax({
            url: 'packages/' + packageId + '/change-status',
            type: 'POST',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Estado actualizado.');
            },
            error: function(response) {
                alert('Error al actualizar el estado.');
            }
        });
    });
  });
</script> --}}

<script>
  $(document).ready(function() {
      $('.change-status').click(function(e) {
          e.preventDefault();
          var packageId = $(this).data('package-id');
          var newStatus = $(this).data('new-status');

          $.ajax({
              url: 'packages/' + packageId + '/change-status',
              type: 'POST',
              data: {
                  status: newStatus,
                  _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                  alert('Estado actualizado.');
                  location.reload(); // Recargar la página para actualizar el estado visualmente
              },
              error: function(response) {
                  alert('Error al actualizar el estado.');
              }
          });
      });
  });
  </script>


@endsection
