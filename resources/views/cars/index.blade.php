@extends('layouts.app')

@section('title', 'Nasze samochody')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-8">
        Nasze samochody
    </h1>

    <!-- Filters -->
    <form method="GET" class="glass rounded-xl p-6 mb-8 flex flex-wrap gap-4">
        <!-- Category Filter -->
        <select 
            name="category_id" 
            class="px-4 py-2 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300">
            
            <option value="">Wszystkie kategorie</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        
        <!-- Price Min -->
        <input 
            type="number" 
            name="price_min" 
            placeholder="Cena od"
            value="{{ request('price_min') }}"
            class="px-4 py-2 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400"
        >
        
        <input 
            type="number" 
            name="price_max" 
            placeholder="Cena do"
            value="{{ request('price_max') }}"
            class="px-4 py-2 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400"
        >
        
        <!-- Submit Button -->
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Filtruj
        </button>

        <!-- Reset Button -->
        @if(request()->hasAny(['category_id', 'price_min', 'price_max']))
            <a href="{{ route('cars.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Wyczyść
            </a>
        @endif
    </form>

    <!-- Cars Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($cars as $car)
            <div class="glass rounded-xl overflow-hidden card-hover">
                <!-- Image -->
                <div class="glass rounded-xl overflow-hidden">
                    <div class="relative h-48 bg-gradient-to-br from-blue-400 to-purple-500">
                        @if($car->image_path)
                            <img 
                                src="{{ asset($car->image_path) }}" 
                                alt="{{ $car->brand }} {{ $car->model }}"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
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
                
                <!-- Content -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">
                        {{ $car->brand }} {{ $car->model }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                        {{ $car->category->name }} • {{ $car->year }}
                    </p>
                    
                    <!-- Quick specs -->
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-4">
                        <span>⚡ {{ $car->specification->horsepower ?? 'N/A' }} KM</span>
                        <span>⛽ {{ $car->specification->fuel_type ?? 'N/A' }}</span>
                        <span>🪑 {{ $car->specification->seats ?? 'N/A' }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('cars.show', $car->id) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center text-sm font-semibold">
                            Zobacz szczegóły
                        </a>
                        @auth
                            <a href="{{ route('client.reservations.create', ['car_id' => $car->id]) }}" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center text-sm font-semibold">
                                Zarezerwuj
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full glass rounded-xl p-12 text-center">
                <p class="text-gray-600 dark:text-gray-300 text-lg">
                    Brak dostępnych samochodów
                </p>
            </div>
        @endforelse
    </div>


</div>
@endsection
