<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Actions;

use App\Modules\CarRental\Enums\RentalStatus;
use App\Modules\CarRental\Enums\ReservationStatus;
use App\Modules\CarRental\Enums\VehicleStatus;
use App\Modules\CarRental\Models\Rental;
use App\Modules\CarRental\Models\Reservation;
use App\Modules\CarRental\Services\AvailabilityService;
use App\Modules\CarRental\Services\NumberGeneratorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ActivateRentalAction
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
        private readonly NumberGeneratorService $numberGeneratorService,
    ) {}

    public function execute(Reservation $reservation): Rental
    {
        return DB::transaction(function () use ($reservation): Rental {
            $reservation->loadMissing(['vehicle', 'customer']);

            if (blank($reservation->customer->driving_license_number)) {
                throw ValidationException::withMessages([
                    'customer_id' => 'The customer must have a driving license number before rental activation.',
                ]);
            }

            $this->availabilityService->ensureVehicleIsAvailable(
                $reservation->vehicle,
                $reservation->pickup_at,
                $reservation->return_at,
                $reservation,
            );

            $rental = Rental::query()->create([
                'rental_number' => $this->numberGeneratorService->nextRentalNumber(),
                'reservation_id' => $reservation->id,
                'vehicle_id' => $reservation->vehicle_id,
                'customer_id' => $reservation->customer_id,
                'status' => RentalStatus::Active,
                'starts_at' => $reservation->pickup_at,
                'ends_at' => $reservation->return_at,
                'pickup_location' => $reservation->pickup_location,
                'return_location' => $reservation->return_location,
                'daily_rate' => $reservation->daily_rate,
                'subtotal' => $reservation->estimated_total,
                'total_amount' => $reservation->estimated_total,
                'notes' => $reservation->notes,
                'activated_at' => now(),
                'created_by' => Auth::id(),
            ]);

            $reservation->update([
                'status' => ReservationStatus::Converted,
            ]);

            $reservation->vehicle->update([
                'status' => VehicleStatus::Rented,
            ]);

            $rental->deposit()->create([
                'amount' => $reservation->vehicle->deposit_amount,
            ]);

            return $rental->refresh();
        });
    }
}
