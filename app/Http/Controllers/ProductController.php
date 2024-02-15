<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\Client;
use App\Models\ClientsStock;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
  public function index()
  {
    // Obtén los productos
    $products = Product::all();

    // Obtén los permisos del usuario actual para editar y eliminar productos
    $canEditProduct = auth()
      ->user()
      ->can('edit product');
    $canDeleteProduct = auth()
      ->user()
      ->can('delete product');

    return view('content.pages.products.products', compact('products', 'canEditProduct', 'canDeleteProduct'));
  }

  public function create()
  {
    $categories = Category::all();
    return view('content.pages.products.add-product', compact('categories'));
  }

  public function getProductsList()
  {
    // Asume que tienes una relación llamada 'categories' en tu modelo Product
    $products = \App\Models\Product::with('categories')->get();

    // Opcionalmente, puedes personalizar los datos antes de enviarlos, si es necesario
    $products = $products->map(function ($product) {
      // Aquí puedes formatear tus productos como prefieras
      // Por ejemplo, añadir una lista de nombres de categorías como una cadena
      $product->category_names = $product->categories->pluck('name')->join(', ');
      return $product;
    });

    return response()->json(['data' => $products]);
  }

  public function store(Request $request)
  {
    try {
      $request->validate([
        'file' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
        // Añade validaciones para otros campos si es necesario
      ]);

      $product = new \App\Models\Product();
      $product->SKU = $request->input('productSku');
      $product->name = $request->input('productName');
      $product->description = $request->input('description');
      $product->stock = is_null($request->input('stock')) ? 0 : $request->input('stock');
      $product->status = $request->input('status');
      $product->price = $request->input('productPrice');

      // Manejar la carga de la imagen
      if ($request->hasFile('file')) {
        $imageName = time() . '.' . $request->file('file')->extension();
        $request->file('file')->move(public_path('assets/img/products'), $imageName);
        $product->image = 'assets/img/products/' . $imageName;
      }
      $product->save();

      $product->categories()->attach($request->input('category-org'));

      return redirect()
        ->back()
        ->with('success', true);
    } catch (\Exception $e) {
      // Imprimir mensaje de error o registrar en el log
      dd($e->getMessage());
    }
  }

  public function reStock()
  {
    $clients = Client::all();

    return view('content.pages.products.re-stock', compact('clients'));
  }

  public function edit($id)
  {
    // Obtener el producto por ID
    $product = Product::findOrFail($id);

    // Obtener todas las categorías disponibles
    $categories = Category::all();

    // Obtener los IDs de las categorías del producto
    $productCategoryIds = $product->categories->pluck('id')->toArray();

    // Pasar el producto, categorías y los IDs de categorías del producto a la vista
    return view('content.pages.products.edit-product', compact('product', 'categories', 'productCategoryIds'));
  }

  public function update(Request $request, $id)
  {
    // Validación de datos del formulario
    $validator = Validator::make($request->all(), [
      'productName' => 'required|string|max:255',
      'productSku' => 'required|string|max:255',
      'description' => 'nullable|string',
      'stock' => 'required|integer|min:0',
      'status' => 'required|in:1,2',
      'file' => 'nullable|image|max:2048',
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    // Encontrar el producto por ID y actualizarlo
    $product = Product::findOrFail($id);

    // Asigna los valores a las propiedades del producto
    $product->name = $request->productName;
    $product->SKU = $request->productSku;
    $product->description = $request->description;
    $product->stock = $request->stock;
    $product->status = $request->status;

    // Si hay una nueva imagen, procesarla
    if ($request->hasFile('file')) {
      $imagePath = $request->file->store('products', 'public');
      // Aquí deberías eliminar la imagen anterior si es necesario
      $product->image = $imagePath;
    }

    // Guardar los cambios en la base de datos
    $product->save();

    // Redirigir con un mensaje de éxito
    return redirect()
      ->route('products')
      ->with('success', 'Producto actualizado correctamente.');
  }

  public function destroy($productId)
  {
    // Verificar si el usuario tiene el permiso para eliminar productos
    if (Gate::allows('delete products')) {
      // Buscar el producto por ID
      $product = Product::find($productId);

      // Eliminar el producto
      if ($product) {
        $product->delete();
        return redirect()
          ->route('products')
          ->with('success', 'Producto eliminado correctamente.');
      }

      return response()->json(['error' => 'Producto no encontrado.'], 404);
    } else {
      // El usuario no tiene permisos para eliminar productos
      return redirect()
        ->route('products')
        ->with('error', 'No tienes permisos para eliminar productos.');
    }
  }

  public function updateStatus(Request $request)
  {
    $productId = $request->input('id');
    $newStatus = $request->input('status');

    // Buscar y actualizar el producto
    $product = Product::find($productId);
    if ($product) {
      $product->status = $newStatus;
      $product->save();
      return response()->json(['message' => 'Estado actualizado con éxito.']);
    }

    return response()->json(['error' => 'Producto no encontrado.'], 404);
  }

  public function processRestock(Request $request)
  {
      $clientId = $request->input('client_id');
      $branchId = $request->input('branch_id');
      $products = $request->input('products');

      DB::beginTransaction();

      try {
          foreach ($products as $productId => $quantity) {
              if ($quantity > 0) {
                  $clientStock = ClientsStock::where('client_id', $clientId)
                                  ->where('branch_id', $branchId)
                                  ->where('product_id', $productId)
                                  ->first();

                  if ($clientStock) {
                      // Resta la cantidad devuelta del stock existente en ClientsStock
                      $clientStock->stock -= $quantity;
                      $clientStock->save();

                      // Encuentra el producto correspondiente y suma la cantidad devuelta al stock de la empresa
                      $product = Product::find($productId);
                      if ($product) {
                          $product->increment('stock', $quantity);
                      }
                  } else {
                    Log::alert("message: No se encontró el stock del producto en la sucursal del cliente.");
                  }
              }
          }

          DB::commit();
          return redirect()->back()->with('success', 'Devolución procesada correctamente.');
      } catch (\Exception $e) {
          DB::rollBack();
          return redirect()->back()->with('error', 'Error al procesar la devolución: ' . $e->getMessage());
      }
  }



  public function getProductsForReturn($clientId, $branchId)
  {
      // Asume que ClientsStock tiene relaciones 'product', 'client', y 'branch'
      $clientsStock = ClientsStock::with('product')
                      ->where('client_id', $clientId)
                      ->where('branch_id', $branchId)
                      ->get();

      $products = $clientsStock->map(function ($clientStock) {
          return [
              'id' => $clientStock->product->id,
              'name' => $clientStock->product->name,
              'stock' => $clientStock->stock,
          ];
      });

      return response()->json(['data' => $products]);
  }


}
