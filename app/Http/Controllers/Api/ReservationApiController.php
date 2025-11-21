<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Services\ReservationService;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ReservationApiController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="Lista rezerwacji zalogowanego użytkownika",
     *     description="Zwraca wszystkie rezerwacje aktualnie zalogowanego klienta",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista rezerwacji",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Reservation")),
     *             @OA\Property(property="count", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function index(): JsonResponse
    {
        $userId = auth('api')->id();
        
        $reservations = $this->reservationService->getUserReservations($userId);

        return response()->json([
            'success' => true,
            'data' => ReservationResource::collection($reservations),
            'count' => $reservations->count(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Utwórz nową rezerwację",
     *     description="Tworzy nową rezerwację samochodu (status: pending - oczekuje na zatwierdzenie przez admina)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"car_id","start_date","end_date"},
     *             @OA\Property(property="car_id", type="integer", example=1, description="ID samochodu do wynajęcia"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-11-15", description="Data rozpoczęcia wynajmu"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-11-20", description="Data zakończenia wynajmu"),
     *             @OA\Property(property="notes", type="string", example="Rezerwacja na wakacje", description="Opcjonalne uwagi do rezerwacji")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rezerwacja utworzona pomyślnie",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rezerwacja została utworzona. Oczekuje na zaakceptowanie przez administratora."),
     *             @OA\Property(property="data", ref="#/components/schemas/Reservation")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Błąd walidacji lub samochód niedostępny"),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function store(StoreReservationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = auth('api')->id();

            $reservation = $this->reservationService->createReservation($validated);

            return response()->json([
                'success' => true,
                'message' => 'Rezerwacja została utworzona. Oczekuje na zaakceptowanie przez administratora.',
                'data' => new ReservationResource($reservation),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/{id}",
     *     summary="Szczegóły rezerwacji",
     *     description="Zwraca szczegóły konkretnej rezerwacji (tylko własne rezerwacje)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID rezerwacji",
     *         required=true,
     *         @OA\Schema(type="integer", example=14)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Szczegóły rezerwacji",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Reservation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rezerwacja nie znaleziona",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Rezerwacja nie znaleziona.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $userId = auth('api')->id();
        $reservation = Reservation::where('id', $id)
            ->where('user_id', $userId)
            ->with(['car.category', 'car.specification'])
            ->first();

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Rezerwacja nie znaleziona.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ReservationResource($reservation),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/reservations/{id}/cancel",
     *     summary="Anuluj rezerwację",
     *     description="Anuluje rezerwację (tylko rezerwacje pending lub approved)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID rezerwacji do anulowania",
     *         required=true,
     *         @OA\Schema(type="integer", example=14)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rezerwacja anulowana",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rezerwacja została anulowana."),
     *             @OA\Property(property="data", ref="#/components/schemas/Reservation")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Rezerwacja nie znaleziona"),
     *     @OA\Response(response=422, description="Nie można anulować tej rezerwacji"),
     *     @OA\Response(response=401, description="Brak autoryzacji")
     * )
     */
    public function cancel(int $id): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $reservation = Reservation::where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rezerwacja nie znaleziona.',
                ], 404);
            }

            $this->reservationService->cancelReservation($id);

            return response()->json([
                'success' => true,
                'message' => 'Rezerwacja została anulowana.',
                'data' => new ReservationResource($reservation->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/check-availability",
     *     summary="Sprawdź dostępność samochodu w terminie",
     *     description="Sprawdza czy samochód jest dostępny w wybranym okresie i oblicza cenę",
     *     tags={"Reservations"},
     *     @OA\Parameter(
     *         name="car_id",
     *         in="query",
     *         description="ID samochodu",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Data rozpoczęcia (format: YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="2025-11-15")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Data zakończenia (format: YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="2025-11-20")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informacja o dostępności",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="available", type="boolean", example=true, description="Czy samochód jest dostępny"),
     *             @OA\Property(property="car_id", type="integer", example=1),
     *             @OA\Property(property="start_date", type="string", example="2025-11-15"),
     *             @OA\Property(property="end_date", type="string", example="2025-11-20"),
     *             @OA\Property(property="days", type="integer", example=5, description="Liczba dni wynajmu"),
     *             @OA\Property(property="daily_price", type="number", example=250.00, description="Cena za dzień"),
     *             @OA\Property(property="total_price", type="number", example=1250.00, description="Całkowity koszt")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Błąd walidacji")
     * )
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);

            $isAvailable = $this->reservationService->isCarAvailableInPeriod(
                $validated['car_id'],
                $startDate,
                $endDate
            );

            $car = \App\Models\Car::find($validated['car_id']);
            $days = $startDate->diffInDays($endDate);
            $totalPrice = $days * $car->daily_price;

            return response()->json([
                'success' => true,
                'available' => $isAvailable,
                'car_id' => $validated['car_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'days' => $days,
                'daily_price' => $car->daily_price,
                'total_price' => round($totalPrice, 2),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
