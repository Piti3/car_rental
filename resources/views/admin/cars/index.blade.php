@extends('layouts.admin')

@section('title', 'Zarządzanie samochodami - Panel Admina')
@section('page-title', 'Zarządzanie samochodami')

@section('content')
<div x-data="adminCarManagement()" class="space-y-6">
    <!-- Header z przyciskiem dodawania -->
    <div class="flex justify-between items-center">
        <p class="text-gray-400">Wszystkie samochody w systemie</p>
        <a href="{{ route('admin.cars.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
            + Dodaj samochód
        </a>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-4 text-gray-400">Ładowanie danych...</p>
    </div>

    <!-- Grid samochodów -->
    <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 min-h-[400px]">
        <template x-for="car in paginatedCars" :key="car.id">
            <div class="glass-dark rounded-xl overflow-hidden flex flex-col">
                <!-- Image -->
                <div class="h-48 bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center relative overflow-hidden group">
                    <template x-if="car.image_path">
                        <img :src="car.image_path" :alt="car.brand" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    </template>
                    <template x-if="!car.image_path">
                        <span class="text-6xl select-none">🚗</span>
                    </template>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-4 flex-1 flex flex-col">
                    <div>
                        <h3 class="text-xl font-bold text-white" x-text="car.brand + ' ' + car.model"></h3>
                        <p class="text-gray-400 text-sm">
                            <span x-text="car.category ? car.category.name : 'Brak kategorii'"></span> • <span x-text="car.year"></span>
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold"
                              :class="{
                                  'bg-green-500/20 text-green-400': car.status === 'available',
                                  'bg-yellow-500/20 text-yellow-400': car.status === 'rented',
                                  'bg-red-500/20 text-red-400': car.status !== 'available' && car.status !== 'rented'
                              }">
                            <span x-text="car.status === 'available' ? 'Dostępny' : (car.status === 'rented' ? 'Wynajęty' : 'Serwis')"></span>
                        </span>
                        <span class="text-white font-bold" x-text="car.daily_price + ' zł/dzień'"></span>
                    </div>

                    <div class="flex gap-2 mt-auto pt-2">
                        <a :href="'/admin/cars/' + car.id + '/edit'" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center text-sm">
                            Edytuj
                        </a>
                        <button 
                            @click="deleteCar(car.id)"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-center text-sm"
                        >
                            Usuń
                        </button>
                    </div>
                </div>
            </div>
        </template>
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
function adminCarManagement() {
    return {
        cars: [],
        loading: true,
        currentPage: 1,
        itemsPerPage: 12,

        async init() {
            await this.loadCars();
        },

        async loadCars() {
            this.loading = true;
            try {
                const apiUrl = '{{ url("/api/cars") }}';
                const response = await axios.get(apiUrl);
                const rawData = response.data.data || response.data;
                
                if (Array.isArray(rawData)) {
                    this.cars = rawData;
                } else {
                    console.error('Nieprawidłowy format danych:', response.data);
                }
            } catch (error) {
                console.error('Błąd:', error);
                alert('Nie udało się pobrać listy samochodów.');
            } finally {
                this.loading = false;
            }
        },

        get paginatedCars() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.cars.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.cars.length / this.itemsPerPage) || 1;
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

        async deleteCar(id) {
            if (!confirm('Czy na pewno chcesz usunąć ten samochód?')) {
                return;
            }

            try {
                await axios.delete('{{ url("/admin/cars") }}/' + id);
                this.cars = this.cars.filter(car => car.id !== id);
                
                if (this.paginatedCars.length === 0 && this.currentPage > 1) {
                    this.currentPage--;
                }
            } catch (error) {
                console.error('Błąd usuwania:', error);
                alert('Błąd podczas usuwania.');
            }
        }
    }
}
</script>
@endpush
@endsection