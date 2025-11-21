<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Car;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Tworzy testowe rezerwacje
     */
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $cars = Car::all();

        // Tworzenie różnorodnych rezerwacji
        $reservations = [
            // Rezerwacje oczekujące (pending)
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(8),
                'status' => 'pending',
                'total_price' => 750.00,
                'notes' => 'Odbiór z lotniska Okęcie'
            ],
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(15),
                'status' => 'pending',
                'total_price' => 1250.00,
                'notes' => null
            ],
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(3),
                'end_date' => Carbon::now()->addDays(5),
                'status' => 'pending',
                'total_price' => 480.00,
                'notes' => 'Proszę o fotelik dziecięcy'
            ],

            // Rezerwacje zatwierdzone (approved)
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(7),
                'status' => 'approved',
                'total_price' => 1400.00,
                'notes' => null
            ],
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(3),
                'status' => 'approved',
                'total_price' => 520.00,
                'notes' => 'Płatność przy odbiorze'
            ],
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(14),
                'status' => 'approved',
                'total_price' => 1960.00,
                'notes' => 'Wycieczka rodzinna'
            ],

            // Rezerwacje anulowane (cancelled)
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->subDays(2),
                'status' => 'cancelled',
                'total_price' => 720.00,
                'notes' => 'Anulowano na prośbę klienta'
            ],
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(25),
                'status' => 'cancelled',
                'total_price' => 1200.00,
                'notes' => 'Zmiana planów'
            ],

            // Rezerwacje zakończone (completed)
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->subDays(14),
                'end_date' => Carbon::now()->subDays(10),
                'status' => 'completed',
                'total_price' => 960.00,
                'notes' => null
            ],
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->subDays(25),
                'status' => 'completed',
                'total_price' => 1250.00,
                'notes' => 'Wszystko przebiegło pomyślnie'
            ],
            [
                'user_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->subDays(20),
                'end_date' => Carbon::now()->subDays(17),
                'status' => 'completed',
                'total_price' => 720.00,
                'notes' => null
            ],
        ];

        foreach ($reservations as $reservation) {
            Reservation::create($reservation);
        }
    }
}
