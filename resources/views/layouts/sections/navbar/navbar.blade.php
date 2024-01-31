@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');

@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="{{$containerNav}}">
      @endif

      <!--  Brand demo (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{url('/')}}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
          <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
        </a>

        @if(isset($menuHorizontal))
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
          <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
        @endif
      </div>
      @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          <i class="bx bx-menu bx-sm"></i>
        </a>
      </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

      @if($configData['hasCustomizer'] == true)
      <!-- Style Switcher -->
      <div class="navbar-nav align-items-center">
        <div class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <i class='bx bx-sm'></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-start dropdown-styles">
            <li>
              <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                <span class="align-middle"><i class='bx bx-sun me-2'></i>Claro</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                <span class="align-middle"><i class="bx bx-moon me-2"></i>Oscuro</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                <span class="align-middle"><i class="bx bx-desktop me-2"></i>Sistema</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <!--/ Style Switcher -->
      @endif


        <ul class="navbar-nav flex-row align-items-center ms-auto">


        <!-- Notification -->
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            <i class="bx bx-bell bx-sm"></i>
            @if($unreadNotifications->count() > 0)
                <span class="badge bg-danger rounded-pill badge-notifications">
                    {{ $unreadNotifications->count() }}
                </span>
            @endif
          </a>
          <ul class="dropdown-menu dropdown-menu-end py-0">
            <li class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                    <h5 class="text-body mb-0 me-auto">Notificaciones</h5>
                    {{-- Puedes agregar funcionalidad para marcar todas como leídas aquí --}}
                    <a href="{{ route('notifications.mark-all-read') }}" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Marcar todas como leídas"><i class="bx fs-4 bx-envelope-open"></i></a>
                  </div>
            </li>
            <li class="dropdown-notifications-list scrollable-container">
                <ul class="list-group list-group-flush">
                    @foreach ($unreadNotifications as $notification)
                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                      @switch($notification->data['status'])
                                      @case('delivered')
                                          <img src="{{ asset('assets/img/icons/custom/check-regular-24.png') }}" alt="Check" class="rounded">
                                          @break
                                      @case('shipped')
                                          <img src="{{ asset('assets/img/icons/custom/success-truck.png') }}" alt="Truck" class="rounded">
                                          @break
                                      @case('processing')
                                          <img src="{{ asset('assets/img/icons/custom/warning-clock.png') }}" alt="Clock" class="rounded">
                                          @break
                                      @default
                                          <img src="{{ asset('assets/img/icons/custom/warning-clock.png') }}" alt="Clock" class="rounded">
                                      @endswitch
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    {{-- Personaliza el título y el contenido de la notificación según tus necesidades --}}
                                    <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification Title' }}</h6>
                                    <p class="mb-0">{{ $notification->data['message'] ?? 'Notification message goes here...' }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>

                                </div>
                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                    {{-- Puedes agregar acciones como marcar como leída o eliminar aquí --}}
                                    <a href="{{ route('notifications.mark-as-read', $notification->id) }}" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                  </div>
                            </div>
                        </li>
                    @endforeach
                    @foreach ($readNotifications as $notification)
                    <li class="list-group-item list-group-item-action dropdown-notifications-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                        {{-- Puedes personalizar el avatar según el tipo de notificación --}}
                                        @if($notification->type == 'App\Notifications\NewOrder')
                                            <img src="{{ asset('assets/img/icons/custom/check-regular-24.png') }}" alt="Check" class="rounded">
                                        @else
                                            <img src="{{ asset('assets/img/icons/custom/success-truck.png') }}" alt="Truck" class="rounded">
                                        @endif

                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    {{-- Personaliza el título y el contenido de la notificación según tus necesidades --}}
                                    <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification Title' }}</h6>
                                    <p class="mb-0">{{ $notification->data['message'] ?? 'Notification message goes here...' }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>

                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="dropdown-menu-footer border-top p-3">
                {{-- Link para ver todas las notificaciones --}}
              </li>
        </ul>

        </li>
        <!--/ Notification -->

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar avatar-online">
              <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="{{ route('settings.my-account')}}">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online">
                      <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="fw-medium d-block">
                      @if (Auth::check())
                      {{ Auth::user()->name }} {{ Auth::user()->lastname }}
                      @else
                        -
                      @endif
                    </span>
                    <small class="text-muted">
                      @if (Auth::check())
                        {{ Auth::user()->roles->first()->name ?? 'Sin Rol' }}
                      @else
                        -
                      @endif</small>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('settings.my-account')}}">
                <i class="bx bx-user me-2"></i>
                <span class="align-middle">Mi Perfil</span>
              </a>
            </li>
            @if (Auth::check() && Laravel\Jetstream\Jetstream::hasApiFeatures())
            <li>
              <a class="dropdown-item" href="{{ route('api-tokens.index') }}">
                <i class='bx bx-key me-2'></i>
                <span class="align-middle">API Tokens</span>
              </a>
            </li>
            @endif


              <li>
                <div class="dropdown-divider"></div>
              </li>
              @if (Auth::check())
              <li>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class='bx bx-power-off me-2'></i>
                  <span class="align-middle">Cerrar Sesión</span>
                </a>
              </li>
              <form method="POST" id="logout-form" action="{{ route('logout') }}">
                @csrf
              </form>
              @else
              <li>
                <a class="dropdown-item" href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                  <i class='bx bx-log-in me-2'></i>
                  <span class="align-middle">Iniciar Sesión</span>
                </a>
              </li>
              @endif
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>

      @if(!isset($navbarDetached))
    </div>
    @endif
  </nav>
  <!-- / Navbar -->
