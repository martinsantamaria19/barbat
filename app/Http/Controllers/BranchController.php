<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Client;

class BranchController extends Controller
{
  public function index()
  {
    $clients = Client::all();
    return view('content.pages.branches.branches', [
      'clients' => $clients
    ]);
  }

  public function getBranchesList()
  {
    $branches = Branch::with('client')->get(); // 'client' es el nombre del método de relación en el modelo Branch
    return response()->json(['data' => $branches]);
  }

  public function store(Request $request)
  {
    $branch = Branch::create([
      'branch_client' => $request->input('branchClient'),
      'branch_name' => $request->input('branchName'),
      'branch_phone' => $request->input('branchPhone'),
      'branch_email' => $request->input('branchEmail'),
      'branch_address' => $request->input('branchAddress'),
      'branch_city' => $request->input('branchCity'),
      'branch_state' => $request->input('branchState'),
      'branch_rut' => $request->input('branchRut'),
    ]);

    return redirect()->back()->with('success', true);
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
