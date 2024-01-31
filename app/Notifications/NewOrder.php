<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrder extends Notification
{
  use Queueable;

  public $package;
  public $status;


  public function __construct($package, $status)
  {
      $this->package = $package;
      $this->status = $status;

  }

  public function via($notifiable)
  {
      $settings = $notifiable->notificationSettings()->where('type', 'NewOrder')->first();

      $channels = [];
      if ($settings && $settings->email) {
          $channels[] = 'mail';
      }
      if ($settings && $settings->app) {
          $channels[] = 'database';
      }

      return $channels;
  }


  public function toMail($notifiable)
  {
      // Si vas a enviar la notificación por correo, debes definir este método
      return (new MailMessage)
                  ->line('Se ha creado un nuevo envío')
                  ->action('Ver envío', url('/path/to/package'))
                  ->line('El nuevo estado es: ' . $this->status);
  }

  public function toArray($notifiable)
  {
      $clientCompanyName = $this->package->client ? $this->package->client->company_name : 'Cliente desconocido';
      $clientBranchName = $this->package->branch ? $this->package->branch->branch_name : 'Sucursal desconocida';

      return [

          'title' => "Nuevo envío para {$clientCompanyName} - {$clientBranchName}",
          'message' => "Se ha creado el envío #{$this->package->id}",
          'status' => $this->status,
          'type' => 'new_package',
      ];
  }
}
