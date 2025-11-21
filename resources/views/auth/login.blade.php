@extends('layouts.app')

@section('title', 'Logowanie')

@section('content')
<div class="max-w-md mx-auto">
    <div class="glass rounded-2xl p-8 shadow-lg">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">
            Zaloguj się
        </h2>

        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Email
                </label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="twoj@email.com"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Hasło
                </label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="••••••••"
                >
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Zaloguj się
            </button>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Nie masz konta? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Zarejestruj się
                </a>
            </p>
        </form>

        <!-- Credentials -->
        <div class="mt-6 p-4 glass-dark rounded-lg">
            <p class="text-xs text-gray-300 mb-2"><strong>Credentials:</strong></p>
            <p class="text-xs text-gray-400">Admin: admin@carrental.com / admin123</p>
            <p class="text-xs text-gray-400">Klient: jan.kowalski@example.com / password123</p>
        </div>
    </div>
</div>
@endsection
