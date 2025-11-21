@extends('layouts.admin')

@section('title', 'Wszystkie rezerwacje - Panel Admina')
@section('page-title', 'Wszystkie rezerwacje')

@section('content')
<div class="glass-dark rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Samochód</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Klient</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Termin</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Dni</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Cena</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($reservations as $reservation)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 text-white font-semibold">#{{ $reservation->id }}</td>
                        <td class="px-6 py-4 text-white">{{ $reservation->car->brand }} {{ $reservation->car->model }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $reservation->user->name }}</td>
                        <td class="px-6 py-4 text-gray-300 text-sm">
                            {{ $reservation->start_date->format('d.m.Y') }}<br>
                            {{ $reservation->end_date->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $reservation->start_date->diffInDays($reservation->end_date) }}</td>
                        <td class="px-6 py-4 text-white font-semibold">{{ number_format($reservation->total_price, 2) }} zł</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($reservation->status === 'pending') bg-yellow-500/20 text-yellow-400
                                @elseif($reservation->status === 'approved') bg-green-500/20 text-green-400
                                @elseif($reservation->status === 'cancelled') bg-red-500/20 text-red-400
                                @else bg-blue-500/20 text-blue-400
                                @endif
                            ">
                                @if($reservation->status === 'pending') Oczekująca
                                @elseif($reservation->status === 'approved') Zatwierdzona
                                @elseif($reservation->status === 'cancelled') Anulowana
                                @else Zakończona
                                @endif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            Brak rezerwacji w systemie
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
