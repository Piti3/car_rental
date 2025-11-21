@extends('layouts.client')

@section('title', 'Mój profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <h1 class="text-4xl font-bold text-gray-800 dark:text-white">
        Mój profil
    </h1>

    <!-- Personal Data -->
    <div class="glass rounded-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Dane osobowe</h2>
        
        <form action="{{ route('client.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Imię i nazwisko *
                    </label>
                    <input 
                        type="text" 
                        value="{{ $user->name }}"
                        disabled
                        class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300 cursor-not-allowed"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Email *
                    </label>
                    <input 
                        type="email" 
                        value="{{ $user->email }}"
                        disabled
                        class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300 cursor-not-allowed "
                    >
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Telefon
                </label>
                <input 
                    type="tel" 
                    name="phone" 
                    value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                >
            </div>

            <button 
                type="submit"
                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Zapisz zmiany
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="glass rounded-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Zmiana hasła</h2>
        
        <form action="{{ route('client.profile.password') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Aktualne hasło *
                </label>
                <input 
                    type="password" 
                    name="current_password" 
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                >
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Nowe hasło *
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Potwierdź hasło *
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        required
                        class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-300"
                    >
                </div>
            </div>

            <button 
                type="submit"
                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Zmień hasło
            </button>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="glass bg-red-500/10 border border-red-500/30 rounded-xl p-8">
        <h2 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-4">Usuń konto</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-6">
            Po usunięciu konta wszystkie Twoje dane zostaną trwale usunięte. Ta operacja jest nieodwracalna.
        </p>
        
        <form action="{{ route('client.profile.destroy') }}" method="POST" class="space-y-4">
            @csrf
            @method('DELETE')

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Potwierdź hasłem *
                </label>
                <input 
                    type="password" 
                    name="password" 
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-900 dark:text-gray-300"
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button 
                type="submit"
                onclick="return confirm('Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna!')"
                class="px-8 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold"
            >
                Usuń moje konto
            </button>
        </form>
    </div>
</div>
@endsection
