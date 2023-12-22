@extends('layouts/layoutMaster')

@section('title', 'Editar producto')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />


@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/custom-js/edit-product.js')}}"></script>
@endsection

@section('content')

@can('edit products')

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


<div class="app-ecommerce">
  <form action="{{ route('products.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" id="productId" name="id" value="{{ $product->id }}">

  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

    <div class="d-flex flex-column justify-content-center">
      <h4 class="mb-1 mt-3">Editar Producto</h4>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-3">
      <button type="submit" class="btn btn-primary" id="submitButton">Publicar</button>
    </div>

  </div>


  <div class="row">

    <!-- First column-->
    <div class="col-12 col-lg-8">
      <!-- Product Information -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-tile mb-0">Información básica</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label" for="productName">Nombre</label>
            <input type="text" class="form-control" id="productName" name="productName" value="{{ $product->name }}" placeholder="Nombre del Producto">
          </div>
          <div class="row mb-3">
            <div class="col"><label class="form-label" for="productSku">SKU</label>
              <input type="number" class="form-control" id="productSku" placeholder="SKU" name="productSku" value="{{ $product->SKU }}">
            </div>
          </div>
          <!-- Description -->
          <div>
            <label class="form-label" for="description">Descripción <span class="text-muted">(Opcional)</span></label>
            <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
          </div>

        </div>
      </div>

      <!-- /Product Information -->
      <!-- Media -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 card-title">Imagen</h5>
        </div>
        <div class="card-body">
          <form action="/upload" class="dropzone needsclick" id="dropzone-basic">
            <div class="dz-message needsclick my-5">
              <span class="note needsclick btn bg-label-primary d-inline" id="btnBrowse">Buscar en su dispositivo</span>
            </div>
            <div class="fallback">
              <input name="file" type="file" id="fileInput" style="display: none;" /> <!-- Con ID agregado -->
            </div>
            <img id="imagePreview" src="{{ asset('' . $product->image) }}" alt="Vista previa de la imagen" style="width: 100px;" />
          </form>
        </div>
      </div>

      <script>
        document.getElementById('btnBrowse').addEventListener('click', function() {
      document.getElementById('fileInput').click();
      });
      </script>

      <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
        var imagePreview = document.getElementById('imagePreview');

        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block'; // Mostrar la imagen
                imagePreview.style.width = '100px';   // Ajustar el ancho
            }

            reader.readAsDataURL(e.target.files[0]);
        } else {
            imagePreview.style.display = 'none'; // Ocultar la vista previa si no hay archivo
        }
        });

      </script>
      <!-- /Media -->
      {{-- <!-- Variants -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Variantes</h5>
        </div>
        <div class="card-body">
          <form class="form-repeater">
            <div data-repeater-list="group-a">
              <div data-repeater-item>
                <div class="row">

                  <div class="mb-3 col-4">
                    <label class="form-label" for="form-repeater-1-1">Options</label>
                    <select id="form-repeater-1-1" class="select2 form-select" data-placeholder="Size">
                      <option value="">Size</option>
                      <option value="size">Size</option>
                      <option value="color">Color</option>
                      <option value="weight">Weight</option>
                      <option value="smell">Smell</option>
                    </select>
                  </div>

                  <div class="mb-3 col-8">
                    <label class="form-label invisible" for="form-repeater-1-2">Not visible</label>
                    <input type="number" id="form-repeater-1-2" class="form-control" placeholder="Enter size" />
                  </div>

                </div>
              </div>
            </div>
            <div>
              <button class="btn btn-primary" data-repeater-create>
                Add another option
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- /Variants --> --}}

    </div>
    <!-- /Second column -->

    <!-- Second column -->
    <div class="col-12 col-lg-4">
      <!-- Inventary Card -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Inventario</h5>
        </div>
        <div class="card-body">
          <!-- Instock switch -->
          <div class="d-flex col-2 justify-content-between align-items-center pt-3">
            <span class="mb-0 h6">Stock</span>
            <div class="w-25">
                <label class="switch switch-primary switch-sm me-4 pe-2">
                  <input type="checkbox" class="switch-input" id="stockSwitch" {{ $product->stock > 0 ? 'checked' : '' }}>
                  <span class="switch-toggle-slider">
                        <span class="switch-on">
                            <span class="switch-off"></span>
                        </span>
                    </span>
                </label>
            </div>

        </div>
        <div class="mt-3">
          <input type="number" id="stock" name="stock" class="form-control" value="{{ $product->stock }}" placeholder="Cantidad de stock" style="{{ $product->stock > 0 ? 'display: block;' : 'display: none;' }}">
        </div>
        </div>
      </div>
      <!-- /Inventary Card -->
      <!-- Organize Card -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Organizar</h5>
        </div>
        <div class="card-body">
          <!-- Category -->
          <div class="mb-3 col ecommerce-select2-dropdown">
            <label class="form-label mb-1 d-flex justify-content-between align-items-center" for="category-org">
              <span>Categoria</span><a href="product-categories" class="fw-medium">Crear nueva categoría</a>
            </label>
            <select id="category-org" name="category-org[]" class="select2 form-select" multiple data-placeholder="Seleccione las categorías">
              @foreach ($categories as $category)
              <option value="{{ $category->id }}" {{ in_array($category->id, $productCategoryIds) ? 'selected' : '' }}>
                  {{ $category->name }}
              </option>
              @endforeach
            </select>
          </div>
          <!-- Status -->
          <div class="mb-3 col ecommerce-select2-dropdown">
            <label class="form-label mb-1" for="status">Estado
            </label>
            <select id="status" class="select2 form-select" name="status">
              <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Activo</option>
              <option value="2" {{ $product->status == 2 ? 'selected' : '' }}>Inactivo</option>
            </select>
          </div>
        </div>
      </div>
      <!-- /Organize Card -->

      {{-- <!-- Inventory -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Inventario</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Navigation -->
            <div class="col-12 col-md-4 mx-auto card-separator">
              <div class="d-flex justify-content-between flex-column mb-3 mb-md-0 pe-md-3">
                <ul class="nav nav-align-left nav-pills flex-column">
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#restock">
                      <i class="bx bx-cube me-2"></i>
                      <span class="align-middle">Restock</span>
                    </button>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /Navigation -->
            <!-- Options -->
            <div class="col-12 col-md-8 pt-4 pt-md-0">
              <div class="tab-content p-0 pe-md-5 ps-md-3">
                <!-- Restock Tab -->
                <div class="tab-pane fade show active" id="restock" role="tabpanel">
                  <h5>Opciones</h5>
                  <label class="form-label" for="ecommerce-product-stock">Agregar Stock</label>
                  <div class="row mb-3 g-3">
                    <div class="col-12 col-sm-9">
                      <input type="number" class="form-control" id="ecommerce-product-stock" placeholder="Cantidad" name="quantity" aria-label="Cantidad"></div>
                    <div class="col-12 col-sm-3">
                      <button class="btn  btn-primary "><i class='bx bx-check me-2'></i></button>
                    </div>
                  </div>
                  <div>
                    <h6>En Stock actualmente: <span class="text-muted">54</span></h6>
                    <h6>En transito: <span class="text-muted">390</span></h6>
                    <h6>Último re-stock: <span class="text-muted">24 de Junio de 2022</span></h6>
                    <h6>Stock total lifetime: <span class="text-muted">2430</span></h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Options-->
          </div>
        </div>
      </div>
      <!-- /Inventory --> --}}

    </div>
    <!-- /Second column -->
  </form>
  </div>
