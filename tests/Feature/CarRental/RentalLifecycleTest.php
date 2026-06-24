<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\CarRental\Enums\DepositStatus;
use App\Modules\CarRental\Enums\InspectionType;
use App\Modules\CarRental\Models\Rental;
use App\Modules\CarRental\Services\RentalService;
use App\Modules\Customer\Models\Customer;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Types\ReservationStatus;
use App\Modules\Vehicle\Models\Vehicle;
use App\Modules\Vehicle\Types\VehicleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it converts a confirmed reservation into an active rental with a deposit record', function () {
    $user = User::factory()->create(['role' => UserRole::Manager]);
    $vehicle = Vehicle::factory()->create([
        'deposit_amount' => 4000,
        'status' => VehicleStatus::Available,
    ]);
    $customer = Customer::factory()->create();
    $reservation = Reservation::factory()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'status' => ReservationStatus::Confirmed,
        'pickup_at' => now()->addDay(),
        'return_at' => now()->addDays(3),
        'estimated_total' => 1200,
    ]);

    $this->be($user);

    $rental = app(RentalService::class)->activateFromReservation($reservation->fresh());

    expect(Rental::query()->count())->toBe(1);
    expect($rental->deposit)->not->toBeNull();
});

test('it requires a return inspection before completing a rental', function () {
    $user = User::factory()->create(['role' => UserRole::Manager]);
    $rental = Rental::factory()->create();
    $rental->deposit()->create([
        'amount' => 3000,
        'status' => DepositStatus::Collected,
        'collected_at' => now(),
    ]);

    $response = $this->actingAs($user)->post(route('car-rental.rentals.return', $rental));

    $response->assertSessionHasErrors('inspection');
});

test('it completes a rental when a return inspection exists', function () {
    $user = User::factory()->create(['role' => UserRole::Manager]);
    $rental = Rental::factory()->create();
    $rental->deposit()->create([
        'amount' => 3000,
        'status' => DepositStatus::Collected,
        'collected_at' => now(),
    ]);
    $rental->inspections()->create([
        'type' => InspectionType::Return,
        'inspected_at' => now(),
        'mileage' => 120000,
    ]);

    $response = $this->actingAs($user)->post(route('car-rental.rentals.return', $rental));

    $response->assertSessionHasNoErrors();
    expect($rental->fresh()->actual_returned_at)->not->toBeNull();
});
