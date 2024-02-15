<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;

class HomePage extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $companyId = $user->company; // ID de la 'company' del usuario autenticado

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
      })
      ->count();

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
      ->withCount([
        'packages as monthly_packages' => function ($query) use ($startOfMonth) {
          $query->where('created_at', '>=', $startOfMonth);
        },
      ])
      ->orderByDesc('monthly_packages') // Ordenar por los más activos este mes
      ->take(10) // Por ejemplo, los top 10
      ->get();

    $totalClients = Client::count(); // Calcula el total de clientes
    $activeClientsCount = Client::where('client_status', 'active')->count(); // Calcula el total de clientes activos
    $inactiveClients = Client::where('client_status', 'inactive')->count(); // Calcula el total de clientes inactivos
    $totalBranches = Branch::count(); // Calcula el total de sucursales

    // PARA CLIENTES

    // Para paquetes entregados
    $ownDeliveredCount = Package::whereHas('latestActivity', function ($query) {
      $query->where('status', 'delivered');
    })
      ->where(function ($query) use ($companyId) {
        $query
          ->whereHas('client', function ($subQuery) use ($companyId) {
            $subQuery->where('owner', $companyId); // Paquetes de sub-clientes
          })
          ->orWhere('client_id', $companyId); // Paquetes propios
      })
      ->count();

    // Para paquetes en proceso
    $ownProcessingCount = Package::whereDoesntHave('activities')
      ->orWhereHas('latestActivity', function ($query) {
        $query->where('status', 'processing');
      })
      ->where(function ($query) use ($companyId) {
        $query
          ->whereHas('client', function ($subQuery) use ($companyId) {
            $subQuery->where('owner', $companyId); // Paquetes de sub-clientes
          })
          ->orWhere('client_id', $companyId); // Paquetes propios
      })
      ->count();

    // Para paquetes enviados
    $ownShippedCount = Package::whereHas('latestActivity', function ($query) {
      $query->where('status', 'shipped');
    })
      ->where(function ($query) use ($companyId) {
        $query
          ->whereHas('client', function ($subQuery) use ($companyId) {
            $subQuery->where('owner', $companyId); // Paquetes de sub-clientes
          })
          ->orWhere('client_id', $companyId); // Paquetes propios
      })
      ->count();

    // Para las últimas actividades
    $ownLatestActivities = Activity::with(['package.branch', 'user'])
      ->whereHas('package', function ($query) use ($companyId) {
        $query
          ->whereHas('client', function ($subQuery) use ($companyId) {
            $subQuery->where('owner', $companyId); // Actividades de paquetes de sub-clientes
          })
          ->orWhere('client_id', $companyId); // Actividades de paquetes propios
      })
      ->latest()
      ->take(10)
      ->get();

    // Pasar datos a la vista
    return view('content.pages.dashboard.pages-home', [
      'deliveredCount' => $deliveredCount,
      'shippedCount' => $shippedCount,
      'processingCount' => $processingCount,
      'latestActivities' => $latestActivities,
      'packages' => $packages,
      'activeClientsCount' => $activeClientsCount,
      'notifications' => $notifications,
      'totalClients' => $totalClients,
      'activeClients' => $activeClients,
      'inactiveClients' => $inactiveClients,
      'totalBranches' => $totalBranches,
      'ownDeliveredCount' => $ownDeliveredCount,
      'ownProcessingCount' => $ownProcessingCount,
      'ownShippedCount' => $ownShippedCount,
      'ownLatestActivities' => $ownLatestActivities,
    ]);
  }
}
