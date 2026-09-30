<?php

declare(strict_types=1);

namespace Database\Factories\Modules\CarRental\Models;

use App\Modules\Customer\Models\Customer;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Types\ReservationStatus;
use App\Modules\Vehicle\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $pickupAt = now()->addDays(fake()->numberBetween(1, 10));
        $returnAt = (clone $pickupAt)->addDays(fake()->numberBetween(2, 7));
        $rate = fake()->randomFloat(2, 250, 900);

        return [
            'reservation_number' => 'RES-' . now()->format('Ymd') . '-' . fake()->unique()->numberBetween(1000, 9999),
            'vehicle_id' => Vehicle::factory(),
            'customer_id' => Customer::factory(),
            'status' => fake()->randomElement([ReservationStatus::Pending, ReservationStatus::Confirmed]),
            'pickup_at' => $pickupAt,
            'return_at' => $returnAt,
            'pickup_location' => fake()->city(),
            'return_location' => fake()->city(),
            'daily_rate' => $rate,
            'estimated_total' => $rate * $pickupAt->diffInDays($returnAt),
            'notes' => fake()->sentence(),
        ];
    }
}
