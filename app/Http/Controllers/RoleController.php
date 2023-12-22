<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;




class RoleController extends Controller
{

  public function listPermissions()
    {
    $permissions = Permission::all();

    return view('content.pages.config.permissions', compact('permissions'));
  }


  public function index()
    {
        $roles = Role::withCount('users')->get();
        $permissions = Permission::all(); // Obtén todos los permisos

        return view('content.pages.users.roles', compact('roles', 'permissions'));
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
        $permissions = $role->permissions->pluck('name')->toArray(); // Obtén solo los nombres de los permisos


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
        // Validar la solicitud
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Obtener el rol por su ID
        $role = Role::findOrFail($id);

        // Actualizar el nombre del rol
        $role->name = $validatedData['name'];
        $role->save();

        // Sincronizar los permisos si se enviaron en la solicitud
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return back()->with('success', 'Rol actualizado correctamente.');
    } catch (\Exception $e) {
        Log::error('Error in update method: ' . $e->getMessage());

        // Agregar declaración dd para depurar
        dd('Error en la actualización:', $e->getMessage());

        return back()->with('error', 'Ocurrió un error al actualizar el rol.');
    }
}



public function destroy($id)
{
    try {
        $role = Role::findOrFail($id);

        // Verifica si hay usuarios asignados a este rol
        if ($role->users->count() > 0) {
            return back()->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        // Elimina el rol
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    } catch (\Exception $e) {
        Log::error('Error in destroy method: ' . $e->getMessage());
        return back()->with('error', 'Ocurrió un error al eliminar el rol.');
    }
}



}
