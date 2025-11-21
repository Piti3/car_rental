@extends('layouts.client')

@section('title', 'Rezerwacja - ' . $car->brand . ' ' . $car->model)

@section('content')
<div x-data="reservationForm()" class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('cars.show', $car->id) }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
            ← Powrót do szczegółów
        </a>
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white">
            Zarezerwuj {{ $car->brand }} {{ $car->model }}
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2">
            {{ $car->daily_price }} zł / dzień
        </p>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="glass bg-red-500/20 border-red-500 text-red-800 dark:text-red-200 px-6 py-4 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="glass bg-red-500/20 border-red-500 text-red-800 dark:text-red-200 px-6 py-4 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formularz -->
    <form action="{{ route('client.reservations.store') }}" method="POST" class="glass rounded-xl p-8">
        @csrf
        <input type="hidden" name="car_id" value="{{ $car->id }}">

        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <!-- Data początkowa -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Data początkowa *
                </label>
                <input 
                    type="date" 
                    name="start_date"
                    x-model="startDate"
                    @change="updatePrice()"
                    min="{{ date('Y-m-d') }}"
                    value="{{ old('start_date') }}"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                >
            </div>

            <!-- Data końcowa -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Data końcowa *
                </label>
                <input 
                    type="date" 
                    name="end_date"
                    x-model="endDate"
                    @change="updatePrice()"
                    :min="startDate || '{{ date('Y-m-d') }}'"
                    value="{{ old('end_date') }}"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                >
            </div>
        </div>

        <!-- Kalendarz dostępności -->
        @if(isset($calendar) && count($calendar) > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                📅 Kalendarz dostępności
            </h3>
            <div class="grid grid-cols-7 gap-2">
                @foreach($calendar as $day)
                    <div 
                        class="text-center p-2 rounded text-sm border-2 
                            @if($day['available'] && !$day['is_past']) bg-green-100 dark:bg-green-900/30 border-green-500
                            @elseif(!$day['available']) bg-red-100 dark:bg-red-900/30 border-red-500
                            @else bg-gray-100 dark:bg-gray-800 border-gray-400 opacity-50
                            @endif
                        "
                        title="{{ $day['available'] ? 'Dostępny' : 'Zajęty' }}"
                    >
                        <div class="font-semibold dark:text-gray-400">{{ $day['formatted'] ?? date('d.m', strtotime($day['date'])) }}</div>
                        <div class="text-xs dark:text-gray-400">{{ $day['available'] ? '✓' : '✗' }}</div>
                    </div>
                @endforeach
            </div>
            <div class="flex gap-4 mt-4 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-100 dark:bg-green-900/30 border-2 border-green-500 rounded"></div>
                    <span>Dostępny</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-100 dark:bg-red-900/30 border-2 border-red-500 rounded"></div>
                    <span>Zajęty</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-100 dark:bg-gray-800 border-2 border-gray-400 rounded opacity-50"></div>
                    <span>Przeszłość</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Notatki -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Dodatkowe uwagi (opcjonalne)
            </label>
            <textarea 
                name="notes" 
                rows="3"
                placeholder="Np. Odbiór z lotniska, fotelik dziecięcy..."
                class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
            >{{ old('notes') }}</textarea>
        </div>

        <!-- Podsumowanie -->
        <div class="glass-dark rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Podsumowanie</h3>
            <div class="space-y-2 text-white">
                <div class="flex justify-between">
                    <span>Liczba dni:</span>
                    <span x-text="days" class="font-semibold">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Cena za dzień:</span>
                    <span class="font-semibold">{{ $car->daily_price }} zł</span>
                </div>
                <div class="border-t border-white/20 my-2"></div>
                <div class="flex justify-between text-xl">
                    <span>Całkowity koszt:</span>
                    <span x-text="totalPrice + ' zł'" class="font-bold text-blue-300">-</span>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button 
                type="submit"
                class="flex-1 px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Zarezerwuj teraz
            </button>
            <a 
                href="{{ route('cars.show', $car->id) }}"
                class="px-8 py-3 glass rounded-lg hover:bg-white/20 transition font-semibold text-center"
            >
                Anuluj
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function reservationForm() {
    return {
        startDate: '{{ old('start_date') }}',
        endDate: '{{ old('end_date') }}',
        dailyPrice: {{ $car->daily_price }},
        days: 0,
        totalPrice: 0,
        
        updatePrice() {
            if (this.startDate && this.endDate) {
                const start = new Date(this.startDate);
                const end = new Date(this.endDate);
                this.days = Math.max(1, Math.ceil((end - start) / (1000 * 60 * 60 * 24)));
                this.totalPrice = (this.days * this.dailyPrice).toFixed(2);
            } else {
                this.days = 0;
                this.totalPrice = 0;
            }
        },
        
        init() {
            this.updatePrice();
        }
    }
}
</script>
@endpush
@endsection
