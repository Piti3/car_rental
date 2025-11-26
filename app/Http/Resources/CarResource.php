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
            
            'category_id' => $this->category_id,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            
            'specification' => [
                'id' => $this->specification?->id,
                'horsepower' => $this->specification?->horsepower,
                'fuel_type' => $this->specification?->fuel_type,
                'engine_capacity' => $this->specification?->engine_capacity,
                'acceleration_0_100' => $this->specification?->acceleration_0_100,
                'seats' => $this->specification?->seats,
                'transmission' => $this->specification?->transmission,
                'fun_fact' => $this->specification?->fun_fact,
            ],
            
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}