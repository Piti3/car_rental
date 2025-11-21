<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\CarSpecification;

class CarSpecificationSeeder extends Seeder
{
    /**
     * Tworzy specyfikacje techniczne dla wszystkich samochodów
     */
    public function run(): void
    {
        $cars = Car::all();

        $specifications = [
            // Toyota Camry
            ['engine_capacity' => 2.5, 'horsepower' => 203, 'acceleration_0_100' => 8.4, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Camry to najlepiej sprzedający się sedan w USA przez wiele lat z rzędu.'],
            
            // Honda Accord
            ['engine_capacity' => 2.0, 'horsepower' => 252, 'acceleration_0_100' => 7.3, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Accord wygrał tytuł "Samochód Roku" dziesięć razy w różnych krajach.'],
            
            // BMW 5 Series
            ['engine_capacity' => 3.0, 'horsepower' => 340, 'acceleration_0_100' => 5.5, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Seria 5 oferuje system autonomicznej jazdy na poziomie 2, pozwalający na jazdę bez rąk w niektórych sytuacjach.'],
            
            // Mercedes E-Class
            ['engine_capacity' => 2.0, 'horsepower' => 299, 'acceleration_0_100' => 6.2, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Klasa E jako pierwsza wprowadziła system Pre-Safe, który przygotowuje auto na nieuchronną kolizję.'],
            
            // Audi A6
            ['engine_capacity' => 3.0, 'horsepower' => 340, 'acceleration_0_100' => 5.1, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'A6 posiada system quattro z inteligentnym rozdziałem momentu na wszystkie koła.'],
            
            // VW Passat
            ['engine_capacity' => 2.0, 'horsepower' => 190, 'acceleration_0_100' => 7.9, 'fuel_type' => 'Diesel', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Passat jest produkowany od 1973 roku i sprzedano ponad 30 milionów sztuk.'],
            
            // Skoda Superb
            ['engine_capacity' => 2.0, 'horsepower' => 190, 'acceleration_0_100' => 7.7, 'fuel_type' => 'Diesel', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Superb ma jeden z największych bagażników w swojej klasie - 625 litrów.'],
            
            // Mazda 6
            ['engine_capacity' => 2.5, 'horsepower' => 194, 'acceleration_0_100' => 8.1, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Mazda 6 wygrała ponad 280 międzynarodowych nagród za design.'],
            
            // Toyota RAV4
            ['engine_capacity' => 2.5, 'horsepower' => 218, 'acceleration_0_100' => 8.4, 'fuel_type' => 'Hybryda', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'RAV4 był pierwszym kompaktowym SUV-em na rynku (1994).'],
            
            // Honda CR-V
            ['engine_capacity' => 1.5, 'horsepower' => 193, 'acceleration_0_100' => 9.2, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'CR-V oznacza "Comfortable Runabout Vehicle".'],
            
            // BMW X5
            ['engine_capacity' => 3.0, 'horsepower' => 340, 'acceleration_0_100' => 5.5, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'X5 może być wyposażony w system Off-Road Package dla prawdziwej jazdy terenowej.'],
            
            // Mercedes GLE
            ['engine_capacity' => 3.0, 'horsepower' => 367, 'acceleration_0_100' => 5.7, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'GLE ma system E-Active Body Control z funkcją "bounce" do wydostania się z piasku.'],
            
            // Audi Q5
            ['engine_capacity' => 2.0, 'horsepower' => 265, 'acceleration_0_100' => 6.3, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Q5 oferuje system adaptacyjnego zawieszenia z regulacją tłumienia.'],
            
            // VW Tiguan
            ['engine_capacity' => 2.0, 'horsepower' => 190, 'acceleration_0_100' => 8.0, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Tiguan jest najlepiej sprzedającym się SUV-em Volkswagena.'],
            
            // Nissan Qashqai
            ['engine_capacity' => 1.3, 'horsepower' => 140, 'acceleration_0_100' => 10.5, 'fuel_type' => 'Benzyna', 'transmission' => 'Manualna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Qashqai rozpoczął erę kompaktowych crossoverów w Europie (2007).'],
            
            // Hyundai Tucson
            ['engine_capacity' => 1.6, 'horsepower' => 180, 'acceleration_0_100' => 8.9, 'fuel_type' => 'Hybryda', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Nowy Tucson ma ukryty wzór świateł w chromowanej siatce.'],
            
            // Kia Sportage
            ['engine_capacity' => 1.6, 'horsepower' => 180, 'acceleration_0_100' => 8.7, 'fuel_type' => 'Hybryda', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Sportage oferuje 7-letnią gwarancję producenta.'],
            
            // Jeep Cherokee
            ['engine_capacity' => 2.0, 'horsepower' => 272, 'acceleration_0_100' => 7.4, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Cherokee ma system Selec-Terrain z 5 trybami jazdy terenowej.'],
            
            // VW Golf
            ['engine_capacity' => 1.5, 'horsepower' => 150, 'acceleration_0_100' => 8.5, 'fuel_type' => 'Benzyna', 'transmission' => 'Manualna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Golf jest najlepiej sprzedającym się samochodem w Europie przez 40 lat.'],
            
            // Ford Focus
            ['engine_capacity' => 1.5, 'horsepower' => 150, 'acceleration_0_100' => 9.0, 'fuel_type' => 'Benzyna', 'transmission' => 'Manualna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Focus wygrał tytuł "Europejski Samochód Roku" w 1999 roku.'],
            
            // Toyota Corolla
            ['engine_capacity' => 1.8, 'horsepower' => 122, 'acceleration_0_100' => 10.9, 'fuel_type' => 'Hybryda', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Corolla to najlepiej sprzedający się samochód w historii - ponad 50 milionów sztuk.'],
            
            // Peugeot 308
            ['engine_capacity' => 1.5, 'horsepower' => 130, 'acceleration_0_100' => 10.2, 'fuel_type' => 'Diesel', 'transmission' => 'Manualna', 'seats' => 5, 'doors' => 4, 'fun_fact' => '308 SW (kombi) zdobył tytuł "Car of the Year 2014".'],
            
            // Renault Megane
            ['engine_capacity' => 1.3, 'horsepower' => 140, 'acceleration_0_100' => 9.8, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Megane RS Trophy-R ustanowił rekord okrążenia Nurburgringu dla aut przednionapędowych.'],
            
            // Opel Astra
            ['engine_capacity' => 1.5, 'horsepower' => 130, 'acceleration_0_100' => 10.5, 'fuel_type' => 'Diesel', 'transmission' => 'Manualna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Nowa Astra ma reflektory IntelliLux LED z 168 elementami świetlnymi.'],
            
            // BMW 4 Series
            ['engine_capacity' => 3.0, 'horsepower' => 374, 'acceleration_0_100' => 4.5, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 4, 'doors' => 2, 'fun_fact' => 'M4 Competition ma silnik S58 z podwójnym turbodoładowaniem.'],
            
            // Audi A5
            ['engine_capacity' => 2.0, 'horsepower' => 252, 'acceleration_0_100' => 6.0, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 4, 'doors' => 2, 'fun_fact' => 'A5 było pierwszym autem Audi z jednolub ramą i pełnym napędem quattro.'],
            
            // Mercedes C-Class Coupe
            ['engine_capacity' => 2.0, 'horsepower' => 258, 'acceleration_0_100' => 6.0, 'fuel_type' => 'Benzyna', 'transmission' => 'Automatyczna', 'seats' => 4, 'doors' => 2, 'fun_fact' => 'C-Class Coupe ma współczynnik oporu powietrza Cd 0,26.'],
            
            // VW Passat Variant
            ['engine_capacity' => 2.0, 'horsepower' => 190, 'acceleration_0_100' => 8.1, 'fuel_type' => 'Diesel', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Variant ma bagażnik o pojemności 650 litrów.'],
            
            // Skoda Octavia Combi
            ['engine_capacity' => 2.0, 'horsepower' => 150, 'acceleration_0_100' => 8.4, 'fuel_type' => 'Diesel', 'transmission' => 'Manualna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Octavia Combi ma największy bagażnik w klasie - 640 litrów.'],
            
            // BMW 3 Series Touring
            ['engine_capacity' => 2.0, 'horsepower' => 184, 'acceleration_0_100' => 7.5, 'fuel_type' => 'Diesel', 'transmission' => 'Automatyczna', 'seats' => 5, 'doors' => 4, 'fun_fact' => 'Touring ma rozkładaną tylną kanapę 40:20:40 dla maksymalnej elastyczności.'],
        ];

        foreach ($cars as $index => $car) {
            $spec = $specifications[$index];
            $spec['car_id'] = $car->id;
            CarSpecification::create($spec);
        }
    }
}
