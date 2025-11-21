<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CarViewController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Admin\AdminReservationController;
use App\Http\Controllers\Web\Admin\AdminCarController;
use App\Http\Controllers\Web\Admin\AdminUserController;
use App\Http\Controllers\Web\Client\ClientReservationController;
use App\Http\Controllers\Web\Client\ClientProfileController;

// Strona główna
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autoryzacja (Goście)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Wylogowanie (dostępne tylko dla zalogowanych)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Samochody (Publiczne)
Route::prefix('cars')->name('cars.')->group(function () {
    Route::get('/', [CarViewController::class, 'index'])->name('index');
    Route::get('/{id}', [CarViewController::class, 'show'])->name('show');
});

// ======= PANEL ADMINA =======
// Prefix: /admin | Name: admin.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Rezerwacje
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/pending', [AdminReservationController::class, 'pending'])->name('pending');
        Route::get('/all', [AdminReservationController::class, 'all'])->name('all');
        Route::post('/{id}/approve', [AdminReservationController::class, 'approve'])->name('approve');
        Route::post('/{id}/cancel', [AdminReservationController::class, 'cancel'])->name('cancel');
    });

    // Samochody (CRUD)
    Route::prefix('cars')->name('cars.')->group(function () {
        Route::get('/', [AdminCarController::class, 'index'])->name('index');
        Route::get('/create', [AdminCarController::class, 'create'])->name('create');
        Route::post('/', [AdminCarController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminCarController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminCarController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminCarController::class, 'destroy'])->name('destroy');
    });

    // Użytkownicy
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminUserController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
    });
});

// ======= PANEL KLIENTA =======
// Prefix: /dashboard | Name: client.
Route::middleware(['auth'])->prefix('dashboard')->name('client.')->group(function () {

    // Rezerwacje klienta
    // URL: /dashboard/reservations...
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ClientReservationController::class, 'index'])->name('index');      // Lista
        Route::get('/create', [ClientReservationController::class, 'create'])->name('create'); // Formularz
        Route::post('/', [ClientReservationController::class, 'store'])->name('store');     // Akcja zapisu (zamiast /reservations-create)
        Route::post('/{id}/cancel', [ClientReservationController::class, 'cancel'])->name('cancel');
    });

    // Profil klienta
    // URL: /dashboard/profile...
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ClientProfileController::class, 'show'])->name('index');           // Widok
        Route::put('/', [ClientProfileController::class, 'update'])->name('update');        // Aktualizacja danych (zamiast /profile-update)
        Route::put('/password', [ClientProfileController::class, 'updatePassword'])->name('password'); // Zmiana hasła
        Route::delete('/', [ClientProfileController::class, 'destroy'])->name('destroy');   // Usunięcie konta
    });
});