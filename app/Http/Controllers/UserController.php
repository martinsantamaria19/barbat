<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
  public function index()
  {
    $roles = Role::all();
    return view('content.pages.users.users', compact('roles'));
  }

  public function getUsersList()
  {
      $users = User::with('role')->get()->map(function ($user) {
          $user->role_name = $user->role ? $user->role->name : 'Sin Rol';
          return $user;
      });

      return response()->json(['data' => $users]);
  }

  public function store(Request $request)
  {
      $user = User::create([
          'name' => $request->input('name'),
          'company' => $request->input('company'),
          'email' => $request->input('email'),
          'password' => bcrypt($request->input('password')),
          'role_id' => $request->input('role'),
      ]);

      return back()->with('success', 'Usuario creado correctamente.');
  }

  public function edit($userId)
  {
    $user = User::findOrFail($userId);
    return response()->json($user);
  }

  public function update(Request $request, $id)
  {
      // Validar la entrada
      $validatedData = $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|email|unique:users,email,' . $id,
          'company' => 'nullable|string',
          'role_id' => 'required|integer',
      ]);

      // Encontrar el usuario por ID y actualizarlo
      $user = User::findOrFail($id);
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

}
