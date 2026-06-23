<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\CarRental\Models\Rental;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InspectionService
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(Rental $rental, array $data): void
    {
        DB::transaction(function () use ($rental, $data): void {
            $inspection = $rental->inspections()->create([
                'type' => $data['type'],
                'inspected_at' => $data['inspected_at'],
                'mileage' => $data['mileage'],
                'fuel_level' => $data['fuel_level'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            foreach ($data['items'] ?? [] as $item) {
                $inspection->items()->create($item);
            }

            foreach ($data['photos'] ?? [] as $photo) {
                if ($photo instanceof UploadedFile) {
                    $inspection->photos()->create([
                        'file_path' => $photo->store('car-rental/inspections', 'public'),
                    ]);
                }
            }
        });
    }
}
