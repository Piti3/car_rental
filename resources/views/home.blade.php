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
                    class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    Szukaj
                </button>
            </div>
        </div>
    </section>
    
    <!-- Category Filters -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-4 justify-center">
            <button 
                @click="filterCategory(null)" 
                :class="filters.category === null ? 'bg-blue-600 text-white' : 'bg-gray-600 dark:text-gray-300'"
                class="px-6 py-2 rounded-full transition hover:scale-105 font-medium"
            >
                Wszystkie
            </button>
            <template x-for="category in categories" :key="category.id">
                <button 
                    @click="filterCategory(category.id)" 
                    :class="filters.category === category.id ? 'bg-blue-600 text-white' : 'bg-gray-600 dark:text-gray-300'"
                    class="px-6 py-2 rounded-full transition hover:scale-105 font-medium"
                    x-text="category.name"
                ></button>
            </template>
        </div>
    </div>
    
    <!-- Loading State -->
    <div x-show="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-4 text-gray-600 dark:text-gray-400">Ładowanie samochodów...</p>
    </div>
    
    <!-- Car Grid -->
    <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 min-h-[500px]">
        <template x-for="car in paginatedCars" :key="car.id">
            <div 
                @click="openCarDetails(car)"
                class="glass rounded-xl overflow-hidden card-hover cursor-pointer animate-slide-up flex flex-col"
            >
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
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-auto">
                        <span>⚡ <span x-text="car.specification?.horsepower || '-'"></span> KM</span>
                        <span>⛽ <span x-text="car.specification?.fuel_type || '-'"></span></span>
                        <span>🪑 <span x-text="car.specification?.seats || '-'"></span> osób</span>
                    </div>
                </div>
            </div>
        </template>
        
        <!-- Empty State -->
        <div x-show="!loading && paginatedCars.length === 0" class="col-span-full text-center py-12">
            <p class="text-gray-500 text-xl">Brak samochodów spełniających kryteria.</p>
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

        <div class="flex gap-1 overflow-x-auto px-2">
            <template x-for="page in totalPages" :key="page">
                <button 
                    @click="goToPage(page)"
                    x-text="page"
                    :class="currentPage === page 
                        ? 'bg-blue-600 text-white border-blue-600 shadow-md scale-105' 
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'"
                    class="w-10 h-10 rounded-lg border flex-shrink-0 flex items-center justify-center transition font-medium hidden md:flex"
                ></button>
            </template>
            <span class="md:hidden text-gray-600 dark:text-gray-400 font-medium">
                Strona <span x-text="currentPage"></span> z <span x-text="totalPages"></span>
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
        <div class="absolute inset-0 bg-black/50 blur-bg"></div>
        
        <div 
            x-show="selectedCar" 
            class="relative glass rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto animate-expand"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
        >
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
                    <div class="relative h-64 bg-gradient-to-br from-blue-400 to-purple-500">
                        <template x-if="selectedCar.image_path">
                            <img :src="selectedCar.image_path" :alt="selectedCar.brand" class="w-full h-full object-cover">
                        </template>
                    </div>
                    
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">
                            <span x-text="selectedCar.brand"></span> <span x-text="selectedCar.model"></span>
                        </h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">Moc</p>
                                <p class="text-xl font-bold text-white"><span x-text="selectedCar.specification?.horsepower"></span> KM</p>
                            </div>
                            <!-- ... reszta specyfikacji ... -->
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">Paliwo</p>
                                <p class="text-xl font-bold text-white" x-text="selectedCar.specification?.fuel_type"></p>
                            </div>
                            <div class="glass-dark rounded-lg p-4">
                                <p class="text-sm text-gray-400">Skrzynia</p>
                                <p class="text-xl font-bold text-white" x-text="selectedCar.specification?.transmission"></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-8">
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
        
        // Filtry
        filters: {
            category: null,
            startDate: null,
            endDate: null,
        },
        
        // Paginacja
        currentPage: 1,
        itemsPerPage: 12,
        
        async init() {
            await this.loadCategories();
            await this.loadCars();
        },
        
        async loadCategories() {
            try {
                const apiUrl = '{{ url("/api/cars") }}';
                const response = await axios.get(apiUrl);
                const rawData = response.data.data || response.data;
                
                if (!Array.isArray(rawData)) return;

                const uniqueCategories = [...new Map(rawData.map(car => 
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
                const apiUrl = '{{ url("/api/cars") }}';
                console.log('Ładowanie aut z:', apiUrl);
                const response = await axios.get(apiUrl);
                
                const rawData = response.data.data || response.data;
                if (Array.isArray(rawData)) {
                    this.cars = rawData;
                    this.currentPage = 1;
                } else {
                    console.error('Zły format danych:', response.data);
                }
            } catch (error) {
                console.error('Błąd ładowania samochodów:', error);
                alert('Nie udało się załadować listy samochodów. Sprawdź konsolę.');
            } finally {
                this.loading = false;
            }
        },
        
        filterCategory(categoryId) {
            this.filters.category = categoryId;
            this.currentPage = 1;
        },
        
        async searchCars() {
            if (!this.filters.startDate || !this.filters.endDate) {
                alert('Wybierz daty rezerwacji');
                return;
            }
            
            this.loading = true;
            try {
                // FIX 4: Poprawny URL do wyszukiwarki
                const apiUrl = '{{ url("/api/cars/available") }}';
                console.log('Wyszukiwanie aut:', apiUrl, this.filters);

                const response = await axios.get(apiUrl, {
                    params: {
                        start_date: this.filters.startDate,
                        end_date: this.filters.endDate
                    }
                });
                
                // Obsługa różnych formatów odpowiedzi
                const rawData = response.data.data || response.data;
                
                if (Array.isArray(rawData)) {
                    this.cars = rawData;
                    this.currentPage = 1;
                } else {
                    console.error('Błędny format danych wyszukiwania:', response.data);
                    alert('Serwer zwrócił nieoczekiwane dane.');
                }
            } catch (error) {
                console.error('Błąd wyszukiwania:', error);
                
                let msg = 'Nie udało się wyszukać samochodów.';
                
                if (error.response) {
                    // Błąd HTTP (np. 404, 422, 500)
                    msg += ` (Błąd ${error.response.status})`;
                    
                    if (error.response.status === 404) {
                        msg += '\nNie znaleziono endpointu API.';
                    } else if (error.response.data && error.response.data.message) {
                        // Komunikat z backendu
                        msg += '\n' + error.response.data.message;
                    }
                } else if (error.request) {
                    // Brak odpowiedzi z serwera
                    msg += '\nBrak odpowiedzi z serwera.';
                } else {
                    msg += '\n' + error.message;
                }
                
                alert(msg);
            } finally {
                this.loading = false;
            }
        },

        // Pobiera WSZYSTKIE pasujące auta
        get filteredCars() {
            if (this.filters.category === null) {
                return this.cars;
            }
            return this.cars.filter(car => {
                // Obsługa category jako obiekt lub ID
                if (car.category && car.category.id == this.filters.category) return true;
                if (car.category_id == this.filters.category) return true;
                return false;
            });
        },

        // Paginacja
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
            window.location.href = '{{ url("/dashboard/reservations/create") }}?car_id=' + carId;
        }
    }
}
</script>
@endpush
@endsection