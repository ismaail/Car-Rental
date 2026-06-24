<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\CarRental\Enums\RentalStatus;
use App\Modules\CarRental\Models\Rental;
use App\Modules\CarRental\Models\Reservation;
use App\Modules\CarRental\Models\Vehicle;
use App\Modules\Reservations\Types\ReservationStatus;
use App\Modules\Vehicles\Types\VehicleStatus;
use Carbon\CarbonInterface;
use Illuminate\Validation\ValidationException;

/**
 * @deprecated
 */
class AvailabilityService
{
    public function ensureVehicleIsAvailable(
        Vehicle $vehicle,
        CarbonInterface $pickupAt,
        CarbonInterface $returnAt,
        ?Reservation $ignoreReservation = null,
        ?Rental $ignoreRental = null,
    ): void {
        if (! $this->isVehicleAvailable($vehicle, $pickupAt, $returnAt, $ignoreReservation, $ignoreRental)) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'The selected vehicle is not available for the chosen dates.',
            ]);
        }
    }

    public function isVehicleAvailable(
        Vehicle $vehicle,
        CarbonInterface $pickupAt,
        CarbonInterface $returnAt,
        ?Reservation $ignoreReservation = null,
        ?Rental $ignoreRental = null,
    ): bool {
        if (in_array($vehicle->status, [VehicleStatus::Maintenance, VehicleStatus::Unavailable], true)) {
            return false;
        }

        $hasReservationConflict = Reservation::query()
            ->whereBelongsTo($vehicle)
            ->whereIn('status', [ReservationStatus::Pending, ReservationStatus::Confirmed])
            ->when($ignoreReservation, fn ($query) => $query->whereKeyNot($ignoreReservation->getKey()))
            ->where('pickup_at', '<', $returnAt)
            ->where('return_at', '>', $pickupAt)
            ->exists()
        ;

        if ($hasReservationConflict) {
            return false;
        }

        $hasRentalConflict = Rental::query()
            ->whereBelongsTo($vehicle)
            ->whereIn('status', [RentalStatus::Draft, RentalStatus::Active, RentalStatus::Overdue])
            ->when($ignoreRental, fn ($query) => $query->whereKeyNot($ignoreRental->getKey()))
            ->where('starts_at', '<', $returnAt)
            ->where('ends_at', '>', $pickupAt)
            ->exists()
        ;

        return ! $hasRentalConflict;
    }
}
