@extends('layouts.app')

@section('title', 'Nasze samochody')

@section('content')
<!-- Przekazujemy status logowania do Alpine.js -->
<div x-data="carCatalog({ isLoggedIn: {{ auth()->check() ? 'true' : 'false' }} })" class="max-w-7xl mx-auto">
    <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-8">
        Nasze samochody
    </h1>

    <!-- Filters -->
    <div class="glass rounded-xl p-6 mb-8 flex flex-wrap gap-4 items-end">
        <!-- Category Filter -->
        <div class="flex-1 min-w-[200px]">
            <select 
                x-model="filters.category_id" 
                @change="filterCars()"
                class="w-full px-4 py-2 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300">
                <option value="">Wszystkie kategorie</option>
                
                <template x-for="category in categories" :key="category.id">
                    <option :value="category.id" x-text="category.name"></option>
                </template>
            </select>
        </div>
        
        <!-- Price Min -->
        <div class="flex-1 min-w-[150px]">
            <input 
                type="number" 
                x-model="filters.price_min"
                placeholder="Cena od"
                class="w-full px-4 py-2 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400"
            >
        </div>
        
        <!-- Price Max -->
        <div class="flex-1 min-w-[150px]">
            <input 
                type="number" 
                x-model="filters.price_max"
                placeholder="Cena do"
                class="w-full px-4 py-2 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400"
            >
        </div>
        
        <!-- Submit Button -->
        <button @click="filterCars()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition h-full">
            Filtruj
        </button>

        <!-- Reset Button -->
        <button 
            @click="resetFilters()" 
            x-show="filters.category_id || filters.price_min || filters.price_max"
            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"
        >
            Wyczyść
        </button>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-4 text-gray-600 dark:text-gray-400">Ładowanie oferty...</p>
    </div>

    <!-- Cars Grid -->
    <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 min-h-[500px]">
        <template x-for="car in paginatedCars" :key="car.id">
            <div class="glass rounded-xl overflow-hidden card-hover flex flex-col">
                <!-- Image -->
                <div class="relative h-48 bg-gradient-to-br from-blue-400 to-purple-500 overflow-hidden group">
                    <template x-if="car.image_path">
                        <img 
                            :src="car.image_path" 
                            :alt="car.brand + ' ' + car.model"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                        >
                    </template>
                    <template x-if="!car.image_path">
                        <div class="w-full h-full flex items-center justify-center text-6xl">🚗</div>
                    </template>
                    
                    <div class="absolute top-4 right-4 glass-dark rounded-full px-3 py-1 text-white text-sm font-semibold">
                        <span x-text="car.daily_price"></span> zł/dzień
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">
                        <span x-text="car.brand"></span> <span x-text="car.model"></span>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                        <span x-text="car.category?.name"></span> • <span x-text="car.year"></span>
                    </p>
                    
                    <!-- Quick specs -->
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-6">
                        <span>⚡ <span x-text="car.specification?.horsepower || 'N/A'"></span> KM</span>
                        <span>⛽ <span x-text="car.specification?.fuel_type || 'N/A'"></span></span>
                        <span>🪑 <span x-text="car.specification?.seats || 'N/A'"></span></span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 mt-auto">
                        <!-- Link do szczegółów (Web Route) -->
                        <a :href="'/cars/' + car.id" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center text-sm font-semibold">
                            Szczegóły
                        </a>
                        
                        <!-- Przycisk Rezerwacji (Widoczny tylko dla zalogowanych) -->
                        <template x-if="isLoggedIn">
                            <a :href="'/dashboard/reservations/create?car_id=' + car.id" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center text-sm font-semibold">
                                Zarezerwuj
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </template>
        
        <!-- Empty State -->
        <div x-show="paginatedCars.length === 0" class="col-span-full glass rounded-xl p-12 text-center">
            <p class="text-gray-600 dark:text-gray-300 text-lg">
                Brak dostępnych samochodów spełniających kryteria.
            </p>
        </div>
    </div>

   <!-- Paginacja -->
    <div x-show="!loading && totalPages > 1" class="mt-16 flex justify-between items-center w-full pb-8 px-2">
        <button 
            @click="prevPage" 
            :disabled="currentPage === 1"
            class="px-6 py-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center gap-2 shadow-md font-semibold"
        >
            &laquo; Poprzednia
        </button>

        <!-- numery stron -->
        <div class="flex items-center justify-center">
            <span class="text-gray-600 dark:text-gray-400 font-medium text-lg">
                Strona <span x-text="currentPage" class="font-bold text-blue-600"></span> z <span x-text="totalPages" class="font-bold"></span>
            </span>
        </div>

        <button 
            @click="nextPage" 
            :disabled="currentPage === totalPages"
            class="px-6 py-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center gap-2 shadow-md font-semibold"
        >
            Następna &raquo;
        </button>
    </div>

@push('scripts')
<script>
function carCatalog(config) {
    return {
        cars: [],
        categories: [],
        loading: true,
        isLoggedIn: config.isLoggedIn,
        
        filters: {
            category_id: '',
            price_min: '',
            price_max: ''
        },

        currentPage: 1,
        itemsPerPage: 12,

        async init() {
            await this.loadCategories();
            await this.loadCars();
        },

        async loadCategories() {
            try {
                const apiUrl = '{{ url("/api/cars") }}';
                console.log('Fetching categories from:', apiUrl);
                
                const response = await axios.get(apiUrl);
                const rawData = response.data.data || response.data;
                
                if (!Array.isArray(rawData)) return;

                const uniqueCategories = [...new Map(rawData.map(car => 
                    [car.category.id, car.category]
                )).values()];
                this.categories = uniqueCategories;
            } catch (error) {
                console.error('Błąd kategorii:', error);
            }
        },

        async loadCars() {
            this.loading = true;
            try {
                const apiUrl = '{{ url("/api/cars") }}';
                console.log('Fetching cars from:', apiUrl);

                const response = await axios.get(apiUrl);
                const rawData = response.data.data || response.data;

                if (Array.isArray(rawData)) {
                    this.cars = rawData;
                    this.currentPage = 1;
                } else {
                    console.error('API format error:', response.data);
                    alert('Błąd danych API: Oczekiwano tablicy.');
                }
            } catch (error) {
                console.error('Błąd aut:', error);
                let msg = 'Nie udało się pobrać listy samochodów.';
                if (error.response) {
                    msg += ` (Błąd ${error.response.status}: ${error.response.statusText})`;
                    console.error('Szczegóły błędu:', error.response);
                }
                alert(msg);
            } finally {
                this.loading = false;
            }
        },

        filterCars() {
            this.currentPage = 1;
        },

        resetFilters() {
            this.filters.category_id = '';
            this.filters.price_min = '';
            this.filters.price_max = '';
            this.currentPage = 1;
        },

        get filteredCars() {
            if (!this.cars) return [];
            
            return this.cars.filter(car => {
                if (this.filters.category_id && car.category.id != this.filters.category_id) return false;
                if (this.filters.price_min && car.daily_price < parseFloat(this.filters.price_min)) return false;
                if (this.filters.price_max && car.daily_price > parseFloat(this.filters.price_max)) return false;
                return true;
            });
        },

        get paginatedCars() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredCars.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.filteredCars.length / this.itemsPerPage) || 1;
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.scrollToTop();
            }
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.scrollToTop();
            }
        },

        goToPage(page) {
            this.currentPage = page;
            this.scrollToTop();
        },

        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
}
</script>
@endpush
@endsection