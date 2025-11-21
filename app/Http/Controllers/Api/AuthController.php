<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="Car Rental API",
 *     version="1.0.0",
 *     description="REST API dla systemu wypożyczalni samochodów. Autentykacja JWT.",
 *     @OA\Contact(
 *         name="Car Rental Support",
 *         email="support@carrental.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8080",
 *     description="Local Development Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Użyj JWT tokenu otrzymanego z /api/auth/login. Format: Bearer {token}"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpointy do rejestracji, logowania i zarządzania sesją"
 * )
 * 
 * @OA\Tag(
 *     name="Cars",
 *     description="Endpointy do przeglądania i filtrowania samochodów"
 * )
 * 
 * @OA\Tag(
 *     name="Reservations",
 *     description="Endpointy do zarządzania rezerwacjami"
 * )
 * 
 * @OA\Tag(
 *     name="Profile",
 *     description="Endpointy do zarządzania profilem użytkownika"
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="Model użytkownika",
 *     @OA\Property(property="id", type="integer", example=17),
 *     @OA\Property(property="name", type="string", example="Jan Kowalski"),
 *     @OA\Property(property="email", type="string", format="email", example="jan@example.com"),
 *     @OA\Property(property="phone", type="string", example="+48123456789", nullable=true),
 *     @OA\Property(property="role", type="string", example="client", enum={"admin", "client"}),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-07T10:28:42.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-07T10:28:42.000000Z")
 * )
 * 
 * @OA\Schema(
 *     schema="Car",
 *     type="object",
 *     title="Car",
 *     description="Model samochodu",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="brand", type="string", example="Toyota"),
 *     @OA\Property(property="model", type="string", example="Camry"),
 *     @OA\Property(property="year", type="integer", example=2023),
 *     @OA\Property(property="daily_price", type="number", format="float", example=250.00),
 *     @OA\Property(property="status", type="string", example="available", enum={"available", "rented", "maintenance"}),
 *     @OA\Property(property="category", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Sedan")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Reservation",
 *     type="object",
 *     title="Reservation",
 *     description="Model rezerwacji",
 *     @OA\Property(property="id", type="integer", example=14),
 *     @OA\Property(property="user_id", type="integer", example=17),
 *     @OA\Property(property="car_id", type="integer", example=1),
 *     @OA\Property(property="start_date", type="string", format="date", example="2025-11-15"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2025-11-20"),
 *     @OA\Property(property="days", type="integer", example=5),
 *     @OA\Property(property="status", type="string", example="pending", enum={"pending", "approved", "cancelled", "completed"}),
 *     @OA\Property(property="total_price", type="number", format="float", example=1250.00),
 *     @OA\Property(property="notes", type="string", example="Rezerwacja na wakacje", nullable=true),
 *     @OA\Property(property="car", ref="#/components/schemas/Car"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Rejestracja nowego użytkownika",
     *     description="Tworzy nowe konto klienta i zwraca JWT token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Jan Kowalski", description="Pełne imię i nazwisko"),
     *             @OA\Property(property="email", type="string", format="email", example="jan@example.com", description="Unikalny adres email"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", minLength=8, description="Hasło (min. 8 znaków)"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123", description="Potwierdzenie hasła"),
     *             @OA\Property(property="phone", type="string", example="+48123456789", description="Numer telefonu (opcjonalny)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Użytkownik zarejestrowany pomyślnie",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rejestracja zakończona sukcesem."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGc..."),
     *                 @OA\Property(property="token_type", type="string", example="bearer"),
     *                 @OA\Property(property="expires_in", type="integer", example=86400, description="Czas życia tokenu w sekundach")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Błąd walidacji danych",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="Email jest już zajęty."))
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'phone' => $request->phone,
        ]);

        // Wygeneruj token JWT
        $token = auth('api')->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Rejestracja zakończona sukcesem.',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Logowanie użytkownika",
     *     description="Autentykacja użytkownika i generowanie JWT tokenu",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="jan7@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Logowanie pomyślne",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logowanie zakończone sukcesem."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGc..."),
     *                 @OA\Property(property="token_type", type="string", example="bearer"),
     *                 @OA\Property(property="expires_in", type="integer", example=86400)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Nieprawidłowe dane logowania",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Nieprawidłowy email lub hasło.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Błąd walidacji",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Nieprawidłowy email lub hasło.'
            ], 401);
        }

        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Logowanie zakończone sukcesem.',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Wylogowanie użytkownika",
     *     description="Unieważnia aktualny JWT token",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Wylogowano pomyślnie",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Wylogowano pomyślnie.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Brak autoryzacji",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Token jest nieprawidłowy")
     *         )
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Wylogowano pomyślnie.'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     summary="Odświeżenie tokenu JWT",
     *     description="Generuje nowy token JWT na podstawie starego",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token odświeżony",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGc..."),
     *                 @OA\Property(property="token_type", type="string", example="bearer"),
     *                 @OA\Property(property="expires_in", type="integer", example=86400)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Token wygasł lub jest nieprawidłowy")
     * )
     */
    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Pobierz dane zalogowanego użytkownika",
     *     description="Zwraca informacje o aktualnie zalogowanym użytkowniku",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dane użytkownika",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function me(): JsonResponse
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
