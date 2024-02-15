@php
$permissionNamesMap = [
    'view products'   => 'Ver productos',
    'create products' => 'Crear productos',
    'edit products'   => 'Editar productos',
    'delete products' => 'Eliminar productos',
    'view clients'    => 'Ver clientes',
    'create clients'  => 'Crear clientes',
    'edit clients'    => 'Editar clientes',
    'delete clients'  => 'Eliminar clientes',
    'view branches'     => 'Ver sucursales',
    'view roles'      => 'Ver roles',
    'view users'      => 'Ver usuarios',
    'edit status'     => 'Editar estado',
    'view own clients' => 'Ver clientes propios',
    'view dashboard'   => 'Ver dashboard',
    'make re-stock'    => 'Crear devoluciones',
    'view product categories' => 'Ver categorías de productos',
    'view packages'    => 'Ver envíos',
    'view activities'  => 'Ver actividades',
    'create branches'  => 'Crear sucursales',
    'edit branches'    => 'Editar sucursales',
    'delete branches'  => 'Eliminar sucursales',
    'view own'         => 'Ver propios',
    'create packages'  => 'Crear envíos',
];
@endphp


<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Editar Rol</h3>
          <p>Modificar permisos del Rol</p>
        </div>
        <!-- Edit role form -->
        <form id="editRoleForm" action="{{ route('roles.update', $role->id) }}" method="POST" class="row g-3">
          @csrf
          @method('PUT')
          <input type="hidden" id="editRoleId" name="id" value="{{ $role->id }}">
          <div class="col-12 mb-4">
            <label class="form-label" for="editRoleName">Nombre del Rol</label>
            <input type="text" id="editRoleName" name="name" class="form-control" placeholder="Ingrese un nombre al Rol" required />
          </div>
          <div class="col-12">
            <h4>Permisos</h4>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing" id="permissionsTable"> <!-- Agregamos un ID -->
                  <tbody>
                    @foreach($permissions as $permission)
                    <tr>
                        <td class="text-nowrap fw-medium">
                            {{ $permissionNamesMap[$permission->name] ?? $permission->name }}
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="permission_{{ str_replace(' ', '_', $permission->name) }}"
                                    name="permissions[]"
                                    value="{{ $permission->id }}">
                                <label class="form-check-label" for="permission_{{ str_replace(' ', '_', $permission->name) }}"></label>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                  </tbody>
              </table>
          </div>
            <!-- Permission table -->
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Actualizar</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
          </div>
        </form>
        <!--/ Edit role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Edit Role Modal -->
