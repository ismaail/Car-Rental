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
            vehicleId: $request->integer('vehicle_id'),
            customerId: $request->integer('customer_id'),
            pickupAt: Carbon::parse($request->string('pickup_at')->toString()),
            returnAt: Carbon::parse($request->string('return_at')->toString()),
            dailyRate: $request->float('daily_rate'),
            estimatedTotal: $request->float('estimated_total'),
            pickupLocation: $request->filled('pickup_location')
                ? $request->string('pickup_location')->toString()
                : null,
            returnLocation: $request->filled('return_location')
                ? $request->string('return_location')->toString() :
                null,
            notes: $request->filled('notes')
                ? $request->string('notes')->toString()
                : null,
        );
    }
}
