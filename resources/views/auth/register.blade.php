@extends('layouts.app')

@section('title', 'Rejestracja')

@section('content')
<div class="max-w-md mx-auto mt-12">
    <div class="glass dark:glass-dark rounded-2xl p-8 shadow-lg">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">
            Zarejestruj się
        </h2>

        <form id="register-form" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Imię i nazwisko *
                </label>
                <input 
                    type="text" 
                    id="name"
                    name="name" 
                    required
                    autofocus
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="Jan Kowalski"
                >
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Email *
                </label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="twoj@email.com"
                >
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Telefon (opcjonalnie)
                </label>
                <input 
                    type="tel" 
                    id="phone"
                    name="phone" 
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="+48 123 456 789"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Hasło *
                </label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="Min. 8 znaków"
                >
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Potwierdź hasło *
                </label>
                <input 
                    type="password" 
                    id="password_confirmation"
                    name="password_confirmation" 
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="Powtórz hasło"
                >
            </div>

            <button 
                type="submit"
                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Zarejestruj się
            </button>

            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Masz już konto? 
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                    Zaloguj się
                </a>
            </p>
        </form>
    </div>
</div>

<script src="{{ asset('js/auth.js') }}"></script>
@endsection
