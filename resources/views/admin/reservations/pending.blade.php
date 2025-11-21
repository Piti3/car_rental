@extends('layouts.admin')

@section('title', 'Oczekujące rezerwacje - Panel Admina')
@section('page-title', 'Oczekujące rezerwacje')

@section('content')
<div class="space-y-6">
    @if($reservations->isEmpty())
        <div class="glass-dark rounded-xl p-12 text-center">
            <div class="text-6xl mb-4">✅</div>
            <p class="text-gray-400 text-xl">Brak rezerwacji oczekujących na zatwierdzenie</p>
            <a href="{{ route('admin.dashboard') }}" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Powrót do Dashboard
            </a>
        </div>
    @else
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-400">Znaleziono: <span class="text-white font-bold">{{ $reservations->count() }}</span> rezerwacji</p>
        </div>

        @foreach($reservations as $reservation)
            <div class="glass-dark rounded-xl p-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <!-- Car & User Info -->
                    <div class="flex-1 space-y-3">
                        <div>
                            <h3 class="text-2xl font-bold text-white mb-1">
                                {{ $reservation->car->brand }} {{ $reservation->car->model }}
                            </h3>
                            <span class="inline-block px-3 py-1 bg-blue-600/20 text-blue-400 rounded-full text-sm">
                                {{ $reservation->car->category->name }}
                            </span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 text-gray-300">
                            <div>
                                <p class="text-gray-500 text-sm">📅 Termin rezerwacji</p>
                                <p class="font-semibold">{{ $reservation->start_date->format('d.m.Y') }} - {{ $reservation->end_date->format('d.m.Y') }}</p>
                                <p class="text-sm">({{ $reservation->start_date->diffInDays($reservation->end_date) }} dni)</p>
                            </div>

                            <div>
                                <p class="text-gray-500 text-sm">👤 Klient</p>
                                <p class="font-semibold">{{ $reservation->user->name }}</p>
                                <p class="text-sm">{{ $reservation->user->email }}</p>
                                <p class="text-sm">📞 {{ $reservation->user->phone ?? 'Brak telefonu' }}</p>
                            </div>
                        </div>

                        @if($reservation->notes)
                            <div class="bg-white/5 rounded-lg p-4">
                                <p class="text-gray-500 text-sm mb-1">💬 Uwagi klienta:</p>
                                <p class="text-gray-300">{{ $reservation->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Price & Actions -->
                    <div class="flex flex-col items-end gap-4 min-w-[200px]">
                        <div class="text-right">
                            <p class="text-gray-500 text-sm">Całkowity koszt</p>
                            <p class="text-3xl font-bold text-white">{{ number_format($reservation->total_price, 2) }} zł</p>
                            <p class="text-sm text-gray-400">{{ number_format($reservation->car->daily_price, 2) }} zł/dzień</p>
                        </div>

                        <div class="flex gap-2 w-full">
                            <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button 
                                    type="submit"
                                    class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold"
                                >
                                    ✓ Zatwierdź
                                </button>
                            </form>

                            <form action="{{ route('admin.reservations.cancel', $reservation->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button 
                                    type="submit"
                                    onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')"
                                    class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold"
                                >
                                    ✗ Odrzuć
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
