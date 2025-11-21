<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarCategory extends Model
{
    use HasFactory;

    protected $table='car_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relacja: Jedna kategoria może mieć wiele samochodów
     * @return HasMany
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'category_id');
    }
}
