<?php

declare(strict_types=1);

namespace Database\Factories\Modules\CarRental\Models;

use App\Modules\CarRental\Enums\RentalStatus;
use App\Modules\CarRental\Models\Rental;
use App\Modules\Customer\Models\Customer;
use App\Modules\Vehicle\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    protected $model = Rental::class;

    public function definition(): array
    {
        $startsAt = now()->subDays(fake()->numberBetween(0, 3));
        $endsAt = (clone $startsAt)->addDays(fake()->numberBetween(2, 8));
        $rate = fake()->randomFloat(2, 250, 900);
        $days = $startsAt->diffInDays($endsAt);

        return [
            'rental_number' => 'RNT-' . now()->format('Ymd') . '-' . fake()->unique()->numberBetween(1000, 9999),
            'vehicle_id' => Vehicle::factory(),
            'customer_id' => Customer::factory(),
            'status' => RentalStatus::Active,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'pickup_location' => fake()->city(),
            'return_location' => fake()->city(),
            'daily_rate' => $rate,
            'subtotal' => $rate * $days,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => $rate * $days,
            'activated_at' => $startsAt,
        ];
    }
}
