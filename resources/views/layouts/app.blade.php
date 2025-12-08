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
    <nav class="glass sticky top-0 z-50 shadow-lg" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800 dark:text-white">
                    🚗 Car Rental
                </a>
                
                <!-- Desktop Menu (widoczne od LG - 1024px) -->
                <div class="hidden lg:flex items-center space-x-6">
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
                            
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-red-600 transition">
                                    Wyloguj
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                            Zaloguj się
                        </a>
                        <a href="{{ route('register') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                            Zarejestruj
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button (widoczny poniżej LG - 1024px) -->
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

        <!-- Mobile Menu Panel (widoczny poniżej LG) -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden glass border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800" 
             style="display: none;">
            
                @auth
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

                <div class="px-4 py-4 flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                    Strona główna
                </a>
                <a href="{{ route('cars.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                    Samochody
                </a>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block text-red-600 font-semibold hover:text-red-700 transition mb-3">
                                Panel Admin
                            </a>
                        @else
                            <a href="{{ route('client.reservations.index') }}" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 transition mb-3">
                                Moje rezerwacje
                            </a>
                            <a href="{{ route('client.profile.index') }}" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 transition mb-3">
                                Mój profil
                            </a>
                        @endif

                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                                <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-red-600 transition w-full text-left">
                                    Wyloguj się
                                </button>
                        </form>
                    </div>
                @else
                    <div class="px-4 py-4 flex flex-col space-y-4">
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">
                            Zaloguj się
                        </a>
                        <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-700 transition">
                            Zarejestruj
                        </a>
                    </div>
                @endauth
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