<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class ClientProfileApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Pobierz profil zalogowanego użytkownika",
     *     description="Zwraca informacje o profilu aktualnie zalogowanego klienta",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dane profilu",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=17),
     *                 @OA\Property(property="name", type="string", example="Jan Kowalski"),
     *                 @OA\Property(property="email", type="string", example="jan@example.com"),
     *                 @OA\Property(property="phone", type="string", example="+48123456789"),
     *                 @OA\Property(property="role", type="string", example="client"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function show(): JsonResponse
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'created_at' => $user->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Aktualizuj dane profilu",
     *     description="Aktualizuje dane profilu użytkownika (imię, email, telefon)",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jan Nowak"),
     *             @OA\Property(property="email", type="string", format="email", example="jan.nowak@example.com"),
     *             @OA\Property(property="phone", type="string", example="+48987654321")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil zaktualizowany",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profil został zaktualizowany pomyślnie."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Błąd walidacji"),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $validated = $request->validated();

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil został zaktualizowany pomyślnie.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/profile/password",
     *     summary="Zmień hasło",
     *     description="Zmienia hasło użytkownika (wymaga podania aktualnego hasła)",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password","password","password_confirmation"},
     *             @OA\Property(property="current_password", type="string", format="password", example="oldpassword123", description="Aktualne hasło"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123", minLength=8, description="Nowe hasło"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123", description="Potwierdzenie nowego hasła")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hasło zmienione",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Hasło zostało zmienione pomyślnie.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Błąd walidacji lub nieprawidłowe aktualne hasło",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Aktualne hasło jest nieprawidłowe."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $validated = $request->validated();

        // Sprawdź aktualne hasło
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Aktualne hasło jest nieprawidłowe.',
                'errors' => ['current_password' => 'Hasło jest nieprawidłowe.'],
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hasło zostało zmienione pomyślnie.',
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/profile",
     *     summary="Usuń konto",
     *     description="Usuwa konto użytkownika (wymaga potwierdzenia hasłem, nie można usunąć z aktywnymi rezerwacjami)",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="Hasło do potwierdzenia")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Konto usunięte",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Konto zostało usunięte pomyślnie.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Błąd - nieprawidłowe hasło lub aktywne rezerwacje",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Nie możesz usunąć konta z aktywnymi rezerwacjami.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth('api')->user();

        // Sprawdź hasło
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Hasło jest nieprawidłowe.',
            ], 422);
        }

        // Sprawdź czy ma aktywne rezerwacje
        if ($user->reservations()->whereIn('status', ['pending', 'approved'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Nie możesz usunąć konta z aktywnymi rezerwacjami. Najpierw anuluj rezerwacje.',
            ], 422);
        }

        $user->delete();
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Konto zostało usunięte pomyślnie.',
        ]);
    }
}
