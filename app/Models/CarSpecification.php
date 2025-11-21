<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarSpecification extends Model
{
    use HasFactory;

    protected $table = 'car_specifications';

    protected $fillable = [
        'car_id',
        'engine_capacity',
        'horsepower',
        'acceleration_0_100',
        'fuel_type',
        'transmission',
        'seats',
        'doors',
        'fun_fact',
    ];

    protected $casts = [
        'engine_capacity' => 'decimal:1',
        'horsepower' => 'integer',
        'acceleration_0_100' => 'decimal:2',
        'seats' => 'integer',
        'doors' => 'integer',
    ];

    /**
     * Relacja: Specyfikacja należy do jednego samochodu (1:1)
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
