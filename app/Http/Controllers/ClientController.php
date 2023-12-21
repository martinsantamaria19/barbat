<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Branch;

class ClientController extends Controller
{
  public function index()
  {
    $totalClients = Client::count(); // Calcula el total de clientes
    $activeClients = Client::where('client_status', 'active')->count(); // Calcula el total de clientes activos
    $inactiveClients = Client::where('client_status', 'inactive')->count(); // Calcula el total de clientes inactivos
    $totalBranches = Branch::count(); // Calcula el total de sucursales

    return view('content.pages.clients.clients', [
      'totalClients' => $totalClients,
      'activeClients' => $activeClients,
      'inactiveClients' => $inactiveClients,
      'totalBranches' => $totalBranches,
  ]);
  }


  public function store(Request $request)
  {
      // Validación y lógica de creación del cliente
      $client = Client::create([
          'company_name' => $request->input('companyName'),
          'company_address' => $request->input('companyAddress'),
          'company_phone' => $request->input('companyPhone'),
          'company_email' => $request->input('companyEmail'),
          'company_rut' => $request->input('companyRut'),
          'company_state' => $request->input('companyState'),
          'company_city' => $request->input('companyCity'),
      ]);

      return back()->with('success', 'Cliente creado correctamente.');

  }

  public function show($clientId)
  {
      $client = Client::find($clientId);
      return view('content.pages.clients.show', ['client' => $client]);
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
    ]);

    // Encontrar el cliente por ID y actualizarlo
    $client = Client::findOrFail($clientId);
    $client->fill($validatedData);
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
    $clients = Client::withCount('branches')->get(); // Asume que tienes una relación 'branches' en tu modelo Client
    return response()->json(['data' => $clients]);
  }

}
