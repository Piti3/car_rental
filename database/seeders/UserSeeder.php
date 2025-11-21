<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Tworzy testowych użytkowników
     * 1 admin + 9 klientów = 10 użytkowników
     */
    public function run(): void
    {
        // Administrator
        User::create([
            'name' => 'Admin Systemu',
            'email' => 'admin@carrental.com',
            'password' => Hash::make('admin123'), // Hash::make() hashuje hasło
            'role' => 'admin',
            'phone' => '+48 123 456 789',
        ]);

        // Testowi klienci
        $clients = [
            ['name' => 'Jan Kowalski', 'email' => 'jan.kowalski@example.com', 'phone' => '+48 500 100 200'],
            ['name' => 'Anna Nowak', 'email' => 'anna.nowak@example.com', 'phone' => '+48 500 200 300'],
            ['name' => 'Piotr Wiśniewski', 'email' => 'piotr.wisniewski@example.com', 'phone' => '+48 500 300 400'],
            ['name' => 'Maria Wójcik', 'email' => 'maria.wojcik@example.com', 'phone' => '+48 500 400 500'],
            ['name' => 'Tomasz Kamiński', 'email' => 'tomasz.kaminski@example.com', 'phone' => '+48 500 500 600'],
            ['name' => 'Agnieszka Lewandowska', 'email' => 'agnieszka.lewandowska@example.com', 'phone' => '+48 500 600 700'],
            ['name' => 'Krzysztof Zieliński', 'email' => 'krzysztof.zielinski@example.com', 'phone' => '+48 500 700 800'],
            ['name' => 'Magdalena Szymańska', 'email' => 'magdalena.szymanska@example.com', 'phone' => '+48 500 800 900'],
            ['name' => 'Paweł Woźniak', 'email' => 'pawel.wozniak@example.com', 'phone' => '+48 500 900 100'],
        ];

        foreach ($clients as $client) {
            User::create([
                'name' => $client['name'],
                'email' => $client['email'],
                'password' => Hash::make('password123'), // Wszystkie mają to samo hasło testowe
                'role' => 'client',
                'phone' => $client['phone'],
            ]);
        }
    }
}
