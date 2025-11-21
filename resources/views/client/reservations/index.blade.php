@extends('layouts.client')

@section('title', 'Moje rezerwacje')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white">
            Moje rezerwacje
        </h1>
        <a href="{{ route('cars.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
            + Nowa rezerwacja
        </a>
    </div>

    @if($reservations->isEmpty())
        <div class="glass rounded-xl p-12 text-center">
            <div class="text-6xl mb-4">📅</div>
            <p class="text-gray-600 dark:text-gray-300 text-xl mb-6">
                Nie masz jeszcze żadnych rezerwacji
            </p>
            <a href="{{ route('cars.index') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                Przeglądaj samochody
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reservations as $reservation)
                <div class="glass rounded-xl p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Car Info -->
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                                {{ $reservation->car->brand }} {{ $reservation->car->model }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                📅 {{ $reservation->start_date->format('d.m.Y') }} - {{ $reservation->end_date->format('d.m.Y') }}
                                ({{ $reservation->start_date->diffInDays($reservation->end_date) }} dni)
                            </p>
                            @if($reservation->notes)
                                <p class="text-gray-500 dark:text-gray-400 text-sm italic">
                                    💬 {{ $reservation->notes }}
                                </p>
                            @endif
                        </div>

                        <!-- Status & Price -->
                        <div class="flex flex-col items-end gap-3">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold
                                @if($reservation->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200
                                @elseif($reservation->status === 'approved') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200
                                @elseif($reservation->status === 'cancelled') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200
                                @else bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200
                                @endif
                            ">
                                @if($reservation->status === 'pending') ⏳ Oczekująca
                                @elseif($reservation->status === 'approved') ✅ Zatwierdzona
                                @elseif($reservation->status === 'cancelled') ❌ Anulowana
                                @else ✔️ Zakończona
                                @endif
                            </span>

                            <span class="text-2xl font-bold text-gray-800 dark:text-white">
                                {{ number_format($reservation->total_price, 2) }} zł
                            </span>

                            @if(in_array($reservation->status, ['pending', 'approved']))
                                <form action="{{ route('client.reservations.cancel', $reservation->id) }}" method="POST">
                                    @csrf
                                    <button 
                                        type="submit"
                                        onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold"
                                    >
                                        Anuluj rezerwację
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
