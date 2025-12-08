<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Klienta - Car Rental')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen">
    
    <!-- Navigation -->
    <nav class="glass sticky top-0 z-50 shadow-lg" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                
                <!-- 1. Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold text-gray-800 dark:text-white">
                    🚗 <span>Car Rental</span> 
                </a>
                
                <!-- 2. Desktop Menu (Widoczne od LG - 1024px) -->
                <div class="hidden lg:flex items-center">
                    
                    <!-- Linki nawigacyjne (Bez pogrubienia, styl jak w app.blade.php) -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                            Strona główna
                        </a>
                        <a href="{{ route('client.reservations.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition {{ request()->routeIs('client.reservations.*') ? 'text-blue-600' : '' }}">
                            Moje rezerwacje
                        </a>
                        <a href="{{ route('client.profile.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition {{ request()->routeIs('client.profile.*') ? 'text-blue-600' : '' }}">
                            Mój profil
                        </a>
                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center gap-3 ml-8 border-gray-300 dark:border-gray-600">
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                
                            </div>
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-red-600 transition">
                                Wyloguj
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 3. Mobile Menu Button -->
                <!-- Prosty przycisk bez avatara obok -->
                <div class="lg:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- 4. Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden glass border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800" 
             style="display: none;">
            
            <div class="px-4 py-4 flex flex-col space-y-4">
                <!-- Info o użytkowniku w menu mobilnym -->
                <div class="pb-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                    Strona główna
                </a>
                <a href="{{ route('cars.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                    Oferta aut
                </a>
                <a href="{{ route('client.reservations.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                    Moje rezerwacje
                </a>
                <a href="{{ route('client.profile.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                    Mój profil
                </a>

                <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-600 hover:text-red-700 transition py-2">
                            Wyloguj się
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6" role="alert">
                <p class="font-bold">Sukces</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-6" role="alert">
                <p class="font-bold">Błąd</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <footer class="glass mt-16 py-8">
        <div class="container mx-auto px-4 text-center text-gray-700 dark:text-gray-300">
            <p>&copy; {{ date('Y') }} Car Rental System. Panel Klienta.</p>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>