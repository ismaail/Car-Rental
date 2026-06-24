<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\Vehicles\Models\Vehicle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class VehicleService
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Vehicle
    {
        return DB::transaction(function () use ($data): Vehicle {
            $vehicle = Vehicle::query()->create(Arr::except($data, ['images']));

            $this->syncImages($vehicle, $data['images'] ?? []);

            return $vehicle->load('images');
        });
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $data): Vehicle {
            $vehicle->update(Arr::except($data, ['images']));
            $this->syncImages($vehicle, $data['images'] ?? []);

            return $vehicle->load('images');
        });
    }

    /**
     * @param array<int, UploadedFile> $images
     */
    private function syncImages(Vehicle $vehicle, array $images): void
    {
        if ([] === $images) {
            return;
        }

        foreach ($images as $index => $image) {
            $vehicle->images()->create([
                'path' => $image->store('car-rental/vehicles', 'public'),
                'is_primary' => 0 === $index && ! $vehicle->images()->exists(),
                'sort_order' => $index,
            ]);
        }
    }
}
