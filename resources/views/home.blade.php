@extends('layouts.app')

@section('title', 'Strona główna - Car Rental')

@section('content')
<div x-data="carGallery()">
    <!-- Hero Section -->
    <section class="text-center mb-12 animate-fade-in">
        <h1 class="text-5xl font-bold text-gray-800 dark:text-white mb-4">
            Wypożycz samochód marzeń
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
            Szeroki wybór samochodów premium dostępnych od ręki!
        </p>
        
        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto glass rounded-lg p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <input 
                    type="date" 
                    x-model="filters.startDate"
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="Data od"
                >
                <input 
                    type="date" 
                    x-model="filters.endDate"
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    placeholder="Data do"
                >
                <button 
                    @click="searchCars()"
                    class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    Szukaj
                </button>
            </div>
        </div>
    </section>
    
    <!-- Filters -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-4 justify-center">
            <button 
                @click="filterCategory(null)" 
                :class="filters.category === null ? 'bg-blue-600 text-white' : 'bg-gray-600 dark:text-gray-300'"
                class="px-6 py-2 rounded-full transition hover:scale-105"
            >
                Wszystkie
            </button>
            <template x-for="category in categories" :key="category.id">
                <button 
                    @click="filterCategory(category.id)" 
                    :class="filters.category === category.id ? 'bg-blue-600 text-white' : 'bg-gray-600 dark:text-gray-300'"
                    class="px-6 py-2 rounded-full transition hover:scale-105"
                    x-text="category.name"
                ></button>
            </template>
        </div>
    </div>
    
    <!-- Loading State -->
    <div x-show="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-4 text-gray-600">Ładowanie samochodów...</p>
    </div>
    
    <!-- Car Grid -->
    <!-- Iterujemy po 'paginatedCars' -->
    <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 min-h-[500px]">
        <template x-for="car in paginatedCars" :key="car.id">
            <div 
                @click="openCarDetails(car)"
                class="glass rounded-xl overflow-hidden card-hover cursor-pointer animate-slide-up"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
            >
                <!-- Image -->
                <div class="relative h-48 bg-gradient-to-br from-blue-400 to-purple-500 overflow-hidden">
                    <img 
                        :src="car.image_path || '/images/placeholder-car.jpg'" 
                        :alt="car.brand + ' ' + car.model"
                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                    >
                    <div class="absolute top-4 right-4 glass-dark rounded-full px-3 py-1 text-white text-sm font-semibold">
                        <span x-text="car.daily_price"></span> zł/dzień
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">
                        <span x-text="car.brand"></span> <span x-text="car.model"></span>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                        <span x-text="car.category?.name"></span> • <span x-text="car.year"></span>
                    </p>
                    
                    <!-- Quick specs -->
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>⚡ <span x-text="car.specification?.horsepower"></span> KM</span>
                        <span>⛽ <span x-text="car.specification?.fuel_type"></span></span>
                        <span>🪑 <span x-text="car.specification?.seats"></span> osób</span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Sekcja Przycisków Paginacji -->
    <div x-show="!loading && totalPages > 1" class="mt-16 flex justify-between items-center w-full pb-8 px-2">
        <!-- Przycisk Poprzednia  -->
        <button 
            @click="prevPage" 
            :disabled="currentPage === 1"
            class="px-6 py-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center gap-2 shadow-md font-semibold"
        >
            <span>&laquo;</span> Poprzednia
        </button>

        <!-- Numery stron  -->
        <div class="flex gap-1 overflow-x-auto max-w-[200px] md:max-w-none px-2">
            <template x-for="page in totalPages" :key="page">
                <button 
                    @click="goToPage(page)"
                    x-text="page"
                    :class="currentPage === page 
                        ? 'bg-blue-600 text-white border-blue-600 shadow-md transform scale-105' 
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'"
                    class="w-10 h-10 rounded-lg border flex-shrink-0 flex items-center justify-center transition font-medium hidden md:flex"
                ></button>
            </template>
            <!-- Wersja mobilna licznika -->
            <span class="md:hidden text-gray-600 dark:text-gray-400 font-medium">
                Strona <span x-text="currentPage"></span> z <span x-text="totalPages"></span>
            </span>
        </div>

        <!-- Przycisk Następna-->
        <button 
            @click="nextPage" 
            :disabled="currentPage === totalPages"
            class="px-6 py-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center gap-2 shadow-md font-semibold"
        >
            Następna <span>&raquo;</span>
        </button>
    </div>
    
    <!-- Popup Modal -->
    <div 
        x-show="selectedCar !== null" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
        @click.self="closeCarDetails()"
    >
        <!-- Blur Background -->
        <div class="absolute inset-0 bg-black/50 blur-bg"></div>
        
        <!-- Modal Content -->
        <div 
            x-show="selectedCar" 
            class="relative glass rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto animate-expand"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
        >
            <!-- Close Button -->
            <button 
                @click="closeCarDetails()"
                class="absolute top-4 right-4 z-10 glass-dark rounded-full p-2 text-white hover:bg-red-500 transition"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <template x-if="selectedCar">
                <div>
                    <!-- Image -->
                    <div class="relative h-64 bg-gradient-to-br from-blue-400 to-purple-500">
                        <img 
                            :src="selectedCar.image_path || '/images/placeholder-car.jpg'" 
                            :alt="selectedCar.brand + ' ' + selectedCar.model"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    
                    <!-- Content -->
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">
                            <span x-text="selectedCar.brand"></span> <span x-text="selectedCar.model"></span>
                        </h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">Moc</p>
                                <p class="text-xl font-bold text-white"><span x-text="selectedCar.specification?.horsepower"></span> KM</p>
                            </div>
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">Pojemność</p>
                                <p class="text-xl font-bold text-white"><span x-text="selectedCar.specification?.engine_capacity"></span> L</p>
                            </div>
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">0-100 km/h</p>
                                <p class="text-xl font-bold text-white"><span x-text="selectedCar.specification?.acceleration_0_100"></span> s</p>
                            </div>
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">Paliwo</p>
                                <p class="text-xl font-bold text-white" x-text="selectedCar.specification?.fuel_type"></p>
                            </div>
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">Skrzynia</p>
                                <p class="text-xl font-bold text-white" x-text="selectedCar.specification?.transmission"></p>
                            </div>
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">Miejsca</p>
                                <p class="text-xl font-bold text-white"><span x-text="selectedCar.specification?.seats"></span> osób</p>
                            </div>
                        </div>
                        
                        <!-- Fun Fact -->
                        <div class="glass rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">💡 Ciekawostka</h3>
                            <p class="text-gray-600 dark:text-gray-300" x-text="selectedCar.specification?.fun_fact"></p>
                        </div>
                        
                        <!-- Price & CTA -->
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Cena za dzień</p>
                                <p class="text-3xl font-bold text-blue-600"><span x-text="selectedCar.daily_price"></span> zł</p>
                            </div>
                            <button 
                                @click="reserveCar(selectedCar.id)"
                                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
                            >
                                Zarezerwuj teraz
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function carGallery() {
    return {
        cars: [],
        categories: [],
        selectedCar: null,
        loading: true,
        filters: {
            category: null,
            startDate: null,
            endDate: null,
        },
        
        // --- Konfiguracja Paginacji ---
        currentPage: 1,
        itemsPerPage: 12, // Wyświetlamy max 15 aut
        
        async init() {
            await this.loadCategories();
            await this.loadCars();
        },
        
        async loadCategories() {
            try {
                // Tutaj uderzamy do endpointu, który zwraca JSON
                const response = await axios.get('/cars');
                const uniqueCategories = [...new Map(response.data.data.map(car => 
                    [car.category.id, car.category]
                )).values()];
                this.categories = uniqueCategories;
            } catch (error) {
                console.error('Błąd ładowania kategorii:', error);
            }
        },
        
        async loadCars() {
            this.loading = true;
            try {
                // Tutaj uderzamy do endpointu, który zwraca JSON
                const response = await axios.get('/cars');
                this.cars = response.data.data;
                this.currentPage = 1; 
            } catch (error) {
                console.error('Błąd ładowania samochodów:', error);
                alert('Nie udało się załadować samochodów');
            } finally {
                this.loading = false;
            }
        },
        
        filterCategory(categoryId) {
            this.filters.category = categoryId;
            this.currentPage = 1; 
        },
        
        // Pobiera WSZYSTKIE pasujące auta
        get filteredCars() {
            if (this.filters.category === null) {
                return this.cars;
            }
            return this.cars.filter(car => car.category_id === this.filters.category);
        },

        // Zwraca x pojazdów
        get paginatedCars() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredCars.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.filteredCars.length / this.itemsPerPage);
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
        },
        
        openCarDetails(car) {
            this.selectedCar = car;
            document.body.style.overflow = 'hidden'; 
        },
        
        closeCarDetails() {
            this.selectedCar = null;
            document.body.style.overflow = ''; 
        },
        
        reserveCar(carId) {
            window.location.href = `/cars/${carId}/`;
        },
        
        async searchCars() {
            if (!this.filters.startDate || !this.filters.endDate) {
                alert('Wybierz daty rezerwacji');
                return;
            }
            
            this.loading = true;
            try {
                const response = await axios.get('/cars/available', {
                    params: {
                        start_date: this.filters.startDate,
                        end_date: this.filters.endDate
                    }
                });
                this.cars = response.data.data;
                this.currentPage = 1; // Reset po wyszukiwaniu
            } catch (error) {
                console.error('Błąd wyszukiwania:', error);
                alert('Nie udało się wyszukać samochodów');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
@endsection