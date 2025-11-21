<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tworzy tabelę rezerwacji
     * Łączy użytkownika z samochodem
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            
            // Klucz obcy do użytkownika
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Klucz obcy do samochodu
            $table->foreignId('car_id')
                  ->constrained('cars')
                  ->onDelete('cascade');
            
            // Daty rezerwacji
            $table->date('start_date');
            $table->date('end_date');
            
            // Status rezerwacji
            // pending - oczekuje na akceptację admina
            // approved - zaakceptowana przez admina
            // cancelled - anulowana
            // completed - zakończona
            $table->enum('status', ['pending', 'approved', 'cancelled', 'completed'])
                  ->default('pending');
            
            // Całkowity koszt
            $table->decimal('total_price', 10, 2);
            
            // Notatki (opcjonalne)
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indeksy dla szybszego wyszukiwania
            $table->index(['user_id', 'status']);
            $table->index(['car_id', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
