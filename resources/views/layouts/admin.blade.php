<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admina - Car Rental')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen">
    
    <div class="flex min-h-screen">
        <aside class="w-64 glass-dark border-r border-gray-700 flex-shrink-0">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-white mb-2">
                    🚗 Car Rental
                </h2>
                <p class="text-sm text-red-400 font-semibold">Panel Administratora</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/10 hover:text-white transition
                          {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white border-l-4 border-blue-500' : '' }}">
                    <span class="mr-3">📊</span>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.reservations.pending') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/10 hover:text-white transition
                          {{ request()->routeIs('admin.reservations.*') ? 'bg-white/10 text-white border-l-4 border-blue-500' : '' }}">
                    <span class="mr-3">🔔</span>
                    Rezerwacje
                    @if($pendingCount ?? 0 > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                
                <a href="{{ route('admin.cars.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/10 hover:text-white transition
                          {{ request()->routeIs('admin.cars.*') ? 'bg-white/10 text-white border-l-4 border-blue-500' : '' }}">
                    <span class="mr-3">🚘</span>
                    Samochody
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/10 hover:text-white transition
                          {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white border-l-4 border-blue-500' : '' }}">
                    <span class="mr-3">👥</span>
                    Użytkownicy
                </a>
                
                <div class="border-t border-gray-700 my-4"></div>
                
                <a href="{{ route('home') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/10 hover:text-white transition">
                    <span class="mr-3">🏠</span>
                    Strona główna
                </a>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-6 py-3 text-gray-300 hover:bg-red-500/20 hover:text-red-400 transition">
                        <span class="mr-3">🚪</span>
                        Wyloguj się
                    </button>
                </form>
            </nav>
        </aside>
        
        <!-- Main Content - CIEMNE TŁO -->
        <main class="flex-1 bg-gray-900/50">
            
            <!-- Top Bar -->
            <header class="glass-dark border-b border-gray-700 px-8 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-white">
                        @yield('page-title', 'Dashboard')
                    </h1>
                    
                    <div class="flex items-center gap-4">
                        <span class="text-gray-300">
                            {{ auth()->user()->name }}
                        </span>
                        <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-500/20 border border-green-500 text-green-200 px-6 py-4 rounded-lg mb-6 backdrop-blur-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500/20 border border-red-500 text-red-200 px-6 py-4 rounded-lg mb-6 backdrop-blur-sm">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
