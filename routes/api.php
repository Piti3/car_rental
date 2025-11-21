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

// ===== PUBLICZNE ENDPOINTY =====

// Autoryzacja
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Samochody (Przeglądanie)
Route::prefix('cars')->group(function () {
    // Specyficzne trasy przed parametrem {id}
    Route::get('/available', [CarApiController::class, 'available']);
    
    Route::get('/', [CarApiController::class, 'index']);
    Route::get('/{id}', [CarApiController::class, 'show']);
    Route::get('/{id}/availability', [CarApiController::class, 'availability']);
});

// ===== CHRONIONE ENDPOINTY (JWT) =====

Route::middleware(['jwt'])->group(function () { // Upewnij się, że masz alias 'jwt' lub użyj 'auth:api'
    
    // Autoryzacja (Zalogowany)
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Profil klienta
    Route::prefix('profile')->group(function () {
        Route::get('/', [ClientProfileApiController::class, 'show']);
        Route::put('/', [ClientProfileApiController::class, 'update']);         // REST: PUT /profile
        Route::put('/password', [ClientProfileApiController::class, 'updatePassword']);
        Route::delete('/', [ClientProfileApiController::class, 'destroy']);
    });

    // Rezerwacje (Klient)
    Route::prefix('reservations')->group(function () {
        Route::get('/check-availability', [ReservationApiController::class, 'checkAvailability']);
        
        Route::get('/', [ReservationApiController::class, 'index']);
        Route::post('/', [ReservationApiController::class, 'store']);           // REST: POST /reservations
        Route::get('/{id}', [ReservationApiController::class, 'show']);
        Route::put('/{id}/cancel', [ReservationApiController::class, 'cancel']);
    });

    // ===== API DLA ADMINA =====
    Route::middleware(['admin'])->prefix('api-admin')->group(function () {
        Route::put('/reservations/{id}/approve', [ReservationApiController::class, 'approve']);
    });
});