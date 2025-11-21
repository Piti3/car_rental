@extends('layouts.app')

@section('title', $car->brand . ' ' . $car->model)

@section('content')
<div class="max-w-6xl mx-auto">
    <a href="{{ route('cars.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
        ← Powrót do listy
    </a>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Image -->
        <div class="glass rounded-xl overflow-hidden self-center">
            <div class="relative w-full bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl overflow-hidden">
                @if($car->image_path)
                    <img 
                        src="{{ asset($car->image_path) }}" 
                        alt="{{ $car->brand }} {{ $car->model }}"
                        class="w-full h-full object-contain transition-transform duration-300 hover:scale-110"
                    >
                @else
                    <div class="w-full h-full flex items-center justify-center text-9xl">
                        🚗
                    </div>
                @endif
                
                <div class="absolute top-4 right-4 glass-dark rounded-full px-3 py-1 text-white text-sm font-semibold">
                    {{ $car->daily_price }} zł/dzień
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
                    {{ $car->brand }} {{ $car->model }}
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300">
                    {{ $car->category->name }} • {{ $car->year }}
                </p>
            </div>

            <div class="glass rounded-xl p-6">
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">Cena za dzień</p>
                <p class="text-4xl font-bold text-gray-800 dark:text-white">
                    {{ $car->daily_price }} zł
                </p>
            </div>

            <!-- Specifications -->
            @if($car->specification)
            <div class="glass rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Specyfikacja</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Moc</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $car->specification->horsepower }} KM</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Paliwo</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $car->specification->fuel_type }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Skrzynia</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $car->specification->transmission }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Miejsca</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $car->specification->seats }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Reserve Button -->
            @auth
                <a href="{{ route('client.reservations.create', ['car_id' => $car->id]) }}" class="block w-full px-8 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center text-lg font-semibold">
                    Zarezerwuj teraz
                </a>
            @else
                <a href="{{ route('login') }}" class="block w-full px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center text-lg font-semibold">
                    Zaloguj się aby zarezerwować
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
