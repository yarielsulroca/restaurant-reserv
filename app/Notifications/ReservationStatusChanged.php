<?php

namespace App\Notifications;

use App\Models\Reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        protected Reserva $reserva,
        protected string $previousStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = match($this->reserva->estado) {
            'confirmada' => 'Tu reserva ha sido confirmada.',
            'cancelada' => 'Tu reserva ha sido cancelada.',
            'completada' => 'Tu reserva ha sido completada. ¡Gracias por visitarnos!',
            'expirada' => 'Tu reserva ha expirado.',
            default => 'El estado de tu reserva ha cambiado.'
        };

        return (new MailMessage)
            ->subject('Estado de Reserva Actualizado')
            ->greeting('Hola ' . $notifiable->name)
            ->line($message)
            ->line('Detalles de la reserva:')
            ->line('Fecha: ' . $this->reserva->hora_inicio->format('d/m/Y'))
            ->line('Hora: ' . $this->reserva->hora_inicio->format('H:i'))
            ->line('Personas: ' . $this->reserva->numero_personas)
            ->action('Ver Reserva', url('/reservas/' . $this->reserva->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reserva_id' => $this->reserva->id,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->reserva->estado,
            'message' => "Tu reserva ha cambiado de estado a {$this->reserva->estado}"
        ];
    }
}