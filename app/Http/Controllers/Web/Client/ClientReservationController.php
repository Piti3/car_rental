<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use App\Services\ReservationService;
use App\Services\CarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService,
        private CarService $carService
    ) {}

    /*Lista rezerwacji klienta*/
    public function index()
    {
        $reservations = $this->reservationService->getUserReservations(Auth::id());
        
        return view('client.reservations.index', compact('reservations'));
    }

    /*Formularz nowej rezerwacji*/
    public function create(Request $request)
    {
        $carId = $request->query('car_id');
        
        if (!$carId) {
            return redirect()->route('cars.index')
                ->with('error', 'Wybierz samochód do zarezerwowania.');
        }
        
        $car = $this->carService->getCarDetails($carId);
        $calendar = $this->carService->getCarAvailabilityCalendar($carId);
        
        return view('client.reservations.create', compact('car', 'calendar'));
    }

    /*Zapisz rezerwację*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $validated['user_id'] = Auth::id();
            $reservation = $this->reservationService->createReservation($validated);
            
            return redirect()
                ->route('client.reservations.index')
                ->with('success', 'Rezerwacja została utworzona i oczekuje na akceptację administratora.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /*Anuluj rezerwację*/
    public function cancel(int $id)
    {
        try {
            $reservation = $this->reservationService->getUserReservations(Auth::id())
                ->firstWhere('id', $id);
            
            if (!$reservation) {
                return back()->with('error', 'Rezerwacja nie została znaleziona.');
            }
            
            $this->reservationService->cancelReservation($id);
            
            return back()->with('success', 'Rezerwacja została anulowana.');
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
