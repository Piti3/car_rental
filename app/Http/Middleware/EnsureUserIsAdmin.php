<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Dla web (sesja)
        if (auth('web')->check()) {
            if (auth('web')->user()->isAdmin()) {
                return $next($request);
            }
            return redirect('/')->with('error', 'Brak uprawnień');
        }
        
        // Dla API (JWT)
        if (auth('api')->check()) {
            if (auth('api')->user()->isAdmin()) {
                return $next($request);
            }
            return response()->json([
                'success' => false,
                'message' => 'Brak uprawnień administratora.'
            ], 403);
        }
        
        return redirect('/login');
    }
}
