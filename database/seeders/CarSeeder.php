<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\CarCategory;

class CarSeeder extends Seeder
{
    /**
     * Tworzy testowe samochody (minimum 30)
     */
    public function run(): void
    {
        // Pobieramy ID kategorii
        $sedan = CarCategory::where('name', 'Sedan')->first()->id;
        $suv = CarCategory::where('name', 'SUV')->first()->id;
        $hatchback = CarCategory::where('name', 'Hatchback')->first()->id;
        $coupe = CarCategory::where('name', 'Coupe')->first()->id;
        $kombi = CarCategory::where('name', 'Kombi')->first()->id;

        $cars = [
            // Sedany (8 sztuk)
            ['category_id' => $sedan, 'brand' => 'Toyota', 'model' => 'Camry', 'year' => 2023, 'registration' => 'WA12345', 'daily_price' => 250.00, 'image_path' => '/images/cars/toyota-camry.jpg'],
            ['category_id' => $sedan, 'brand' => 'Honda', 'model' => 'Accord', 'year' => 2022, 'registration' => 'WA23456', 'daily_price' => 240.00, 'image_path' => '/images/cars/honda-accord.jpg'],
            ['category_id' => $sedan, 'brand' => 'BMW', 'model' => '5 Series', 'year' => 2023, 'registration' => 'WA34567', 'daily_price' => 450.00, 'image_path' => '/images/cars/bmw-5series.jpg'],
            ['category_id' => $sedan, 'brand' => 'Mercedes', 'model' => 'E-Class', 'year' => 2023, 'registration' => 'WA45678', 'daily_price' => 480.00, 'image_path' => '/images/cars/mercedes-eclass.jpg'],
            ['category_id' => $sedan, 'brand' => 'Audi', 'model' => 'A6', 'year' => 2022, 'registration' => 'WA56789', 'daily_price' => 420.00, 'image_path' => '/images/cars/audi-a6.jpg'],
            ['category_id' => $sedan, 'brand' => 'Volkswagen', 'model' => 'Passat', 'year' => 2021, 'registration' => 'WA67890', 'daily_price' => 220.00, 'image_path' => '/images/cars/vw-passat.jpg'],
            ['category_id' => $sedan, 'brand' => 'Skoda', 'model' => 'Superb', 'year' => 2023, 'registration' => 'WA78901', 'daily_price' => 230.00, 'image_path' => '/images/cars/skoda-superb.jpg'],
            ['category_id' => $sedan, 'brand' => 'Mazda', 'model' => '6', 'year' => 2022, 'registration' => 'WA89012', 'daily_price' => 210.00, 'image_path' => '/images/cars/mazda-6.jpg'],

            // SUV-y (10 sztuk)
            ['category_id' => $suv, 'brand' => 'Toyota', 'model' => 'RAV4', 'year' => 2023, 'registration' => 'WB12345', 'daily_price' => 300.00, 'image_path' => '/images/cars/toyota-rav4.jpg'],
            ['category_id' => $suv, 'brand' => 'Honda', 'model' => 'CR-V', 'year' => 2023, 'registration' => 'WB23456', 'daily_price' => 290.00, 'image_path' => '/images/cars/honda-crv.jpg'],
            ['category_id' => $suv, 'brand' => 'BMW', 'model' => 'X5', 'year' => 2023, 'registration' => 'WB34567', 'daily_price' => 550.00, 'image_path' => '/images/cars/bmw-x5.jpg'],
            ['category_id' => $suv, 'brand' => 'Mercedes', 'model' => 'GLE', 'year' => 2023, 'registration' => 'WB45678', 'daily_price' => 580.00, 'image_path' => '/images/cars/mercedes-gle.jpg'],
            ['category_id' => $suv, 'brand' => 'Audi', 'model' => 'Q5', 'year' => 2022, 'registration' => 'WB56789', 'daily_price' => 480.00, 'image_path' => '/images/cars/audi-q5.jpg'],
            ['category_id' => $suv, 'brand' => 'Volkswagen', 'model' => 'Tiguan', 'year' => 2023, 'registration' => 'WB67890', 'daily_price' => 280.00, 'image_path' => '/images/cars/vw-tiguan.jpg'],
            ['category_id' => $suv, 'brand' => 'Nissan', 'model' => 'Qashqai', 'year' => 2022, 'registration' => 'WB78901', 'daily_price' => 260.00, 'image_path' => '/images/cars/nissan-qashqai.jpg'],
            ['category_id' => $suv, 'brand' => 'Hyundai', 'model' => 'Tucson', 'year' => 2023, 'registration' => 'WB89012', 'daily_price' => 270.00, 'image_path' => '/images/cars/hyundai-tucson.jpg'],
            ['category_id' => $suv, 'brand' => 'Kia', 'model' => 'Sportage', 'year' => 2023, 'registration' => 'WB90123', 'daily_price' => 265.00, 'image_path' => '/images/cars/kia-sportage.jpg'],
            ['category_id' => $suv, 'brand' => 'Jeep', 'model' => 'Cherokee', 'year' => 2022, 'registration' => 'WB01234', 'daily_price' => 320.00, 'image_path' => '/images/cars/jeep-cherokee.jpg'],

            // Hatchbacki (6 sztuk)
            ['category_id' => $hatchback, 'brand' => 'Volkswagen', 'model' => 'Golf', 'year' => 2023, 'registration' => 'WC12345', 'daily_price' => 180.00, 'image_path' => '/images/cars/vw-golf.jpg'],
            ['category_id' => $hatchback, 'brand' => 'Ford', 'model' => 'Focus', 'year' => 2022, 'registration' => 'WC23456', 'daily_price' => 170.00, 'image_path' => '/images/cars/ford-focus.jpg'],
            ['category_id' => $hatchback, 'brand' => 'Toyota', 'model' => 'Corolla', 'year' => 2023, 'registration' => 'WC34567', 'daily_price' => 175.00, 'image_path' => '/images/cars/toyota-corolla.jpg'],
            ['category_id' => $hatchback, 'brand' => 'Peugeot', 'model' => '308', 'year' => 2022, 'registration' => 'WC45678', 'daily_price' => 165.00, 'image_path' => '/images/cars/peugeot-308.jpg'],
            ['category_id' => $hatchback, 'brand' => 'Renault', 'model' => 'Megane', 'year' => 2021, 'registration' => 'WC56789', 'daily_price' => 160.00, 'image_path' => '/images/cars/renault-megane.jpg'],
            ['category_id' => $hatchback, 'brand' => 'Opel', 'model' => 'Astra', 'year' => 2023, 'registration' => 'WC67890', 'daily_price' => 170.00, 'image_path' => '/images/cars/opel-astra.jpg'],

            // Coupe (3 sztuki)
            ['category_id' => $coupe, 'brand' => 'BMW', 'model' => '4 Series', 'year' => 2023, 'registration' => 'WD12345', 'daily_price' => 500.00, 'image_path' => '/images/cars/bmw-4series.jpg'],
            ['category_id' => $coupe, 'brand' => 'Audi', 'model' => 'A5', 'year' => 2023, 'registration' => 'WD23456', 'daily_price' => 480.00, 'image_path' => '/images/cars/audi-a5.jpg'],
            ['category_id' => $coupe, 'brand' => 'Mercedes', 'model' => 'C-Class Coupe', 'year' => 2022, 'registration' => 'WD34567', 'daily_price' => 520.00, 'image_path' => '/images/cars/mercedes-ccoupe.jpg'],

            // Kombi (3 sztuki)
            ['category_id' => $kombi, 'brand' => 'Volkswagen', 'model' => 'Passat Variant', 'year' => 2023, 'registration' => 'WE12345', 'daily_price' => 240.00, 'image_path' => '/images/cars/vw-passat-variant.jpg'],
            ['category_id' => $kombi, 'brand' => 'Skoda', 'model' => 'Octavia Combi', 'year' => 2023, 'registration' => 'WE23456', 'daily_price' => 220.00, 'image_path' => '/images/cars/skoda-octavia-combi.jpg'],
            ['category_id' => $kombi, 'brand' => 'BMW', 'model' => '3 Series Touring', 'year' => 2022, 'registration' => 'WE34567', 'daily_price' => 380.00, 'image_path' => '/images/cars/bmw-3touring.jpg'],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
