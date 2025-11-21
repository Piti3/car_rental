<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Services\CarService;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class CarApiController extends Controller
{
    public function __construct(
        private CarService $carService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/cars",
     *     summary="Lista wszystkich samochodów",
     *     description="Zwraca listę samochodów z możliwością filtrowania",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filtruj po kategorii samochodu",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="price_min",
     *         in="query",
     *         description="Minimalna cena dzienna",
     *         required=false,
     *         @OA\Schema(type="number", example=100.00)
     *     ),
     *     @OA\Parameter(
     *         name="price_max",
     *         in="query",
     *         description="Maksymalna cena dzienna",
     *         required=false,
     *         @OA\Schema(type="number", example=500.00)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status samochodu",
     *         required=false,
     *         @OA\Schema(type="string", enum={"available", "rented", "maintenance"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista samochodów",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Car")),
     *             @OA\Property(property="count", type="integer", example=10)
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category_id', 'price_min', 'price_max', 'status']);
        
        $cars = $this->carService->getAllCars($filters);

        return response()->json([
            'success' => true,
            'data' => CarResource::collection($cars),
            'count' => $cars->count(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/cars/available",
     *     summary="Samochody dostępne w wybranym terminie",
     *     description="Zwraca listę samochodów dostępnych do wynajęcia w określonym okresie",
     *     tags={"Cars"},
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
     *         description="Lista dostępnych samochodów",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Car")),
     *             @OA\Property(property="count", type="integer", example=5),
     *             @OA\Property(property="filters", type="object",
     *                 @OA\Property(property="start_date", type="string", example="2025-11-15"),
     *                 @OA\Property(property="end_date", type="string", example="2025-11-20")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Błąd walidacji dat")
     * )
     */
    public function available(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $availableCars = $this->carService->getAvailableCars($startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => CarResource::collection($availableCars),
            'count' => $availableCars->count(),
            'filters' => [
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/cars/{id}",
     *     summary="Szczegóły samochodu",
     *     description="Zwraca szczegółowe informacje o samochodzie",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID samochodu",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Szczegóły samochodu",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Samochód nie znaleziony",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Samochód nie znaleziony.")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $car = $this->carService->getCarDetails($id);

            return response()->json([
                'success' => true,
                'data' => new CarResource($car),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Samochód nie znaleziony.',
            ], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cars/{id}/availability",
     *     summary="Kalendarz dostępności samochodu",
     *     description="Zwraca kalendarz z informacją o dostępności samochodu w wybranym okresie",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID samochodu",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Data początkowa kalendarza (domyślnie: dziś)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-11-10")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Data końcowa kalendarza (domyślnie: +3 miesiące)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2026-02-10")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kalendarz dostępności",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="car_id", type="integer", example=1),
     *             @OA\Property(property="calendar", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="date", type="string", example="2025-11-10"),
     *                     @OA\Property(property="formatted", type="string", example="10.11"),
     *                     @OA\Property(property="available", type="boolean", example=true),
     *                     @OA\Property(property="is_past", type="boolean", example=false),
     *                     @OA\Property(property="is_today", type="boolean", example=false)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Samochód nie znaleziony")
     * )
     */
    public function availability(int $id, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        try {
            $startDate = $validated['start_date'] ? Carbon::parse($validated['start_date']) : null;
            $endDate = $validated['end_date'] ? Carbon::parse($validated['end_date']) : null;

            $calendar = $this->carService->getCarAvailabilityCalendar($id, $startDate, $endDate);

            return response()->json([
                'success' => true,
                'car_id' => $id,
                'calendar' => $calendar,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Nie udało się pobrać kalendarza dostępności.',
            ], 404);
        }
    }
}
