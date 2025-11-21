<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'category_id',
        'brand',
        'model',
        'year',
        'registration',
        'status',
        'daily_price',
        'image_path',
    ];

    protected $casts = [
        'year' => 'integer',
        'daily_price' => 'decimal:2',
    ];

    /**
     * Relacja: Samochód należy do jednej kategorii
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CarCategory::class, 'category_id');
    }

    /**
     * Relacja: Samochód ma jedną specyfikację techniczną (1:1)
     * @return HasOne
     */
    public function specification(): HasOne
    {
        return $this->hasOne(CarSpecification::class, 'car_id');
    }

    /**
     * Relacja: Samochód może mieć wiele rezerwacji
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'car_id');
    }

    //filtruj dostepne samochody
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    //filtruj wedlug statusu
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
