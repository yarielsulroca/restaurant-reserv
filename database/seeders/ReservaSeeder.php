<?php

namespace Database\Seeders;

use App\Models\Mesa;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        // Get users with 'user' role
        $users = User::role('user')->where('is_active', true)->get();
        $mesas = Mesa::all();
        
        // Current date for reference
        $today = Carbon::now();
        
        // Create some reservations for the next 7 days
        for ($day = 0; $day < 7; $day++) {
            $date = $today->copy()->addDays($day);
            
            // Create reservations for different time slots
            $timeSlots = ['12:00', '13:00', '14:00', '19:00', '20:00', '21:00'];
            
            foreach ($timeSlots as $time) {
                // Create 2-3 reservations per time slot
                $numReservations = rand(2, 3);
                
                for ($i = 0; $i < $numReservations; $i++) {
                    $user = $users->random();
                    $mesa = $mesas->random();
                    
                    // Calculate random number of people based on table capacity
                    $numPersonas = rand(1, $mesa->capacidad);
                    
                    $notas = [
                        'Celebración de cumpleaños',
                        'Preferencia ventana',
                        'Alergias alimentarias',
                        null
                    ];

                    $estados = ['pending', 'confirmed', 'cancelled'];
                    
                    Reserva::create([
                        'fecha' => $date->format('Y-m-d'),
                        'hora' => $time,
                        'numero_personas' => $numPersonas,
                        'mesa_id' => $mesa->id,
                        'user_id' => $user->id,
                        'status' => Arr::random($estados),
                        'notas' => Arr::random($notas),
                        'expired' => false,
                    ]);
                }
            }
        }
    }
}
