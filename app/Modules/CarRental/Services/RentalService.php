<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\CarRental\Actions\ActivateRentalAction;
use App\Modules\CarRental\Actions\ProcessReturnAction;
use App\Modules\CarRental\Enums\RentalStatus;
use App\Modules\CarRental\Models\Rental;
use App\Modules\Reservations\Models\Reservation;

class RentalService
{
    public function __construct(
        private readonly ActivateRentalAction $activateRentalAction,
        private readonly ProcessReturnAction $processReturnAction,
    ) {}

    public function activateFromReservation(Reservation $reservation): Rental
    {
        return $this->activateRentalAction->execute($reservation);
    }

    public function processReturn(Rental $rental): Rental
    {
        return $this->processReturnAction->execute($rental);
    }

    public function markOverdueRentals(): void
    {
        Rental::query()
            ->where('status', RentalStatus::Active)
            ->where('ends_at', '<', now())
            ->update(['status' => RentalStatus::Overdue])
        ;
    }
}
