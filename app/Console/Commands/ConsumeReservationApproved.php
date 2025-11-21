<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Models\Reservation;
use App\Jobs\SendReservationApprovedEmail;

class ConsumeReservationApproved extends Command
{
    protected $signature = 'rabbitmq:consume-reservation-approved';
    protected $description = 'Consume messages from RabbitMQ queue reservation_approved';

    public function handle()
    {
        $this->info('Starting RabbitMQ consumer for reservation_approved queue...');

        try {
            $connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.host'),
                config('queue.connections.rabbitmq.port'),
                config('queue.connections.rabbitmq.user'),
                config('queue.connections.rabbitmq.password')
            );

            $channel = $connection->channel();

            // Deklaruj queue
            $channel->queue_declare(
                'reservation_approved',
                false,
                true,
                false,
                false
            );

            $this->info('Waiting for messages. To exit press CTRL+C');

            $callback = function ($msg) {
                $data = json_decode($msg->body, true);
                
                $this->info('Received message for reservation ID: ' . $data['reservation_id']);

                try {
                    // Pobierz rezerwację
                    $reservation = Reservation::with(['user', 'car'])->find($data['reservation_id']);

                    if ($reservation) {
                        // Dispatch Job do wysłania emaila
                        SendReservationApprovedEmail::dispatch($reservation);
                        
                        $this->info('Email job dispatched for: ' . $data['user_email']);
                        $msg->ack(); // Potwierdź przetworzenie
                    } else {
                        $this->error('Reservation not found: ' . $data['reservation_id']);
                        $msg->ack(); // Potwierdź aby nie retry
                    }

                } catch (\Exception $e) {
                    $this->error('Error processing message: ' . $e->getMessage());
                    $msg->nack(false, true); // Nie potwierdzaj, requeue
                }
            };

            $channel->basic_consume(
                'reservation_approved',
                '',
                false,
                false,
                false,
                false,
                $callback
            );

            while ($channel->is_consuming()) {
                $channel->wait();
            }

            $channel->close();
            $connection->close();

        } catch (\Exception $e) {
            $this->error('RabbitMQ consumer error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
