<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReservationService;
use App\Models\Car;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    /*Dashboard admina z statystykami*/
    public function dashboard()
    {
        $pendingReservations = $this->reservationService->getAllReservations('pending');
        $approvedReservations = $this->reservationService->getAllReservations('approved');
        $allReservations = $this->reservationService->getAllReservations();
        
        $stats = [
            'total_cars' => Car::count(),
            'available_cars' => Car::where('status', 'available')->count(),
            'rented_cars' => Car::where('status', 'rented')->count(),
            'total_users' => User::where('role', 'client')->count(),
            'pending_reservations' => $pendingReservations->count(),
            'approved_reservations' => $approvedReservations->count(),
            'total_revenue' => $approvedReservations->sum('total_price'),
        ];
        
        return view('admin.dashboard', compact('stats', 'pendingReservations'));
    }
}
