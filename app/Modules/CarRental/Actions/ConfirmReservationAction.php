<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Actions;

use App\Modules\CarRental\Services\AvailabilityService;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Types\ReservationStatus;
use App\Modules\Vehicle\Types\VehicleStatus;
use Illuminate\Support\Facades\DB;

/**
 * @deprecated
 */
class ConfirmReservationAction
{
    public function __construct(private readonly AvailabilityService $availabilityService) {}

    public function execute(Reservation $reservation): Reservation
    {
        return DB::transaction(function () use ($reservation): Reservation {
            $reservation->loadMissing('vehicle');

            $this->availabilityService->ensureVehicleIsAvailable(
                $reservation->vehicle,
                $reservation->pickup_at,
                $reservation->return_at,
                $reservation,
            );

            $reservation->update([
                'status' => ReservationStatus::Confirmed,
                'confirmed_at' => now(),
            ]);

            $reservation->vehicle->update([
                'status' => VehicleStatus::Reserved,
            ]);

            return $reservation->refresh();
        });
    }
}
