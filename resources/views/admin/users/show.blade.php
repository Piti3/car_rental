@extends('layouts.admin')

@section('title', 'Szczegóły użytkownika - Panel Admina')
@section('page-title', 'Szczegóły użytkownika')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Back button -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-4">
        ← Powrót do listy użytkowników
    </a>

    <!-- User Info Card -->
    <div class="glass-dark rounded-xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Informacje o użytkowniku</h2>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-400 text-sm mb-1">Imię i nazwisko</p>
                <p class="text-white font-semibold text-lg">{{ $user->name }}</p>
            </div>
            
            <div>
                <p class="text-gray-400 text-sm mb-1">Email</p>
                <p class="text-white font-semibold">{{ $user->email }}</p>
            </div>
            
            <div>
                <p class="text-gray-400 text-sm mb-1">Telefon</p>
                <p class="text-white font-semibold">{{ $user->phone ?? 'Brak' }}</p>
            </div>
            
            <div>
                <p class="text-gray-400 text-sm mb-1">Rola</p>
                <p class="text-white font-semibold">
                    @if($user->isAdmin())
                        <span class="px-3 py-1 bg-red-600 text-white rounded-full text-sm">Administrator</span>
                    @else
                        <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-sm">Klient</span>
                    @endif
                </p>
            </div>
            
            <div>
                <p class="text-gray-400 text-sm mb-1">Data rejestracji</p>
                <p class="text-white font-semibold">{{ $user->created_at->format('d.m.Y H:i') }}</p>
            </div>
            
            <div>
                <p class="text-gray-400 text-sm mb-1">Ostatnia aktywność</p>
                <p class="text-white font-semibold">{{ $user->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <!-- Reservations -->
    <div class="glass-dark rounded-xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6">
            Historia rezerwacji ({{ $user->reservations->count() }})
        </h2>
        
        @if($user->reservations->isEmpty())
            <p class="text-gray-400">Użytkownik nie ma jeszcze rezerwacji.</p>
        @else
            <div class="space-y-4">
                @foreach($user->reservations as $reservation)
                    <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-white font-semibold text-lg mb-2">
                                    {{ $reservation->car->brand }} {{ $reservation->car->model }}
                                </h3>
                                <div class="grid md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-400">Od</p>
                                        <p class="text-white">{{ \Carbon\Carbon::parse($reservation->start_date)->format('d.m.Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400">Do</p>
                                        <p class="text-white">{{ \Carbon\Carbon::parse($reservation->end_date)->format('d.m.Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400">Cena całkowita</p>
                                        <p class="text-white font-semibold">{{ $reservation->total_price }} zł</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                @if($reservation->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-600 text-white rounded-full text-sm">Oczekująca</span>
                                @elseif($reservation->status === 'approved')
                                    <span class="px-3 py-1 bg-green-600 text-white rounded-full text-sm">Zatwierdzona</span>
                                @elseif($reservation->status === 'cancelled')
                                    <span class="px-3 py-1 bg-red-600 text-white rounded-full text-sm">Anulowana</span>
                                @elseif($reservation->status === 'completed')
                                    <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-sm">Zakończona</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
        <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            Powrót
        </a>
        
        @if(!$user->isAdmin())
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Usuń użytkownika
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
