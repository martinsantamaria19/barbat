@php
$configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  @if(!isset($navbarFull))
  <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="assets\img\branding\logo.png" alt="">
      </span>
      <div class="user-data-container">
        @auth
        <h6 class="user-data-name">{{ Auth::user()->name }}</h6>
        <p class="user-data-mail">{{ Auth::user()->email }}</p>
        <p class="user-data-company">{{ Auth::user()->company ?? 'Barbat' }}</p>
        @endauth

      </div>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
  @endif


  <div class="menu-inner-shadow"></div>

  @php
  $currentRouteName = Route::currentRouteName();
  @endphp

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)
      @php
        $activeClass = '';
        $isSubmenuActive = false;

        if ($currentRouteName === $menu->slug) {
            $activeClass = 'active';
        } elseif (isset($menu->submenu)) {
            foreach ($menu->submenu as $submenu) {
                if ($currentRouteName === $submenu->slug) {
                    $activeClass = 'active';
                    $isSubmenuActive = true;
                    break;
                }
            }
        }
      @endphp

      <li class="menu-item {{ $activeClass }} {{ $isSubmenuActive ? 'open' : '' }}">
        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" {{ isset($menu->target) && !empty($menu->target) ? 'target="_blank"' : '' }}>
          @isset($menu->icon)
          <i class="{{ $menu->icon }}"></i>
          @endisset
          <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
          @isset($menu->badge)
          <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
          @endisset
        </a>

        @isset($menu->submenu)
          @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu, 'isSubmenuActive' => $isSubmenuActive])
        @endisset
      </li>
    @endforeach
  </ul>
</aside>
