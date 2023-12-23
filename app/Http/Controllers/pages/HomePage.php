<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;



class HomePage extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        // Conteo de paquetes entregados
        $deliveredCount = Package::whereHas('latestActivity', function ($query) {
            $query->where('status', 'delivered');
        })->count();

        // Conteo de paquetes enviados
        $shippedCount = Package::whereHas('latestActivity', function ($query) {
            $query->where('status', 'shipped');
        })->count();

        // Conteo de paquetes en proceso, incluyendo aquellos sin actividades
        $processingCount = Package::whereDoesntHave('activities')
                            ->orWhereHas('latestActivity', function ($query) {
                                $query->where('status', 'processing');
                            })->count();

        // Obtener las últimas 5 actividades con sus relaciones necesarias
        $latestActivities = Activity::with(['package.branch', 'user'])
                                  ->latest()
                                  ->take(10)
                                  ->get();

        // Obtener todos los paquetes con sus relaciones para cualquier otra lógica que necesites en la vista
        $packages = Package::with(['client', 'branch', 'products', 'latestActivity'])->get();


        // Obtener las estadísticas de los clientes más activos
        $startOfMonth = Carbon::now()->startOfMonth();
        $activeClients = Client::withCount(['branches', 'packages as total_packages'])
                            ->withCount(['packages as monthly_packages' => function ($query) use ($startOfMonth) {
                                $query->where('created_at', '>=', $startOfMonth);
                            }])
                            ->orderByDesc('monthly_packages') // Ordenar por los más activos este mes
                            ->take(10) // Por ejemplo, los top 10
                            ->get();


        // Pasar datos a la vista
        return view('content.pages.dashboard.pages-home', [
            'deliveredCount' => $deliveredCount,
            'shippedCount' => $shippedCount,
            'processingCount' => $processingCount,
            'latestActivities' => $latestActivities,
            'packages' => $packages,
            'activeClients' => $activeClients,
            'notifications' => $notifications,
        ]);
    }
}
