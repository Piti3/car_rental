<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReservationService;

class CompleteExpiredReservations extends Command
{
    protected $signature = 'reservations:complete-expired';
    protected $description = 'Zakończ przeterminowane rezerwacje';

    public function handle(ReservationService $reservationService)
    {
        $this->info('Sprawdzanie przeterminowanych rezerwacji...');
        
        $count = $reservationService->completeExpiredReservations();
        
        $this->info("Zakończono {$count} rezerwacji.");
        
        return Command::SUCCESS;
    }
}
