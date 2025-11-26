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
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col">
    
    <!-- Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200 dark:border-gray-700" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold text-blue-600 dark:text-blue-400">
                    🚗 <span>Car Rental</span> <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full ml-2 hidden sm:inline-block">Klient</span>
                </a>
                
                <!-- Desktop Menu (MD+) -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Strona główna
                    </a>
                    
                    <a href="{{ route('cars.index') }}" class="px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Oferta aut
                    </a>

                    <a href="{{ route('client.reservations.index') }}" 
                       class="px-4 py-2 rounded-lg transition {{ request()->routeIs('client.reservations.*') ? 'bg-blue-50 text-blue-600 font-semibold dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        Moje rezerwacje
                    </a>
                    
                    <a href="{{ route('client.profile.index') }}" 
                       class="px-4 py-2 rounded-lg transition {{ request()->routeIs('client.profile.*') ? 'bg-blue-50 text-blue-600 font-semibold dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        Mój profil
                    </a>
                </div>

                <!-- User Actions (Desktop) -->
                <div class="hidden md:flex items-center gap-4">
                     <div class="flex items-center gap-3">
                        <div class="text-right hidden lg:block">
                            <div class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">Panel klienta</div>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 transition rounded-full hover:bg-red-50" title="Wyloguj się">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center gap-3">
                    <!-- Avatar (Mobile) -->
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-md">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>

                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 focus:outline-none p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden glass border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800" 
             style="display: none;">
            
            <div class="px-4 py-4 flex flex-col space-y-2">
                <div class="pb-4 mb-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Zalogowany jako:</p>
                    <p class="font-bold text-gray-800 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-500 font-medium">{{ auth()->user()->email }}</p>
                </div>

                <a href="{{ route('home') }}" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium">
                    🏠 Strona główna
                </a>
                <a href="{{ route('cars.index') }}" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium">
                    🚗 Oferta aut
                </a>
                <a href="{{ route('client.reservations.index') }}" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium {{ request()->routeIs('client.reservations.*') ? 'text-blue-600 font-bold' : '' }}">
                    📅 Moje rezerwacje
                </a>
                <a href="{{ route('client.profile.index') }}" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium {{ request()->routeIs('client.profile.*') ? 'text-blue-600 font-bold' : '' }}">
                    👤 Mój profil
                </a>

                <div class="pt-4 mt-2 border-t border-gray-100 dark:border-gray-700">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center text-red-600 hover:text-red-700 font-medium w-full py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Wyloguj się
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="mb-6 text-sm text-gray-500 hidden md:block">
            <a href="{{ route('home') }}" class="hover:underline">Home</a> 
            <span class="mx-2">/</span> 
            <span class="text-gray-800 dark:text-gray-300 font-medium">@yield('page-title', 'Dashboard')</span>
        </div>

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
    
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-6 mt-auto">
        <div class="container mx-auto px-4 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} Car Rental System. Panel Klienta.</p>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>