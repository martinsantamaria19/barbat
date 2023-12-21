<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class RoleController extends Controller
{
  public function index()
  {
      $roles = Role::all();
      $users = User::all(); // Obtén todos los usuarios
      $permissions = Permission::all(); // Obtén todos los permisos

      return view('content.pages.users.roles', compact('roles', 'users', 'permissions'));
  }


  public function store(Request $request)
  {

    // Validar la solicitud
    $request->validate([
        'name' => 'required|unique:roles,name', // Asegúrate de que el nombre del rol sea único
        'permissions' => 'required|array',    // Asegúrate de que se hayan seleccionado los permisos
        'permissions.*' => 'exists:permissions,id' // Asegúrate de que los permisos existan en la base de datos
    ]);
    // Crear el rol
    $role = Role::create(['name' => $request->name]);
    // Asignar permisos al rol
    $permissions = Permission::whereIn('id', $request->permissions)->get();
    $role->syncPermissions($permissions);
    // Redirigir o responder según sea necesario
    return back()->with('success', 'Rol creado correctamente.');
  }

  public function create()
  {
    $permissions = Permission::all();
    return view('roles.create', compact('permissions'));
  }

  public function edit($id)
  {
      try {
          $role = Role::with('permissions')->findOrFail($id);
          $permissions = $role->permissions;

          Log::info('Role and permissions data:', ['role' => $role, 'permissions' => $permissions]);

          return response()->json([
              'role' => $role,
              'permissions' => $permissions
          ]);
      } catch (\Exception $e) {
          Log::error('Error in edit method: ' . $e->getMessage());
          return response()->json(['error' => $e->getMessage()], 500);
      }
  }

  public function update(Request $request, $id)
  {
      try {
          // Validación inicial
          $validatedData = $request->validate([
              'name' => 'required|unique:roles,name,' . $id,
              'permissions' => 'required|array',
              'permissions.*' => 'exists:permissions,id',
          ]);

          // Registro de datos validados
          Log::info('Validated data:', $validatedData);

          // Buscar el rol
          $role = Role::findOrFail($id);
          Log::info("Updating role: {$role->name}");

          // Verificar si el nuevo nombre es diferente del nombre actual
          if ($validatedData['name'] !== $role->name) {
              // Verificar si el nuevo nombre ya existe en otro rol
              if (Role::where('name', $validatedData['name'])->exists()) {
                  Log::error("Error updating role: The name '{$validatedData['name']}' is already taken.");
                  return back()->with('error', 'Error al actualizar el rol. El nombre ya está en uso.');
              }

              // Actualizar nombre del rol solo si es diferente y único
              $role->update(['name' => $validatedData['name']]);
              Log::info("Updated role name to: {$validatedData['name']}");
          } else {
              Log::info("New name is the same as the current name. No update needed.");
          }

          // Buscar y sincronizar permisos
          $permissions = Permission::find($validatedData['permissions']);
          if ($permissions) {
              $role->syncPermissions($permissions);
              Log::info('Permissions synced:', $permissions->pluck('name')->toArray()); // Convertir a array
          } else {
              Log::warning("Permissions not found or empty for IDs: ", $validatedData['permissions']);
          }

          return back()->with('success', 'Rol actualizado correctamente.');
      } catch (\Exception $e) {
          // Capturar cualquier excepción y registrarla
          Log::error('Error updating role: ' . $e->getMessage());
          return back()->with('error', 'Error al actualizar el rol.');
      }
  }






}
