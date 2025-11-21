<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\CarSpecification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class AdminCarController extends Controller
{
    /* Lista wszystkich samochodów */
    public function index()
    {
        $cars = Car::with(['category', 'specification'])->paginate(20);
        
        return view('admin.cars.index', compact('cars'));
    }

    /* Formularz dodawania samochodu */
    public function create()
    {
        $categories = CarCategory::all();
        
        return view('admin.cars.create', compact('categories'));
    }

    /* Zapisz nowy samochód */
    public function store(Request $request)
    {
        // Pobieramy reguły i dodajemy walidację obrazka (wymagany przy tworzeniu)
        $rules = $this->getValidationRules();
        $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120'; // max 5MB

        $validated = $request->validate($rules);

        // Obsługa obrazka (tylko jeśli przesłano i jest poprawny - walidacja wyżej to gwarantuje)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadCarImage($request->file('image'));
        }

        // Transaction is recommended here, but keeping it simple as per your structure
        $car = Car::create([
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'category_id' => $validated['category_id'],
            'daily_price' => $validated['daily_price'],
            'registration' => $validated['registration'],
            'status' => $validated['status'],
            'image_path' => $imagePath,
        ]);

        $this->saveSpecification($car, $validated);

        return redirect()->route('admin.cars.index')
            ->with('success', 'Samochód został dodany pomyślnie.');
    }

    /* Formularz edycji samochodu */
    public function edit(int $id)
    {
        $car = Car::with('specification')->findOrFail($id);
        $categories = CarCategory::all();
        
        return view('admin.cars.edit', compact('car', 'categories'));
    }

    /* Aktualizuj samochód */
    public function update(Request $request, int $id)
    {
        $car = Car::with('specification')->findOrFail($id);

        // Pobieramy reguły i modyfikujemy unikalność oraz obrazek
        $rules = $this->getValidationRules($car->id);
        $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120';

        $validated = $request->validate($rules);

        // Obsługa obrazka
        $imagePath = $car->image_path;
        if ($request->hasFile('image')) {
            // Usuń stary tylko jeśli wgrywamy nowy
            if ($car->image_path) {
                $this->deleteCarImage($car->image_path);
            }
            $imagePath = $this->uploadCarImage($request->file('image'));
        }

        $car->update([
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'category_id' => $validated['category_id'],
            'daily_price' => $validated['daily_price'],
            'registration' => $validated['registration'],
            'status' => $validated['status'],
            'image_path' => $imagePath,
        ]);

        $this->saveSpecification($car, $validated);

        return redirect()->route('admin.cars.index')
            ->with('success', 'Samochód został zaktualizowany pomyślnie.');
    }

    /* Usuń samochód */
    public function destroy(int $id)
    {
        $car = Car::findOrFail($id);
        
        if ($car->reservations()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Nie można usunąć samochodu z aktywnymi rezerwacjami.');
        }
        
        if ($car->image_path) {
            $this->deleteCarImage($car->image_path);
        }
        
        $car->delete();
        
        return redirect()->route('admin.cars.index')
            ->with('success', 'Samochód został usunięty.');
    }

    /**
     * Wspólna logika walidacji
     */
    private function getValidationRules($ignoreId = null): array
    {
        return [
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:car_categories,id',
            'daily_price' => 'required|numeric|min:0',
            // Fix: Ignorowanie ID przy edycji, żeby nie wyrzucało błędu "email zajęty" dla tego samego auta
            'registration' => ['required', 'string', 'max:20', Rule::unique('cars', 'registration')->ignore($ignoreId)],
            'status' => 'required|in:available,rented,maintenance',
            
            // Specyfikacja
            'engine_type' => 'nullable|string|max:50',
            'engine_capacity' => 'nullable|numeric',
            'horsepower' => 'nullable|integer',
            'fuel_type' => 'nullable|string', // Możesz tu dodać in:... jeśli chcesz
            'transmission' => 'nullable|string',
            'seats' => 'nullable|integer|min:1|max:9',
            'doors' => 'nullable|integer|min:2|max:5',
            'color' => 'nullable|string|max:50',
            'acceleration_0_100' => 'nullable|numeric',
            'fun_fact' => 'nullable|string|max:500',
        ];
    }

    /**
     * Pomocnicza metoda do zapisu specyfikacji (DRY)
     */
    private function saveSpecification(Car $car, array $validated): void
    {
        // Używamy array_filter lub null coalescing, ale updateOrCreate jest tu wygodne.
        // Pobieramy tylko klucze specyfikacji z validated, jeśli istnieją.
        
        $specData = [
            'engine_type' => $validated['engine_type'] ?? null,
            'engine_capacity' => $validated['engine_capacity'] ?? null,
            'horsepower' => $validated['horsepower'] ?? null,
            'fuel_type' => $validated['fuel_type'] ?? 'petrol',
            'transmission' => $validated['transmission'] ?? 'manual',
            'seats' => $validated['seats'] ?? 5,
            'doors' => $validated['doors'] ?? 4,
            'color' => $validated['color'] ?? null,
            'acceleration_0_100' => $validated['acceleration_0_100'] ?? null,
            'fun_fact' => $validated['fun_fact'] ?? null,
        ];

        // Przy update, chcemy nadpisać tylko to co podano, albo zostawić stare?
        // W Twoim oryginalnym kodzie create miał defaulty, a update brał stare wartości.
        // UpdateOrCreate zadziała tak, że zaktualizuje rekord powiązany z car_id.
        
        $car->specification()->updateOrCreate(
            ['car_id' => $car->id],
            $specData
        );
    }

    private function uploadCarImage($file): string
    {
        $directory = public_path('images/cars');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);
        
        return 'images/cars/' . $filename;
    }

    private function deleteCarImage(string $path): void
    {
        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}