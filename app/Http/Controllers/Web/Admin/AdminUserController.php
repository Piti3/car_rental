<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /*Lista użytkowników*/
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filtruj po roli
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Wyszukiwanie
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    /*Szczegóły użytkownika*/
    public function show(int $id)
    {
        $user = User::with(['reservations.car'])->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    /*Usuń użytkownika*/
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        
        // Nie pozwól usunąć samego siebie
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Nie możesz usunąć własnego konta.');
        }
        
        // Sprawdź czy ma aktywne rezerwacje
        if ($user->reservations()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Nie można usunąć użytkownika z aktywnymi rezerwacjami.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Użytkownik został usunięty.');
    }
}
