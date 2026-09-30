<?php

declare(strict_types=1);

namespace Database\Factories\Modules\CarRental\Models;

use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->unique()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'national_id_number' => strtoupper(fake()->bothify('AB######')),
            'passport_number' => strtoupper(fake()->bothify('PA######')),
            'driving_license_number' => strtoupper(fake()->bothify('DL######')),
            'driving_license_expires_at' => now()->addYears(2),
            'birth_date' => fake()->dateTimeBetween('-55 years', '-21 years'),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Casablanca', 'Rabat', 'Marrakech', 'Tangier']),
            'country' => 'Morocco',
            'notes' => fake()->sentence(),
        ];
    }
}
