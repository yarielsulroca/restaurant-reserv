<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ReservationExpired extends Mailable
{
    public function __construct(
        protected Reservation $reservation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reserva Expirada - Restaurant',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservations.expired',
            with: [
                'reservation' => $this->reservation,
            ]
        );
    }
}