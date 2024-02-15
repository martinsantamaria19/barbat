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

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Crear Rol</h3>
          <p>Establecer permisos del Rol</p>
        </div>
        <!-- Add role form -->
        <form id="addRoleForm" action="create-role" method="POST" class="row g-3">
          @csrf
          <div class="col-12 mb-4">
            <label class="form-label" for="modalRoleName">Nombre del Rol</label>
            <input type="text" id="modalRoleName" name="name" class="form-control" placeholder="Ingrese un nombre al Rol" required />
          </div>
          <div class="col-12">
            <h4>Permisos</h4>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody>
                  @foreach($permissions as $permission)
                  <tr>
                    <td class="text-nowrap fw-medium">
                      {{ $permissionNamesMap[$permission->name] ?? $permission->name }}
                    </td>
                    <td>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                              id="permission_{{ $permission->id }}"
                              name="permissions[]"
                              value="{{ $permission->id }}">
                        <label class="form-check-label" for="permission_{{ $permission->id }}"></label>
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
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Crear</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
          </div>
        </form>
        <!--/ Add role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->
