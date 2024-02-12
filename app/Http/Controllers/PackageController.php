<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Branch;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Package;
use App\Models\Activity;
use App\Models\Receiver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OrderStatusChangedNotification;
use App\Notifications\NewOrder;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PackageController extends Controller
{
  public function index()
  {
    // Comienza con una consulta general
    $query = Package::with([
      'client',
      'branch',
      'products',
      'activities',
      'receiver',
      'latestActivity' => function ($query) {
        $query->latest()->first();
      },
    ]);

    // Verifica si el usuario tiene el permiso "view own"
    if (
      auth()
        ->user()
        ->hasPermissionTo('view own')
    ) {
      // Asumiendo que cada usuario está asociado con un cliente
      $clientId = auth()->user()->client_id; // Asegúrate de que esto coincida con tu estructura de base de datos
      $query->whereHas('client', function ($q) use ($clientId) {
        $q->where('id', $clientId);
      });
    }

    $packages = $query->get();

    return view('content.pages.packages.packages', compact('packages'));
  }

  public function show($packageId)
  {
    // Obtén el paquete específico con la información relacionada
    $package = Package::with(['latestActivity', 'client', 'branch', 'products', 'receiver'])->findOrFail($packageId);

    if ($package) {
      $latestActivity = $package->latestActivity;
      if (!$latestActivity) {
        $latestActivity = new Activity(['status' => 'processing']);
      }

      // Acceder al receptor asociado al paquete
      $receiver = $package->receiver;

      // Pasa el paquete a la vista
      return view('content.pages.packages.show', [
        'package' => $package,
        'products' => $package->products,
        'activity' => $package->latestActivity,
        'receiver' => $receiver,
      ]);
    } else {
      // Manejar el error si el paquete no existe
      return redirect()
        ->back()
        ->withErrors(['error' => 'Paquete no encontrado.']);
    }
  }

  public function create()
  {
    $clients = Client::with('branches')->get();
    $users = User::all();
    $products = Product::with('categories')->get(); // Carga los productos con sus categorías
    $categories = Category::all();
    return view('content.pages.packages.add-package', compact('clients', 'users', 'products', 'categories'));
  }

  public function store(Request $request)
  {
    // Iniciar una transacción para asegurar la integridad de los datos
    DB::beginTransaction();

    try {
      // Crear el paquete
      $package = new Package();
      $package->client_id = $request->client_id;
      $package->branch_id = $request->branch_id;
      $package->priority = $request->priority;
      $package->delivery_date = $request->delivery_date;
      $package->save(); // Guardar el paquete
      Log::info('Paquete creado con ID: ' . $package->id);

      // Agregar productos al paquete y validar el stock
      $productosCarrito = json_decode($request->input('productos_carrito'), true);
      foreach ($productosCarrito as $producto) {
        $product = Product::find($producto['id']);
        if ($product) {
          if ($product->stock < $producto['cantidad']) {
            // Si no hay suficiente stock, detener el proceso y enviar un mensaje
            DB::rollback();
            return back()->with('error', 'Stock no disponible para el producto: ' . $product->name);
          }

          $package->products()->attach($producto['id'], ['cantidad' => $producto['cantidad']]);
          $product->stock -= $producto['cantidad'];
          $product->save();
        } else {
          DB::rollback();
          return back()->with('error', 'Producto no encontrado: ' . $producto['id']);
        }
      }

      if (is_null($package->id)) {
        Log::error('Error: El paquete no tiene ID después de guardarse.');
        DB::rollback();
        return back()
          ->withErrors(['error' => 'Error al guardar el paquete.'])
          ->withInput();
      }

      // Crear una actividad asociada con el paquete
      $activity = new Activity([
        'package_id' => $package->id,
        'status' => 'processing',
        'updated_by' => Auth::id(),
      ]);
      Log::info('Creando actividad para el paquete ID: ' . $package->id);
      $activity->save();
      Log::info('Actividad creada para el paquete ID: ' . $package->id);

      DB::commit();

      $usersToNotify = User::all();
      $status = 'new';
      Notification::send($usersToNotify, new NewOrder($package, $status));

      return redirect()
        ->route('packages')
        ->with('success', 'Paquete creado correctamente');
    } catch (\Exception $e) {
      Log::error('Error en store: ' . $e->getMessage());
      DB::rollback();
      return back()
        ->withErrors(['error' => $e->getMessage()])
        ->withInput();
    }
  }

  public function getBranches($client_id)
  {
    $branches = Branch::where('branch_client', $client_id)->get();
    return response()->json($branches);
  }

  public function changeStatus($packageId, Request $request)
  {
    // Validar el request
    $validated = $request->validate([
      'status' => 'required|string|in:processing,shipped,delivered', // Reemplaza con los estados válidos
    ]);

    try {
      $package = Package::findOrFail($packageId);
      $newStatus = $request->status;

      // Log de información adicional
      Log::info('Package ID: ' . $packageId);
      Log::info('Current Status: ' . $package->status); // Asegúrate de que 'status' es el campo correcto
      Log::info('New Status: ' . $request->status);

      // Registrar la nueva actividad
      $activity = new Activity([
        'package_id' => $package->id,
        'status' => $request->status,
        'updated_by' => auth()->id(), // Suponiendo que quieres registrar quién cambió el estado
        // Otros detalles de la actividad
      ]);
      $package->activities()->save($activity);

      $usersToNotify = User::all(); // Ejemplo: Notificar a todos los usuarios
      Notification::send($usersToNotify, new OrderStatusChangedNotification($package, $newStatus));

      return back()->with('success', 'Estado del envío actualizado correctamente');
    } catch (\Exception $e) {
      Log::error('Error al cambiar estado del paquete: ' . $e->getMessage());

      // Devolver una respuesta de error
      return response()->json(['error' => 'Error al cambiar el estado del paquete.'], 500);
    }
  }

  public function showLabel($packageId)
  {
    $package = Package::findOrFail($packageId);

    // Ruta al archivo PDF de la etiqueta
    $pdfPath = $package->label_path;
    if (Storage::disk('public')->exists($pdfPath)) {
      return response()->download(storage_path('app/public/' . $pdfPath));
    } else {
      // Manejar el error si el archivo no existe
      return redirect()
        ->back()
        ->withErrors(['error' => 'No se encontró el archivo de etiqueta.']);
    }
  }

  public function getPackagesList()
  {
    // Comienza con una consulta general
    $query = Package::with(['client', 'branch', 'products', 'latestActivity']);

    // Verifica si el usuario tiene el rol "cliente"
    if (
      auth()
        ->user()
        ->hasRole('Cliente')
    ) {
      // Filtra los paquetes por el cliente asociado al usuario a través de la relación "company"
      $clientId = auth()->user()->company;
      $query->where('client_id', $clientId);
    }

    $packages = $query->get()->map(function ($package) {
      // Aquí puedes formatear los datos como necesites
      return [
        'id' => $package->id,
        'client' => $package->client->company_name,
        'branch' => $package->branch->branch_name,
        'products' => $package->products->pluck('name'),
        'status' => optional($package->latestActivity)->status,
        'delivery_date' => $package->delivery_date,
        'priority' => $package->priority,
        'label_path' => asset($package->label_path),
      ];
    });

    return response()->json(['data' => $packages]);
  }

  public function rastreo()
  {
    return view('content.pages.packages.rastreo');
  }

  public function trackPackage(Request $request)
  {
    $trackingCode = $request->input('tracking_code');
    $package = Package::with(['latestActivity', 'products', 'receiver'])
      ->where('tracking_code', $trackingCode)
      ->first();

    if ($package) {
      // Asegúrate de que existe una última actividad para el paquete
      $latestActivity = $package->latestActivity;
      if (!$latestActivity) {
        // Si no hay una última actividad, puedes establecer un estado predeterminado o manejarlo como desees
        $latestActivity = new Activity(['status' => 'unknown']); // 'unknown' es un ejemplo, elige lo que sea adecuado
      }

      // Acceder al receptor asociado al paquete
      $receiver = $package->receiver;

      // Ahora, pasamos la última actividad a la vista junto con el paquete
      return view('content.pages.packages.track-result', [
        'package' => $package,
        'activity' => $latestActivity,
        'products' => $package->products,
        'receiver' => $receiver,
      ]);
    } else {
      // Si no se encuentra el paquete, redirige o muestra un mensaje de error
      return back()->with('error', 'Código de rastreo no encontrado.');
    }
  }

  public function destroy($packageId)
  {
    $package = Package::findOrFail($packageId);
    $package->delete();
    return redirect()
      ->back()
      ->with('success', 'Paquete eliminado correctamente.');
  }

  public function saveReceiver(Request $request, $packageId)
{
    // Validación de los datos del receptor
    $request->validate([
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'cedula' => 'required|string|max:255',
        'image' => 'required|image',
    ]);

    // Obtener el paquete al que quieres asociar el receptor
    $package = Package::find($packageId);

    // Verificar si el paquete existe
    if (!$package) {
        return response()->json(['message' => 'El paquete no fue encontrado'], 404);
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $destinationPath = public_path('/assets/img/receivers');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $image->move($destinationPath, $filename);
        $imagePath = 'assets/img/receivers/' . $filename;
    } else {
        return response()->json(['message' => 'La imagen es obligatoria'], 400);
    }

    // Crear una instancia del receptor con los datos de la solicitud
    $receiver = new Receiver([
        'package_id' => $package->id,
        'name' => $request->input('name'),
        'lastname' => $request->input('lastname'),
        'cedula' => $request->input('cedula'),
        'image' => $imagePath, // Ajustado para coincidir con el nombre de la columna de la base de datos
    ]);

    // Asociar el receptor con el paquete
    $package->receiver()->save($receiver);

    // Opcionalmente, puedes devolver una respuesta JSON indicando el éxito de la operación
    return response()->json(['message' => 'Receptor asociado correctamente al paquete'], 200);
  }

}
