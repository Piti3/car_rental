<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*Pokaż formularz logowania*/
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        
        return view('auth.login');
    }

    /*Zaloguj użytkownika*/
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Nieprawidłowy email lub hasło.',
        ])->onlyInput('email');
    }

    /*Pokaż formularz rejestracji*/
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        
        return view('auth.register');
    }

    /*Zarejestruj nowego użytkownika*/
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'phone' => $validated['phone'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Konto zostało utworzone pomyślnie!');
    }

    /* Wyloguj użytkownika*/
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Wylogowano pomyślnie.');
    }

    /* Przekieruj użytkownika w zależności od roli*/
    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('home');
    }
}
