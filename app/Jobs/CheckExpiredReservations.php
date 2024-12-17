<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Mail\ReservationExpired;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CheckExpiredReservations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiredReservations = Reservation::query()
            ->where('status', 'confirmed')
            ->where('end_time', '<=', now())
            ->get();

        foreach ($expiredReservations as $reservation) {
            $reservation->update(['status' => 'expired']);
            
            // Notificar al usuario y al admin
            Mail::to($reservation->user->email)->send(new ReservationExpired($reservation));
            Mail::to(config('mail.admin_address'))->send(new ReservationExpired($reservation));
        }
    }
}
