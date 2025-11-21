<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Admin próbuje wejść na stronę klienta
            if ($user->isAdmin() && $request->is('client/*')) {
                return redirect()->route('admin.dashboard');
            }
            
            // Klient próbuje wejść na panel admina
            if ($user->isClient() && $request->is('admin/*')) {
                return redirect()->route('home');
            }
        }
        
        return $next($request);
    }
}
