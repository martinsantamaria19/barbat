@extends('layouts/layoutMaster')

@section('title', 'Crear Paquete')

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

@section('page-script')
<script src="{{asset('assets/js/custom-js/add-package.js')}}"></script>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-primary d-flex" role="alert">
  <span class="badge badge-center rounded-pill bg-primary border-label-primary p-3 me-2"><i class="bx bx-user fs-6"></i></span>
  <div class="d-flex flex-column ps-1">
    <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Paquete Creado!</h6>
    <span>El paquete ha sido creado correctamente.</span>
  </div>
</div>
@endif


<div class="row">
  <form method="POST" action="{{ route('package.store') }}">
    @csrf
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

    <div class="d-flex flex-column justify-content-center">
      <h4 class="mb-1 mt-3">Crear Paquete</h4>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-3">
      <button type="submit" class="btn btn-primary" id="submitButton">Publicar</button>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-tile mb-0">Información básica</h5>
      </div>
      <div class="card-body row">
        <div class="col-4 ecommerce-select2-dropdown">
          <label class="form-label d-flex justify-content-between align-items-center" for="category-org">
            <span>Cliente</span><a href="product-categories" class="fw-medium">Crear nuevo cliente</a>
          </label>
          <select id="client_id" name="client_id" class="select2 form-select" data-placeholder="Seleccione el cliente">
            <option value="" disabled selected>Seleccione un cliente</option>
            @foreach ($clients as $client)
              <option value="{{ $client->id }}">{{ $client->company_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-4 ecommerce-select2-dropdown">
          <label class="form-label" for="branch_id">Sucursal
          </label>
          <select id="branch_id" class="select2 form-select" name="branch_id" data-placeholder="Seleccionar sucursal">

          </select>
        </div>
        <div class="mb-3 col-4">
          <label class="form-label" for="delivery_date">Fecha de Entrega</label>
          <input type="text" class="form-control" id="delivery_date" placeholder="Seleccione la fecha de entrega" name="delivery_date" aria-label="Fecha de Entrega" required>
      </div>

        <div class="col-4 ecommerce-select2-dropdown">
          <label class="form-label" for="priority">Prioridad
          </label>
          <select id="priority" class="select2 form-select" name="priority" data-placeholder="Seleccionar sucursal">
            <option value="high">Alta</option>
            <option value="medium">Media</option>
            <option value="low">Baja</option>
          </select>
        </div>
      </div>
    </div>
    <div class="d-flex ">
      <div class="card mb-4 col-8">
        <div class="card-header headerProductos">
          <h5 class="card-tile mb-0">Productos</h5>
        </div>
        <div class="card-body">
          <div class="card-datatable table-responsive">
            <table class="datatables-products table border-top">
              <thead>
                <tr>
                  <th>Imagen</th>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Categoría</th>
                  <th>Descripción</th>
                  <th>Stock</th>
                  <th>Acciones</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="card mb-4 col-4">
        <div class="card-header">
          <h5>Carrito</h5>
        </div>

        <div class="card-body" id="carrito">

        </div>


        <div class="card-footer">
          <div class="d-flex justify-content-end">
            <button class="btn btn-danger" id="vaciarCarrito">Vaciar Carrito</button>
          </div>
        </div>
      </div>

    </div>

  <input type="hidden" name="productos_carrito" id="productosCarrito">
  </form>

  </div>
</div>


@endsection
