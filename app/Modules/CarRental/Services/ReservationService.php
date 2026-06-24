<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\CarRental\Actions\ConfirmReservationAction;
use App\Modules\CarRental\Models\Reservation;
use App\Modules\CarRental\Models\Vehicle;
use App\Modules\Reservations\Types\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationService
{
    public function __construct(
        private readonly NumberGeneratorService $numberGeneratorService,
        private readonly AvailabilityService $availabilityService,
        private readonly ConfirmReservationAction $confirmReservationAction,
    ) {}

    /**
     * @param array<string, mixed> $data
     *
     * @deprecated
     */
    public function create(array $data): Reservation
    {
        $vehicle = Vehicle::query()->findOrFail($data['vehicle_id']);
        $pickupAt = Carbon::parse($data['pickup_at']);
        $returnAt = Carbon::parse($data['return_at']);

        $this->availabilityService->ensureVehicleIsAvailable($vehicle, $pickupAt, $returnAt);

        return Reservation::query()->create([
            ...$data,
            'pickup_at' => $pickupAt,
            'return_at' => $returnAt,
            'reservation_number' => $this->numberGeneratorService->nextReservationNumber(),
            'status' => ReservationStatus::Pending,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Reservation $reservation, array $data): Reservation
    {
        $vehicle = Vehicle::query()->findOrFail($data['vehicle_id']);
        $pickupAt = Carbon::parse($data['pickup_at']);
        $returnAt = Carbon::parse($data['return_at']);

        $this->availabilityService->ensureVehicleIsAvailable($vehicle, $pickupAt, $returnAt, $reservation);

        $reservation->update([
            ...$data,
            'pickup_at' => $pickupAt,
            'return_at' => $returnAt,
        ]);

        return $reservation->refresh();
    }

    public function confirm(Reservation $reservation): Reservation
    {
        return $this->confirmReservationAction->execute($reservation);
    }
}
