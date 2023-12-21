<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Permisos

          // Permisos de Productos
          Permission::create(['name' => 'view products']);
          Permission::create(['name' => 'create products']);
          Permission::create(['name' => 'edit products']);
          Permission::create(['name' => 'delete products']);

          // Permisos de Clientes y Sucursales
          Permission::create(['name' => 'view clients']);
          Permission::create(['name' => 'create clients']);
          Permission::create(['name' => 'edit clients']);
          Permission::create(['name' => 'delete clients']);

        // Roles

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleManager = Role::create(['name' => 'manager']);
        $roleStaff = Role::create(['name' => 'staff']);

        // Asignar permisos a roles

          // Permisos de Productos
          $roleAdmin->givePermissionTo('view products');
          $roleAdmin->givePermissionTo('create products');
          $roleAdmin->givePermissionTo('edit products');
          $roleAdmin->givePermissionTo('delete products');

          $roleManager->givePermissionTo('view products');
          $roleManager->givePermissionTo('create products');
          $roleManager->givePermissionTo('edit products');

          $roleStaff->givePermissionTo('view products');

          // Permisos de Clientes y Sucursales
          $roleAdmin->givePermissionTo('view clients');
          $roleAdmin->givePermissionTo('create clients');
          $roleAdmin->givePermissionTo('edit clients');
          $roleAdmin->givePermissionTo('delete clients');

          $roleManager->givePermissionTo('view clients');
          $roleManager->givePermissionTo('create clients');
          $roleManager->givePermissionTo('edit clients');

          $roleStaff->givePermissionTo('view clients');
    }
}