</div>

<script>
  var productId = {{ $product->id }};
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
  // Obtener el ID del producto
  var productId = {{ $product->id }};

  // Rellena los campos del formulario con los datos del producto
  document.getElementById('productName').value = '{{ $product->name }}';
  document.getElementById('productSku').value = '{{ $product->SKU }}';
  document.getElementById('description').value = '{{ $product->description }}';
  // Agrega más campos según sea necesario...
});

document.getElementById('editProductForm').addEventListener('submit', function(event) {
  event.preventDefault();

  var formData = new FormData(this);
  formData.append('_method', 'PUT'); // Para simular una solicitud PUT

  // Envía la información actualizada al servidor
  fetch('/products/' + productId + '/update', {
    method: 'POST',
    body: formData,
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}' // Agregar el token CSRF
    }
  })
  .then(response => {
    if(response.ok) {
      console.log('Producto actualizado con éxito');
      window.location.href = '/products'; // Redireccionar a la lista de productos
    }
  })
  .catch(error => console.error('Error al actualizar el producto:', error));
});

</script>
<script>
  // Función para controlar la visibilidad del input basado en el estado del switch
  function toggleStockInput() {
      var stockSwitch = document.getElementById('stockSwitch');
      var stockInput = document.getElementById('stock');
      if (stockSwitch.checked) {
          stockInput.style.display = 'block';
      } else {
          stockInput.style.display = 'none';
      }
  }

  // Escucha el evento 'change' del switch
  document.getElementById('stockSwitch').addEventListener('change', toggleStockInput);

  // Ejecuta la función inmediatamente para establecer el estado inicial
  toggleStockInput();
</script>

@else

  @extends('layouts/not-authorized')

@endcan

@endsection
