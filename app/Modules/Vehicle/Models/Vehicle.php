<?php

declare(strict_types=1);

namespace App\Modules\Vehicle\Models;

use App\Modules\CarRental\Models\Rental;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Vehicle\Types\VehicleStatus;
use Database\Factories\Modules\CarRental\Models\VehicleFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperVehicle
 */
#[UseFactory(VehicleFactory::class)]
class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'plate_number',
        'vin',
        'brand',
        'model',
        'year',
        'color',
        'fuel_type',
        'transmission',
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
        'deposit_amount',
        'mileage',
        'status',
        'registration_expires_at',
        'insurance_expires_at',
        'notes',
    ];

    protected static function newFactory(): VehicleFactory
    {
        return VehicleFactory::new();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
            'weekly_rate' => 'decimal:2',
            'monthly_rate' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'registration_expires_at' => 'date',
            'insurance_expires_at' => 'date',
            'status' => VehicleStatus::class,
        ];
    }

    /**
     * @return HasMany<VehicleImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class);
    }

    /**
     * @return HasMany<Reservation, $this>
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * @return HasMany<Rental, $this>
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * @param Builder<$this> $query
     */
    #[Scope]
    protected function available(Builder $query): void
    {
        $query->where('status', VehicleStatus::Available);
    }
}
