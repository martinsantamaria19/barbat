@php
$configData = Helper::appClasses();
$userPermissions = auth()->user()->getAllPermissions()->pluck('name'); // Obtener todos los permisos del usuario.
$currentRouteName = Route::currentRouteName(); // Obtener el nombre de la ruta actual
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- Brand Logo & User's Data -->
  @if(!isset($navbarFull))
  <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/img/branding/RB-icono.svg') }}" class="menuLogo" alt="Brand Logo">
      </span>
      <!-- User's Data -->
      <div class="user-data-container">
        @auth
        <h6 class="user-data-name">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</h6>
        <p class="user-data-mail">{{ Auth::user()->email }}</p>
        <p class="user-data-company">{{ Auth::user()->client->company_name ?? 'Barbat' }}</p>
        @endauth
      </div>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)
      @php
      $subMenuPermissions = collect($menu->submenu ?? [])->flatMap(function ($submenu) {
        return $submenu->permissions ?? [];
      });
      $menuPermissions = array_merge($menu->permissions ?? [], $subMenuPermissions->all());
      $isVisible = empty($menuPermissions) || $userPermissions->intersect($menuPermissions)->isNotEmpty();
      $isActive = $currentRouteName && Str::startsWith($currentRouteName, $menu->slug);
      @endphp

      @if ($isVisible)
        <li class="menu-item {{ $isActive ? 'active open' : '' }}">
          <a href="{{ url($menu->url) }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}">
            <i class="{{ $menu->icon }}"></i>
            <div>{{ $menu->name }}</div>
          </a>
          @if (!empty($menu->submenu))
            <ul class="menu-sub">
              @foreach ($menu->submenu as $submenu)
                @php
                $isSubmenuVisible = empty($submenu->permissions) || $userPermissions->intersect($submenu->permissions)->isNotEmpty();
                $isSubmenuActive = $currentRouteName && Str::startsWith($currentRouteName, $submenu->slug);
                @endphp

                @if ($isSubmenuVisible)
                  <li class="menu-item {{ $isSubmenuActive ? 'active' : '' }}">
                    <a href="{{ url($submenu->url) }}" class="menu-link">
                      <div>{{ $submenu->name }}</div>
                    </a>
                  </li>
                @endif
              @endforeach
            </ul>
          @endif
        </li>
      @endif
    @endforeach
  </ul>
</aside>
