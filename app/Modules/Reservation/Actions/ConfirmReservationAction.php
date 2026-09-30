<?php

declare(strict_types=1);

namespace App\Modules\Reservation\Actions;

use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Types\ReservationStatus;
use App\Modules\Vehicle\Types\VehicleStatus;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @method static Reservation run(Reservation $reservation)
 */
class ConfirmReservationAction
{
    use AsAction;

    public function handle(Reservation $reservation): Reservation
    {
        DB::beginTransaction();

        $reservation->loadMissing('vehicle');

        $reservation->update([
            'status' => ReservationStatus::Confirmed,
            'confirmed_at' => now(),
        ]);

        $reservation->vehicle->update([
            'status' => VehicleStatus::Reserved,
        ]);

        DB::commit();

        return $reservation->refresh();
    }
}
