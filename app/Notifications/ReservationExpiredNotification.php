<?php

namespace App\Notifications;

use App\Models\Reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationExpiredNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Reserva $reserva
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reserva Expirada')
            ->line('La reserva ha expirado.')
            ->line("Mesa: {$this->reserva->mesa->numero}")
            ->line("Fecha: {$this->reserva->fecha}")
            ->line("Hora: {$this->reserva->hora}")
            ->action('Ver Reserva', url("/reservas/{$this->reserva->id}"));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reserva_id' => $this->reserva->id,
            'mesa_numero' => $this->reserva->mesa->numero,
            'fecha' => $this->reserva->fecha,
            'hora' => $this->reserva->hora,
        ];
    }
}
