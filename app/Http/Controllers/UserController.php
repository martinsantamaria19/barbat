<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{


  public function index()
  {
    $roles = Role::all();

    return view('content.pages.users.users', compact('roles'));
  }


  public function getUsersList()
  {
    $users = User::with('roles')->get()->map(function ($user) {

          // Recoger los nombres de los roles
          $roleNames = $user->roles->pluck('name');
          $user->role_name = $roleNames->isNotEmpty() ? implode(', ', $roleNames->toArray()) : 'Sin rol';

          return $user;
      });

      return response()->json(['data' => $users]);
  }


  public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string',
            'company' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        // Crea el usuario con los datos del formulario
        $user = User::create([
            'name' => $request->input('name'),
            'lastname' => $request->input('lastname'),
            'company' => $request->input('company'),
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

      // Validar la entrada
      $validatedData = $request->validate([
          'name' => 'required|string|max:255',
          'lastname' => 'required|string|max:255',
          'email' => 'required|email|unique:users,email,' . $id,
          'phone' => 'nullable|string',
          'company' => 'nullable|string',
          'role' => 'required|exists:roles,name',
      ]);

      // Encontrar el usuario por ID y actualizarlo
      $user = User::findOrFail($id);
      $user->syncRoles([$request->input('role')]);
      $user->status = $request->input('status');
      $user->update($validatedData);

      // Redirigir con un mensaje de éxito
      return back()->with('success', 'Usuario actualizado correctamente.');
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

}
