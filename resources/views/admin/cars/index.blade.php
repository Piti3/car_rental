@extends('layouts.admin')

@section('title', 'Zarządzanie samochodami - Panel Admina')
@section('page-title', 'Zarządzanie samochodami')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-400">Wszystkie samochody w systemie</p>
        <a href="{{ route('admin.cars.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
            + Dodaj samochód
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($cars as $car)
            <div class="glass-dark rounded-xl overflow-hidden">
                <!-- Image -->
                <div class="h-48 bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-6xl">
                    🚗
                </div>

                <!-- Content -->
                <div class="p-6 space-y-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $car->brand }} {{ $car->model }}</h3>
                        <p class="text-gray-400 text-sm">{{ $car->category->name }} • {{ $car->year }}</p>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($car->status === 'available') bg-green-500/20 text-green-400
                            @elseif($car->status === 'rented') bg-yellow-500/20 text-yellow-400
                            @else bg-red-500/20 text-red-400
                            @endif
                        ">
                            @if($car->status === 'available') Dostępny
                            @elseif($car->status === 'rented') Wynajęty
                            @else Serwis
                            @endif
                        </span>
                        <span class="text-white font-bold">{{ $car->daily_price }} zł/dzień</span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.cars.edit', $car->id) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center text-sm">
                            Edytuj
                        </a>
                        <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                onclick="return confirm('Czy na pewno chcesz usunąć ten samochód?')"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm"
                            >
                                Usuń
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $cars->links() }}
    </div>
</div>
@endsection
