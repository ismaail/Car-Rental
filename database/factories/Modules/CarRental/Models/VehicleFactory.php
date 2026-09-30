<?php

declare(strict_types=1);

namespace Database\Factories\Modules\CarRental\Models;

use App\Modules\Vehicle\Models\Vehicle;
use App\Modules\Vehicle\Types\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plate_number' => strtoupper(fake()->bothify('??-###-??')),
            'vin' => strtoupper(fake()->unique()->bothify('###############')),
            'brand' => fake()->randomElement(['Dacia', 'Renault', 'Peugeot', 'Hyundai']),
            'model' => fake()->randomElement(['Logan', 'Clio', '208', 'i10']),
            'year' => fake()->numberBetween(2019, 2025),
            'color' => fake()->safeColorName(),
            'fuel_type' => fake()->randomElement(['Diesel', 'Petrol', 'Hybrid']),
            'transmission' => fake()->randomElement(['Manual', 'Automatic']),
            'daily_rate' => fake()->randomFloat(2, 250, 900),
            'weekly_rate' => fake()->randomFloat(2, 1500, 5000),
            'monthly_rate' => fake()->randomFloat(2, 5000, 15000),
            'deposit_amount' => fake()->randomFloat(2, 2000, 10000),
            'mileage' => fake()->numberBetween(10000, 120000),
            'status' => fake()->randomElement(VehicleStatus::cases()),
            'registration_expires_at' => now()->addYear(),
            'insurance_expires_at' => now()->addMonths(8),
            'notes' => fake()->sentence(),
        ];
    }

    public function available(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => VehicleStatus::Available,
        ]);
    }
}
