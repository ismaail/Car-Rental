<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Actions;

use App\Modules\CarRental\Enums\DepositStatus;
use App\Modules\CarRental\Enums\InspectionType;
use App\Modules\CarRental\Enums\RentalStatus;
use App\Modules\CarRental\Enums\VehicleStatus;
use App\Modules\CarRental\Models\Rental;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProcessReturnAction
{
    public function execute(Rental $rental): Rental
    {
        return DB::transaction(function () use ($rental): Rental {
            $rental->loadMissing(['vehicle', 'inspections', 'deposit']);

            $hasReturnInspection = $rental->inspections
                ->contains(fn ($inspection) => InspectionType::Return === $inspection->type)
            ;

            if (! $hasReturnInspection) {
                throw ValidationException::withMessages([
                    'inspection' => 'A return inspection is required before completing the rental.',
                ]);
            }

            $rental->update([
                'status' => RentalStatus::Completed,
                'actual_returned_at' => now(),
                'closed_at' => now(),
            ]);

            $rental->vehicle->update([
                'status' => VehicleStatus::Available,
                'mileage' => $rental->inspections->sortByDesc('inspected_at')->first()?->mileage ?? $rental->vehicle->mileage,
            ]);

            $rental->deposit()->where('status', DepositStatus::Collected)->update([
                'status' => DepositStatus::Released,
                'released_at' => now(),
            ]);

            return $rental->refresh();
        });
    }
}
