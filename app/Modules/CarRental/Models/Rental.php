<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Models;

use App\Models\User;
use App\Modules\CarRental\Enums\RentalStatus;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Vehicles\Models\Vehicle;
use Carbon\CarbonImmutable;
use Database\Factories\Modules\CarRental\Models\RentalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperRental
 */
class Rental extends Model
{
    /** @use HasFactory<RentalFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'rental_number',
        'reservation_id',
        'vehicle_id',
        'customer_id',
        'status',
        'starts_at',
        'ends_at',
        'actual_returned_at',
        'pickup_location',
        'return_location',
        'daily_rate',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'notes',
        'activated_at',
        'closed_at',
        'created_by',
    ];

    protected static function newFactory(): RentalFactory
    {
        return RentalFactory::new();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => RentalStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'actual_returned_at' => 'datetime',
            'daily_rate' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'activated_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isOverdue(): bool
    {
        return RentalStatus::Active === $this->status
            && null === $this->actual_returned_at
            && CarbonImmutable::now()->greaterThan($this->ends_at);
    }
}
