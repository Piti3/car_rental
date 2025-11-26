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
<body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen text-gray-100" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile Header -->
    <div class="md:hidden flex items-center justify-between bg-gray-800 p-4 border-b border-gray-700 sticky top-0 z-40">
        <a href="{{ route('home') }}" class="text-xl font-bold text-white">
            🚗 Car Rental
        </a>
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-300 hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <div class="flex min-h-screen relative">
        
        <!-- Sidebar Overlay (Mobile only) -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 z-40 md:hidden glass-blur"
             style="display: none;">
        </div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 border-r border-gray-700 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto md:flex-shrink-0 overflow-y-auto"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div class="p-6 flex justify-between items-center">
                <div>
                    <a href="{{ route('home') }}" class="block">
                        <h2 class="text-2xl font-bold text-white mb-2">
                            🚗 Car Rental
                        </h2>
                    </a>
                    <p class="text-sm text-red-400 font-semibold">Panel Administratora</p>
                </div>
            </div>
            
            <nav class="mt-6 px-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition group
                          {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                    <span class="mr-3 text-xl">📊</span>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.reservations.pending') }}" 
                   class="flex items-center px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition group
                          {{ request()->routeIs('admin.reservations.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                    <span class="mr-3 text-xl">🔔</span>
                    <span class="flex-1">Rezerwacje</span>
                    @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-2">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                
                <a href="{{ route('admin.cars.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition group
                          {{ request()->routeIs('admin.cars.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                    <span class="mr-3 text-xl">🚘</span>
                    Samochody
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition group
                          {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                    <span class="mr-3 text-xl">👥</span>
                    Użytkownicy
                </a>
                
                <div class="border-t border-gray-700 my-4 mx-2"></div>
                
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-700 hover:text-white transition">
                    <span class="mr-3 text-xl">🏠</span>
                    Strona główna
                </a>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-red-900/30 hover:text-red-400 transition">
                        <span class="mr-3 text-xl">🚪</span>
                        Wyloguj się
                    </button>
                </form>
            </nav>
        </aside>
        
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Bar (Desktop) -->
            <header class="hidden md:block glass-dark border-b border-gray-700 px-8 py-4 sticky top-0 z-20">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-white">
                        @yield('page-title', 'Dashboard')
                    </h1>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">Administrator</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-full flex items-center justify-center text-white font-bold shadow-md border border-red-500/30">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-900/50 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-6 py-4 rounded-xl mb-6 backdrop-blur-sm shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-4 rounded-xl mb-6 backdrop-blur-sm shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>