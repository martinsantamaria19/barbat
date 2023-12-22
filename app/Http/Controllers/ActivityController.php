<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{

    public function index()
    {
        $activities = Activity::with('package')->get();
        // Obtener las últimas 5 actividades con sus relaciones necesarias
        $latestActivities = Activity::with(['package.branch', 'user'])
                                  ->latest()
                                  ->get();

        return view('content.pages.activities.activity', compact('activities', 'latestActivities'));
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
            'status' => 'required|string'
        ]);

        $activity = new Activity();
        $activity->package_id = $request->package_id;
        $activity->status = $request->status;
        $activity->updated_by = Auth::id()->default('null');
        $activity->save();

        return redirect()->back()->with('success', 'Actividad registrada con éxito.');
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
