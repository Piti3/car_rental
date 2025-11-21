<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CarService
{
    /**
     * Pobiera wszystkie samochody z filtrowaniem
     * @param array $filters - Filtry (category_id, status, itp.)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCars(array $filters = [])
    {
        $query = Car::with(['category', 'specification']);

        // Filtruj po kategorii
        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filtruj po statusie
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filtruj po cenie (min-max)
        if (isset($filters['price_min'])) {
            $query->where('daily_price', '>=', $filters['price_min']);
        }

        if (isset($filters['price_max'])) {
            $query->where('daily_price', '<=', $filters['price_max']);
        }

        return $query->get();
    }

    /**
     * Pobiera dostępne samochody w danym terminie
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableCars(Carbon $startDate, Carbon $endDate)
    {
        // Pobierz wszystkie samochody
        $allCars = Car::with(['category', 'specification'])->get();

        // Filtruj tylko te dostępne w danym terminie
        return $allCars->filter(function ($car) use ($startDate, $endDate) {
            return $this->isCarAvailableInPeriod($car->id, $startDate, $endDate);
        });
    }

    /**
     * Sprawdza czy samochód jest dostępny w całym okresie
     * @param int $carId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return bool
     */
    public function isCarAvailableInPeriod(int $carId, Carbon $startDate, Carbon $endDate): bool
    {
        // Sprawdź czy są zatwierdzone lub oczekujące rezerwacje w tym okresie
        $conflictingReservations = Reservation::where('car_id', $carId)
            ->whereIn('status', ['pending', 'approved']) // Tylko aktywne rezerwacje
            ->where(function ($query) use ($startDate, $endDate) {
                // Sprawdź nakładanie się dat
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Rezerwacja zaczyna się w naszym okresie
                    $q->whereBetween('start_date', [$startDate, $endDate])
                        // Rezerwacja kończy się w naszym okresie
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        // Rezerwacja obejmuje cały nasz okres
                        ->orWhere(function ($q2) use ($startDate, $endDate) {
                            $q2->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                });
            })
            ->exists();

        return !$conflictingReservations;
    }

    /**
     * Pobiera kalendarz dostępności dla samochodu
     * Zwraca listę dat z informacją czy są dostępne
     * @param int $carId
     * @param Carbon $startDate - Początek okresu (domyślnie: dziś)
     * @param Carbon $endDate - Koniec okresu (domyślnie: +3 miesiące)
     * @return array
     */
    public function getCarAvailabilityCalendar(int $carId, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::today();
        $endDate = $endDate ?? Carbon::today()->addMonths(3);

        // Pobierz wszystkie rezerwacje w tym okresie
        $reservations = Reservation::where('car_id', $carId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->get();

        // Utwórz mapę zajętych dni
        $occupiedDays = [];
        foreach ($reservations as $reservation) {
            $period = CarbonPeriod::create($reservation->start_date, $reservation->end_date);
            foreach ($period as $date) {
                $occupiedDays[$date->format('Y-m-d')] = true;
            }
        }

        // Wygeneruj kalendarz
        $calendar = [];
        $period = CarbonPeriod::create($startDate, $endDate);
        
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $calendar[] = [
                'date' => $dateStr,
                'formatted' => $date->format('d.m'),  
                'available' => !isset($occupiedDays[$dateStr]),
                'is_past' => $date->isPast(),
                'is_today' => $date->isToday(),
            ];
        }


        return $calendar;
    }

    /**
     * Pobiera szczegóły samochodu
     * @param int $carId
     * @return Car
     */
    public function getCarDetails(int $carId): Car
    {
        return Car::with(['category', 'specification', 'reservations' => function ($query) {
            $query->whereIn('status', ['approved', 'pending'])
                ->orderBy('start_date');
        }])->findOrFail($carId);
    }
}
