<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ClientProfileController extends Controller
{
    /*Pokaż profil użytkownika*/
    public function show()
    {
        $user = Auth::user();
        
        return view('client.profile.show', compact('user'));
    }

    /*Aktualizuj dane profilu*/
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Dane zostały zaktualizowane pomyślnie.');
    }

    /*Zmień hasło*/
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Sprawdź aktualne hasło
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Podane hasło jest nieprawidłowe.']);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Hasło zostało zmienione pomyślnie.');
    }

    /*Usuń konto*/
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        // Sprawdź hasło
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Podane hasło jest nieprawidłowe.']);
        }

        // Sprawdź czy ma aktywne rezerwacje
        if ($user->reservations()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Nie możesz usunąć konta z aktywnymi rezerwacjami. Najpierw anuluj rezerwacje.');
        }

        Auth::logout();
        $user->delete();

        return redirect()->route('home')
            ->with('success', 'Konto zostało usunięte pomyślnie.');
    }
}
