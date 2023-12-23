<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Package;

class OrderStatusChangedNotification extends Notification
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
        return ['database']; // Aquí especificas los canales, por ejemplo: ['mail', 'database']
    }

    public function toMail($notifiable)
    {
        // Si vas a enviar la notificación por correo, debes definir este método
        return (new MailMessage)
                    ->line('El estado de tu paquete ha cambiado.')
                    ->action('Ver Paquete', url('/path/to/package'))
                    ->line('El nuevo estado es: ' . $this->status);
    }

    public function toArray($notifiable)
    {
        // Traducir el estado del paquete a un mensaje legible
        $statusMessage = '';

        switch ($this->status) {
            case 'delivered':
                $statusMessage = 'entregado';
                break;
            case 'shipped':
                $statusMessage = 'en camino';
                break;
            case 'processing':
                $statusMessage = 'en proceso';
                break;
            default:
                $statusMessage = $this->status;
        }

         // Obtener el nombre de la compañía del cliente
        $clientCompanyName = $this->package->client ? $this->package->client->company_name : 'Cliente desconocido';
        $clientBranchName = $this->package->branch ? $this->package->branch->branch_name : 'Sucursal desconocida';

        return [
            'title' => "Envío {$statusMessage} a {$clientCompanyName} - {$clientBranchName}",
            'message' => "El estado del envío #{$this->package->id} ha cambiado a {$statusMessage}.",
            'status' => $this->status,
        ];
    }

}
