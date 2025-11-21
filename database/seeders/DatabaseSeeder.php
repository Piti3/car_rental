<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Wywołuje wszystkie seedery w odpowiedniej kolejności
     */
    public function run(): void
    {
        // Kolejność jest WAŻNA! 
        // Najpierw tabele bez zależności, potem te z kluczami obcymi
        
        $this->call([
            UserSeeder::class,              // 10 użytkowników (1 admin + 9 klientów)
            CarCategorySeeder::class,       // 5 kategorii
            CarSeeder::class,               // 30 samochodów
            CarSpecificationSeeder::class,  // 30 specyfikacji
            ReservationSeeder::class,       // 11 rezerwacji
        ]);
    }
}
