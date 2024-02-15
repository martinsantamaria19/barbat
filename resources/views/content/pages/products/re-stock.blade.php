@extends('layouts/layoutMaster')

@section('title', 'Devolución de Stock')

@section('page-script')
<script src="{{asset('assets/js/custom-js/re-stock.js')}}"></script>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-primary d-flex" role="alert">
  <span class="badge badge-center rounded-pill bg-primary border-label-primary p-3 me-2"><i class="bx bx-user fs-6"></i></span>
  <div class="d-flex flex-column ps-1">
    <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Devolución correcta!</h6>
    <span>La devolución ha sido registrada correctamente.</span>
  </div>
</div>
@endif


<div class=" flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Operaciones /</span> Devolución de Stock</h4>

    <!-- Formulario de devolución -->
    <form action="{{ route('products.processRestock') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Cliente</label>
                <select class="form-select" name="client_id" id="clientSelect">
                    <option value="">Seleccione un cliente</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Sucursal</label>
                <select class="form-select" name="branch_id" id="branchSelect">
                    <!-- Las sucursales se cargarán dinámicamente -->
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Fecha de Devolución</label>
                <input type="date" class="form-control" name="return_date" required>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Productos a Devolver</h5>
            </div>
            <div class="card-body">
                <table class="table" id="productsTable">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Devolver</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los productos se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Procesar Devolución</button>
            </div>
        </div>
    </form>
</div>

@section('page-script')
<script>
    // Aquí vendría el JavaScript necesario para cargar las sucursales basadas en el cliente seleccionado,
    // cargar los productos disponibles para devolución y manejar el formulario de devolución.
</script>
@endsection

@endsection
