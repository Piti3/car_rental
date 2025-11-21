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

// Strona główna (publiczna)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autoryzacja
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Samochody (publiczne)
Route::prefix('cars')->name('cars.')->group(function () {
    Route::get('/', [CarViewController::class, 'index'])->name('index');
    Route::get('/{id}', [CarViewController::class, 'show'])->name('show');
});

// ======= PANEL ADMINA =======
Route::middleware(['auth:web', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/reservations/pending', [AdminReservationController::class, 'pending'])->name('reservations.pending');
    Route::get('/reservations/all', [AdminReservationController::class, 'all'])->name('reservations.all');
    Route::post('/reservations/{id}/approve', [AdminReservationController::class, 'approve'])->name('reservations.approve');
    Route::post('/reservations/{id}/cancel', [AdminReservationController::class, 'cancel'])->name('reservations.cancel');
    
    Route::get('/cars', [AdminCarController::class, 'index'])->name('cars.index');
    Route::get('/cars/create', [AdminCarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [AdminCarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{id}/edit', [AdminCarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{id}', [AdminCarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{id}', [AdminCarController::class, 'destroy'])->name('cars.destroy');
    
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

// ======= PANEL KLIENTA (WEB) =======
Route::middleware(['auth'])->prefix('dashboard')->name('client.')->group(function () {  // ← ZMIENIONE z / na /dashboard
    
    // Rezerwacje klienta
    Route::get('/reservations', [ClientReservationController::class, 'index'])->name('reservations.index');  // ← /dashboard/reservations
    Route::get('/reservations/create', [ClientReservationController::class, 'create'])->name('reservations.create');  // ← /dashboard/reservations/create
    Route::post('/reservations-create', [ClientReservationController::class, 'store'])->name('reservations.store');  // ← /dashboard/reservations-create
    Route::post('/reservations/{id}/cancel', [ClientReservationController::class, 'cancel'])->name('reservations.cancel');  // ← /dashboard/reservations/{id}/cancel
    
    // Profil klienta
    Route::get('/profile', [ClientProfileController::class, 'show'])->name('profile');  // ← /dashboard/profile
    Route::put('/profile-update', [ClientProfileController::class, 'update'])->name('profile.update');  // ← /dashboard/profile-update
    Route::put('/profile-password', [ClientProfileController::class, 'updatePassword'])->name('profile.password');  // ← /dashboard/profile-password
    Route::delete('/profile-delete', [ClientProfileController::class, 'destroy'])->name('profile.destroy');  // ← /dashboard/profile-delete
});
