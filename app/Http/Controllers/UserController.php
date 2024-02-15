<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function index()
  {
    $roles = Role::all();
    $clients = Client::all();

    return view('content.pages.users.users', compact('roles', 'clients'));
  }

  public function getUsersList()
  {
    $users = User::with(['roles', 'client']) // Cargar la relación con la empresa (client)
      ->get()
      ->map(function ($user) {
        // Recoger los nombres de los roles
        $roleNames = $user->roles->pluck('name');
        $user->role_name = $roleNames->isNotEmpty() ? implode(', ', $roleNames->toArray()) : 'Sin rol';

        // Obtener el nombre de la empresa si está relacionada
        $companyName = $user->client ? $user->client->company_name : 'Barbat';
        $user->company_name = $companyName;

        return $user;
      });

    return response()->json(['data' => $users]);
  }

  public function store(Request $request)
  {
    try {
      $request->validate([
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'company' => 'nullable|integer|exists:clients,id',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string',
        'password' => 'required|string|min:8',
        'role' => 'required|exists:roles,name',
      ]);

      // Crea el usuario con los datos del formulario
      $user = User::create([
        'name' => $request->input('name'),
        'lastname' => $request->input('lastname'),
        'company' => $request->input('company') === '' ? null : $request->input('company'),
        'email' => $request->input('email'),
        'phone' => $request->input('phone'),
        'password' => bcrypt($request->input('password')),
      ]);

      $roleName = $request->input('role');

      if (!empty($roleName)) {
        // Asigna el rol al usuario por su nombre
        $user->assignRole($roleName);
      } else {
        // Manejar el caso en el que no se proporcionó un rol válido
        return back()->with('error', 'No se proporcionó un rol válido.');
      }

      return back()->with('success', 'Usuario creado correctamente.');
    } catch (\Exception $e) {
      // Registra el error en los logs de Laravel
      \Log::error('Error en UserController@store: ' . $e->getMessage());

      // Retorna una respuesta de error
      return back()->with('error', 'Error interno del servidor.');
    }
  }

  public function edit($userId)
  {
    $user = User::with('roles')->findOrFail($userId);
    $roles = $user->roles->pluck('name'); // Obtener los nombres de los roles

    return response()->json([
      'user' => $user,
      'roles' => $roles,
      'status' => $user->status,
    ]);
  }

  public function update(Request $request, $id)
  {
    // Validar la entrada, excluyendo la contraseña
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'lastname' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $id,
      'phone' => 'nullable|string',
      'company' => 'nullable|string',
      'role' => 'required|exists:roles,name',
    ]);

    // Encontrar el usuario por ID
    $user = User::findOrFail($id);

    // Verificar y actualizar la contraseña solo si se proporciona una nueva
    if (!empty($request->input('password'))) {
      $request->validate([
        'password' => 'string|min:8|confirmed',
      ]);
      $validatedData['password'] = bcrypt($request->input('password'));
    }

    // Actualizar el usuario con los datos validados
    $user->update($validatedData);
    $user->syncRoles([$request->input('role')]);
    $user->status = $request->input('status');
    $user->save();

    // Redirigir con un mensaje de éxito
    return back()->with('success', 'Usuario actualizado correctamente.');
  }

  public function changeUserPassword(Request $request, $id)
  {
    $request->validate([
      'userId' => 'required|exists:users,id',
      'newPassword' => 'required|string|min:8|confirmed',
    ]);

    $user = User::findOrFail($id);
    $user->password = bcrypt($request->input('newPassword'));
    $user->save();

    return response()->json(['success' => 'Contraseña actualizada correctamente']);
  }

  public function destroy($userId)
  {
    // Encontrar el usuario por ID y eliminarlo
    $user = User::findOrFail($userId);
    $user->delete();

    // Redirigir con un mensaje de éxito
    return back()->with('success', 'Usuario eliminado correctamente.');
  }

  public function show($id)
  {
    $user = User::findOrFail($id);
    $roles = Role::all();

    return view('content.pages.users.show', compact('user', 'roles'));
  }

  public function updatePassword(Request $request)
  {
    $request->validate([
      'newPassword' => 'required|string|min:8',
    ]);

    $user = auth()->user();
    $user->password = bcrypt($request->newPassword);
    $user->save();

    return back()->with('success', 'Contraseña actualizada correctamente.');
  }

  public function suspend($id)
  {
    $user = User::findOrFail($id);
    $user->status = 'inactive';
    $user->save();

    return response()->json(['success' => 'Usuario suspendido correctamente']);
  }

  public function activate($id)
  {
    $user = User::findOrFail($id);
    $user->status = 'active';
    $user->save();

    return response()->json(['success' => 'Usuario activado correctamente']);
  }

  public function getUserPermissions()
  {
    // Verificar si el usuario está autenticado
    if (Auth::check()) {
      // Obtener el usuario autenticado
      $user = Auth::user();

      // Obtener los permisos del usuario
      $permissions = $user
        ->getAllPermissions()
        ->pluck('name')
        ->toArray();

      // Devolver los permisos como respuesta JSON
      return response()->json(['permissions' => $permissions]);
    } else {
      // Si el usuario no está autenticado, devolver un mensaje de error
      return response()->json(['error' => 'Usuario no autenticado'], 401);
    }
  }
}
