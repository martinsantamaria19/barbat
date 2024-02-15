<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Branch;
use App\Models\Package;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
  public function index()
  {
    $totalClients = Client::count(); // Calcula el total de clientes
    $activeClients = Client::where('client_status', 'active')->count(); // Calcula el total de clientes activos
    $inactiveClients = Client::where('client_status', 'inactive')->count(); // Calcula el total de clientes inactivos
    $totalBranches = Branch::count(); // Calcula el total de sucursales

    // Subclientes
    $totalOwnClients = Client::where('owner', auth()->user()->company)->count();
    $ownActiveClients = Client::where('owner', auth()->user()->company)
      ->where('client_status', 'active')
      ->count();
    $ownInactiveClients = Client::where('owner', auth()->user()->company)
      ->where('client_status', 'inactive')
      ->count();
    $ownTotalBranches = Branch::where('branch_client', auth()->user()->company)->count();

    return view('content.pages.clients.clients', [
      'totalClients' => $totalClients,
      'activeClients' => $activeClients,
      'inactiveClients' => $inactiveClients,
      'totalBranches' => $totalBranches,
      'totalOwnClients' => $totalOwnClients,
      'ownActiveClients' => $ownActiveClients,
      'ownInactiveClients' => $ownInactiveClients,
      'ownTotalBranches' => $ownTotalBranches,
    ]);
  }

  public function store(Request $request)
  {
    // Obtén el usuario autenticado
    $user = auth()->user();
    Log::info('El usuario autenticado es: ', ['user_id' => $user->id, 'user_name' => $user->name]);

    $owner = null;
    Log::info('El propietario inicial es: ', ['owner' => $owner]);

    if ($user->company !== null) {
      // Si el usuario tiene 'company', establece 'owner' como el ID de ese 'company'
      $owner = $user->company;
      Log::info('El propietario actualizado es: ', ['owner' => $owner]);
    }

    // Validación y lógica de creación del cliente
    $client = Client::create([
      'company_name' => $request->input('companyName'),
      'company_address' => $request->input('companyAddress'),
      'company_phone' => $request->input('companyPhone'),
      'company_email' => $request->input('companyEmail'),
      'company_rut' => $request->input('companyRut'),
      'company_state' => $request->input('companyState'),
      'company_city' => $request->input('companyCity'),
      'owner' => $owner,
    ]);

    Log::info('El cliente creado es: ', [
      'client_id' => $client->id,
      'company_name' => $client->company_name,
      'company_email' => $client->company_email,
      'company_phone' => $client->company_phone,
      'company_address' => $client->company_address,
      'company_rut' => $client->company_rut,
      'company_state' => $client->company_state,
      'company_city' => $client->company_city,
      'owner' => $client->owner,
    ]);

    return back()->with('success', 'Cliente creado correctamente.');
  }

  public function show($clientId)
  {
    $client = Client::find($clientId);
    $totalBranches = $client->branches()->count();
    $totalPackages = Package::where('client_id', $clientId)->count();

    // Corrige la forma en que pasas las variables a la vista
    return view('content.pages.clients.show', [
      'client' => $client,
      'totalBranches' => $totalBranches,
      'totalPackages' => $totalPackages,
    ]);
  }

  public function edit($clientId)
  {
    $client = Client::findOrFail($clientId);
    return response()->json($client);
  }

  public function update(Request $request, $clientId)
  {
    // Validar la entrada
    $validatedData = $request->validate([
      'company_name' => 'required|string|max:255',
      'company_address' => 'required|string',
      'company_phone' => 'required|string',
      'company_email' => 'required|email',
      'company_rut' => 'required|string',
      'company_state' => 'required|string',
      'company_city' => 'required|string',
      'client_status' => 'required|string',
    ]);

    // Encontrar el cliente por ID y actualizarlo
    $client = Client::findOrFail($clientId);
    $client->fill($validatedData);
    $client->client_status = $request->input('client_status');
    $client->save();

    // Redirigir con un mensaje de éxito
    return back()->with('success', 'Cliente actualizado correctamente.');
  }

  public function destroy($clientId)
  {
    // Encontrar el cliente por ID y eliminarlo
    $client = Client::findOrFail($clientId);
    $client->delete();

    // Redirigir con un mensaje de éxito
    return back()->with('success', 'Cliente eliminado correctamente.');
  }

  public function getClientsList()
  {
    // Obtén el ID de company del usuario autenticado
    $companyId = auth()->user()->company;

    // Inicia la consulta
    $query = Client::withCount('branches');

    // Si el usuario tiene un companyId, filtra los clientes por el campo 'owner'
    if (!is_null($companyId)) {
      $query->where('owner', $companyId);
    }

    $clients = $query->get();

    return response()->json(['data' => $clients]);
  }
}
