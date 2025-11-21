<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarApiController;
use App\Http\Controllers\Api\ReservationApiController;
use App\Http\Controllers\Api\ClientProfileApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ===== PUBLICZNE ENDPOINTY (bez autoryzacji) =====

// Autoryzacja
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Samochody (publiczny dostęp - przeglądanie)
Route::prefix('cars')->group(function () {
    Route::get('/available', [CarApiController::class, 'available']);
    Route::get('/', [CarApiController::class, 'index']);
    Route::get('/{id}', [CarApiController::class, 'show']);
    Route::get('/{id}/availability', [CarApiController::class, 'availability']);
});

// ===== CHRONIONE ENDPOINTY (wymagają JWT tokenu) =====

Route::middleware(['jwt'])->group(function () {
    
    // Autoryzacja (zalogowany)
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Profil klienta
    Route::prefix('profile')->group(function () {
        Route::get('/', [ClientProfileApiController::class, 'show']);
        Route::put('/', [ClientProfileApiController::class, 'update']);
        Route::put('/password', [ClientProfileApiController::class, 'updatePassword']);
        Route::delete('/', [ClientProfileApiController::class, 'destroy']);
    });

    // Rezerwacje (klient)
    Route::prefix('reservations')->group(function () {
        // ✅ Najpierw definicje GET z konkretną ścieżką
        Route::get('/check-availability', [ReservationApiController::class, 'checkAvailability']);
        
        // ✅ Potem GET, POST, PUT z parametrem {id}
        Route::get('/', [ReservationApiController::class, 'index']);
        Route::post('/', [ReservationApiController::class, 'store']);
        Route::get('/{id}', [ReservationApiController::class, 'show']);
        Route::put('/{id}/cancel', [ReservationApiController::class, 'cancel']);
    });

    // ===== ENDPOINTY TYLKO DLA ADMINA =====
    
    Route::middleware(['admin'])->prefix('api-admin')->group(function () {
        Route::put('/reservations/{id}/approve', [ReservationApiController::class, 'approve']);
    });
});
