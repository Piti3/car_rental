<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'registration' => $this->registration,
            'status' => $this->status,
            'daily_price' => (float) $this->daily_price,
            'image_path' => $this->image_path,
            
            // Dodajemy category_id na głównym poziomie dla łatwiejszego filtrowania w JS
            'category_id' => $this->category_id,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            
            // Tura najważniejsza zmiana: Dodano brakujące pola specyfikacji
            'specification' => [
                'id' => $this->specification?->id,
                'horsepower' => $this->specification?->horsepower,       // Moc (KM)
                'fuel_type' => $this->specification?->fuel_type,         // Paliwo
                'engine_capacity' => $this->specification?->engine_capacity, // Pojemność
                'acceleration_0_100' => $this->specification?->acceleration_0_100, // 0-100 km/h
                'seats' => $this->specification?->seats,
                'transmission' => $this->specification?->transmission,
                'fun_fact' => $this->specification?->fun_fact,           // Ciekawostka (dla modala)
            ],
            
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}