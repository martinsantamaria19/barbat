@extends('layouts/layoutMaster')

@section('title', 'Notificaciones')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Configuración /</span> Notificaciones
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link" href="{{ route('settings.my-account')}}"><i class="bx bx-user me-1"></i> Mi Cuenta</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('settings.security') }}"><i class="bx bx-lock-alt me-1"></i> Contraseña</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-bell me-1"></i> Notificaciones</a></li>
    </ul>
    <div class="card">
      <!-- Notifications -->
      <form action="{{ route('settings.notifications.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="table-responsive">
          <table class="table table-striped table-borderless border-bottom">
            <thead>
              <tr>
                <th class="text-nowrap">Notificación</th>
                <th class="text-nowrap text-center">Email</th>
                <th class="text-nowrap text-center">Aplicación</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($settings as $setting)
                <tr>
                  <td class="text-nowrap">{{ $notificationNames[$setting->type] ?? $setting->type }}</td>
                  <td>
                    <div class="form-check d-flex justify-content-center">
                      <input class="form-check-input" type="checkbox" name="settings[{{$setting->type}}][email]" {{ $setting->email ? 'checked' : '' }} />
                    </div>
                  </td>
                  <td>
                    <div class="form-check d-flex justify-content-center">
                      <input class="form-check-input" type="checkbox" name="settings[{{$setting->type}}][app]" {{ $setting->app ? 'checked' : '' }} />
                    </div>
                  </td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="mt-4">
              <button type="submit" class="btn btn-primary me-2">Guardar</button>
              <button type="reset" class="btn btn-label-secondary">Cancelar</button>
            </div>
          </div>
        </div>
      </form>
      </div>
  </div>
</div>
@endsection
