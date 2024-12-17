<?php

namespace App\Console\Commands;

use App\Services\Reserva\ReservaService;
use Illuminate\Console\Command;

class CheckReservationStatus extends Command
{
    protected $signature = 'reservations:check-status';
    protected $description = 'Check and update reservation statuses';

    public function __construct(
        private readonly ReservaService $reservaService
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->reservaService->checkAndUpdateExpiredReservations();
        $this->reservaService->updateMesaStatus();
        
        $this->info('Reservation statuses have been updated successfully.');
    }
}
