<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Action;

use App\Models\User;
use App\Modules\CarRental\Services\NumberGeneratorService;
use App\Modules\Reservations\DataObjects\ReservationData;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Types\ReservationStatus;
use Illuminate\Container\Attributes\CurrentUser;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @method static Reservation run(ReservationData $data)
 */
class CreateReservationAction
{
    use AsAction;

    public function __construct(
        #[CurrentUser]
        private readonly User $user,
        private readonly NumberGeneratorService $numberGeneratorService,
    ) {}

    public function handle(ReservationData $data): Reservation
    {
        return Reservation::create([
            'vehicle_id' => $data->vehicleId,
            'customer_id' => $data->customerId,
            'pickup_at' => $data->pickupAt,
            'return_at' => $data->returnAt,
            'daily_rate' => $data->dailyRate,
            'estimated_total' => $data->estimatedTotal,
            'pickup_location' => $data->pickupLocation,
            'return_location' => $data->returnLocation,
            'reservation_number' => $this->numberGeneratorService->nextReservationNumber(),
            'status' => ReservationStatus::Pending,
            'notes' => $data->notes,
            'created_by' => $this->user->id,
        ]);
    }
}
