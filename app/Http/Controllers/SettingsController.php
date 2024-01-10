<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationSetting;
use App\Models\User;

class SettingsController extends Controller {

  public function index()
  {
    return view('content.pages.settings.settings');
  }

  public function notifications()
  {
    $user = auth()->user();
    $settings = NotificationSetting::where('user_id', $user->id)->get();

    $notificationNames = [
      'NewOrder' => 'Nuevo Envío',
      'OrderStatusChanged' => 'Cambios de estado en envíos'
    ];

    // Obtener o crear las configuraciones para las notificaciones específicas
    $newOrderSetting = $user->notificationSettings()->firstOrCreate(
        ['type' => 'NewOrder'],
        ['email' => true, 'app' => true]
    );

    $orderStatusChangedSetting = $user->notificationSettings()->firstOrCreate(
        ['type' => 'OrderStatusChanged'],
        ['email' => true, 'app' => true]
    );



    // Pasar estas configuraciones a la vista
    return view('content.pages.settings.notifications', compact('newOrderSetting', 'orderStatusChangedSetting', 'settings', 'notificationNames'));
  }


  public function updateNotifications(Request $request)
  {
    $user = auth()->user();
    $settingsInput = $request->input('settings', []);

    // Obtén todas las configuraciones de notificación del usuario
    $allNotifications = $user->notificationSettings;

    foreach ($allNotifications as $notificationSetting) {
        // Verifica si se envió la configuración para este tipo de notificación
        if (isset($settingsInput[$notificationSetting->type])) {
            // Actualiza según los valores enviados
            $prefs = $settingsInput[$notificationSetting->type];
            $notificationSetting->update([
                'email' => isset($prefs['email']),
                'app' => isset($prefs['app']),
            ]);
        } else {
            // Si no se envió nada, desactiva ambos
            $notificationSetting->update([
                'email' => false,
                'app' => false,
            ]);
        }
    }

    return back()->with('success', 'Configuraciones actualizadas correctamente.');
  }

  public function security()
  {
    return view('content.pages.settings.security');
  }

  public function myAccount()
  {
    $user = auth()->user();
    return view('content.pages.settings.my-account', compact('user'));
  }






}
