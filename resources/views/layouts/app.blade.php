<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Car Rental System')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen">
    
    <!-- Navigation -->
    <nav class="glass sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800 dark:text-white">
                    🚗 Car Rental
                </a>
                
                <!-- Menu -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                        Strona główna
                    </a>
                    <a href="{{ route('cars.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                        Samochody
                    </a>
                    
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <!-- Menu dla admina -->
                            <a href="{{ route('admin.dashboard') }}" class="text-red-600 font-semibold hover:text-red-700 transition">
                                Panel Admin
                            </a>
                        @else
                            <!-- Menu dla klienta -->
                            <a href="{{ route('client.reservations.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                                Moje rezerwacje
                            </a>
                            <a href="{{ route('client.profile.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                                Mój profil
                            </a>
                        @endif
                        <!-- User Avatar -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <form action="{{ route('logout') }}" method="POST"
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-red-600 transition">
                                Wyloguj
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                            Zaloguj się
                        </a>
                        <a href="{{ route('register') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                            Zarejestruj
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="glass bg-green-500/20 border border-green-500 text-green-800 dark:text-green-200 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="glass bg-red-500/20 border border-red-500 text-red-800 dark:text-red-200 px-6 py-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="glass mt-16 py-8">
        <div class="container mx-auto px-4 text-center text-gray-700 dark:text-gray-300">
            <p>&copy; 2025 Car Rental System. Wszystkie prawa zastrzeżone.</p>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>
