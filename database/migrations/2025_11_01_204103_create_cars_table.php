<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tworzy główną tabelę samochodów
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            
            // Klucz obcy do kategorii
            $table->foreignId('category_id')
                  ->constrained('car_categories')
                  ->onDelete('cascade');
            
            // Podstawowe informacje
            $table->string('brand', 100);          // Marka (np. "Toyota")
            $table->string('model', 100);          // Model (np. "Corolla")
            $table->integer('year');               // Rok produkcji
            
            // Numer rejestracyjny - UNIQUE (każdy samochód ma unikalny)
            $table->string('registration', 20)->unique();
            
            // Status dostępności
            $table->enum('status', ['available', 'rented', 'maintenance'])
                  ->default('available');
            
            // Cena za dzień
            $table->decimal('daily_price', 10, 2);
            
            // Ścieżka do zdjęcia
            $table->string('image_path')->nullable();
            
            $table->timestamps();
            
            // Indeks dla szybszego wyszukiwania po marce i modelu
            $table->index(['brand', 'model']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
