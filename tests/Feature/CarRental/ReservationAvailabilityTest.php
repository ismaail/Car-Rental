<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\CarRental\Enums\ReservationStatus;
use App\Modules\CarRental\Models\Customer;
use App\Modules\CarRental\Models\Reservation;
use App\Modules\CarRental\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it prevents double booking for overlapping reservations', function () {
    $user = User::factory()->create(['role' => UserRole::Manager]);
    $vehicle = Vehicle::factory()->create();
    $customer = Customer::factory()->create();

    Reservation::factory()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'status' => ReservationStatus::Confirmed,
        'pickup_at' => now()->addDay(),
        'return_at' => now()->addDays(4),
    ]);

    $response = $this->actingAs($user)->post(route('car-rental.reservations.store'), [
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'pickup_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'return_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'pickup_location' => 'Casablanca Airport',
        'return_location' => 'Casablanca Airport',
        'daily_rate' => 500,
        'estimated_total' => 1500,
    ]);

    $response->assertSessionHasErrors('vehicle_id');
});
