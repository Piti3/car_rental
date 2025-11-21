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
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'specification' => [
                'id' => $this->specification?->id,
                'seats' => $this->specification?->seats,
                'engine_type' => $this->specification?->engine_type,
                'transmission' => $this->specification?->transmission,
            ],
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
