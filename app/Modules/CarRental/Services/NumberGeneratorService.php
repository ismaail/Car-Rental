<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\CarRental\Models\Rental;
use App\Modules\Reservation\Models\Reservation;

class NumberGeneratorService
{
    public function nextReservationNumber(): string
    {
        $prefix = 'RES-' . now()->format('Ymd');
        /** @var ?string $lastNumber */
        $lastNumber = Reservation::query()
            ->where('reservation_number', 'like', $prefix . '-%')
            ->latest('id')
            ->value('reservation_number')
        ;

        $sequence = $this->extractSequence($lastNumber) + 1;

        return sprintf('%s-%04d', $prefix, $sequence);
    }

    public function nextRentalNumber(): string
    {
        $prefix = 'RNT-' . now()->format('Ymd');
        /** @var ?string $lastNumber */
        $lastNumber = Rental::query()
            ->where('rental_number', 'like', $prefix . '-%')
            ->latest('id')
            ->value('rental_number')
        ;

        $sequence = $this->extractSequence($lastNumber) + 1;

        return sprintf('%s-%04d', $prefix, $sequence);
    }

    private function extractSequence(?string $number): int
    {
        if (null === $number) {
            return 0;
        }

        return (int)str($number)->afterLast('-')->value();
    }
}
