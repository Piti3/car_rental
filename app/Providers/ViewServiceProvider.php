<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Reservation;

class ViewServiceProvider extends ServiceProvider
{
    /*Register services.*/
    public function register(): void
    {
        //
    }

    /*Bootstrap services.*/
    public function boot(): void
    {
        // Udostępnij liczbę pending rezerwacji dla layoutu admina
        View::composer('layouts.admin', function ($view) {
            $pendingCount = Reservation::where('status', 'pending')->count();
            $view->with('pendingCount', $pendingCount);
        });
    }
}
