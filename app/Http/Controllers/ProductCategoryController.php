<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;


class ProductCategoryController extends Controller
{
  public function index()
  {
    return view('content.pages.categories.product-categories');
  }

  public function getProductCategoriesList()
  {
    $categories = \App\Models\Category::withCount('products')->get();
    return response()->json(['data' => $categories]);
  }

  public function store(Request $request)
  {

    $productCategory = new \App\Models\Category();
    $productCategory->name = $request->input('name');
    $productCategory->save();


    // Devolver una respuesta JSON
    return back()->with('success', 'Categoría actualizada correctamente.');
  }

  public function update(Request $request) {
    $category = Category::find($request->id);
    $category->name = $request->name;
    $category->save();

    // Redirige con un mensaje de éxito
    return back()->with('success', 'Categoría actualizada correctamente.');
  }

  public function delete($id)
  {
      $category = Category::find($id);
      if ($category) {
          $category->delete();
          return response()->json(['message' => 'Categoría eliminada con éxito']);
      } else {
          return response()->json(['error' => 'Categoría no encontrada'], 404);
      }
  }


}
