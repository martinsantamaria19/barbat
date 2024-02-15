<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Client;
use App\Models\ClientsStock;
use App\Models\Activity;
use App\Models\Package;

class BranchController extends Controller
{
  public function index()
  {
      $user = auth()->user();
      $allowedClients = []; // Inicializa la lista de clientes permitidos

      // Determina los clientes permitidos basado en el rol o permisos del usuario
      if ($user->hasRole(['Cliente', 'Cliente + Sub-Clientes'])) {
          // Si el usuario es un cliente, puede seleccionar su propia empresa
          $allowedClients[] = $user->company;

          // Si el usuario puede gestionar sub-clientes, añade esos también
          $subClients = Client::where('owner', $user->company)->pluck('id')->toArray();
          $allowedClients = array_merge($allowedClients, $subClients);
      } else {
          // Si el usuario tiene un rol que le permite gestionar todos los clientes
          $allowedClients = Client::pluck('id')->toArray();
      }

      // Obtiene todos los clientes para pasarlo a la vista, puedes ajustar esto según sea necesario
      $clients = Client::all();

      return view('content.pages.branches.branches', [
          'clients' => $clients,
          'allowedClients' => $allowedClients, // Pasa los clientes permitidos a la vista
      ]);
  }


  public function getBranchesList()
  {
    $user = auth()->user(); // Obtiene el usuario autenticado

    // Determina si el usuario es de tipo "Cliente" o "Cliente + Sub-Clientes"
    if ($user->hasRole(['Cliente', 'Cliente + Sub-Clientes'])) {
      // Filtra las sucursales que pertenecen al cliente del usuario autenticado y a cualquier "sub-cliente" donde el usuario es el "owner"
      $branches = Branch::whereHas('client', function ($query) use ($user) {
        $query->where('id', $user->company)->orWhere('owner', $user->company); // Asume que 'company' es el campo que relaciona directamente al usuario con su cliente y 'owner' es el campo que indica la propiedad de un "sub-cliente"
      })
        ->with('client')
        ->get();
    } else {
      // Si el usuario no es de tipo "Cliente" o "Cliente + Sub-Clientes", obtiene todas las sucursales
      $branches = Branch::with('client')->get();
    }

    return response()->json(['data' => $branches]);
  }

  public function show($branchId)
  {
    // Encuentra la sucursal por su ID y carga su cliente relacionado y los productos en stock
    $branch = Branch::with(['client', 'clientsStock.product'])->findOrFail($branchId);
    $client = $branch->client;

    // Procesa los productos en stock
    $products = $branch->clientsStock->map(function ($clientsStock) {
      return [
        'id' => $clientsStock->product->id,
        'name' => $clientsStock->product->name,
        'stock' => $clientsStock->stock,
      ];
    });

    // Calcula el total de productos en stock para la sucursal
    $totalStock = $branch->clientsStock->sum('stock');

    // Calcula el total de paquetes entregados asociados a la sucursal
    $totalDeliveredPackages = Activity::whereHas('package', function ($query) use ($branchId) {
      $query->where('branch_id', $branchId);
    })
      ->where('status', 'delivered')
      ->count();

    return view('content.pages.branches.show', [
      'branch' => $branch,
      'products' => $products,
      'client' => $client,
      'totalStock' => $totalStock,
      'totalDeliveredPackages' => $totalDeliveredPackages,
    ]);
  }

  public function showStock($branchId)
  {
    // Encuentra el stock del local especificado incluyendo información del producto
    $stockItems = ClientsStock::where('branch_id', $branchId)
      ->with('product') // Asegúrate de que la relación con 'product' esté definida en el modelo ClientsStock
      ->get()
      ->map(function ($item) {
        return [
          'product_id' => $item->product->id,
          'product_name' => $item->product->name,
          'stock' => $item->stock,
          // Incluye cualquier otra información que necesites
        ];
      });

    return response()->json($stockItems);
  }

  public function store(Request $request)
  {
    $user = auth()->user(); // Obtén el usuario autenticado
    $branchClientId = $request->input('branchClient');
    $isAllowed = false; // Bandera para verificar si el usuario puede crear la sucursal para el cliente seleccionado

    // Verifica si el usuario es "Cliente" o "Cliente + Sub-Clientes"
    if ($user->hasRole(['Cliente', 'Cliente + Sub-Clientes'])) {
      // Permite al usuario crear sucursales para sí mismo
      if ($user->company == $branchClientId) {
        $isAllowed = true;
      } else {
        // Verifica si el cliente seleccionado es un "sub-cliente" del usuario
        $subClients = Client::where('owner', $user->company)
          ->pluck('id')
          ->toArray();
        if (in_array($branchClientId, $subClients)) {
          $isAllowed = true;
        }
      }
    } else {
      // Aquí puedes manejar la lógica para otros roles, si es necesario
      $isAllowed = true; // Por defecto, permite la creación para roles no restringidos
    }

    // Si el usuario tiene permiso para crear la sucursal
    if ($isAllowed) {
      $branch = Branch::create([
        'branch_client' => $branchClientId,
        'branch_name' => $request->input('branchName'),
        'branch_phone' => $request->input('branchPhone'),
        'branch_email' => $request->input('branchEmail'),
        'branch_address' => $request->input('branchAddress'),
        'branch_city' => $request->input('branchCity'),
        'branch_state' => $request->input('branchState'),
        'branch_rut' => $request->input('branchRut'),
      ]);

      return redirect()
        ->back()
        ->with('success', 'Sucursal creada correctamente.');
    } else {
      // Si el usuario no tiene permiso para crear la sucursal para el cliente seleccionado
      return redirect()
        ->back()
        ->with('error', 'No tienes permiso para crear una sucursal para este cliente.');
    }
  }

  public function edit($branchId)
  {
    $branch = Branch::findOrFail($branchId);
    return response()->json($branch);
  }

  public function update(Request $request, $branchId)
  {
    // Validar la entrada
    $validatedData = $request->validate([
      'branch_client' => 'required|integer|exists:clients,id',
      'branch_name' => 'required|string|max:255',
      'branch_phone' => 'required|string',
      'branch_email' => 'required|email',
      'branch_address' => 'required|string',
      'branch_city' => 'required|string',
      'branch_state' => 'required|string',
      'branch_rut' => 'required|string',
    ]);

    // Encontrar la sucursal por ID y actualizarla
    $branch = Branch::findOrFail($branchId);
    $branch->fill($validatedData);
    $branch->save();

    // Redirigir con un mensaje de éxito
    return back()->with('success', 'Sucursal actualizada correctamente.');
  }

  public function destroy($branchId)
  {
    $branch = Branch::findOrFail($branchId);
    $branch->delete();
    return response()->json(['success' => true]);
  }
}
