<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RabbitMQService
{
    private $connection;
    private $channel;

    public function __construct()
    {
        try {
            $this->connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.host'),
                config('queue.connections.rabbitmq.port'),
                config('queue.connections.rabbitmq.user'),
                config('queue.connections.rabbitmq.password')
            );
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            Log::error('RabbitMQ connection failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /*Publikuj wiadomość do queue*/
    public function publish(string $queue, array $data): void
    {
        try {
            // Deklaruj queue
            $this->channel->queue_declare(
                $queue,      // nazwa queue
                false,       // passive
                true,        // durable (przetrwa restart)
                false,       // exclusive
                false        // auto_delete
            );

            // Utwórz wiadomość
            $message = new AMQPMessage(
                json_encode($data),
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
            );

            // Opublikuj wiadomość
            $this->channel->basic_publish($message, '', $queue);

            Log::info('Message published to RabbitMQ', [
                'queue' => $queue,
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to publish message to RabbitMQ', [
                'queue' => $queue,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /*Zamknij połączenie*/
    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
