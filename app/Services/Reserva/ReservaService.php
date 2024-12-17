<?php

namespace App\Services\Reserva;

use App\Models\Reserva;
use App\Models\Mesa;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use App\Notifications\ReservationExpiredNotification;
use App\Notifications\ReservationStatusChanged;

class ReservaService
{
    public function __construct(
        private readonly Reserva $reserva
    ) {}

    public function list(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->reserva
            ->when(isset($filters['fecha']), fn($query) => $query->whereDate('fecha', $filters['fecha']))
            ->when(isset($filters['status']), fn($query) => $query->where('status', $filters['status']))
            ->when(isset($filters['user_id']), fn($query) => $query->where('user_id', $filters['user_id']))
            ->with(['mesa', 'user'])
            ->paginate($perPage);
    }

    public function create(array $data): Reserva
    {
        // Calculate end time (1:45 hours + 15 minutes for preparation)
        $startTime = Carbon::parse($data['hora']);
        $data['hora_fin'] = $startTime->copy()->addMinutes(120)->format('H:i');
        
        $reserva = $this->reserva->create($data);
        
        // Update mesa status
        $mesa = Mesa::find($data['mesa_id']);
        $mesa->update(['status' => 'reserved']);
        
        return $reserva;
    }

    public function update(Reserva $reserva, array $data): Reserva
    {
        $reserva->update($data);
        return $reserva->fresh();
    }

    public function cancel(Reserva $reserva): bool
    {
        $reservationTime = Carbon::parse("{$reserva->fecha} {$reserva->hora}");
        $now = Carbon::now();

        if ($reservationTime->diffInMinutes($now) < 60) {
            throw new \Exception('Las reservas solo pueden cancelarse con al menos 1 hora de anticipación.');
        }

        $reserva->update(['status' => 'cancelled']);
        $reserva->mesa->update(['status' => 'available']);
        
        return true;
    }

    public function validateReservationTime(string $date, string $time): bool
    {
        $dateTime = Carbon::parse("$date $time");
        $dayOfWeek = $dateTime->dayOfWeek;
        $hour = (int) $dateTime->format('H');
        $minutes = (int) $dateTime->format('i');

        // Validate minutes are in 30-minute intervals
        if ($minutes % 30 !== 0) {
            return false;
        }

        // Monday to Friday: 10:00 to 24:00
        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
            return $hour >= 10 && $hour < 24;
        }

        // Saturday: 22:00 to 02:00
        if ($dayOfWeek === 6) {
            return $hour >= 22 || $hour < 2;
        }

        // Sunday: 12:00 to 16:00
        if ($dayOfWeek === 0) {
            return $hour >= 12 && $hour < 16;
        }

        return false;
    }

    public function validateTableCapacity(array $mesaIds, int $numeroPersonas): bool
    {
        $totalCapacity = Mesa::whereIn('id', $mesaIds)->sum('capacidad');
        return $numeroPersonas <= $totalCapacity;
    }

    public function validateMaxTables(User $user, array $mesaIds): bool
    {
        return count($mesaIds) <= 3;
    }

    public function checkAndUpdateExpiredReservations(): void
    {
        $now = Carbon::now();
        
        $expiredReservations = $this->reserva
            ->where('status', 'confirmed')
            ->where('fecha', $now->toDateString())
            ->where('hora_fin', '<=', $now->format('H:i'))
            ->get();

        foreach ($expiredReservations as $reserva) {
            $reserva->update(['status' => 'expired']);
            
            // Notify admin and user
            $reserva->user->notify(new ReservationExpiredNotification($reserva));
            User::role('admin')->each(function($admin) use ($reserva) {
                $admin->notify(new ReservationExpiredNotification($reserva));
            });
        }
    }

    public function completeReservation(Reserva $reserva): Reserva
    {
        if ($reserva->status !== 'expired') {
            throw new \Exception('Solo se pueden completar reservas expiradas.');
        }

        $reserva->update(['status' => 'completed']);
        $reserva->mesa->update(['status' => 'available']);

        return $reserva->fresh();
    }

    public function updateMesaStatus(): void
    {
        $now = Carbon::now();
        
        // Update to occupied
        $this->reserva
            ->where('status', 'confirmed')
            ->where('fecha', $now->toDateString())
            ->where('hora', '<=', $now->format('H:i'))
            ->where('hora_fin', '>', $now->format('H:i'))
            ->each(function ($reserva) {
                $reserva->mesa->update(['status' => 'occupied']);
            });
    }

    public function updateStatus(Reserva $reserva, string $newStatus): Reserva
    {
        $oldStatus = $reserva->status;
        $reserva->status = $newStatus;
        $reserva->save();

        // Enviar notificación del cambio de estado
        $reserva->user->notify(new ReservationStatusChanged($reserva, $oldStatus));

        return $reserva;
    }
}
