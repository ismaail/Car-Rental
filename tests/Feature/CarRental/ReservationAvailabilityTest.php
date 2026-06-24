<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\CarRental\Models\Customer;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Types\ReservationStatus;
use App\Modules\Vehicles\Models\Vehicle;
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

test('it create a new reservation', function () {
    $user = User::factory()->create(['role' => UserRole::Manager]);
    $vehicle = Vehicle::factory()->available()->create();
    $customer = Customer::factory()->create();

    $this->assertDatabaseCount('reservations', 0);

    $response = $this->actingAs($user)->post(route('car-rental.reservations.store'), [
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'pickup_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'return_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'pickup_location' => 'Casablanca Airport',
        'return_location' => 'Casablanca Airport',
        'daily_rate' => 200,
        'estimated_total' => 1200,
    ]);

    $response->assertSessionHasNoErrors();

    $this->assertDatabaseCount('reservations', 1);
    $this->assertDatabaseHas('reservations', [
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'status' => ReservationStatus::Pending,
        'pickup_location' => 'Casablanca Airport',
        'return_location' => 'Casablanca Airport',
        'daily_rate' => 200,
        'estimated_total' => 1200,
        'confirmed_at' => null,
        'cancelled_at' => null,
        'notes' => null,
        'created_by' => $user->id,
    ]);
});
