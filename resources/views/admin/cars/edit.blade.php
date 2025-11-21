@extends('layouts.admin')

@section('title', 'Edytuj samochód - Panel Admina')
@section('page-title', 'Edytuj samochód')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data" class="glass-dark rounded-xl p-8 space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Marka *</label>
                <input 
                    type="text" 
                    name="brand" 
                    value="{{ old('brand', $car->brand) }}"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="np. BMW"
                >
                @error('brand')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Model *</label>
                <input 
                    type="text" 
                    name="model" 
                    value="{{ old('model', $car->model) }}"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="np. M3"
                >
                @error('model')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Rok *</label>
                <input 
                    type="number" 
                    name="year" 
                    value="{{ old('year', $car->year) }}"
                    required
                    min="1900"
                    max="{{ date('Y') + 1 }}"
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                @error('year')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Kategoria *</label>
                <select 
                    name="category_id" 
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                >
                    <option value="">Wybierz...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $car->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Status *</label>
                <select 
                    name="status" 
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                >
                    <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>Dostępny</option>
                    <option value="rented" {{ old('status', $car->status) == 'rented' ? 'selected' : '' }}>Wynajęty</option>
                    <option value="maintenance" {{ old('status', $car->status) == 'maintenance' ? 'selected' : '' }}>Serwis</option>
                </select>
                @error('status')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Cena za dzień (zł) *</label>
                <input 
                    type="number" 
                    name="daily_price" 
                    value="{{ old('daily_price', $car->daily_price) }}"
                    required
                    min="0"
                    step="0.01"
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="np. 250.00"
                >
                @error('daily_price')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Nr rejestracyjny *</label>
                <input 
                    type="text" 
                    name="license_plate" 
                    value="{{ old('license_plate', $car->registration) }}"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="np. WA 12345"
                >
                @error('license_plate')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Kolor</label>
                <input 
                    type="text" 
                    name="color" 
                    value="{{ old('color', $car->specification->color ?? '') }}"
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="np. Czarny"
                >
            </div>
        </div>

        <!-- Image Upload -->
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Zdjęcie samochodu</label>
            
            @if($car->image_path)
                <div class="mb-4">
                    <img src="{{ asset($car->image_path) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-48 h-32 object-cover rounded-lg border border-gray-600">
                    <p class="text-sm text-gray-400 mt-2">Aktualne zdjęcie</p>
                </div>
            @endif

            <input 
                type="file" 
                name="image" 
                accept="image/*"
                class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <p class="text-sm text-gray-400 mt-1">Zostaw puste aby zachować obecne zdjęcie (max 5MB)</p>
            @error('image')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Specifications -->
        <div class="border-t border-gray-700 pt-6">
            <h3 class="text-xl font-bold text-white mb-4">Specyfikacja techniczna</h3>

            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Typ silnika</label>
                    <input 
                        type="text" 
                        name="engine_type" 
                        value="{{ old('engine_type', $car->specification->engine_type ?? '') }}"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="np. 2.0 TSI"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Pojemność (L)</label>
                    <input 
                        type="number" 
                        name="engine_capacity" 
                        value="{{ old('engine_capacity', $car->specification->engine_capacity ?? '') }}"
                        step="0.1"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Moc (KM)</label>
                    <input 
                        type="number" 
                        name="horsepower" 
                        value="{{ old('horsepower', $car->specification->horsepower ?? '') }}"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Paliwo</label>
                    <select 
                        name="fuel_type"
                        class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    >
                        <option value="">Wybierz...</option>
                        <option value="petrol" {{ old('fuel_type', $car->specification->fuel_type ?? '') == 'petrol' ? 'selected' : '' }}>Benzyna</option>
                        <option value="diesel" {{ old('fuel_type', $car->specification->fuel_type ?? '') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="electric" {{ old('fuel_type', $car->specification->fuel_type ?? '') == 'electric' ? 'selected' : '' }}>Elektryczny</option>
                        <option value="hybrid" {{ old('fuel_type', $car->specification->fuel_type ?? '') == 'hybrid' ? 'selected' : '' }}>Hybryda</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Skrzynia biegów</label>
                    <select 
                        name="transmission"
                        class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    >
                        <option value="">Wybierz...</option>
                        <option value="manual" {{ old('transmission', $car->specification->transmission ?? '') == 'manual' ? 'selected' : '' }}>Manualna</option>
                        <option value="automatic" {{ old('transmission', $car->specification->transmission ?? '') == 'automatic' ? 'selected' : '' }}>Automatyczna</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">0-100 km/h (s)</label>
                    <input 
                        type="number" 
                        name="acceleration_0_100" 
                        value="{{ old('acceleration_0_100', $car->specification->acceleration_0_100 ?? '') }}"
                        step="0.1"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Liczba miejsc</label>
                    <input 
                        type="number" 
                        name="seats" 
                        value="{{ old('seats', $car->specification->seats ?? 5) }}"
                        min="1"
                        max="9"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Liczba drzwi</label>
                    <input 
                        type="number" 
                        name="doors" 
                        value="{{ old('doors', $car->specification->doors ?? 4) }}"
                        min="2"
                        max="5"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">Ciekawostka</label>
                <textarea 
                    name="fun_fact" 
                    rows="3"
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Np. Ten model wygrał 3 wyścigi w 2023 roku..."
                >{{ old('fun_fact', $car->specification->fun_fact ?? '') }}</textarea>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex gap-4 pt-6 border-t border-gray-700">
            <button 
                type="submit"
                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Zapisz zmiany
            </button>
            <a 
                href="{{ route('admin.cars.index') }}"
                class="px-8 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold"
            >
                Anuluj
            </a>
        </div>
    </form>
</div>
@endsection
