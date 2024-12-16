<?php

namespace App\Services;

use App\Models\Reserva;

class ReservationService extends BaseService
{
    protected function setModel()
    {
        $this->model = new Reserva();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Reserva $reserva, array $data)
    {
        $reserva->update($data);
        return $reserva;
    }

    public function delete(Reserva $reserva)
    {
        return $reserva->delete();
    }

    public function getUserReservations(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }
}
