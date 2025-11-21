<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CarService;
use App\Models\CarCategory;
use Illuminate\Http\Request;

class CarViewController extends Controller
{
    public function __construct(
        private CarService $carService
    ) {}

    /*Lista wszystkich samochodów*/
    public function index(Request $request)
    {
        $filters = $request->only(['category_id', 'price_min', 'price_max']);
        $cars = $this->carService->getAllCars($filters);
        $categories = CarCategory::all();
        
        return view('cars.index', compact('cars', 'categories')); 
    }

    /*Szczegóły samochodu*/
    public function show(int $id)
    {
        $car = $this->carService->getCarDetails($id);
        $calendar = $this->carService->getCarAvailabilityCalendar($id);
        
        return view('cars.show', compact('car', 'calendar'));
    }
}
