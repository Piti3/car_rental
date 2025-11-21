<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tworzy tabelę specyfikacji technicznych samochodów
     * Relacja 1:1 z tabelą cars (każdy samochód ma dokładnie jedną specyfikację)
     */
    public function up(): void
    {
        Schema::create('car_specifications', function (Blueprint $table) {
            $table->id();
            
            // Klucz obcy do samochodu - UNIQUE (1:1)
            // Każdy samochód może mieć tylko jedną specyfikację
            $table->foreignId('car_id')
                  ->unique()
                  ->constrained('cars')
                  ->onDelete('cascade');
            
            // Parametry techniczne
            $table->decimal('engine_capacity', 3, 1);  // Pojemność silnika (np. 2.0)
            $table->integer('horsepower');             // Moc (KM)
            $table->decimal('acceleration_0_100', 4, 2)->nullable(); // 0-100 km/h
            $table->string('fuel_type', 30);           // Typ paliwa
            $table->string('transmission', 30);        // Skrzynia biegów
            $table->integer('seats');                  // Liczba miejsc
            $table->integer('doors');                  // Liczba drzwi
            
            // Ciekawostka o samochodzie
            $table->text('fun_fact')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_specifications');
    }
};
