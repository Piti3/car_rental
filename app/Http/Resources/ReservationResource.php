<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'car_id' => $this->car_id,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'days' => $this->start_date->diffInDays($this->end_date),
            'status' => $this->status,
            'total_price' => (float) $this->total_price,
            'notes' => $this->notes,
            'car' => [
                'id' => $this->car?->id,
                'brand' => $this->car?->brand,
                'model' => $this->car?->model,
                'year' => $this->car?->year,
                'daily_price' => (float) $this->car?->daily_price,
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
