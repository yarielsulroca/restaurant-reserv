<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    public function run(): void
    {
        // Create tables with different capacities
        $tables = [
            ['numero' => 1, 'capacidad' => 2, 'zona' => 'Interior', 'ubicacion' => 'Salón Principal'],
            ['numero' => 2, 'capacidad' => 2, 'zona' => 'Interior', 'ubicacion' => 'Salón Principal'],
            ['numero' => 3, 'capacidad' => 4, 'zona' => 'Interior', 'ubicacion' => 'Salón Principal'],
            ['numero' => 4, 'capacidad' => 4, 'zona' => 'Interior', 'ubicacion' => 'Salón Principal'],
            ['numero' => 5, 'capacidad' => 4, 'zona' => 'Ventana', 'ubicacion' => 'Zona Vista'],
            ['numero' => 6, 'capacidad' => 6, 'zona' => 'Ventana', 'ubicacion' => 'Zona Vista'],
            ['numero' => 7, 'capacidad' => 6, 'zona' => 'Terraza', 'ubicacion' => 'Exterior'],
            ['numero' => 8, 'capacidad' => 8, 'zona' => 'Terraza', 'ubicacion' => 'Exterior'],
            ['numero' => 9, 'capacidad' => 2, 'zona' => 'Barra', 'ubicacion' => 'Bar'],
            ['numero' => 10, 'capacidad' => 2, 'zona' => 'Barra', 'ubicacion' => 'Bar'],
        ];

        foreach ($tables as $table) {
            Mesa::create([
                'numero' => $table['numero'],
                'capacidad' => $table['capacidad'],
                'zona' => $table['zona'],
                'ubicacion' => $table['ubicacion'],
                'status' => 'available',
                'estado' => 'disponible'
            ]);
        }
    }
}
