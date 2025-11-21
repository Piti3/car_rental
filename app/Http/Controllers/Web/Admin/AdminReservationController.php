<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReservationService;

class AdminReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    /*Lista rezerwacji oczekujących*/
    public function pending()
    {
        $reservations = $this->reservationService->getAllReservations('pending');
        
        return view('admin.reservations.pending', compact('reservations'));
    }

    /*Lista wszystkich rezerwacji*/
    public function all()
    {
        $reservations = $this->reservationService->getAllReservations();
        
        return view('admin.reservations.all', compact('reservations'));
    }

    /*Zatwierdź rezerwację*/
    public function approve(int $id)
    {
        try {
            $this->reservationService->approveReservation($id);
            
            return back()->with('success', 'Rezerwacja została zatwierdzona pomyślnie.');
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /*Anuluj rezerwację*/
    public function cancel(int $id)
    {
        try {
            $this->reservationService->cancelReservation($id);
            
            return back()->with('success', 'Rezerwacja została anulowana.');
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
