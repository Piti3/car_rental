@extends('layouts.admin')

@section('title', 'Zarządzanie użytkownikami - Panel Admina')
@section('page-title', 'Zarządzanie użytkownikami')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <form method="GET" class="glass-dark rounded-xl p-6 flex gap-4">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}"
            placeholder="Szukaj po nazwie lub emailu..."
            class="flex-1 px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <select 
            name="role"
            class="px-4 py-3 rounded-lg bg-white/5 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value="">Wszystkie role</option>
            <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Klienci</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administratorzy</option>
        </select>
        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Szukaj
        </button>
    </form>

    <!-- Users Table -->
    <div class="glass-dark rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Użytkownik</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Rola</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Telefon</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Rezerwacje</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Akcje</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-white font-semibold">#{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-white font-semibold">{{ $user->name }}</p>
                                    <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $user->role === 'admin' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400' }}
                                ">
                                    {{ $user->role === 'admin' ? 'Administrator' : 'Klient' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">{{ $user->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $user->reservations->count() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                                        Zobacz
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit"
                                                onclick="return confirm('Czy na pewno chcesz usunąć tego użytkownika?')"
                                                class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition"
                                            >
                                                Usuń
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                Brak użytkowników
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
