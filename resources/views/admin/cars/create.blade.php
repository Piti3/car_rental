@extends('layouts.admin')

@section('title', 'Dodaj samochód - Panel Admina')
@section('page-title', 'Dodaj nowy samochód')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data" class="glass-dark rounded-xl p-8 space-y-6">
        @csrf

        <!-- Basic Info -->
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Marka *</label>
                <input 
                    type="text" 
                    name="brand" 
                    value="{{ old('brand') }}"
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
                    value="{{ old('model') }}"
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
                    value="{{ old('year', date('Y')) }}"
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
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-600 dark:border-gray-60 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Wybierz...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-600 dark:border-gray-60 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Dostępny</option>
                    <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Wynajęty</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Serwis</option>
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
                    value="{{ old('daily_price') }}"
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
                    name="registration" 
                    value="{{ old('registration') }}"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="np. WA 12345"
                >
                @error('registration')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Kolor</label>
                <input 
                    type="text" 
                    name="color" 
                    value="{{ old('color') }}"
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="np. Czarny"
                >
            </div>
        </div>

        <!-- Image Upload -->
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Zdjęcie samochodu</label>
            <input 
                type="file" 
                name="image" 
                accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <p class="text-sm text-gray-400 mt-1">Dozwolone formaty: JPEG, PNG, JPG, WebP, GIF (max 5MB)</p>
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
                        value="{{ old('engine_type') }}"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="np. 2.0 TSI"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Pojemność (L)</label>
                    <input 
                        type="number" 
                        name="engine_capacity" 
                        value="{{ old('engine_capacity') }}"
                        step="0.1"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Moc (KM)</label>
                    <input 
                        type="number" 
                        name="horsepower" 
                        value="{{ old('horsepower') }}"
                        class="w-full px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Paliwo</label>
                    <select 
                        name="fuel_type"
                        class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-600 dark:border-gray-60 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Wybierz...</option>
                        <option value="Benzyna" {{ old('fuel_type') == 'Benzyna' ? 'selected' : '' }}>Benzyna</option>
                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Elektryczny" {{ old('fuel_type') == 'Elektryczny' ? 'selected' : '' }}>Elektryczny</option>
                        <option value="Hybryda" {{ old('fuel_type') == 'Hybryda' ? 'selected' : '' }}>Hybryda</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Skrzynia biegów</label>
                    <select 
                        name="transmission"
                        class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-600 dark:border-gray-60 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Wybierz...</option>
                        <option value="Manualna" {{ old('transmission') == 'Manualna' ? 'selected' : '' }}>Manualna</option>
                        <option value="Automatyczna" {{ old('transmission') == 'Automatyczna' ? 'selected' : '' }}>Automatyczna</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">0-100 km/h (s)</label>
                    <input 
                        type="number" 
                        name="acceleration_0_100" 
                        value="{{ old('acceleration_0_100') }}"
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
                        value="{{ old('seats') }}"
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
                        value="{{ old('doors') }}"
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
                >{{ old('fun_fact') }}</textarea>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex gap-4 pt-6 border-t border-gray-700">
            <button 
                type="submit"
                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Dodaj samochód
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
