<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationService
{

    public function __construct(
        private RabbitMQService $rabbitMQService
    ) {}


    /* Tworzy nową rezerwację*/
    public function createReservation(array $data): Reservation
    {
        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        $endDate = Carbon::parse($data['end_date'])->startOfDay();

        // 1. Walidacja dat
        if ($startDate->isBefore(Carbon::today())) {
            throw new \Exception('Data rozpoczęcia nie może być w przeszłości.');
        }

        if ($endDate->isBefore($startDate)) {
            throw new \Exception('Data zakończenia nie może być wcześniejsza niż data rozpoczęcia.');
        }

        // Poprawione obliczanie dni
        $days = $startDate->diffInDays($endDate);
        
        if ($days < 1) {
            throw new \Exception('Rezerwacja musi trwać co najmniej 1 dzień.');
        }

        if ($days > 90) {
            throw new \Exception('Maksymalny okres rezerwacji to 90 dni.');
        }

        // 2. Sprawdź czy samochód istnieje
        $car = Car::findOrFail($data['car_id']);

        if ($car->status === 'maintenance') {
            throw new \Exception('Samochód jest w serwisie i nie może być zarezerwowany.');
        }

        // 3. Sprawdź dostępność (TYLKO zatwierdzone rezerwacje blokują)
        if (!$this->isCarAvailableInPeriod($data['car_id'], $startDate, $endDate)) {
            throw new \Exception('Samochód jest niedostępny w wybranym terminie. Wybierz inny termin.');
        }

        // 4. Oblicz cenę
        $totalPrice = round($days * $car->daily_price, 2);

        // 5. Utwórz rezerwację
        return DB::transaction(function () use ($data, $totalPrice, $startDate, $endDate, $days) {
            return Reservation::create([
                'user_id' => $data['user_id'],
                'car_id' => $data['car_id'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    /*Sprawdź dostępność samochodu w okresie (TYLKO zatwierdzone blokują)*/
    public function isCarAvailableInPeriod(int $carId, Carbon $startDate, Carbon $endDate, ?int $excludeReservationId = null): bool
    {
        $query = Reservation::where('car_id', $carId)
            ->where('status', 'approved')  // TYLKO zatwierdzone blokują
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($query) use ($startDate, $endDate) {
                      $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                  });
            });

        // Wyklucz aktualną rezerwację (przy approve)
        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return !$query->exists();
    }

    /*Akceptuje rezerwację (tylko admin)*/
    public function approveReservation(int $reservationId): Reservation
    {
        $reservation = Reservation::with(['car', 'user'])->findOrFail($reservationId); // ← DODAJ 'user'

        if ($reservation->status !== 'pending') {
            throw new \Exception('Tylko rezerwacje oczekujące mogą być zaakceptowane.');
        }

        // Sprawdź dostępność 
        if (!$this->isCarAvailableInPeriod(
            $reservation->car_id,
            Carbon::parse($reservation->start_date),
            Carbon::parse($reservation->end_date),
            $reservation->id
        )) {
            throw new \Exception('Nie można zaakceptować - samochód jest już zarezerwowany w tym terminie.');
        }

        return DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'approved']);

            // Jeśli rezerwacja zaczyna się dziś lub wcześniej, zmień status samochodu
            $startDate = Carbon::parse($reservation->start_date);
            if ($startDate->isToday() || $startDate->isPast()) {
                $reservation->car->update(['status' => 'rented']);
            }

            //publikuj powiadomienie do rabbita
            try {
                $this->rabbitMQService->publish('reservation_approved', [
                    'reservation_id' => $reservation->id,
                    'user_id' => $reservation->user_id,
                    'user_email' => $reservation->user->email,
                    'user_name' => $reservation->user->name,
                    'car_name' => $reservation->car->brand . ' ' . $reservation->car->model,
                    'start_date' => $reservation->start_date->format('Y-m-d'),
                    'end_date' => $reservation->end_date->format('Y-m-d'),
                    'total_price' => $reservation->total_price,
                    'timestamp' => now()->toIso8601String(),
                ]);

                \Log::info(' Message published to RabbitMQ', [
                    'reservation_id' => $reservation->id,
                    'queue' => 'reservation_approved',
                ]);

            } catch (\Exception $e) {
                \Log::error(' Failed to publish to RabbitMQ', [
                    'reservation_id' => $reservation->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return $reservation->fresh();
        });
    }

    /**
     * Anuluje rezerwację
     */
    public function cancelReservation(int $reservationId): Reservation
    {
        $reservation = Reservation::with('car')->findOrFail($reservationId);

        if (in_array($reservation->status, ['cancelled', 'completed'])) {
            throw new \Exception('Nie można anulować tej rezerwacji.');
        }

        return DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'cancelled']);

            // Jeśli samochód był "rented" przez tę rezerwację, sprawdź czy można przywrócić "available"
            if ($reservation->car->status === 'rented') {
                $hasOtherActiveReservations = Reservation::where('car_id', $reservation->car_id)
                    ->where('id', '!=', $reservation->id)
                    ->where('status', 'approved')
                    ->where('start_date', '<=', Carbon::today())
                    ->where('end_date', '>=', Carbon::today())
                    ->exists();

                if (!$hasOtherActiveReservations) {
                    $reservation->car->update(['status' => 'available']);
                }
            }

            return $reservation->fresh();
        });
    }

    /**
     * Zakończ rezerwację (automatycznie lub ręcznie)
     */
    public function completeReservation(int $reservationId): Reservation
    {
        $reservation = Reservation::with('car')->findOrFail($reservationId);

        if ($reservation->status !== 'approved') {
            throw new \Exception('Tylko zatwierdzone rezerwacje mogą być zakończone.');
        }

        return DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'completed']);

            // Sprawdź czy można zwolnić samochód
            $hasOtherActiveReservations = Reservation::where('car_id', $reservation->car_id)
                ->where('id', '!=', $reservation->id)
                ->where('status', 'approved')
                ->where('start_date', '<=', Carbon::today())
                ->where('end_date', '>=', Carbon::today())
                ->exists();

            if (!$hasOtherActiveReservations && $reservation->car->status === 'rented') {
                $reservation->car->update(['status' => 'available']);
            }

            return $reservation->fresh();
        });
    }

    /**
     * Automatycznie zakończ przeterminowane rezerwacje (CRON)
     */
    public function completeExpiredReservations(): int
    {
        $expiredReservations = Reservation::where('status', 'approved')
            ->where('end_date', '<', Carbon::today())
            ->get();

        $count = 0;
        foreach ($expiredReservations as $reservation) {
            try {
                $this->completeReservation($reservation->id);
                $count++;
            } catch (\Exception $e) {
                \Log::error('Failed to complete reservation', [
                    'reservation_id' => $reservation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }

    /**
     * Pobiera rezerwacje użytkownika
     */
    public function getUserReservations(int $userId)
    {
        return Reservation::where('user_id', $userId)
            ->with(['car.category', 'car.specification'])
            ->orderBy('start_date', 'desc')
            ->get();
    }

    /**
     * Pobiera wszystkie rezerwacje (tylko admin)
     */
    public function getAllReservations(?string $status = null)
    {
        $query = Reservation::with(['user', 'car.category']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Pobiera statystyki rezerwacji
     */
    public function getReservationStats(): array
    {
        return [
            'pending' => Reservation::where('status', 'pending')->count(),
            'approved' => Reservation::where('status', 'approved')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'total' => Reservation::count(),
        ];
    }
}
