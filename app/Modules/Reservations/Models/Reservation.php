<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Models;

use App\Models\User;
use App\Modules\CarRental\Models\Customer;
use App\Modules\Reservations\Types\ReservationStatus;
use App\Modules\Vehicles\Models\Vehicle;
use Carbon\CarbonInterface;
use Database\Factories\Modules\CarRental\Models\ReservationFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperReservation
 */
#[UseFactory(ReservationFactory::class)]
class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'reservation_number',
        'vehicle_id',
        'customer_id',
        'status',
        'pickup_at',
        'return_at',
        'pickup_location',
        'return_location',
        'daily_rate',
        'estimated_total',
        'notes',
        'confirmed_at',
        'cancelled_at',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ReservationStatus::class,
            'pickup_at' => 'datetime',
            'return_at' => 'datetime',
            'daily_rate' => 'decimal:2',
            'estimated_total' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Vehicle, $this>
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @param Builder<$this> $query
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereIn('status', [
            ReservationStatus::Pending,
            ReservationStatus::Confirmed,
        ]);
    }

    public function overlaps(CarbonInterface $pickupAt, CarbonInterface $returnAt): bool
    {
        return $this->pickup_at < $returnAt && $this->return_at > $pickupAt;
    }
}
