@extends('layouts.admin')

@section('title', 'Dashboard - Panel Admina')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pending Reservations -->
        <div class="bg-gray-800 rounded-xl p-6 border-l-4 border-yellow-500 shadow-lg">
            <!--  ↑ USUNIĘTE /90 backdrop-blur-sm -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Oczekujące rezerwacje</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['pending_reservations'] }}</p>
                </div>
                <div class="text-5xl">🔔</div>
            </div>
        </div>

        <!-- Total Cars -->
        <div class="bg-gray-800 rounded-xl p-6 border-l-4 border-blue-500 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Wszystkie samochody</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_cars'] }}</p>
                </div>
                <div class="text-5xl">🚗</div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-gray-800 rounded-xl p-6 border-l-4 border-green-500 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Użytkownicy</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_users'] }}</p>
                </div>
                <div class="text-5xl">👥</div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-gray-800 rounded-xl p-6 border-l-4 border-purple-500 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Przychód (zatwierdzone)</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($stats['total_revenue'], 0) }} zł</p>
                </div>
                <div class="text-5xl">💰</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.reservations.pending') }}" class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-blue-500 hover:bg-gray-700 transition-all group">
            <div class="flex items-center gap-4">
                <div class="text-4xl">📋</div>
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition">Zarządzaj rezerwacjami</h3>
                    <p class="text-gray-400 text-sm">Zatwierdź lub odrzuć rezerwacje</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.cars.index') }}" class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-blue-500 hover:bg-gray-700 transition-all group">
            <div class="flex items-center gap-4">
                <div class="text-4xl">🚘</div>
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition">Zarządzaj samochodami</h3>
                    <p class="text-gray-400 text-sm">Dodaj, edytuj lub usuń samochody</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-blue-500 hover:bg-gray-700 transition-all group">
            <div class="flex items-center gap-4">
                <div class="text-4xl">👤</div>
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition">Zarządzaj użytkownikami</h3>
                    <p class="text-gray-400 text-sm">Przeglądaj i zarządzaj kontami</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Pending Reservations -->
    @if($pendingReservations->count() > 0)
    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-6">🔔 Najnowsze oczekujące rezerwacje</h2>
        
        <div class="space-y-4">
            @foreach($pendingReservations->take(5) as $reservation)
                <div class="bg-gray-900 rounded-lg p-4 flex items-center justify-between border border-gray-700 hover:border-blue-500 hover:bg-gray-800 transition-all">
                    <div class="flex-1">
                        <h3 class="text-white font-semibold">{{ $reservation->car->brand }} {{ $reservation->car->model }}</h3>
                        <p class="text-gray-400 text-sm mt-1">
                            {{ $reservation->user->name }} • {{ $reservation->start_date->format('d.m.Y') }} - {{ $reservation->end_date->format('d.m.Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-white font-bold">{{ number_format($reservation->total_price, 2) }} zł</span>
                        <a href="{{ route('admin.reservations.pending') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-semibold">
                            Zobacz
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($pendingReservations->count() > 5)
            <div class="mt-6 text-center">
                <a href="{{ route('admin.reservations.pending') }}" class="text-blue-400 hover:text-blue-300 transition font-semibold">
                    Zobacz wszystkie ({{ $pendingReservations->count() }}) →
                </a>
            </div>
        @endif
    </div>
    @else
    <div class="bg-gray-800 rounded-xl p-12 text-center border border-gray-700 shadow-lg">
        <div class="text-6xl mb-4">✅</div>
        <p class="text-gray-400 text-lg">Brak oczekujących rezerwacji</p>
    </div>
    @endif
</div>
@endsection
