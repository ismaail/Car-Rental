<?php

declare(strict_types=1);

namespace App\Modules\Reservation\DataObjects;

use App\Modules\Reservation\Requests\ReservationRequest;
use Carbon\Carbon;

class ReservationData
{
    public function __construct(
        public readonly int $vehicleId,
        public readonly int $customerId,
        public readonly Carbon $pickupAt,
        public readonly Carbon $returnAt,
        public readonly float $dailyRate,
        public readonly float $estimatedTotal,
        public readonly ?string $pickupLocation = null,
        public readonly ?string $returnLocation = null,
        public readonly ?string $notes = null,
    ) {}

    public static function fromRequest(ReservationRequest $request): self
    {
        return new self(
            vehicleId: (int)$request->validated('vehicle_id'),
            customerId: (int)$request->validated('customer_id'),
            pickupAt: Carbon::parse($request->validated('pickup_at')),
            returnAt: Carbon::parse($request->validated('return_at')),
            dailyRate: (float)$request->validated('daily_rate'),
            estimatedTotal: (float)$request->validated('estimated_total'),
            pickupLocation: $request->validated('pickup_location'),
            returnLocation: $request->validated('return_location'),
            notes: $request->validated('notes'),
        );
    }
}
