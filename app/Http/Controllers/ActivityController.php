<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Package;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
  public function index()
{
    $user = auth()->user();
    $latestActivities = null;

    // Verifica si el usuario es "Cliente" o "Cliente + Sub-Clientes"
    if ($user->hasRole(['Cliente', 'Cliente + Sub-Clientes'])) {
        // Obtener actividades solo para el cliente del usuario y los sub-clientes
        $clientId = $user->company; // ID del cliente del usuario autenticado

        // Incluir el propio cliente y cualquier sub-cliente
        $clientIds = Client::where('id', $clientId)
                            ->orWhere('owner', $clientId)
                            ->pluck('id')
                            ->toArray();

        $latestActivities = Activity::whereHas('package', function ($query) use ($clientIds) {
            $query->whereIn('client_id', $clientIds);
                                    })->with(['package.branch', 'user'])
                                      ->latest()

          ->get();
    } else if ($user->hasRole('Administrador') || $user->hasRole('Manager') || $user->hasRole('Staff')) {
        // Si el usuario es administrador, mostrar todas las actividades
        $latestActivities = Activity::with(['package.branch', 'user'])
                                    ->latest()
                                    ->get();
    } else {
        // Para otros roles, ajusta esta parte según tus necesidades específicas
        // Por ejemplo, podrías querer limitar las actividades a aquellas creadas por el usuario
        $latestActivities = Activity::where('user_id', $user->id)
                                    ->with(['package.branch', 'user'])
                                    ->latest()
                                    ->get();
    }

    return view('content.pages.activities.activity', compact('latestActivities'));
}


  // Método para mostrar las actividades de un paquete específico
  public function showActivities($packageId)
  {
    $activities = Activity::where('package_id', $packageId)->get();
    return view('content.pages.activities.show', compact('activities'));
  }

  // Método para crear una nueva actividad
  public function store(Request $request)
  {
    $request->validate([
      'package_id' => 'required|exists:packages,id',
      'status' => 'required|string',
    ]);

    $activity = new Activity();
    $activity->package_id = $request->package_id;
    $activity->status = $request->status;
    $activity->updated_by = Auth::id();
    $activity->save();

    return redirect()
      ->back()
      ->with('success', 'Actividad registrada con éxito.');
  }

  public function getDeliveredActivitiesData()
  {
    $data = Activity::selectRaw('DATE(created_at) as date, COUNT(*) as count')
      ->where('status', 'delivered')
      ->groupBy('date')
      ->orderBy('date', 'asc')
      ->get();

    return response()->json($data);
  }
}
