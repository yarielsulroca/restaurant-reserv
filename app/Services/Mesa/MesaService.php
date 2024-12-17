<?php

namespace App\Services\Mesa;

use App\Models\Mesa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MesaService
{
    public function __construct(
        private readonly Mesa $mesa
    ) {}

    public function list(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->mesa
            ->when(isset($filters['zona']), fn($query) => $query->where('zona', $filters['zona']))
            ->when(isset($filters['status']), fn($query) => $query->where('status', $filters['status']))
            ->when(isset($filters['capacidad']), fn($query) => $query->where('capacidad', '>=', $filters['capacidad']))
            ->paginate($perPage);
    }

    public function create(array $data): Mesa
    {
        return $this->mesa->create($data);
    }

    public function update(Mesa $mesa, array $data): Mesa
    {
        $mesa->update($data);
        return $mesa->fresh();
    }

    public function delete(Mesa $mesa): bool
    {
        return $mesa->delete();
    }

    public function getAvailableTables(string $date, string $time, int $guests): Collection
    {
        return $this->mesa
            ->where('status', 'available')
            ->where('capacidad', '>=', $guests)
            ->whereDoesntHave('reservations', function ($query) use ($date, $time) {
                $query->where('fecha', $date)
                    ->where('hora', $time)
                    ->whereIn('status', ['pending', 'confirmed']);
            })
            ->get();
    }
}
