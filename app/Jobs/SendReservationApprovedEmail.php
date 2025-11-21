<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendReservationApprovedEmail
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function handle(): void
    {
        try {
            $this->reservation->load(['user', 'car.category']);

            $data = [
                'user_name' => $this->reservation->user->name,
                'car_name' => $this->reservation->car->brand . ' ' . $this->reservation->car->model,
                'start_date' => $this->reservation->start_date->format('d.m.Y'),
                'end_date' => $this->reservation->end_date->format('d.m.Y'),
                'total_price' => number_format($this->reservation->total_price, 2),
                'reservation_id' => $this->reservation->id,
            ];

            Mail::send('emails.reservation-approved', $data, function ($message) {
                $message->to($this->reservation->user->email, $this->reservation->user->name)
                        ->subject('Rezerwacja zatwierdzona - Car Rental');
            });

            Log::info('Email sent successfully', [
                'reservation_id' => $this->reservation->id,
                'user_email' => $this->reservation->user->email,
            ]);

        } catch (\Exception $e) {
            Log::error('Email sending failed', [
                'reservation_id' => $this->reservation->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}
