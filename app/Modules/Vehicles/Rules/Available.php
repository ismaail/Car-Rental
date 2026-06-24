<?php

declare(strict_types=1);

namespace App\Modules\Vehicles\Rules;

use App\Modules\CarRental\Enums\RentalStatus;
use App\Modules\CarRental\Models\Rental;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Types\ReservationStatus;
use App\Modules\Vehicles\Models\Vehicle;
use App\Modules\Vehicles\Types\VehicleStatus;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class Available implements DataAwareRule, ValidationRule
{
    /**
     * @var array<string, mixed>
     */
    protected $data = [];

    private const string DEFAULT_MESSAGE = 'The selected vehicle is not available for the chosen dates.';

    public function __construct(
        private readonly ?Reservation $ignoreReservation = null,
        private readonly ?Rental $ignoreRental = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $vehicle = Vehicle::query()->select(['id', 'status'])->findOrFail(id: $this->data['vehicle_id']);

        if (in_array($vehicle->status, [VehicleStatus::Maintenance, VehicleStatus::Unavailable], true)) {
            $fail(self::DEFAULT_MESSAGE);
        }

        if ($this->hasReservationConflict($vehicle)) {
            $fail(self::DEFAULT_MESSAGE);
        }

        if ($this->hasRentalConflict($vehicle)) {
            $fail(self::DEFAULT_MESSAGE);
        }
    }

    private function hasReservationConflict(Vehicle $vehicle): bool
    {
        return Reservation::query()
            ->whereBelongsTo($vehicle)
            ->whereIn('status', [ReservationStatus::Pending, ReservationStatus::Confirmed])
            ->when($this->ignoreReservation, fn ($query) => $query->whereKeyNot($this->ignoreReservation->getKey()))
            ->where('pickup_at', '<', $this->data['return_at'])
            ->where('return_at', '>', $this->data['pickup_at'])
            ->exists()
        ;
    }

    private function hasRentalConflict(Vehicle $vehicle): bool
    {
        return Rental::query()
            ->whereBelongsTo($vehicle)
            ->whereIn('status', [RentalStatus::Draft, RentalStatus::Active, RentalStatus::Overdue])
            ->when($this->ignoreRental, fn ($query) => $query->whereKeyNot($this->ignoreRental->getKey()))
            ->where('starts_at', '<', $this->data['return_at'])
            ->where('ends_at', '>', $this->data['pickup_at'])
            ->exists()
        ;
    }
}
